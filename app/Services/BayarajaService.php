<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class BayarajaService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('bayaraja.base_url') ?: (env('BAYARAJA_BASE_URL') ?: 'https://bayaraja.arwebs.my.id'), '/');
        $this->apiKey = config('bayaraja.api_key') ?: (env('BAYARAJA_API_KEY') ?: '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->baseUrl);
    }

    /**
     * Generate Dynamic QRIS from Bayaraja API.
     */
    public function createQris(string $orderId, int $nominal, string $namaDonatur, string $program): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Kunci API Bayaraja belum diatur. Silakan atur BAYARAJA_API_KEY di .env atau Pengaturan Admin.');
        }

        $response = Http::withToken($this->apiKey)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout(15)
            ->post("{$this->baseUrl}/api/qris/create-amount", [
                'amount' => $nominal,
            ]);

        $body = $response->json();

        if ($response->failed() || !empty($body['error'])) {
            $msg = $body['error'] ?? ($body['message'] ?? 'Gagal membuat QRIS di server Bayaraja.');
            if ($msg === 'Server error.' || $msg === 'Server error') {
                $msg .= ' (Pastikan akun QRIS Anda sudah diunggah/diaktifkan di dashboard Bayaraja: ' . $this->baseUrl . ')';
            }
            throw new RuntimeException($msg);
        }

        $data = $body['data'] ?? $body;
        $qrString = $data['qris_dynamic'] ?? ($data['qris'] ?? ($data['qr_string'] ?? null));
        $qrUrl = $data['qr_image_url'] ?? ($data['qr_url'] ?? null);

        if (!$qrUrl && $qrString) {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . urlencode($qrString);
        }

        if (!$qrUrl && !$qrString) {
            throw new RuntimeException('Respons Bayaraja tidak memuat data QRIS: ' . json_encode($body));
        }

        return [
            'qr_url' => $qrUrl,
            'qr_string' => $qrString,
            'order_id' => $orderId,
            'transaction_id' => $data['id'] ?? $orderId,
            'expiry_time' => now()->addMinutes(5)->toDateTimeString(),
        ];
    }

    /**
     * Check transaction/link status from Bayaraja API.
     */
    public function checkStatus(string $slugOrOrderId): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $response = Http::withToken($this->apiKey)
            ->withHeaders(['Accept' => 'application/json'])
            ->timeout(10)
            ->get("{$this->baseUrl}/api/pay/{$slugOrOrderId}/status");

        if ($response->failed()) {
            return null;
        }

        $body = $response->json();
        $status = $body['data']['status'] ?? ($body['status'] ?? null);

        return match (strtolower((string) $status)) {
            'paid', 'confirmed', 'settlement', 'success' => 'settlement',
            'expired', 'expire' => 'expire',
            'rejected', 'failed', 'cancel' => 'cancel',
            default => 'pending',
        };
    }
}

