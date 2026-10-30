<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key') ?: (env('MIDTRANS_SERVER_KEY') ?: '');
        $this->clientKey = config('midtrans.client_key') ?: (env('MIDTRANS_CLIENT_KEY') ?: '');
        $this->isProduction = (bool) (config('midtrans.is_production') ?? env('MIDTRANS_IS_PRODUCTION', false));
        $this->baseUrl = $this->isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
    }

    public function isConfigured(): bool
    {
        return !empty($this->serverKey) && !str_contains($this->serverKey, 'XXXX');
    }

    /**
     * Create real QRIS payment directly from Midtrans Core API.
     */
    public function createQris(string $orderId, int $nominal, string $namaDonatur, string $program): array
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('Kunci Midtrans Server Key belum valid. Silakan masukkan Server Key Midtrans aktif di Pengaturan Super Admin atau file .env.');
        }

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post("{$this->baseUrl}/v2/charge", [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $nominal,
                ],
                'custom_expiry' => [
                    'expiry_duration' => 5,
                    'unit' => 'minute',
                ],
                'item_details' => [[
                    'id' => 'DONASI',
                    'price' => $nominal,
                    'quantity' => 1,
                    'name' => mb_substr("Donasi: {$program}", 0, 50),
                ]],
                'customer_details' => [
                    'first_name' => mb_substr($namaDonatur, 0, 50),
                ],
                'qris' => [
                    'acquirer' => 'gopay',
                ],
            ]);

        $body = $response->json();

        if ($response->failed() || !in_array(($body['status_code'] ?? ''), ['200', '201'])) {
            $msg = $body['status_message'] ?? ($body['message'] ?? 'Midtrans charge gagal dengan kode status ' . $response->status());
            throw new \RuntimeException($msg);
        }

        // Ambil URL gambar QR Code dari actions Midtrans
        $qrUrl = null;
        foreach ($body['actions'] ?? [] as $action) {
            if ($action['name'] === 'generate-qr-code') {
                $qrUrl = $action['url'];
                break;
            }
        }

        $qrString = $body['qr_string'] ?? null;

        if (!$qrUrl && !$qrString) {
            throw new \RuntimeException('Respons Midtrans tidak memuat QR Code: ' . json_encode($body));
        }

        return [
            'qr_url' => $qrUrl ?: ('https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . urlencode($qrString)),
            'qr_string' => $qrString,
            'order_id' => $orderId,
            'transaction_id' => $body['transaction_id'] ?? null,
            'expiry_time' => $body['expiry_time'] ?? null,
        ];
    }

    /**
     * Check real transaction status from Midtrans API.
     */
    public function checkStatus(string $orderId): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders(['Accept' => 'application/json'])
            ->get("{$this->baseUrl}/v2/{$orderId}/status");

        if ($response->failed()) {
            return null;
        }

        $body = $response->json();
        return $body['transaction_status'] ?? null;
    }

    /**
     * Verify notification signature from webhook.
     */
    public function verifySignature(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return hash_equals($expectedSignature, $payload['signature_key'] ?? '');
    }
}
