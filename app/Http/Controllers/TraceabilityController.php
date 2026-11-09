<?php

namespace App\Http\Controllers;

use App\Models\KemasanKopi;
use App\Models\Produksi;
use App\Models\Petani;
use App\Models\KontakPesan;
use App\Models\RekeningDonasi;
use App\Models\DonasiTransaksi;
use App\Services\ImageOptimizationService;
use App\Services\BayarajaService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TraceabilityController extends Controller
{
    protected ?ImageOptimizationService $imageService;

    public function __construct(?ImageOptimizationService $imageService = null)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $totalPetani = Petani::count();
        $totalKemasan = KemasanKopi::where('is_active', true)->count();
        $totalProduksi = Produksi::sum('jumlah_green_bean');
        $recentTraces = KemasanKopi::with(['produksi.lahan.petani', 'coffeeShop'])
            ->where('is_active', true)
            ->orderByDesc('id_kemasan')
            ->get();

        return view('pages.home', compact('totalPetani', 'totalKemasan', 'totalProduksi', 'recentTraces'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function service()
    {
        return view('pages.service');
    }

    public function donasi()
    {
        $rekeningAktif = RekeningDonasi::where('is_active', true)->first();
        $bayaraja = new BayarajaService();
        $qrisEnabled = $bayaraja->isConfigured();
        return view('pages.donasi', compact('rekeningAktif', 'qrisEnabled'));
    }

    /**
     * Legacy: Manual donation (bukti transfer).
     * Still works as fallback when Midtrans is not configured.
     */
    public function donasiSend(Request $request)
    {
        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:150',
            'program' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:200',
            'pesan' => 'nullable|string|max:500',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
        ], [
            'nama_donatur.required' => 'Nama donatur wajib diisi.',
            'nominal.required' => 'Nominal donasi wajib diisi.',
            'nominal.min' => 'Nominal donasi minimal Rp 200.',
            'bukti_transfer.image' => 'Bukti transfer harus berupa file gambar valid.',
        ]);

        // Kunci nilai donasi: $1 (Rp 16.000) untuk Seedling, Rp 200 untuk Cup of Coffee
        if (str_contains(strtolower($validated['program']), 'seedling') || str_contains(strtolower($validated['program']), 'bibit')) {
            $validated['nominal'] = 16000;
        } elseif (str_contains(strtolower($validated['program']), 'cup') || str_contains(strtolower($validated['program']), 'cangkir')) {
            $validated['nominal'] = 200;
        }

        $rekeningAktif = RekeningDonasi::where('is_active', true)->first();
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            if ($this->imageService) {
                $buktiPath = $this->imageService->compressAndStore($request->file('bukti_transfer'), 'donasi');
            } else {
                $buktiPath = $request->file('bukti_transfer')->store('donasi', 'public');
            }
        }

        DonasiTransaksi::create([
            'id_rekening' => $rekeningAktif ? $rekeningAktif->id_rekening : null,
            'jenis' => 'masuk',
            'nominal' => $validated['nominal'],
            'tanggal' => date('Y-m-d'),
            'nama_pihak' => $validated['nama_donatur'],
            'kategori' => $validated['program'],
            'keterangan' => $validated['pesan'] ?? 'Donasi langsung dari website KopiLink',
            'bukti_transfer' => $buktiPath,
            'status' => 'settlement',
        ]);

        return redirect()->route('donasi')
            ->with('status', 'Terima kasih banyak, ' . $validated['nama_donatur'] . '! Konfirmasi donasi Anda sebesar Rp ' . number_format($validated['nominal'], 0, ',', '.') . ' telah berhasil dikirim ke Super Admin KopiLink.');
    }

    /**
     * AJAX: Create Midtrans QRIS payment → returns QR code URL.
     */
    public function donasiCreateQris(Request $request)
    {
        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:150',
            'program' => 'required|string|max:100',
            'nominal' => 'required|integer|min:200',
            'pesan' => 'nullable|string|max:500',
        ]);

        // Kunci nilai donasi: $1 (Rp 16.000) untuk Seedling, Rp 200 untuk Cup of Coffee
        if (str_contains(strtolower($validated['program']), 'seedling') || str_contains(strtolower($validated['program']), 'bibit')) {
            $validated['nominal'] = 16000;
        } elseif (str_contains(strtolower($validated['program']), 'cup') || str_contains(strtolower($validated['program']), 'cangkir')) {
            $validated['nominal'] = 200;
        }

        $rekeningAktif = RekeningDonasi::where('is_active', true)->first();
        $orderId = 'KPL-DON-' . strtoupper(Str::random(8)) . '-' . time();

        // Create pending transaction
        $transaksi = DonasiTransaksi::create([
            'id_rekening' => $rekeningAktif?->id_rekening,
            'order_id' => $orderId,
            'jenis' => 'masuk',
            'nominal' => $validated['nominal'],
            'tanggal' => date('Y-m-d'),
            'nama_pihak' => $validated['nama_donatur'],
            'kategori' => $validated['program'],
            'keterangan' => $validated['pesan'] ?? 'Donasi QRIS via website KopiLink',
            'status' => 'pending',
        ]);

        try {
            $bayaraja = new BayarajaService();
            $result = $bayaraja->createQris(
                $orderId,
                (int) $validated['nominal'],
                $validated['nama_donatur'],
                $validated['program']
            );

            return response()->json([
                'success' => true,
                'qr_url' => $result['qr_url'],
                'order_id' => $orderId,
                'expiry_time' => $result['expiry_time'],
                'nominal' => (int) $validated['nominal'],
            ]);
        } catch (\Throwable $e) {
            // Rollback: delete pending transaction
            $transaksi->delete();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QRIS: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * AJAX: Check payment status by order_id (sync directly with Bayaraja / Gateway).
     */
    public function donasiCheckStatus(Request $request)
    {
        $orderId = $request->input('order_id');
        $transaksi = DonasiTransaksi::where('order_id', $orderId)->first();

        if (!$transaksi) {
            return response()->json(['status' => 'not_found'], 404);
        }

        // Sync langsung ke Bayaraja API secara realtime jika masih pending
        if ($transaksi->status === 'pending') {
            try {
                $bayaraja = new BayarajaService();
                $bStatus = $bayaraja->checkStatus($orderId);
                if ($bStatus === 'settlement') {
                    $transaksi->update(['status' => 'settlement']);
                } elseif (in_array($bStatus, ['expire', 'cancel'])) {
                    $transaksi->update(['status' => $bStatus]);
                }
            } catch (\Throwable $e) {
                // Abaikan jika network error sementara
            }
        }

        return response()->json(['status' => $transaksi->fresh()->status]);
    }

    /**
     * AJAX: Mark transaction expired after 5-minute timer ends.
     */
    public function donasiExpire(Request $request)
    {
        $orderId = $request->input('order_id');
        $transaksi = DonasiTransaksi::where('order_id', $orderId)->where('status', 'pending')->first();

        if ($transaksi && $transaksi->created_at && $transaksi->created_at->diffInSeconds(now()) >= 270) {
            $transaksi->update(['status' => 'expire']);
        }

        return response()->json(['success' => true, 'status' => 'expire']);
    }

    /**
     * Bayaraja notification webhook (POST, no CSRF).
     */
    public function bayarajaNotification(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? ($payload['type'] ?? '');
        $data = $payload['data'] ?? $payload;

        $orderId = $data['order_id'] ?? ($data['title'] ?? ($payload['order_id'] ?? ''));

        $transaksi = DonasiTransaksi::where('order_id', $orderId)->first();
        if (!$transaksi && !empty($data['id'])) {
            $transaksi = DonasiTransaksi::where('order_id', 'LIKE', '%' . $data['id'] . '%')->first();
        }

        if ($transaksi) {
            $newStatus = match ($event) {
                'transaction.confirmed', 'payment.success', 'paid', 'confirmed' => 'settlement',
                'transaction.rejected', 'payment.failed', 'rejected' => 'cancel',
                default => 'pending',
            };

            if ($newStatus !== 'pending') {
                $transaksi->update(['status' => $newStatus]);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Midtrans notification webhook (POST, no CSRF).
     */
    public function midtransNotification(Request $request)
    {
        $payload = $request->all();
        $midtrans = new MidtransService();

        if (!$midtrans->verifySignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';

        $transaksi = DonasiTransaksi::where('order_id', $orderId)->first();
        if (!$transaksi) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Map Midtrans status
        $newStatus = match ($transactionStatus) {
            'capture', 'settlement' => 'settlement',
            'pending' => 'pending',
            'deny' => 'deny',
            'cancel' => 'cancel',
            'expire' => 'expire',
            default => $transactionStatus,
        };

        $transaksi->update(['status' => $newStatus]);

        return response()->json(['message' => 'OK']);
    }

    public function menu()
    {
        return redirect()->route('donasi');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSend(Request $request)
    {
        // 1. Anti-Bot Honeypot Check: field tersembunyi yang hanya diisi bot/crawler
        if ($request->filled('_hp_security_check')) {
            Log::warning("Spambot contact attempt blocked from IP: {$request->ip()}");
            return back()->withInput()->withErrors(['pesan' => 'Pengiriman pesan ditolak (terindikasi bot otomatis).']);
        }

        // 2. Timing Check: deteksi pengiriman instan (< 2 detik)
        if ($request->has('_form_ts')) {
            try {
                $formTime = (int) decrypt($request->input('_form_ts'));
                if ((time() - $formTime) < 2) {
                    return back()->withInput()->withErrors(['pesan' => 'Pengiriman pesan terlalu cepat. Mohon tunggu sejenak sebelum mencoba kembali.']);
                }
            } catch (\Throwable $e) {
                // Abaikan kesalahan dekripsi
            }
        }

        // 3. Rate Limiting per IP: maksimal 3 pesan per 5 menit
        $ipKey = 'contact_flood_ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 3)) {
            $seconds = RateLimiter::availableIn($ipKey);
            return back()->withInput()->withErrors([
                'pesan' => "Anda telah mengirimkan beberapa pesan. Untuk mencegah spam, mohon tunggu {$seconds} detik sebelum mengirim lagi."
            ]);
        }

        // 4. Rate Limiting per Email: maksimal 2 pesan per 5 menit
        $email = strtolower(trim((string)$request->input('email', '')));
        if ($email !== '') {
            $emailKey = 'contact_flood_email:' . md5($email);
            if (RateLimiter::tooManyAttempts($emailKey, 2)) {
                $seconds = RateLimiter::availableIn($emailKey);
                return back()->withInput()->withErrors([
                    'pesan' => "Alamat email ini baru saja mengirimkan pesan. Silakan tunggu {$seconds} detik sebelum mengirim pesan berikutnya."
                ]);
            }
        }

        // 5. Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subjek' => 'required|string|max:150',
            'pesan' => 'required|string|min:5|max:3000',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek pesan wajib diisi.',
            'pesan.required' => 'Isi pesan tidak boleh kosong.',
            'pesan.min' => 'Isi pesan terlalu pendek (minimal 5 karakter).',
            'pesan.max' => 'Isi pesan terlalu panjang (maksimal 3000 karakter).',
        ]);

        // 6. Anti-Duplikat: cegah pengiriman isi pesan yang identik dalam 15 menit terakhir
        $isDuplicate = KontakPesan::where('email', $validated['email'])
            ->where('pesan', $validated['pesan'])
            ->where('created_at', '>=', now()->subMinutes(15))
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'pesan' => 'Pesan dengan isi yang sama telah dikirim sebelumnya. Mohon tunggu tanggapan dari tim kami.'
            ]);
        }

        // 7. Simpan pesan ke database
        KontakPesan::create($validated);

        // Catat rate limiting hits (berlaku 300 detik / 5 menit)
        RateLimiter::hit($ipKey, 300);
        if ($email !== '') {
            RateLimiter::hit($emailKey, 300);
        }

        return redirect()->route('contact')
            ->with('status', 'Terima kasih! Pesan Anda telah berhasil dikirim ke tim KopiLink dan akan segera ditinjau oleh Admin.');
    }

    public function scan()
    {
        return view('pages.scan');
    }

    public function show($slug)
    {
        $kemasan = KemasanKopi::where('qr_code_url', $slug)
            ->with(['produksi.lahan.petani', 'coffeeShop'])
            ->firstOrFail();

        // Jika QR dinonaktifkan oleh Coffee Shop untuk revisi data petani
        if (!$kemasan->is_active) {
            return view('pages.trace_inactive', compact('kemasan'));
        }

        $lahan = $kemasan->produksi->lahan;
        $petani = $lahan->petani;
        $produksi = $kemasan->produksi;
        $coffeeShop = $kemasan->coffeeShop;
        $panenCeri = $lahan->panenCeri()->latest('tanggal_panen')->first();

        return view('pages.trace', compact('kemasan', 'lahan', 'petani', 'produksi', 'coffeeShop', 'panenCeri'));
    }
}
