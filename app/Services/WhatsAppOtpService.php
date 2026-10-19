<?php

namespace App\Services;

use App\Models\Petani;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppOtpService
{
    protected ?string $apiKey;
    protected string $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.whatsapp.api_key', env('WHATSAPP_API_KEY'));
        $this->endpoint = config('services.whatsapp.endpoint', env('WHATSAPP_ENDPOINT', 'https://api.fonnte.com/send'));
    }

    /**
     * Normalisasi nomor telepon ke format internasional (62xxxx)
     */
    public function formatPhoneNumber(string|int $phone): int
    {
        $cleaned = preg_replace('/[^0-9]/', '', (string)$phone);
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (!str_starts_with($cleaned, '62')) {
            $cleaned = '62' . $cleaned;
        }
        return (int)$cleaned;
    }

    /**
     * Generate dan kirim kode OTP
     */
    public function sendOtp(Petani $petani): array
    {
        $otp = (string)random_int(100000, 999999);
        $expiredAt = Carbon::now()->addMinutes(5);

        $petani->update([
            'otp_code' => $otp,
            'otp_expired_at' => $expiredAt,
        ]);

        $message = "Halo {$petani->nama},\n\nKode verifikasi OTP akun KopiLink Anda adalah: *{$otp}*.\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";

        $sent = false;
        if (!empty($this->apiKey) && $this->apiKey !== 'dummy_token') {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $this->apiKey,
                ])->timeout(5)->post($this->endpoint, [
                    'target' => (string)$petani->kontak,
                    'message' => $message,
                    'countryCode' => '62',
                ]);
                $sent = $response->successful();
            } catch (\Throwable $e) {
                Log::warning("Gagal mengirim WhatsApp OTP via Gateway: " . $e->getMessage());
            }
        }

        // Simpan ke log sistem untuk kenyamanan pengujian lokal jika gateway tidak dikonfigurasi
        Log::info("WhatsApp OTP untuk Petani [{$petani->nama} - {$petani->kontak}]: {$otp}");

        return [
            'success' => true,
            'otp' => $otp,
            'sent_via_gateway' => $sent,
            'message' => $sent ? 'OTP berhasil dikirim ke WhatsApp Anda.' : "OTP verifikasi telah dibuat (Mode Simulasi Pengujian: {$otp}).",
        ];
    }

    /**
     * Verifikasi kode OTP
     */
    public function verifyOtp(Petani $petani, string $inputOtp): bool
    {
        if (!$petani->otp_code || !$petani->otp_expired_at) {
            return false;
        }

        if (Carbon::now()->isAfter($petani->otp_expired_at)) {
            return false;
        }

        if ($petani->otp_code === trim($inputOtp)) {
            $petani->update([
                'is_verified' => 1,
                'otp_code' => null,
                'otp_expired_at' => null,
            ]);
            return true;
        }

        return false;
    }
}
