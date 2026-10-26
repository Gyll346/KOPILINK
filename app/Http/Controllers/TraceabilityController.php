<?php

namespace App\Http\Controllers;

use App\Models\KemasanKopi;
use App\Models\Produksi;
use App\Models\Petani;
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

    public function menu()
    {
        return view('pages.menu');
    }

    public function contact()
    {
        return view('pages.contact');
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
