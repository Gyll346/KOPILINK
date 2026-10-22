@extends('layouts.dashboard')

@section('title', 'Edit Lahan - KopiLink')
@section('page_title', 'Edit Data Lahan Kebun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-stat">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5 class="font-weight-bold mb-1">Perbarui Plot Lahan #{{ $lahan->id_lahan }}</h5>
                    <p class="text-muted small mb-0">Ubah koordinat GPS, luas lahan, populasi pohon, atau foto kebun.</p>
                </div>
                <a href="{{ route('petani.lahan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar Lahan
                </a>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('petani.lahan.update', ['id_lahan' => $lahan->id_lahan]) }}" method="POST" enctype="multipart/form-data" id="lahanForm">
                    @csrf
                    @method('PUT')

                    <!-- Info Varietas Kopi -->
                    <div class="alert alert-light border mb-4 d-flex align-items-center" style="border-left: 4px solid #DA9F5B !important;">
                        <i class="fa fa-info-circle fa-2x text-primary mr-3"></i>
                        <div>
                            <div class="font-weight-bold">Varietas Kopi Resmi:</div>
                            <small class="text-muted">
                                Lahan ini terdaftar sebagai 
                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="background-color: #DA9F5B;">{{ $lahan->jenis_kopi }}</span>
                                sesuai Kelompok Tani {{ $petani->kelompok_tani }}.
                            </small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Garis Lintang (Latitude Y) <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="koordinat_y" id="latInput" class="form-control" placeholder="Contoh: -0.02630000" value="{{ old('koordinat_y', $lahan->koordinat_y) }}" required>
                            <small class="text-muted">Garis Lintang antara -90 hingga 90 derajat.</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Garis Bujur (Longitude X) <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="koordinat_x" id="longInput" class="form-control" placeholder="Contoh: 109.34250000" value="{{ old('koordinat_x', $lahan->koordinat_x) }}" required>
                            <small class="text-muted">Garis Bujur antara -180 hingga 180 derajat.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" class="btn btn-outline-dark btn-sm" id="btnGps">
                            <i class="fa fa-crosshairs text-primary mr-1"></i> Perbarui Titik GPS dari Perangkat Ini
                        </button>
                        <span id="gpsStatus" class="ml-2 small text-muted"></span>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Luas Lahan Kebun (Hektar) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="luas_lahan" class="form-control" placeholder="Contoh: 1.50" value="{{ old('luas_lahan', $lahan->luas_lahan) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jumlah Tanaman Kopi (Pohon) <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="jumlah_tanaman" class="form-control" placeholder="Contoh: 1200" value="{{ old('jumlah_tanaman', $lahan->jumlah_tanaman) }}" required>
                        </div>
                    </div>

                    <!-- Foto Lahan & Pratinjau -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Foto Kebun / Tanaman Kopi</label>
                        @if($lahan->foto_lahan)
                            <div class="mb-3">
                                <span class="small font-weight-bold text-muted d-block mb-1">Foto Lahan Saat Ini:</span>
                                <img src="{{ asset('storage/' . $lahan->foto_lahan) }}" alt="Foto Lahan Saat Ini" class="rounded border shadow-sm" style="max-height: 140px; object-fit: cover;">
                            </div>
                        @endif

                        <input type="file" id="fotoInput" accept="image/*" class="form-control-file">
                        <input type="file" name="foto_lahan" id="compressedFoto" class="d-none">
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-compress-arrows-alt text-primary mr-1"></i> 
                            Pilih gambar baru untuk mengganti foto lama. Gambar otomatis dikompresi agar hemat kuota internet.
                        </small>
                        <div id="compressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="previewContainer" class="mt-2 d-none">
                            <span class="small font-weight-bold text-muted d-block mb-1">Pratinjau Foto Baru:</span>
                            <img id="imgPreview" src="" alt="Pratinjau Foto Baru" class="rounded border" style="max-height: 180px;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('petani.lahan.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-koppee px-4 py-2 font-weight-bold">
                            <i class="fa fa-save mr-1"></i> Simpan Perubahan Lahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Geolocation GPS
    const btnGps = document.getElementById('btnGps');
    const latInput = document.getElementById('latInput');
    const longInput = document.getElementById('longInput');
    const gpsStatus = document.getElementById('gpsStatus');

    if (btnGps) {
        btnGps.addEventListener('click', function () {
            if (!navigator.geolocation) {
                gpsStatus.innerHTML = '<span class="text-danger">Browser Anda tidak mendukung Geolocation GPS.</span>';
                return;
            }
            gpsStatus.innerHTML = '<span class="text-info"><i class="fa fa-spinner fa-spin"></i> Mendapatkan koordinat GPS satelit...</span>';
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    latInput.value = position.coords.latitude.toFixed(8);
                    longInput.value = position.coords.longitude.toFixed(8);
                    gpsStatus.innerHTML = `<span class="text-success"><i class="fa fa-check-circle"></i> Berhasil! Akurasi: ±${Math.round(position.coords.accuracy)} meter</span>`;
                },
                function (error) {
                    gpsStatus.innerHTML = `<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> Gagal mengambil GPS: ${error.message}</span>`;
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    }

    // 2. Client-side Pre-compression Canvas
    const fotoInput = document.getElementById('fotoInput');
    const compressedFoto = document.getElementById('compressedFoto');
    const imgPreview = document.getElementById('imgPreview');
    const previewContainer = document.getElementById('previewContainer');
    const compressionInfo = document.getElementById('compressionInfo');

    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const originalSizeKB = (file.size / 1024).toFixed(1);
            compressionInfo.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengompresi gambar...';

            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    const maxDim = 1200;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function (blob) {
                        const newSizeKB = (blob.size / 1024).toFixed(1);
                        const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".webp", {
                            type: 'image/webp',
                            lastModified: Date.now()
                        });

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        compressedFoto.files = dataTransfer.files;

                        imgPreview.src = URL.createObjectURL(blob);
                        previewContainer.classList.remove('d-none');
                        compressionInfo.innerHTML = `<i class="fa fa-check-circle"></i> Terkompresi: ${originalSizeKB} KB &rarr; ${newSizeKB} KB (Format WebP)`;
                    }, 'image/webp', 0.8);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endsection
