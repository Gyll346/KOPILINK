<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class DuitkuService
{
    protected string $merchantCode;
    protected string $apiKey;
    protected bool $isSandbox;
    protected string $paymentMethod;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('duitku.merchant_code') ?: (env('DUITKU_MERCHANT_CODE') ?: '');
        $this->apiKey = config('duitku.api_key') ?: (env('DUITKU_API_KEY') ?: '');
        $this->isSandbox = (bool) (config('duitku.is_sandbox') ?? env('DUITKU_IS_SANDBOX', true));
        $this->paymentMethod = config('duitku.payment_method') ?: (env('DUITKU_PAYMENT_METHOD') ?: 'SP');
        $this->baseUrl = $this->isSandbox
            ? 'https://sandbox.duitku.com/webapi/api/merchant'
            : 'https://passport.duitku.com/webapi/api/merchant';
    }

    public function isConfigured(): bool
    {
        return !empty($this->merchantCode) && !empty($this->apiKey);
    }

    /**
     * Create Dynamic QRIS payment from Duitku Inquiry API v2.
     */
    public function createQris(string $orderId, int $nominal, string $namaDonatur, string $program): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Kredensial Duitku belum diatur. Silakan atur DUITKU_MERCHANT_CODE dan DUITKU_API_KEY di .env atau Pengaturan Admin.');
        }

        $signature = md5($this->merchantCode . $orderId . $nominal . $this->apiKey);
        $callbackUrl = route('donasi.duitku.notification');
        $returnUrl = route('donasi');

        $payload = [
            'merchantCode' => $this->merchantCode,
            'paymentAmount' => $nominal,
            'paymentMethod' => $this->paymentMethod,
            'merchantOrderId' => $orderId,
            'productDetails' => 'Donasi ' . $program . ' - ' . $namaDonatur,
            'email' => 'donatur@kopilink.my.id',
            'phoneNumber' => '08123456789',
            'additionalParam' => '',
            'merchantUserInfo' => '',
            'customerVaName' => substr($namaDonatur, 0, 30),
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
            'expiryPeriod' => 5, // 5 menit
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
        ->timeout(15)
        ->post("{$this->baseUrl}/v2/inquiry", $payload);

        $body = $response->json();

        if ($response->failed() || ($body['statusCode'] ?? '') !== '00') {
            $msg = $body['statusMessage'] ?? ($body['Message'] ?? 'Gagal membuat QRIS di server Duitku.');
            throw new RuntimeException($msg);
        }

        $qrString = $body['qrString'] ?? null;
        $qrUrl = $body['paymentUrl'] ?? null;

        // Buat QR Code gambar langsung dari raw QRIS string
        if ($qrString) {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . urlencode($qrString);
        }

        return [
            'qr_url' => $qrUrl,
            'qr_string' => $qrString,
            'order_id' => $orderId,
            'reference' => $body['reference'] ?? null,
            'payment_url' => $body['paymentUrl'] ?? null,
            'expiry_time' => now()->addMinutes(5)->toDateTimeString(),
        ];
    }

    /**
     * Check transaction status directly from Duitku API.
     */
    public function checkStatus(string $orderId): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $signature = md5($this->merchantCode . $orderId . $this->apiKey);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
        ->timeout(10)
        ->post("{$this->baseUrl}/transactionStatus", [
            'merchantCode' => $this->merchantCode,
            'merchantOrderId' => $orderId,
            'signature' => $signature,
        ]);

        if ($response->failed()) {
            return null;
        }

        $body = $response->json();
        $code = (string) ($body['statusCode'] ?? '');

        return match ($code) {
            '00' => 'settlement', // Success / Lunas
            '01' => 'pending',    // Process / Menunggu bayar
            '02' => 'cancel',     // Canceled / Expired
            default => 'pending',
        };
    }

    /**
     * Verify incoming webhook notification signature from Duitku.
     */
    public function verifyCallback(array $payload): bool
    {
        $merchantCode = $payload['merchantCode'] ?? '';
        $amount = $payload['amount'] ?? '';
        $merchantOrderId = $payload['merchantOrderId'] ?? '';
        $signature = $payload['signature'] ?? '';

        if (empty($merchantCode) || empty($amount) || empty($merchantOrderId) || empty($signature)) {
            return false;
        }

        $expectedSignature = md5($this->merchantCode . $amount . $merchantOrderId . $this->apiKey);

        return hash_equals($expectedSignature, (string) $signature);
    }
}

