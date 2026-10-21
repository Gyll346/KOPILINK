<?php

namespace App\Services;

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\CoffeeShop;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $petani = Petani::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'nama' => $validated['nama'],
            'kontak' => $formattedPhone,
            'alamat' => null,
            'kelompok_tani' => $validated['kelompok_tani'],
            'is_verified' => false,
        ]);

        $otpResult = $this->otpService->sendOtp($petani);

        return redirect()->route('otp.verify.form', ['id_petani' => $petani->id_petani])
            ->with('status', $otpResult['message']);
    }

    public function showVerifyOtp($id_petani)
    {
        $petani = Petani::findOrFail($id_petani);
        return view('auth.verify-otp', compact('petani'));
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'id_petani' => 'required|exists:petani,id_petani',
            'otp_code' => 'required|string|size:6',
        ]);

        $petani = Petani::findOrFail($validated['id_petani']);

        if ($this->otpService->verifyOtp($petani, $validated['otp_code'])) {
            return redirect()->route('login')
                ->with('status', 'Akun Petani berhasil diverifikasi! Silakan login untuk melanjutkan.');
        }

        return back()->withErrors(['otp_code' => 'Kode OTP salah atau telah kedaluwarsa. Silakan coba lagi atau kirim ulang OTP.']);
    }

    public function resendOtp($id_petani)
    {
        $petani = Petani::findOrFail($id_petani);
        $otpResult = $this->otpService->sendOtp($petani);

        return back()->with('status', $otpResult['message']);
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function loginPetani(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $petani = Petani::where('username', $validated['username'])->first();

        if (!$petani || !Hash::check($validated['password'], $petani->password)) {
            return back()->withInput()->withErrors(['username' => 'Username atau password salah!']);
        }

        if (!$petani->is_verified) {
            $this->otpService->sendOtp($petani);
            return redirect()->route('otp.verify.form', ['id_petani' => $petani->id_petani])
                ->with('warning', 'Akun belum diverifikasi via WhatsApp OTP. Kode OTP baru telah dikirimkan!');
        }

        Auth::guard('petani')->login($petani);
        $request->session()->regenerate();

        return redirect()->route('petani.dashboard')
            ->with('status', 'Selamat datang kembali, ' . $petani->nama . '!');
    }

    public function loginCoffeeShop(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('coffee_shop')->attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('coffeeshop.dashboard')
                ->with('status', 'Selamat datang di Portal Coffee Shop / Roastery Mitra!');
        }

        return back()->withInput()->withErrors(['username' => 'Kredensial Coffee Shop tidak valid.']);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('petani')->check()) {
            Auth::guard('petani')->logout();
        } elseif (Auth::guard('coffee_shop')->check()) {
            Auth::guard('coffee_shop')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
