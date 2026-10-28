<?php

namespace App\Http\Controllers;

use App\Models\KemasanKopi;
use App\Models\Produksi;
use App\Models\Petani;
use App\Models\KontakPesan;
use App\Models\RekeningDonasi;
use App\Models\DonasiTransaksi;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;

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
        return view('pages.donasi', compact('rekeningAktif'));
    }

    public function donasiSend(Request $request)
    {
        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:150',
            'program' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:1000',
            'pesan' => 'nullable|string|max:500',
            'bukti_transfer' => 'nullable|image|max:10240',
        ], [
            'nama_donatur.required' => 'Nama donatur wajib diisi.',
            'nominal.required' => 'Nominal donasi wajib diisi.',
            'nominal.min' => 'Nominal donasi minimal Rp 1.000.',
            'bukti_transfer.image' => 'Bukti transfer harus berupa file gambar valid.',
        ]);

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
        ]);

        return redirect()->route('donasi')
            ->with('status', 'Terima kasih banyak, ' . $validated['nama_donatur'] . '! Konfirmasi donasi Anda sebesar Rp ' . number_format($validated['nominal'], 0, ',', '.') . ' telah berhasil dikirim ke Super Admin KopiLink.');
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
