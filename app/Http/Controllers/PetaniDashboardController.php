<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Produksi;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniDashboardController extends Controller
{
    protected ImageOptimizationService $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();
        $totalPohon = $lahanList->sum('jumlah_tanaman');
        $totalLahanHa = $lahanList->sum('luas_lahan');

        $lahanIds = $lahanList->pluck('id_lahan');
        $produksiList = Produksi::whereIn('id_lahan', $lahanIds)->latest()->get();
        $totalGreenBean = $produksiList->sum('jumlah_green_bean');

        return view('petani.dashboard', compact('petani', 'lahanList', 'produksiList', 'totalPohon', 'totalLahanHa', 'totalGreenBean'));
    }

    public function lahanIndex()
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->latest()->get();
        return view('petani.lahan.index', compact('petani', 'lahanList'));
    }

    public function lahanCreate()
    {
        $petani = Auth::guard('petani')->user();
        $autoJenisKopi = ($petani->kelompok_tani === 'Pasak Piang') ? 'Robusta' : 'Liberika';
        return view('petani.lahan.create', compact('petani', 'autoJenisKopi'));
    }

    public function lahanStore(Request $request)
    {
        $petani = Auth::guard('petani')->user();

        $validated = $request->validate([
            'koordinat_x' => 'required|numeric|between:-180,180',
            'koordinat_y' => 'required|numeric|between:-90,90',
            'luas_lahan' => 'required|numeric|min:0.01',
            'jumlah_tanaman' => 'required|integer|min:1',
            'foto_lahan' => 'nullable|image|max:10240', // Max 10MB sebelum kompresi
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_lahan')) {
            $fotoPath = $this->imageService->compressAndStore($request->file('foto_lahan'), 'lahan');
        }

        // Auto-assign jenis kopi sesuai aturan bisnis
        $jenisKopi = ($petani->kelompok_tani === 'Pasak Piang') ? 'Robusta' : 'Liberika';

        Lahan::create([
            'id_petani' => $petani->id_petani,
            'koordinat_x' => $validated['koordinat_x'],
            'koordinat_y' => $validated['koordinat_y'],
            'luas_lahan' => $validated['luas_lahan'],
            'jumlah_tanaman' => $validated['jumlah_tanaman'],
            'jenis_kopi' => $jenisKopi,
            'foto_lahan' => $fotoPath,
        ]);

        return redirect()->route('petani.lahan.index')
            ->with('status', "Data Lahan {$jenisKopi} berhasil didaftarkan ke sistem KopiLink!");
    }

    public function produksiIndex()
    {
        $petani = Auth::guard('petani')->user();
        $lahanIds = Lahan::where('id_petani', $petani->id_petani)->pluck('id_lahan');
        $produksiList = Produksi::whereIn('id_lahan', $lahanIds)->with('lahan')->latest()->get();

        return view('petani.produksi.index', compact('petani', 'produksiList'));
    }

    public function produksiCreate()
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();

        if ($lahanList->isEmpty()) {
            return redirect()->route('petani.lahan.create')
                ->with('warning', 'Harap input data lahan terlebih dahulu sebelum mencatat hasil panen produksi.');
        }

        return view('petani.produksi.create', compact('petani', 'lahanList'));
    }

    public function produksiStore(Request $request)
    {
        $petani = Auth::guard('petani')->user();
        $lahanIds = Lahan::where('id_petani', $petani->id_petani)->pluck('id_lahan')->toArray();

        $validated = $request->validate([
            'id_lahan' => 'required|in:' . implode(',', $lahanIds),
            'jumlah_green_bean' => 'required|numeric|min:0.5',
            'tanggal_input' => 'required|date',
            'foto_green_bean' => 'nullable|image|max:10240',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_green_bean')) {
            $fotoPath = $this->imageService->compressAndStore($request->file('foto_green_bean'), 'produksi');
        }

        Produksi::create([
            'id_lahan' => $validated['id_lahan'],
            'jumlah_green_bean' => $validated['jumlah_green_bean'],
            'tanggal_input' => $validated['tanggal_input'],
            'status' => 'Tersedia',
            'foto_green_bean' => $fotoPath,
        ]);

        return redirect()->route('petani.produksi.index')
            ->with('status', 'Data panen green bean berhasil dicatat dengan status Tersedia!');
    }

    public function profile()
    {
        $petani = Auth::guard('petani')->user();
        return view('petani.profile', compact('petani'));
    }

    public function profileUpdate(Request $request)
    {
        /** @var \App\Models\Petani $petani */
        $petani = Auth::guard('petani')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|max:10240',
        ]);

        $petani->nama = $validated['nama'];
        $petani->alamat = $validated['alamat'];

        if ($request->hasFile('foto_profil')) {
            $petani->foto_profil = $this->imageService->compressAndStore($request->file('foto_profil'), 'petani_profil');
        }

        $petani->save();

        return redirect()->route('petani.profile')
            ->with('status', 'Profil dan foto petani berhasil diperbarui!');
    }
}
