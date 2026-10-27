<?php

namespace App\Http\Controllers;

use App\Models\CoffeeShop;
use App\Models\KemasanKopi;
use App\Models\Produksi;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CoffeeShopDashboardController extends Controller
{
    protected ImageOptimizationService $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function index()
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();

        $totalTersedia = Produksi::where('status', 'Tersedia')->count();
        $totalDiterima = Produksi::where('status', 'Diterima Coffee Shop')->count();
        $totalKemasan = KemasanKopi::where('id_coffee_shop', $coffeeShop->id_coffee_shop)->count();

        $recentKemasan = KemasanKopi::where('id_coffee_shop', $coffeeShop->id_coffee_shop)
            ->with(['produksi.lahan.petani'])
            ->latest()
            ->take(5)
            ->get();

        return view('coffeeshop.dashboard', compact('coffeeShop', 'totalTersedia', 'totalDiterima', 'totalKemasan', 'recentKemasan'));
    }

    public function pasokanIndex()
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();
        $pasokanList = Produksi::where('status', 'Tersedia')
            ->with(['lahan.petani'])
            ->latest()
            ->get();

        return view('coffeeshop.pasokan.index', compact('coffeeShop', 'pasokanList'));
    }

    public function terimaProduksi($id_produksi)
    {
        $produksi = Produksi::where('status', 'Tersedia')->findOrFail($id_produksi);
        $produksi->update([
            'status' => 'Diterima Coffee Shop',
        ]);

        return redirect()->route('coffeeshop.produksi.diterima')
            ->with('status', 'Berhasil menerima pasokan biji kopi! Data kini siap untuk pengemasan dan pembuatan QR Code.');
    }

    public function produksiDiterimaIndex()
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();
        $produksiList = Produksi::where('status', 'Diterima Coffee Shop')
            ->with(['lahan.petani', 'kemasan'])
            ->latest()
            ->get();

        return view('coffeeshop.produksi-diterima.index', compact('coffeeShop', 'produksiList'));
    }

    public function generateQr(Request $request, $id_produksi)
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();
        $produksi = Produksi::where('status', 'Diterima Coffee Shop')->with('lahan')->findOrFail($id_produksi);

        $request->validate([
            'foto_sangrai' => 'required|array|min:1',
            'foto_sangrai.*' => 'image|max:10240',
        ], [
            'foto_sangrai.required' => 'Wajib mengunggah minimal 1 foto green bean yang telah disangrai (roasting).',
            'foto_sangrai.min' => 'Wajib mengunggah minimal 1 foto green bean yang telah disangrai (roasting).',
            'foto_sangrai.*.image' => 'File harus berupa gambar valid (JPG, PNG, JPEG, WEBP).',
            'foto_sangrai.*.max' => 'Ukuran gambar maksimal adalah 10MB.',
        ]);

        $paths = [];
        if ($request->hasFile('foto_sangrai')) {
            foreach ($request->file('foto_sangrai') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'sangrai');
                }
            }
        }

        // Buat slug unik: misal KPL-ROB-A89B atau KPL-LIB-X12Y
        $prefix = ($produksi->lahan->jenis_kopi === 'Robusta') ? 'KPL-ROB' : 'KPL-LIB';
        $slug = $prefix . '-' . date('Y') . '-' . strtoupper(Str::random(6));

        // Pastikan unik
        while (KemasanKopi::where('qr_code_url', $slug)->exists()) {
            $slug = $prefix . '-' . date('Y') . '-' . strtoupper(Str::random(6));
        }

        $kemasan = KemasanKopi::create([
            'id_coffee_shop' => $coffeeShop->id_coffee_shop,
            'id_produksi' => $produksi->id_produksi,
            'qr_code_url' => $slug,
            'foto_sangrai' => !empty($paths) ? json_encode($paths) : null,
        ]);

        return redirect()->route('coffeeshop.kemasan.index')
            ->with('status', "Label QR Code berhasil diterbitkan dengan kode unik: {$slug} beserta dokumentasi sangrai.");
    }

    public function kemasanIndex()
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();
        $kemasanList = KemasanKopi::where('id_coffee_shop', $coffeeShop->id_coffee_shop)
            ->with(['produksi.lahan.petani'])
            ->latest()
            ->get();

        return view('coffeeshop.kemasan.index', compact('coffeeShop', 'kemasanList'));
    }

    public function toggleStatus($id_kemasan)
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();
        $kemasan = KemasanKopi::where('id_coffee_shop', $coffeeShop->id_coffee_shop)
            ->findOrFail($id_kemasan);

        $kemasan->is_active = !$kemasan->is_active;
        $kemasan->save();

        $statusMsg = $kemasan->is_active
            ? "Tampilan QR kemasan [{$kemasan->qr_code_url}] berhasil diaktifkan kembali di publik."
            : "Tampilan QR kemasan [{$kemasan->qr_code_url}] dinonaktifkan dari publik. Petani mitra kini dapat mengedit/mengoreksi data produksi terkait.";

        return redirect()->route('coffeeshop.kemasan.index')
            ->with('status', $statusMsg);
    }

    public function printLabel($id_kemasan)
    {
        $coffeeShop = Auth::guard('coffee_shop')->user();
        $kemasan = KemasanKopi::where('id_coffee_shop', $coffeeShop->id_coffee_shop)
            ->with(['produksi.lahan.petani', 'coffeeShop'])
            ->findOrFail($id_kemasan);

        $traceUrl = url('/trace/' . $kemasan->qr_code_url);

        return view('coffeeshop.kemasan.print', compact('kemasan', 'traceUrl'));
    }
}
