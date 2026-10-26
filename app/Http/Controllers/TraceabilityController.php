<?php

namespace App\Http\Controllers;

use App\Models\KemasanKopi;
use App\Models\Produksi;
use App\Models\Petani;
use App\Models\KontakPesan;
use Illuminate\Http\Request;

class TraceabilityController extends Controller
{
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
        return view('pages.donasi');
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
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subjek' => 'required|string|max:150',
            'pesan' => 'required|string',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek pesan wajib diisi.',
            'pesan.required' => 'Isi pesan tidak boleh kosong.',
        ]);

        KontakPesan::create($validated);

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
