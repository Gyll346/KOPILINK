<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\CoffeeShop;
use App\Models\User;
use App\Services\WhatsAppOtpService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected WhatsAppOtpService $otpService;

    public function __construct(WhatsAppOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showPetaniRegister()
    {
        return view('auth.register-petani');
    }

    public function registerPetani(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:petani,username',
            'password' => 'required|string|min:6|confirmed',
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:20',
            'kelompok_tani' => 'required|in:Pasak Piang,Padi Jaya',
        ]);

        $formattedPhone = $this->otpService->formatPhoneNumber($validated['kontak']);

        if (Petani::where('kontak', $formattedPhone)->exists()) {
            return back()->withInput()->withErrors(['kontak' => 'Nomor WhatsApp sudah terdaftar.']);
        }

        // Generate token registrasi unik
        $token = (string) Str::uuid();

        // Kirim OTP langsung ke WhatsApp tanpa membuat akun di database terlebih dahulu
        $otpResult = $this->otpService->sendOtpToPhone($validated['nama'], $formattedPhone);

        // Simpan data calon pendaftar di Cache sementara selama 15 menit
        Cache::put('pending_register_' . $token, [
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'nama' => $validated['nama'],
            'kontak' => $formattedPhone,
            'kelompok_tani' => $validated['kelompok_tani'],
            'otp_code' => $otpResult['otp'],
            'otp_expired_at' => $otpResult['expired_at'],
        ], now()->addMinutes(15));

        return redirect()->route('otp.verify.form', ['token' => $token])
            ->with('status', $otpResult['message']);
    }

    public function showVerifyOtp($token)
    {
        $pending = Cache::get('pending_register_' . $token);

        if ($pending) {
            $petani = (object) [
                'token' => $token,
                'nama' => $pending['nama'],
                'kontak' => $pending['kontak'],
                'id_petani' => $token,
            ];
            return view('auth.verify-otp', ['petani' => $petani, 'token' => $token]);
        }

        // Fallback untuk akun lama yang sudah di database namun belum diverifikasi
        if (is_numeric($token)) {
            $petani = Petani::find($token);
            if ($petani && !$petani->is_verified) {
                return view('auth.verify-otp', ['petani' => $petani, 'token' => (string)$petani->id_petani]);
            }
        }

        return redirect()->route('register.petani')
            ->withErrors(['error' => 'Sesi verifikasi OTP telah kedaluwarsa atau tidak valid. Silakan lakukan pendaftaran kembali.']);
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'token' => 'nullable|string',
            'id_petani' => 'nullable',
            'otp_code' => 'required|string|size:6',
        ]);

        $token = $validated['token'] ?? $validated['id_petani'] ?? null;
        if (!$token) {
            return redirect()->route('register.petani')
                ->withErrors(['error' => 'Token verifikasi tidak valid.']);
        }

        $pending = Cache::get('pending_register_' . $token);

        if ($pending) {
            if (Carbon::now()->isAfter($pending['otp_expired_at'])) {
                return back()->withErrors(['otp_code' => 'Kode OTP telah kedaluwarsa. Silakan klik kirim ulang kode OTP.']);
            }

            if ($pending['otp_code'] !== trim($validated['otp_code'])) {
                return back()->withErrors(['otp_code' => 'Kode OTP salah. Silakan coba lagi atau periksa kembali WhatsApp Anda.']);
            }

            // Validasi ulang sebelum insert untuk mencegah duplikasi
            if (Petani::where('username', $pending['username'])->exists()) {
                Cache::forget('pending_register_' . $token);
                return redirect()->route('register.petani')
                    ->withErrors(['username' => 'Username ini sudah terdaftar oleh akun lain. Silakan daftar ulang.']);
            }

            if (Petani::where('kontak', $pending['kontak'])->exists()) {
                Cache::forget('pending_register_' . $token);
                return redirect()->route('register.petani')
                    ->withErrors(['kontak' => 'Nomor WhatsApp ini sudah terdaftar. Silakan login.']);
            }

            // Setelah kode OTP diverifikasi dengan benar, BARU masukkan ke database dan masuk ke daftar manajemen user
            Petani::create([
                'username' => $pending['username'],
                'password' => $pending['password'],
                'nama' => $pending['nama'],
                'kontak' => $pending['kontak'],
                'alamat' => null,
                'kelompok_tani' => $pending['kelompok_tani'],
                'is_verified' => true,
                'is_active' => true,
                'otp_code' => null,
                'otp_expired_at' => null,
            ]);

            Cache::forget('pending_register_' . $token);

            return redirect()->route('login')
                ->with('status', 'Pendaftaran berhasil dan akun telah aktif! Silakan login untuk melanjutkan.');
        }

        // Fallback untuk petani di database lama jika ada
        if (is_numeric($token)) {
            $petani = Petani::find($token);
            if ($petani && $this->otpService->verifyOtp($petani, $validated['otp_code'])) {
                return redirect()->route('login')
                    ->with('status', 'Akun Petani berhasil diverifikasi! Silakan login untuk melanjutkan.');
            }
        }

        return back()->withErrors(['otp_code' => 'Sesi verifikasi OTP tidak ditemukan atau telah kedaluwarsa. Silakan daftar kembali.']);
    }

    public function resendOtp($token)
    {
        $pending = Cache::get('pending_register_' . $token);

        if ($pending) {
            $otpResult = $this->otpService->sendOtpToPhone($pending['nama'], $pending['kontak']);
            $pending['otp_code'] = $otpResult['otp'];
            $pending['otp_expired_at'] = $otpResult['expired_at'];

            // Perpanjang masa sesi di cache
            Cache::put('pending_register_' . $token, $pending, now()->addMinutes(15));

            return back()->with('status', $otpResult['message']);
        }

        if (is_numeric($token)) {
            $petani = Petani::find($token);
            if ($petani) {
                $otpResult = $this->otpService->sendOtp($petani);
                return back()->with('status', $otpResult['message']);
            }
        }

        return redirect()->route('register.petani')
            ->withErrors(['error' => 'Sesi pendaftaran telah kedaluwarsa. Silakan melakukan pendaftaran ulang.']);
    }

    public function showLogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $username = trim($validated['username']);
        $password = $validated['password'];

        // 1. Coba autentikasi sebagai Super Admin (User)
        $admin = User::where('username', $username)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            if (!$admin->is_active) {
                return back()->withInput()->withErrors(['username' => 'Akun Super Admin Anda telah dinonaktifkan.']);
            }
            Auth::guard('web')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')
                ->with('status', 'Selamat datang Super Admin KopiLink!');
        }

        // 2. Coba autentikasi sebagai Coffee Shop / Roastery Mitra
        $cs = CoffeeShop::where('username', $username)->first();
        if ($cs && Hash::check($password, $cs->password)) {
            if (!$cs->is_active) {
                return back()->withInput()->withErrors(['username' => 'Akun Coffee Shop Anda telah dinonaktifkan oleh Administrator.']);
            }
            Auth::guard('coffee_shop')->login($cs);
            $request->session()->regenerate();
            return redirect()->route('coffeeshop.dashboard')
                ->with('status', 'Selamat datang di Portal Coffee Shop / Roastery Mitra!');
        }

        // 3. Coba autentikasi sebagai Petani Kopi
        $petani = Petani::where('username', $username)->first();
        if ($petani && Hash::check($password, $petani->password)) {
            if (!$petani->is_active) {
                return back()->withInput()->withErrors(['username' => 'Akun Petani Anda telah dinonaktifkan oleh Administrator.']);
            }

            if (!$petani->is_verified) {
                $this->otpService->sendOtp($petani);
                return redirect()->route('otp.verify.form', ['token' => (string)$petani->id_petani])
                    ->with('warning', 'Akun belum diverifikasi via WhatsApp OTP. Kode OTP baru telah dikirimkan!');
            }

            Auth::guard('petani')->login($petani);
            $request->session()->regenerate();
            return redirect()->route('petani.dashboard')
                ->with('status', 'Selamat datang kembali, ' . $petani->nama . '!');
        }

        // Kredensial tidak cocok pada ketiga aktor
        return back()->withInput()->withErrors(['username' => 'Username atau kata sandi tidak cocok. Silakan periksa kembali.']);
    }

    public function loginPetani(Request $request)
    {
        return $this->login($request);
    }

    public function loginCoffeeShop(Request $request)
    {
        return $this->login($request);
    }

    public function loginAdmin(Request $request)
    {
        return $this->login($request);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('petani')->check()) {
            Auth::guard('petani')->logout();
        } elseif (Auth::guard('coffee_shop')->check()) {
            Auth::guard('coffee_shop')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
