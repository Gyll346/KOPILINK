<?php

namespace App\Http\Controllers;

use App\Models\CoffeeShop;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Produksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
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

        return view('admin.dashboard', compact(
            'totalPetani',
            'totalCoffeeShop',
            'totalLahan',
            'totalLuasLahan',
            'totalProduksiKg',
            'chartMonthly',
            'chartByJenis',
            'chartByKelompok',
            'recentPanen'
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
}
