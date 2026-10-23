@extends('layouts.dashboard')

@section('title', 'Input Lahan Baru - KopiLink')
@section('page_title', 'Form Pendaftaran Lahan Kebun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-stat">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-1">Daftarkan Lahan Kebun Kopi Baru</h5>
                <p class="text-muted small mb-0">Masukkan titik koordinat GPS kebun Anda serta data populasi tanaman kopi.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('petani.lahan.store') }}" method="POST" enctype="multipart/form-data" id="lahanForm">
                    @csrf

                    <!-- Alert Business Rule Auto Variety -->
                    <div class="alert alert-light border mb-4 d-flex align-items-center" style="border-left: 4px solid #DA9F5B !important;">
                        <i class="fa fa-info-circle fa-2x text-primary mr-3"></i>
                        <div>
                            <div class="font-weight-bold">Penetapan Otomatis Varietas Kopi:</div>
                            <small class="text-muted">
                                Berdasarkan keanggotaan Anda di <strong>Kelompok Tani {{ $petani->kelompok_tani }}</strong>, sistem menetapkan varietas kebun ini sebagai 
                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="background-color: #DA9F5B;">{{ $autoJenisKopi }}</span>
                            </small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Garis Lintang (Latitude Y) <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="koordinat_y" id="latInput" class="form-control" placeholder="Contoh: -0.02630000" value="{{ old('koordinat_y') }}" required>
                            <small class="text-muted">Garis Lintang antara -90 hingga 90 derajat.</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Garis Bujur (Longitude X) <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="koordinat_x" id="longInput" class="form-control" placeholder="Contoh: 109.34250000" value="{{ old('koordinat_x') }}" required>
                            <small class="text-muted">Garis Bujur antara -180 hingga 180 derajat.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-dark btn-sm" id="btnGps">
                            <i class="fa fa-crosshairs text-primary mr-1"></i> Ambil Koordinat GPS Otomatis dari Ponsel
                        </button>
                        <span id="gpsStatus" class="ml-2 small text-muted"></span>
                    </div>

                    <!-- Peta Google Maps Interaktif (Live Coordinates Picker) -->
                    <div class="card border mb-4 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                            <span class="small font-weight-bold text-dark">
                                <i class="fab fa-google text-primary mr-1"></i> Pratinjau Peta Google Maps
                            </span>
                            <a id="btnGmapsExternal" href="https://maps.google.com/?q=-0.0263,109.3425" target="_blank" class="btn btn-xs btn-outline-primary" style="font-size: 11px;">
                                <i class="fa fa-external-link-alt mr-1"></i> Buka Layar Penuh
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <iframe 
                                id="gmapsIframe"
                                style="width: 100%; height: 320px; border: 0;"
                                src="https://maps.google.com/maps?q=-0.0263,109.3425&hl=id&z=15&output=embed"
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                        <div class="card-footer bg-white py-2 px-3 small text-muted">
                            <i class="fa fa-info-circle text-primary mr-1"></i> 
                            Peta Google Maps otomatis menyesuaikan saat Anda mengubah Garis Lintang / Garis Bujur atau menekan tombol GPS.
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Luas Lahan Kebun (Hektar) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="luas_lahan" class="form-control" placeholder="Contoh: 1.50" value="{{ old('luas_lahan') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jumlah Tanaman Kopi (Pohon) <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="jumlah_tanaman" class="form-control" placeholder="Contoh: 1200" value="{{ old('jumlah_tanaman') }}" required>
                        </div>
                    </div>

                    <!-- Foto Lahan dengan Client-side Pre-compression Canvas -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Unggah Foto Kebun / Tanaman Kopi</label>
                        <input type="file" id="fotoInput" accept="image/*" class="form-control-file">
                        <input type="file" name="foto_lahan" id="compressedFoto" class="d-none">
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-compress-arrows-alt text-primary mr-1"></i> 
                            Foto otomatis dikompresi di sisi klien sebelum dikirim demi hemat kuota internet perkebunan.
                        </small>
                        <div id="compressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="previewContainer" class="mt-2 d-none">
                            <img id="imgPreview" src="" alt="Pratinjau Foto" class="rounded border" style="max-height: 180px;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('petani.lahan.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-koppee px-4 py-2">
                            <i class="fa fa-save mr-1"></i> Simpan Data Lahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Geolocation GPS Helper & Google Map Sync
    const latInput = document.getElementById('latInput');
    const longInput = document.getElementById('longInput');
    const gmapsIframe = document.getElementById('gmapsIframe');
    const btnGmapsExternal = document.getElementById('btnGmapsExternal');

    function updateGoogleMap() {
        const lat = latInput.value.trim();
        const lng = longInput.value.trim();
        if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
            const query = `${lat},${lng}`;
            gmapsIframe.src = `https://maps.google.com/maps?q=${query}&hl=id&z=15&output=embed`;
            if (btnGmapsExternal) {
                btnGmapsExternal.href = `https://maps.google.com/?q=${query}`;
            }
        }
    }

    latInput.addEventListener('input', updateGoogleMap);
    longInput.addEventListener('input', updateGoogleMap);

    document.getElementById('btnGps').addEventListener('click', function () {
        const status = document.getElementById('gpsStatus');
        if (navigator.geolocation) {
            status.textContent = "Sedang mendeteksi posisi GPS...";
            navigator.geolocation.getCurrentPosition(function (position) {
                latInput.value = position.coords.latitude.toFixed(8);
                longInput.value = position.coords.longitude.toFixed(8);
                status.textContent = "✓ Koordinat berhasil disematkan!";
                updateGoogleMap();
            }, function (error) {
                status.textContent = "Gagal membaca GPS: " + error.message;
            });
        } else {
            status.textContent = "Peramban Anda tidak mendukung sensor GPS.";
        }
    });


    // 2. Client-Side HTML5 Canvas Image Pre-Compression (PRD 6.2 & 11.2)
    document.getElementById('fotoInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const info = document.getElementById('compressionInfo');
        const previewContainer = document.getElementById('previewContainer');
        const preview = document.getElementById('imgPreview');
        const originalSizeKb = (file.size / 1024).toFixed(1);

        info.textContent = `Memproses kompresi gambar asli (${originalSizeKb} KB)...`;

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
                    const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });

                    // Set ke input file tersembunyi
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    document.getElementById('compressedFoto').files = dataTransfer.files;

                    const newSizeKb = (blob.size / 1024).toFixed(1);
                    const saving = (100 - (blob.size / file.size) * 100).toFixed(0);

                    info.textContent = `✓ Kompresi Berhasil: ${originalSizeKb} KB → ${newSizeKb} KB (Hemat ${saving}%)`;
                    preview.src = canvas.toDataURL('image/jpeg', 0.8);
                    previewContainer.classList.remove('d-none');
                }, 'image/jpeg', 0.75);
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
