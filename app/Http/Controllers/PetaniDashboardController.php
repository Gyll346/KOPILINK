<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Produksi;
use App\Models\PanenCeri;
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

        $ceriList = PanenCeri::whereIn('id_lahan', $lahanIds)->latest()->get();
        $totalCeri = $ceriList->sum('jumlah_ceri');

        return view('petani.dashboard', compact('petani', 'lahanList', 'produksiList', 'totalPohon', 'totalLahanHa', 'totalGreenBean', 'ceriList', 'totalCeri'));
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
            'foto_lahan' => 'required|array|min:1',
            'foto_lahan.*' => 'image|max:10240',
        ], [
            'foto_lahan.required' => 'Wajib mengunggah minimal 1 foto lahan/kebun.',
            'foto_lahan.min' => 'Wajib mengunggah minimal 1 foto lahan/kebun.',
        ]);

        $paths = [];
        if ($request->hasFile('foto_lahan')) {
            foreach ($request->file('foto_lahan') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'lahan');
                }
            }
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
            'foto_lahan' => !empty($paths) ? json_encode($paths) : null,
        ]);

        return redirect()->route('petani.lahan.index')
            ->with('status', "Data Lahan {$jenisKopi} berhasil didaftarkan ke sistem KopiLink!");
    }

    public function lahanEdit($id_lahan)
    {
        $petani = Auth::guard('petani')->user();
        $lahan = Lahan::where('id_lahan', $id_lahan)
            ->where('id_petani', $petani->id_petani)
            ->firstOrFail();

        return view('petani.lahan.edit', compact('petani', 'lahan'));
    }

    public function lahanUpdate(Request $request, $id_lahan)
    {
        $petani = Auth::guard('petani')->user();
        $lahan = Lahan::where('id_lahan', $id_lahan)
            ->where('id_petani', $petani->id_petani)
            ->firstOrFail();

        // Jika lahan belum punya foto sama sekali, wajib unggah min 1
        $existingFotos = $lahan->foto_list;
        $rules = [
            'koordinat_x' => 'required|numeric|between:-180,180',
            'koordinat_y' => 'required|numeric|between:-90,90',
            'luas_lahan' => 'required|numeric|min:0.01',
            'jumlah_tanaman' => 'required|integer|min:1',
        ];

        if (empty($existingFotos)) {
            $rules['foto_lahan'] = 'required|array|min:1';
            $rules['foto_lahan.*'] = 'image|max:10240';
        } else {
            $rules['foto_lahan'] = 'nullable|array';
            $rules['foto_lahan.*'] = 'image|max:10240';
        }

        $validated = $request->validate($rules, [
            'foto_lahan.required' => 'Wajib mengunggah minimal 1 foto lahan/kebun.',
            'foto_lahan.min' => 'Wajib mengunggah minimal 1 foto lahan/kebun.',
        ]);

        if ($request->hasFile('foto_lahan')) {
            // Hapus file lama jika ada foto baru yang diunggah
            foreach ($existingFotos as $oldPath) {
                if ($oldPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $paths = [];
            foreach ($request->file('foto_lahan') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'lahan');
                }
            }
            $lahan->foto_lahan = !empty($paths) ? json_encode($paths) : null;
        }

        $lahan->koordinat_x = $validated['koordinat_x'];
        $lahan->koordinat_y = $validated['koordinat_y'];
        $lahan->luas_lahan = $validated['luas_lahan'];
        $lahan->jumlah_tanaman = $validated['jumlah_tanaman'];
        $lahan->save();

        return redirect()->route('petani.lahan.index')
            ->with('status', "Data plot lahan #{$lahan->id_lahan} berhasil diperbarui!");
    }

    public function produksiIndex()
    {
        $petani = Auth::guard('petani')->user();
        $lahanIds = Lahan::where('id_petani', $petani->id_petani)->pluck('id_lahan');
        $produksiList = Produksi::whereIn('id_lahan', $lahanIds)->with(['lahan', 'kemasan'])->latest()->get();

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
            'foto_green_bean' => 'required|array|min:1',
            'foto_green_bean.*' => 'image|max:10240',
        ], [
            'foto_green_bean.required' => 'Wajib mengunggah minimal 1 foto hasil panen green bean.',
            'foto_green_bean.min' => 'Wajib mengunggah minimal 1 foto hasil panen green bean.',
        ]);

        $paths = [];
        if ($request->hasFile('foto_green_bean')) {
            foreach ($request->file('foto_green_bean') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'produksi');
                }
            }
        }

        Produksi::create([
            'id_lahan' => $validated['id_lahan'],
            'jumlah_green_bean' => $validated['jumlah_green_bean'],
            'tanggal_input' => $validated['tanggal_input'],
            'status' => 'Tersedia',
            'foto_green_bean' => !empty($paths) ? json_encode($paths) : null,
        ]);

        return redirect()->route('petani.produksi.index')
            ->with('status', 'Data panen green bean berhasil dicatat dengan status Tersedia!');
    }

    public function produksiEdit($id_produksi)
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();
        $lahanIds = $lahanList->pluck('id_lahan');

        $produksi = Produksi::where('id_produksi', $id_produksi)
            ->whereIn('id_lahan', $lahanIds)
            ->firstOrFail();

        // Guard: Jika status bukan Tersedia, cek apakah Coffee Shop menonaktifkan kemasan untuk revisi data
        if ($produksi->status !== 'Tersedia') {
            $hasActiveKemasan = $produksi->kemasan()->where('is_active', true)->exists();
            if ($hasActiveKemasan) {
                return redirect()->route('petani.produksi.index')
                    ->with('warning', 'Data produksi ini telah diserap oleh Coffee Shop dan tampilan QR publik sedang aktif sehingga tidak dapat diedit langsung. Silakan hubungi Coffee Shop terkait jika memerlukan revisi data.');
            }
        }

        return view('petani.produksi.edit', compact('petani', 'produksi', 'lahanList'));
    }

    public function produksiUpdate(Request $request, $id_produksi)
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();
        $lahanIds = $lahanList->pluck('id_lahan')->toArray();

        $produksi = Produksi::where('id_produksi', $id_produksi)
            ->whereIn('id_lahan', $lahanIds)
            ->firstOrFail();

        // Guard: Jika status bukan Tersedia, cek apakah Coffee Shop menonaktifkan kemasan untuk revisi data
        if ($produksi->status !== 'Tersedia') {
            $hasActiveKemasan = $produksi->kemasan()->where('is_active', true)->exists();
            if ($hasActiveKemasan) {
                return redirect()->route('petani.produksi.index')
                    ->with('warning', 'Perubahan ditolak: Data produksi ini terkunci karena label QR publik sedang aktif.');
            }
        }

        $existingFotos = $produksi->foto_list;
        $rules = [
            'id_lahan' => 'required|in:' . implode(',', $lahanIds),
            'jumlah_green_bean' => 'required|numeric|min:0.5',
            'tanggal_input' => 'required|date',
        ];

        if (empty($existingFotos)) {
            $rules['foto_green_bean'] = 'required|array|min:1';
            $rules['foto_green_bean.*'] = 'image|max:10240';
        } else {
            $rules['foto_green_bean'] = 'nullable|array';
            $rules['foto_green_bean.*'] = 'image|max:10240';
        }

        $validated = $request->validate($rules, [
            'foto_green_bean.required' => 'Wajib mengunggah minimal 1 foto hasil panen green bean.',
            'foto_green_bean.min' => 'Wajib mengunggah minimal 1 foto hasil panen green bean.',
        ]);

        if ($request->hasFile('foto_green_bean')) {
            foreach ($existingFotos as $oldPath) {
                if ($oldPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $paths = [];
            foreach ($request->file('foto_green_bean') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'produksi');
                }
            }
            $produksi->foto_green_bean = !empty($paths) ? json_encode($paths) : null;
        }

        $produksi->id_lahan = $validated['id_lahan'];
        $produksi->jumlah_green_bean = $validated['jumlah_green_bean'];
        $produksi->tanggal_input = $validated['tanggal_input'];
        $produksi->save();

        return redirect()->route('petani.produksi.index')
            ->with('status', "Catatan panen #PRD-{$produksi->id_produksi} berhasil diperbarui!");
    }

    // ==========================================
    // PANEN CERI (BUAH KOPI BASAH)
    // ==========================================

    public function ceriIndex()
    {
        $petani = Auth::guard('petani')->user();
        $lahanIds = Lahan::where('id_petani', $petani->id_petani)->pluck('id_lahan');
        $ceriList = PanenCeri::whereIn('id_lahan', $lahanIds)->with('lahan')->latest()->get();

        return view('petani.ceri.index', compact('petani', 'ceriList'));
    }

    public function ceriCreate()
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();

        if ($lahanList->isEmpty()) {
            return redirect()->route('petani.lahan.create')
                ->with('warning', 'Harap input data lahan kebun terlebih dahulu sebelum mencatat hasil panen ceri.');
        }

        return view('petani.ceri.create', compact('petani', 'lahanList'));
    }

    public function ceriStore(Request $request)
    {
        $petani = Auth::guard('petani')->user();
        $lahanIds = Lahan::where('id_petani', $petani->id_petani)->pluck('id_lahan')->toArray();

        $validated = $request->validate([
            'id_lahan' => 'required|in:' . implode(',', $lahanIds),
            'jumlah_ceri' => 'required|numeric|min:0.5',
            'tanggal_panen' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'foto_ceri' => 'required|array|min:1',
            'foto_ceri.*' => 'image|max:10240',
        ], [
            'foto_ceri.required' => 'Wajib mengunggah minimal 1 foto hasil panen ceri.',
            'foto_ceri.min' => 'Wajib mengunggah minimal 1 foto hasil panen ceri.',
        ]);

        $paths = [];
        if ($request->hasFile('foto_ceri')) {
            foreach ($request->file('foto_ceri') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'ceri');
                }
            }
        }

        PanenCeri::create([
            'id_lahan' => $validated['id_lahan'],
            'jumlah_ceri' => $validated['jumlah_ceri'],
            'tanggal_panen' => $validated['tanggal_panen'],
            'catatan' => $validated['catatan'] ?? null,
            'foto_ceri' => !empty($paths) ? json_encode($paths) : null,
        ]);

        return redirect()->route('petani.ceri.index')
            ->with('status', 'Data panen ceri kopi berhasil dicatat!');
    }

    public function ceriEdit($id_panen_ceri)
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();
        $lahanIds = $lahanList->pluck('id_lahan');

        $ceri = PanenCeri::where('id_panen_ceri', $id_panen_ceri)
            ->whereIn('id_lahan', $lahanIds)
            ->firstOrFail();

        return view('petani.ceri.edit', compact('petani', 'ceri', 'lahanList'));
    }

    public function ceriUpdate(Request $request, $id_panen_ceri)
    {
        $petani = Auth::guard('petani')->user();
        $lahanList = Lahan::where('id_petani', $petani->id_petani)->get();
        $lahanIds = $lahanList->pluck('id_lahan')->toArray();

        $ceri = PanenCeri::where('id_panen_ceri', $id_panen_ceri)
            ->whereIn('id_lahan', $lahanIds)
            ->firstOrFail();

        $existingFotos = $ceri->foto_list;
        $rules = [
            'id_lahan' => 'required|in:' . implode(',', $lahanIds),
            'jumlah_ceri' => 'required|numeric|min:0.5',
            'tanggal_panen' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ];

        if (empty($existingFotos)) {
            $rules['foto_ceri'] = 'required|array|min:1';
            $rules['foto_ceri.*'] = 'image|max:10240';
        } else {
            $rules['foto_ceri'] = 'nullable|array';
            $rules['foto_ceri.*'] = 'image|max:10240';
        }

        $validated = $request->validate($rules, [
            'foto_ceri.required' => 'Wajib mengunggah minimal 1 foto hasil panen ceri.',
            'foto_ceri.min' => 'Wajib mengunggah minimal 1 foto hasil panen ceri.',
        ]);

        if ($request->hasFile('foto_ceri')) {
            foreach ($existingFotos as $oldPath) {
                if ($oldPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }

            $paths = [];
            foreach ($request->file('foto_ceri') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $this->imageService->compressAndStore($file, 'ceri');
                }
            }
            $ceri->foto_ceri = !empty($paths) ? json_encode($paths) : null;
        }

        $ceri->id_lahan = $validated['id_lahan'];
        $ceri->jumlah_ceri = $validated['jumlah_ceri'];
        $ceri->tanggal_panen = $validated['tanggal_panen'];
        $ceri->catatan = $validated['catatan'] ?? null;
        $ceri->save();

        return redirect()->route('petani.ceri.index')
            ->with('status', "Catatan panen ceri #CRI-{$ceri->id_panen_ceri} berhasil diperbarui!");
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
            'username' => 'required|string|max:50|unique:petani,username,' . $petani->id_petani . ',id_petani',
            'kelompok_tani' => 'required|in:Pasak Piang,Padi Jaya',
            'password' => 'nullable|string|min:6|confirmed',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|max:10240',
        ]);

        $petani->nama = $validated['nama'];
        $petani->username = $validated['username'];
        $petani->kelompok_tani = $validated['kelompok_tani'];
        $petani->alamat = $validated['alamat'];

        if (!empty($validated['password'])) {
            $petani->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        if ($request->hasFile('foto_profil')) {
            // Hapus foto profil lama jika ada agar tidak memenuhi storage
            if ($petani->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists($petani->foto_profil)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($petani->foto_profil);
            }

            // Kompresi otomatis (resolusi max 1200px, kualitas WebP 75)
            $petani->foto_profil = $this->imageService->compressAndStore($request->file('foto_profil'), 'petani_profil');
        }

        $petani->save();

        return redirect()->route('petani.profile')
            ->with('status', 'Profil petani berhasil diperbarui!');
    }
}
