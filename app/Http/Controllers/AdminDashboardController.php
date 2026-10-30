<?php

namespace App\Http\Controllers;

use App\Models\CoffeeShop;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Produksi;
use App\Models\User;
use App\Models\KontakPesan;
use App\Models\RekeningDonasi;
use App\Models\DonasiTransaksi;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    protected ImageOptimizationService $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Dashboard Super Admin: Statistik Ringkasan & Grafik Perkembangan Panen
     */
    public function index()
    {
        $totalPetani = Petani::count();
        $totalCoffeeShop = CoffeeShop::count();
        $totalLahan = Lahan::count();
        $totalLuasLahan = Lahan::sum('luas_lahan');
        $totalProduksiKg = Produksi::sum('jumlah_green_bean');

        // Data Grafik: Perkembangan Produksi Panen per Bulan (6 bulan terakhir)
        $chartMonthly = Produksi::select(
            DB::raw("DATE_FORMAT(tanggal_input, '%Y-%m') as periode"),
            DB::raw("SUM(jumlah_green_bean) as total_kg")
        )
            ->groupBy('periode')
            ->orderBy('periode', 'asc')
            ->get();

        // Data Grafik: Produksi Panen berdasarkan Jenis Kopi (Robusta vs Liberika)
        $chartByJenis = Produksi::join('lahan', 'produksi.id_lahan', '=', 'lahan.id_lahan')
            ->select('lahan.jenis_kopi', DB::raw("SUM(produksi.jumlah_green_bean) as total_kg"))
            ->groupBy('lahan.jenis_kopi')
            ->get();

        // Data Grafik: Produksi Panen berdasarkan Kelompok Tani
        $chartByKelompok = Produksi::join('lahan', 'produksi.id_lahan', '=', 'lahan.id_lahan')
            ->join('petani', 'lahan.id_petani', '=', 'petani.id_petani')
            ->select('petani.kelompok_tani', DB::raw("SUM(produksi.jumlah_green_bean) as total_kg"))
            ->groupBy('petani.kelompok_tani')
            ->get();

        $recentPanen = Produksi::with(['lahan.petani'])
            ->orderBy('tanggal_input', 'desc')
            ->take(5)
            ->get();

        // Data Arus Kas Donasi & Modal
        $masukQuery = fn() => DonasiTransaksi::where('jenis', 'masuk')
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', 'settlement');
            });
        $totalMasuk = (float) $masukQuery()->sum('nominal');
        $totalKeluar = (float) DonasiTransaksi::where('jenis', 'keluar')->sum('nominal');
        $saldoKas = $totalMasuk - $totalKeluar;
        $recentTransaksi = DonasiTransaksi::orderBy('tanggal', 'desc')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPetani',
            'totalCoffeeShop',
            'totalLahan',
            'totalLuasLahan',
            'totalProduksiKg',
            'chartMonthly',
            'chartByJenis',
            'chartByKelompok',
            'recentPanen',
            'totalMasuk',
            'totalKeluar',
            'saldoKas',
            'recentTransaksi'
        ));
    }

    /**
     * Halaman Manajemen User (Petani, Coffee Shop, dan Admin)
     */
    public function usersIndex()
    {
        $petanis = Petani::withCount('lahan')->orderBy('created_at', 'desc')->get();
        $coffeeShops = CoffeeShop::withCount('kemasan')->orderBy('created_at', 'desc')->get();
        $admins = User::orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('petanis', 'coffeeShops', 'admins'));
    }

    /**
     * Verifikasi Cepat Akun Petani
     */
    public function verifyPetani($id_petani)
    {
        $petani = Petani::findOrFail($id_petani);
        $petani->update([
            'is_verified' => true,
            'otp_code' => null,
            'otp_expired_at' => null,
        ]);

        return back()->with('status', "Akun Petani {$petani->nama} berhasil diverifikasi!");
    }

    /**
     * Reset Password User (Petani / Coffee Shop / Admin)
     */
    public function resetPassword(Request $request, $type, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $hashed = Hash::make($request->new_password);

        if ($type === 'petani') {
            $user = Petani::findOrFail($id);
            $user->update(['password' => $hashed]);
        } elseif ($type === 'coffeeshop') {
            $user = CoffeeShop::findOrFail($id);
            $user->update(['password' => $hashed]);
        } elseif ($type === 'admin') {
            $user = User::findOrFail($id);
            $user->update(['password' => $hashed]);
        } else {
            return back()->withErrors(['error' => 'Tipe user tidak valid!']);
        }

        return back()->with('status', "Password untuk {$user->username} berhasil diperbarui!");
    }

    /**
     * Tambah User Baru oleh Super Admin (Coffee Shop atau Admin)
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'role_type' => 'required|in:coffeeshop,admin',
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:6',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email',
        ]);

        if ($validated['role_type'] === 'coffeeshop') {
            if (CoffeeShop::where('username', $validated['username'])->exists()) {
                return back()->withErrors(['username' => 'Username roastery sudah terdaftar.']);
            }
            CoffeeShop::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'nama_toko' => $validated['nama'],
            ]);
        } elseif ($validated['role_type'] === 'admin') {
            if (User::where('username', $validated['username'])->exists()) {
                return back()->withErrors(['username' => 'Username admin sudah terdaftar.']);
            }
            User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? ($validated['username'] . '@kopilink.id'),
                'password' => Hash::make($validated['password']),
                'role' => 'super_admin',
            ]);
        }

        return back()->with('status', 'User baru berhasil ditambahkan!');
    }

    /**
     * Hapus User (Petani / Coffee Shop / Admin)
     */
    public function destroyUser($type, $id)
    {
        if ($type === 'petani') {
            $user = Petani::findOrFail($id);
            $user->delete();
        } elseif ($type === 'coffeeshop') {
            $user = CoffeeShop::findOrFail($id);
            $user->delete();
        } elseif ($type === 'admin') {
            $user = User::findOrFail($id);
            if ($user->id === auth('web')->id()) {
                return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri!']);
            }
            $user->delete();
        }

        return back()->with('status', 'User berhasil dihapus dari sistem.');
    }

    /**
     * Aktifkan / Nonaktifkan Akun User (Petani, Coffee Shop, atau Admin)
     */
    public function toggleUserStatus($type, $id)
    {
        if ($type === 'petani') {
            $user = Petani::findOrFail($id);
            $user->is_active = !$user->is_active;
            $user->save();
            $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('status', "Akun Petani {$user->nama} berhasil {$statusText}!");
        } elseif ($type === 'coffeeshop') {
            $user = CoffeeShop::findOrFail($id);
            $user->is_active = !$user->is_active;
            $user->save();
            $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('status', "Akun Coffee Shop {$user->nama_toko} berhasil {$statusText}!");
        } elseif ($type === 'admin') {
            $user = User::findOrFail($id);
            if ($user->id === auth('web')->id()) {
                return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri!']);
            }
            $user->is_active = !$user->is_active;
            $user->save();
            $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('status', "Akun Admin {$user->name} berhasil {$statusText}!");
        }

        return back()->withErrors(['error' => 'Tipe user tidak valid!']);
    }

    /**
     * Halaman Kotak Masuk Pesan Pengunjung / Kontak Kami
     */
    public function pesanIndex()
    {
        $pesanList = KontakPesan::orderBy('created_at', 'desc')->get();
        $unreadCount = KontakPesan::where('is_read', false)->count();

        return view('admin.pesan', compact('pesanList', 'unreadCount'));
    }

    /**
     * Tandai Pesan Telah Dibaca / Belum Dibaca
     */
    public function pesanToggleRead($id_pesan)
    {
        $pesan = KontakPesan::findOrFail($id_pesan);
        $pesan->is_read = !$pesan->is_read;
        $pesan->save();

        $statusText = $pesan->is_read ? 'ditandai sudah dibaca' : 'ditandai belum dibaca';
        return back()->with('status', "Pesan dari {$pesan->nama} berhasil {$statusText}.");
    }

    /**
     * Hapus Pesan Kontak
     */
    public function pesanDestroy($id_pesan)
    {
        $pesan = KontakPesan::findOrFail($id_pesan);
        $nama = $pesan->nama;
        $pesan->delete();

        return back()->with('status', "Pesan dari {$nama} berhasil dihapus.");
    }

    /**
     * Halaman Khusus Donasi & Arus Kas Donasi Super Admin
     */
    public function donasiIndex()
    {
        $queryRekening = RekeningDonasi::orderBy('is_active', 'desc')->latest();
        if ($searchRek = request('search_rekening')) {
            $queryRekening->where(function ($q) use ($searchRek) {
                $q->where('nama_bank', 'like', "%{$searchRek}%")
                  ->orWhere('nomor_rekening', 'like', "%{$searchRek}%")
                  ->orWhere('atas_nama', 'like', "%{$searchRek}%");
            });
        }
        $rekeningList = $queryRekening->get();
        $transaksiList = DonasiTransaksi::with('rekening')->orderBy('tanggal', 'desc')->latest()->get();

        $masukQuery = fn() => DonasiTransaksi::where('jenis', 'masuk')
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', 'settlement');
            });

        $totalMasuk = $masukQuery()->sum('nominal');
        $totalMasukSeedling = (float) $masukQuery()
            ->where(function ($q) {
                $q->where('kategori', 'like', '%Seedling%')
                  ->orWhere('kategori', 'like', '%Bibit%');
            })->sum('nominal');
        $countSeedling = (int) floor($totalMasukSeedling / 16000);

        $totalMasukCup = (float) $masukQuery()
            ->where(function ($q) {
                $q->where('kategori', 'like', '%Cup%')
                  ->orWhere('kategori', 'like', '%Cangkir%');
            })->sum('nominal');
        $countCup = (int) floor($totalMasukCup / 200);

        $totalKeluar = DonasiTransaksi::where('jenis', 'keluar')->sum('nominal');
        $saldoKas = $totalMasuk - $totalKeluar;

        $serverKey = config('midtrans.server_key') ?: env('MIDTRANS_SERVER_KEY', '');
        $midtransConfig = [
            'server_key' => $serverKey,
            'client_key' => config('midtrans.client_key') ?: env('MIDTRANS_CLIENT_KEY', ''),
            'is_production' => (bool) (config('midtrans.is_production') ?? env('MIDTRANS_IS_PRODUCTION', false)),
            'is_configured' => !empty($serverKey) && !str_contains($serverKey, 'XXXX'),
        ];

        return view('admin.donasi', compact(
            'rekeningList',
            'transaksiList',
            'totalMasuk',
            'totalMasukSeedling',
            'countSeedling',
            'totalMasukCup',
            'countCup',
            'totalKeluar',
            'saldoKas',
            'midtransConfig'
        ));
    }

    /**
     * Update Midtrans API Credentials & Environment (.env)
     */
    public function midtransUpdate(Request $request)
    {
        $validated = $request->validate([
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_is_production' => 'nullable|in:0,1',
        ]);

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);
            $isProduction = ($request->input('midtrans_is_production', '0') === '1') ? 'true' : 'false';

            $updates = [
                'MIDTRANS_CLIENT_KEY' => trim($validated['midtrans_client_key'] ?? ''),
                'MIDTRANS_IS_PRODUCTION' => $isProduction,
            ];

            if (!empty($validated['midtrans_server_key'])) {
                $updates['MIDTRANS_SERVER_KEY'] = trim($validated['midtrans_server_key']);
            }

            foreach ($updates as $key => $val) {
                if (preg_match("/^{$key}=.*/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $content);
                } else {
                    $content .= "\n{$key}={$val}";
                }
            }

            file_put_contents($envPath, $content);
            \Illuminate\Support\Facades\Artisan::call('config:clear');
        }

        return back()->with('status', 'Konfigurasi API Midtrans QRIS berhasil diperbarui!');
    }

    /**
     * Simpan Rekening Donasi Baru
     */
    public function rekeningStore(Request $request)
    {
        $validated = $request->validate([
            'nama_bank' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Jika dipilih sebagai rekening aktif, matikan rekening lainnya
        if ($request->has('is_active') && $request->is_active) {
            RekeningDonasi::where('is_active', true)->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = RekeningDonasi::count() === 0;
        }

        RekeningDonasi::create($validated);

        return back()->with('status', 'Rekening penerima donasi berhasil ditambahkan!');
    }

    /**
     * Perbarui Data Rekening Donasi
     */
    public function rekeningUpdate(Request $request, $id_rekening)
    {
        $rekening = RekeningDonasi::findOrFail($id_rekening);

        $validated = $request->validate([
            'nama_bank' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->has('is_active') && $request->is_active) {
            RekeningDonasi::where('id_rekening', '!=', $id_rekening)->update(['is_active' => false]);
            $validated['is_active'] = true;
        }

        $rekening->update($validated);

        return back()->with('status', "Data rekening [{$rekening->nama_bank} - {$rekening->nomor_rekening}] berhasil diperbarui!");
    }

    /**
     * Toggle Rekening Tujuan Utama (Set Rekening Aktif)
     */
    public function rekeningToggle($id_rekening)
    {
        $rekening = RekeningDonasi::findOrFail($id_rekening);
        
        // Jadikan rekening ini aktif, dan nonaktifkan lainnya
        RekeningDonasi::where('id_rekening', '!=', $id_rekening)->update(['is_active' => false]);
        $rekening->is_active = true;
        $rekening->save();

        return back()->with('status', "Rekening [{$rekening->nama_bank} - {$rekening->nomor_rekening} a.n {$rekening->atas_nama}] diatur sebagai rekening penerima donasi utama.");
    }

    /**
     * Hapus Rekening Donasi
     */
    public function rekeningDestroy($id_rekening)
    {
        $rekening = RekeningDonasi::findOrFail($id_rekening);
        $rekening->delete();

        return back()->with('status', 'Rekening tujuan donasi berhasil dihapus.');
    }

    /**
     * Simpan Transaksi Kas Donasi Keluar (Penyaluran)
     * Catatan: Uang masuk dihitung otomatis dari sistem donasi publik.
     */
    public function donasiTransaksiStore(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:keluar',
            'nominal' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
            'nama_pihak' => 'required|string|max:150',
            'id_rekening' => 'nullable|exists:rekening_donasi,id_rekening',
            'kategori' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'bukti_transfer' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('bukti_transfer')) {
            $validated['bukti_transfer'] = $this->imageService->compressAndStore($request->file('bukti_transfer'), 'donasi');
        }

        DonasiTransaksi::create($validated);

        return back()->with('status', "Catatan penyaluran uang keluar sebesar Rp " . number_format($validated['nominal'], 0, ',', '.') . " berhasil disimpan!");
    }

    /**
     * Hapus Catatan Transaksi Kas Donasi
     */
    public function donasiTransaksiDestroy($id_transaksi)
    {
        $transaksi = DonasiTransaksi::findOrFail($id_transaksi);
        
        if ($transaksi->bukti_transfer && Storage::disk('public')->exists($transaksi->bukti_transfer)) {
            Storage::disk('public')->delete($transaksi->bukti_transfer);
        }

        $transaksi->delete();

        return back()->with('status', 'Catatan transaksi kas donasi berhasil dihapus.');
    }
}
