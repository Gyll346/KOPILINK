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
     * Generate dan kirim kode OTP ke nomor WhatsApp (sebelum akun dibuat di DB)
     */
    public function sendOtpToPhone(string $nama, string|int $kontak): array
    {
        $otp = (string)random_int(100000, 999999);
        $expiredAt = Carbon::now()->addMinutes(5);

        $message = "Halo {$nama},\n\nKode verifikasi OTP akun KopiLink Anda adalah: *{$otp}*.\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";

        $sent = false;
        if (!app()->environment('testing')) {
            try {
                $target = (string)$this->formatPhoneNumber($kontak);
                \Kstmostofa\LaravelWhatsApp\Facades\WhatsApp::web('main')->messages()->sendText(
                    $target,
                    $message
                );
                $sent = true;
            } catch (\Throwable $e) {
                Log::warning("Gagal mengirim WhatsApp OTP via Web Sidecar: " . $e->getMessage());
            }
        }

        $logOtp = config('app.debug') ? $otp : (substr($otp, 0, 2) . '****');
        Log::info("WhatsApp OTP untuk [{$nama} - {$kontak}]: {$logOtp} (Terkirim WA: " . ($sent ? 'YA' : 'TIDAK') . ")");

        return [
            'success' => true,
            'otp' => $otp,
            'expired_at' => $expiredAt,
            'sent_via_gateway' => $sent,
            'message' => $sent 
                ? 'Kode OTP verifikasi berhasil dikirim langsung ke WhatsApp Anda.' 
                : "OTP telah dibuat. (WhatsApp Sidecar belum terkoneksi: {$otp})",
        ];
    }

    /**
     * Generate dan kirim kode OTP untuk model Petani yang sudah ada di database
     */
    public function sendOtp(Petani $petani): array
    {
        $result = $this->sendOtpToPhone($petani->nama, $petani->kontak);

        $petani->update([
            'otp_code' => $result['otp'],
            'otp_expired_at' => $result['expired_at'],
        ]);

        return $result;
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
