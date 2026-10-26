@extends('layouts.dashboard')

@section('title', 'Edit Lahan - KopiLink')
@section('page_title', 'Edit Data Lahan Kebun')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container { font-family: inherit; }
</style>
@endpush

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

                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-dark btn-sm" id="btnGps">
                            <i class="fa fa-crosshairs text-primary mr-1"></i> Perbarui Titik GPS dari Perangkat Ini
                        </button>
                        <span id="gpsStatus" class="ml-2 small text-muted"></span>
                    </div>

                    <!-- Peta Interaktif Google Maps dengan Pin yang Bisa Digeser (Draggable Marker) -->
                    <div class="card border mb-4 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center flex-wrap">
                            <span class="small font-weight-bold text-dark">
                                <i class="fa fa-map-marked-alt text-primary mr-1"></i> Peta Satelit Google Lahan #{{ $lahan->id_lahan }}
                            </span>
                            <div class="small">
                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">
                                    <i class="fa fa-hand-pointer mr-1"></i> Klik peta atau geser pin merah
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0 position-relative">
                            <div id="interactiveMap" style="width: 100%; height: 360px; z-index: 1;"></div>
                        </div>
                        <div class="card-footer bg-white py-2 px-3 small d-flex justify-content-between align-items-center flex-wrap">
                            <span class="text-muted">
                                <i class="fa fa-info-circle text-primary mr-1"></i> 
                                Geser (drag) pin merah atau klik pada peta untuk mengubah titik koordinat Garis Lintang & Bujur lahan ini.
                            </span>
                            <a id="btnGmapsExternal" href="https://maps.google.com/?q={{ $lahan->koordinat_y }},{{ $lahan->koordinat_x }}" target="_blank" class="btn btn-xs btn-outline-primary" style="font-size: 11px;">
                                <i class="fa fa-external-link-alt mr-1"></i> Buka Google Maps
                            </a>
                        </div>
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

                    <!-- Foto Lahan & Pratinjau (Multi-Foto) -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">
                            Foto Kebun / Tanaman Kopi
                            @if(count($lahan->foto_list) === 0)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        @if(count($lahan->foto_list) > 0)
                            <div class="mb-3">
                                <span class="small font-weight-bold text-muted d-block mb-1">
                                    <i class="fa fa-images mr-1"></i> Foto Lahan Saat Ini ({{ count($lahan->foto_list) }} foto):
                                </span>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @foreach($lahan->foto_list as $idx => $fPath)
                                        <div class="position-relative rounded border overflow-hidden shadow-sm" style="width: 120px; height: 95px;">
                                            <a href="{{ asset('storage/' . $fPath) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $fPath) }}" alt="Foto Lahan {{ $idx + 1 }}" class="w-100 h-100" style="object-fit: cover;">
                                            </a>
                                            <span class="badge badge-dark position-absolute" style="bottom: 2px; left: 2px; font-size: 9px;">
                                                Foto {{ $idx + 1 }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <input type="file" id="fotoInput" accept="image/*" class="form-control-file" multiple>
                        <input type="file" name="foto_lahan[]" id="compressedFoto" class="d-none" multiple>
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-info-circle text-primary mr-1"></i> 
                            Pilih gambar baru jika ingin mengganti seluruh foto kebun. Bisa pilih lebih dari 1 foto (foto pertama wajib, tambahan opsional).
                        </small>
                        <div id="compressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="previewContainer" class="mt-2 d-none">
                            <span class="small font-weight-bold text-muted d-block mb-1">Pratinjau Foto Pengganti:</span>
                            <div id="previewGrid" class="d-flex flex-wrap" style="gap: 10px;"></div>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const latInput = document.getElementById('latInput');
    const longInput = document.getElementById('longInput');
    const btnGmapsExternal = document.getElementById('btnGmapsExternal');
    const btnGps = document.getElementById('btnGps');
    const gpsStatus = document.getElementById('gpsStatus');

    // Koordinat awal lahan saat ini
    let currentLat = parseFloat(latInput.value) || {{ $lahan->koordinat_y }};
    let currentLng = parseFloat(longInput.value) || {{ $lahan->koordinat_x }};

    // Inisialisasi Peta Leaflet
    const map = L.map('interactiveMap').setView([currentLat, currentLng], 15);

    // Layer Google Hybrid / Satellite (Satelit asli Google Maps dengan nama jalan)
    const googleHybrid = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps Satellite'
    }).addTo(map);

    // Layer Google Streets standar
    const googleStreets = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps Streets'
    });

    // Layer switcher kontrol
    L.control.layers({
        "Satelit Google (Hybrid)": googleHybrid,
        "Peta Jalan Google": googleStreets
    }).addTo(map);

    // Marker yang dapat digeser (Draggable Marker)
    const marker = L.marker([currentLat, currentLng], {
        draggable: true,
        autoPan: true
    }).addTo(map);

    marker.bindPopup("<b>Lahan #{{ $lahan->id_lahan }}</b><br>Geser pin merah ini untuk mengubah titik koordinat.").openPopup();

    function setCoordinates(lat, lng, syncInput = true, panMap = false) {
        const roundedLat = parseFloat(lat).toFixed(8);
        const roundedLng = parseFloat(lng).toFixed(8);

        if (syncInput) {
            latInput.value = roundedLat;
            longInput.value = roundedLng;
        }

        marker.setLatLng([roundedLat, roundedLng]);

        if (panMap) {
            map.panTo([roundedLat, roundedLng]);
        }

        if (btnGmapsExternal) {
            btnGmapsExternal.href = `https://maps.google.com/?q=${roundedLat},${roundedLng}`;
        }
    }

    // 1. Saat marker digeser (dragend) oleh petani
    marker.on('dragend', function (e) {
        const pos = marker.getLatLng();
        setCoordinates(pos.lat, pos.lng, true, false);
    });

    // 2. Saat petani mengklik area pada peta
    map.on('click', function (e) {
        setCoordinates(e.latlng.lat, e.latlng.lng, true, false);
        marker.openPopup();
    });

    // 3. Saat petani mengubah manual angka koordinat X & Y
    function onManualInputChange() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(longInput.value);
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            setCoordinates(lat, lng, false, true);
        }
    }
    if (latInput && longInput) {
        latInput.addEventListener('input', onManualInputChange);
        longInput.addEventListener('input', onManualInputChange);
    }

    // 4. Geolocation GPS Sensor dari HP
    if (btnGps) {
        btnGps.addEventListener('click', function () {
            if (!navigator.geolocation) {
                gpsStatus.innerHTML = '<span class="text-danger">Browser Anda tidak mendukung Geolocation GPS.</span>';
                return;
            }
            gpsStatus.innerHTML = '<span class="text-info"><i class="fa fa-spinner fa-spin"></i> Mendapatkan koordinat GPS satelit...</span>';
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    setCoordinates(lat, lng, true, true);
                    map.setZoom(16);
                    gpsStatus.innerHTML = `<span class="text-success"><i class="fa fa-check-circle"></i> Berhasil! Akurasi: ±${Math.round(position.coords.accuracy)} meter</span>`;
                },
                function (error) {
                    gpsStatus.innerHTML = `<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> Gagal mengambil GPS: ${error.message}</span>`;
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    }

    setTimeout(() => { map.invalidateSize(); }, 300);


    // 2. Client-side Pre-compression Canvas (Multi-Files)
    const fotoInput = document.getElementById('fotoInput');
    const compressedFoto = document.getElementById('compressedFoto');
    const previewGrid = document.getElementById('previewGrid');
    const previewContainer = document.getElementById('previewContainer');
    const compressionInfo = document.getElementById('compressionInfo');

    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            if (!files || files.length === 0) return;

            previewGrid.innerHTML = '';
            compressionInfo.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Mengompresi ${files.length} foto...`;

            const dataTransfer = new DataTransfer();
            let processedCount = 0;
            let totalOriginalSize = 0;
            let totalNewSize = 0;

            files.forEach((file, index) => {
                totalOriginalSize += file.size;
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
                            totalNewSize += blob.size;
                            const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            dataTransfer.items.add(compressedFile);

                            // Buat thumbnail pratinjau
                            const thumbWrapper = document.createElement('div');
                            thumbWrapper.className = 'position-relative rounded border overflow-hidden shadow-sm';
                            thumbWrapper.style.width = '120px';
                            thumbWrapper.style.height = '100px';

                            const thumbImg = document.createElement('img');
                            thumbImg.src = canvas.toDataURL('image/jpeg', 0.8);
                            thumbImg.className = 'w-100 h-100';
                            thumbImg.style.objectFit = 'cover';

                            const badge = document.createElement('span');
                            badge.className = 'badge badge-dark position-absolute';
                            badge.style.bottom = '2px';
                            badge.style.left = '2px';
                            badge.style.fontSize = '9px';
                            badge.textContent = `Foto ${index + 1}`;

                            thumbWrapper.appendChild(thumbImg);
                            thumbWrapper.appendChild(badge);
                            previewGrid.appendChild(thumbWrapper);

                            processedCount++;
                            if (processedCount === files.length) {
                                compressedFoto.files = dataTransfer.files;
                                const origKb = (totalOriginalSize / 1024).toFixed(1);
                                const newKb = (totalNewSize / 1024).toFixed(1);
                                const saving = totalOriginalSize > 0 ? (100 - (totalNewSize / totalOriginalSize) * 100).toFixed(0) : 0;
                                compressionInfo.innerHTML = `<i class="fa fa-check-circle"></i> Terkompresi ${files.length} foto: ${origKb} KB &rarr; ${newKb} KB (Hemat ${saving}%)`;
                                previewContainer.classList.remove('d-none');
                            }
                        }, 'image/jpeg', 0.75);
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>
@endsection
