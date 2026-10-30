<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Midtrans Core API — raw HTTP, no SDK needed.
 * ponytail: raw Http::post over midtrans-php SDK; add SDK when needing Snap/recurring.
 */
class MidtransService
{
    protected string $serverKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key') ?? '';
        $this->baseUrl = config('midtrans.base_url') ?? 'https://api.sandbox.midtrans.com';
    }

    public function isMockMode(): bool
    {
        return empty($this->serverKey) || str_contains($this->serverKey, 'XXXX');
    }

    /**
     * Create QRIS payment, returns ['qr_url' => ..., 'order_id' => ..., 'is_mock' => bool]
     */
    public function createQris(string $orderId, int $nominal, string $namaDonatur, string $program): array
    {
        // Mock mode for local testing without valid Midtrans Sandbox credentials
        if ($this->isMockMode()) {
            $qrData = "00020101021226670016ID.CO.MIDTRANS.WWW01189360091800000000000215{$orderId}520458125303360540" . strlen((string)$nominal) . "{$nominal}5802ID5910KOPILINK6008PONTIANAK6304";
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data=" . urlencode($qrData);

            return [
                'qr_url' => $qrUrl,
                'order_id' => $orderId,
                'transaction_id' => 'MOCK-' . time(),
                'expiry_time' => date('Y-m-d H:i:s', time() + 300),
                'is_mock' => true,
            ];
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

        if ($response->failed() || ($body['status_code'] ?? '999') !== '201') {
            throw new \RuntimeException($body['status_message'] ?? 'Midtrans charge gagal: ' . $response->body());
        }

        $qrUrl = null;
        foreach ($body['actions'] ?? [] as $action) {
            if ($action['name'] === 'generate-qr-code') {
                $qrUrl = $action['url'];
                break;
            }
        }

        if (!$qrUrl) {
            throw new \RuntimeException('QR code URL tidak ditemukan di respons Midtrans.');
        }

        return [
            'qr_url' => $qrUrl,
            'order_id' => $orderId,
            'transaction_id' => $body['transaction_id'] ?? null,
            'expiry_time' => $body['expiry_time'] ?? date('Y-m-d H:i:s', time() + 300),
            'is_mock' => false,
        ];
    }

    /**
     * Verify notification signature.
     */
    public function verifySignature(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = $this->serverKey;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $payload['signature_key'] ?? '');
    }
}
