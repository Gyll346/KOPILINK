@extends('layouts.app')

@section('title', 'Jejak Kopi Single-Origin - ' . $kemasan->qr_code_url)

@push('styles')
<!-- Leaflet.js CSS for OpenStreetMap Interactive Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        height: 380px;
        width: 100%;
        border-radius: 8px;
        z-index: 1;
    }
    .badge-sdg {
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .timeline-step {
        position: relative;
        padding-left: 32px;
        margin-bottom: 24px;
    }
    .timeline-step::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: -24px;
        width: 2px;
        background: #DA9F5B;
    }
    .timeline-step:last-child::before {
        display: none;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #DA9F5B;
        border: 3px solid #33211D;
    }
</style>
@endpush

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Jejak Kopi</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white"><a class="text-white" href="{{ route('scan') }}">Scan</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Traceability</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid py-5">
    <div class="container">
        <!-- Top Banner Header -->
        <div class="section-title text-center mb-5">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Single-Origin Authentic Story</h4>
            <h1 class="display-4">Jejak Kopi: Dari Kebun ke Cangkir</h1>
            <div class="d-inline-block p-2 rounded mt-2" style="background-color: #33211D;">
                <span class="text-white-50 small mr-2">Kode Sertifikat Digital:</span>
                <span class="badge badge-warning text-dark font-weight-bold px-3 py-2 text-monospace" style="background-color: #DA9F5B; font-size: 14px;">
                    <i class="fa fa-qrcode mr-1"></i> {{ $kemasan->qr_code_url }}
                </span>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Farmer Profile & Variety Info -->
            <div class="col-lg-5 mb-5">
                <!-- Farmer Profile Card -->
                <div class="card border-0 shadow-lg text-white mb-4" style="background-color: #33211D; border-radius: 8px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary text-secondary mr-3 d-flex align-items-center justify-content-center font-weight-bold shadow-sm" style="width: 70px; height: 70px; font-size: 30px; color: #33211D !important; flex-shrink: 0;">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <span class="badge badge-success px-2 py-1 mb-1" style="font-size: 10px;">
                                    <i class="fab fa-whatsapp mr-1"></i> Petani Terverifikasi OTP
                                </span>
                                <h4 class="text-white font-weight-bold m-0">{{ $petani->nama }}</h4>
                                <small class="text-white-50"><i class="fa fa-map-marker-alt text-primary mr-1"></i> {{ $petani->alamat }}</small>
                            </div>
                        </div>

                        <hr class="border-secondary my-3">

                        <div class="mb-3">
                            <span class="text-white-50 d-block small">Kelompok Tani Pengelola:</span>
                            <strong class="text-white font-weight-bold" style="font-size: 16px;">
                                Kelompok Tani {{ $petani->kelompok_tani }}
                            </strong>
                        </div>

                        <div class="row text-center my-3">
                            <div class="col-6 border-right border-secondary">
                                <span class="text-white-50 small d-block">Luas Plot Kebun</span>
                                <h4 class="text-primary font-weight-bold m-0">{{ $lahan->luas_lahan }} Ha</h4>
                            </div>
                            <div class="col-6">
                                <span class="text-white-50 small d-block">Populasi Pohon</span>
                                <h4 class="text-primary font-weight-bold m-0">{{ number_format($lahan->jumlah_tanaman) }}</h4>
                            </div>
                        </div>

                        <div class="p-3 rounded mt-3" style="background: rgba(255,255,255,0.05); border-left: 3px solid #DA9F5B;">
                            <span class="text-white-50 small d-block font-weight-bold">Varietas Kopi Resmi:</span>
                            <h5 class="text-primary font-weight-bold m-0">{{ $lahan->jenis_kopi }} Single-Origin</h5>
                            <p class="text-white-50 small mt-1 mb-0">
                                @if($lahan->jenis_kopi === 'Robusta')
                                    Kopi Robusta Pasak Piang memiliki body tebal yang kaya, tingkat keasaman rendah, aroma cokelat earthy, serta sentuhan rempah khas lahan agroforestri tropis.
                                @else
                                    Kopi Liberika Padi Jaya terkenal dengan ukuran biji 'gajah' yang eksotis, aroma nangka (jackfruit) manis semerbak, dengan sentuhan aftertaste floral yang segar.
                                @endif
                            </p>
                        </div>

                        <!-- Dokumentasi Foto Kebun & Foto Hasil Panen dari Petani -->
                        @if($lahan->foto_lahan || $produksi->foto_green_bean)
                            <div class="mt-4 pt-3 border-top border-secondary">
                                <span class="text-white-50 small d-block font-weight-bold mb-2">
                                    <i class="fa fa-images text-primary mr-1"></i> Dokumentasi Lapangan Petani:
                                </span>
                                <div class="row">
                                    @if($lahan->foto_lahan)
                                        <div class="col-{{ $produksi->foto_green_bean ? '6' : '12' }} mb-2">
                                            <a href="{{ asset('storage/' . $lahan->foto_lahan) }}" target="_blank" class="d-block text-decoration-none">
                                                <div class="rounded overflow-hidden border border-secondary position-relative">
                                                    <img src="{{ asset('storage/' . $lahan->foto_lahan) }}" alt="Foto Kebun Kopi" class="w-100" style="height: 120px; object-fit: cover;">
                                                    <span class="badge badge-dark position-absolute" style="bottom: 4px; left: 4px; font-size: 10px; opacity: 0.9;">
                                                        <i class="fa fa-tree mr-1"></i> Foto Kebun
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                    @if($produksi->foto_green_bean)
                                        <div class="col-{{ $lahan->foto_lahan ? '6' : '12' }} mb-2">
                                            <a href="{{ asset('storage/' . $produksi->foto_green_bean) }}" target="_blank" class="d-block text-decoration-none">
                                                <div class="rounded overflow-hidden border border-secondary position-relative">
                                                    <img src="{{ asset('storage/' . $produksi->foto_green_bean) }}" alt="Foto Hasil Panen" class="w-100" style="height: 120px; object-fit: cover;">
                                                    <span class="badge badge-dark position-absolute" style="bottom: 4px; left: 4px; font-size: 10px; opacity: 0.9;">
                                                        <i class="fa fa-seedling mr-1"></i> Biji Panen
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SDGs Impact Card -->
                <div class="card border-0 shadow-lg text-white" style="background-color: #251714; border-radius: 8px;">
                    <div class="card-body p-4">
                        <h5 class="text-white font-weight-bold mb-3"><i class="fa fa-globe-asia text-primary mr-2"></i> Sertifikat Keberlanjutan SDGs</h5>
                        
                        <div class="mb-3">
                            <span class="badge-sdg" style="background-color: #56C02B; color: #fff;">
                                <i class="fa fa-tree mr-2"></i> SDG 15: Life on Land
                            </span>
                            <p class="text-white-50 small mt-1 mb-0">
                                Budidaya kopi agroforestri terpetakan koordinat GPS di luar zona hutan lindung, melindungi keanekaragaman hayati dan mencegah deforestasi.
                            </p>
                        </div>

                        <div class="mb-3">
                            <span class="badge-sdg" style="background-color: #A21942; color: #fff;">
                                <i class="fa fa-hand-holding-usd mr-2"></i> SDG 8: Decent Work
                            </span>
                            <p class="text-white-50 small mt-1 mb-0">
                                Transparansi transaksi penyerahan panen menjamin harga yang adil (*Fair Trade*) langsung kepada petani mandiri tanpa perantara tengkulak.
                            </p>
                        </div>

                        <div>
                            <span class="badge-sdg" style="background-color: #CF8D2A; color: #fff;">
                                <i class="fa fa-shield-alt mr-2"></i> SDG 12: Responsible Consumption
                            </span>
                            <p class="text-white-50 small mt-1 mb-0">
                                Keaslian asal-usul 100% terverifikasi digital. Bebas manipulasi campuran dan anti-*greenwashing*.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Leaflet Map & Supply Chain Timeline -->
            <div class="col-lg-7">
                <!-- Interactive Map -->
                <div class="card card-stat mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold m-0"><i class="fa fa-map-marked-alt text-primary mr-2"></i> Peta Koordinat Lahan Kebun</h5>
                            <small class="text-muted">Titik geospasial aktual budidaya kopi di Kalimantan Barat</small>
                        </div>
                        <a href="https://www.openstreetmap.org/?mlat={{ $lahan->koordinat_y }}&mlon={{ $lahan->koordinat_x }}#map=16/{{ $lahan->koordinat_y }}/{{ $lahan->koordinat_x }}" target="_blank" class="btn btn-sm btn-outline-dark">
                            Buka di OSM &rarr;
                        </a>
                    </div>
                    <div class="card-body p-3">
                        <div id="map"></div>
                        <div class="mt-2 text-center text-muted small">
                            <strong>Koordinat GPS:</strong> Latitude: <code>{{ $lahan->koordinat_y }}</code> &bull; Longitude: <code>{{ $lahan->koordinat_x }}</code>
                        </div>
                    </div>
                </div>

                <!-- Supply Chain Timeline -->
                <div class="card card-stat">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="font-weight-bold m-0"><i class="fa fa-route text-primary mr-2"></i> Rantai Jejak Produksi (Supply Chain Timeline)</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline-step">
                            <div class="timeline-icon"></div>
                            <h6 class="font-weight-bold text-dark mb-1">Tahap 1: Budidaya di Kebun Agroforestri</h6>
                            <p class="text-muted small mb-1">
                                Dikelola oleh <strong>{{ $petani->nama }}</strong> di <strong>Desa {{ $petani->kelompok_tani }}</strong>. 
                                Populasi: {{ number_format($lahan->jumlah_tanaman) }} pohon di atas lahan seluas {{ $lahan->luas_lahan }} Ha.
                            </p>
                            @if($lahan->foto_lahan)
                                <div class="mt-2 mb-3">
                                    <a href="{{ asset('storage/' . $lahan->foto_lahan) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $lahan->foto_lahan) }}" alt="Foto Kebun" class="rounded border shadow-sm" style="height: 110px; max-width: 100%; object-fit: cover;">
                                    </a>
                                    <small class="d-block text-muted mt-1"><i class="fa fa-camera mr-1"></i> Foto Kebun Agroforestri</small>
                                </div>
                            @endif
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-icon"></div>
                            <h6 class="font-weight-bold text-dark mb-1">Tahap 2: Panen Green Bean</h6>
                            <p class="text-muted small mb-1">
                                Panen batch seberat <strong>{{ $produksi->jumlah_green_bean }} Kg</strong> dipetik dan dicatat pada tanggal 
                                <strong>{{ $produksi->tanggal_input->format('d F Y') }}</strong>.
                            </p>
                            @if($produksi->foto_green_bean)
                                <div class="mt-2 mb-3">
                                    <a href="{{ asset('storage/' . $produksi->foto_green_bean) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $produksi->foto_green_bean) }}" alt="Foto Biji Kopi" class="rounded border shadow-sm" style="height: 110px; max-width: 100%; object-fit: cover;">
                                    </a>
                                    <small class="d-block text-muted mt-1"><i class="fa fa-camera mr-1"></i> Foto Biji Kopi (Green Bean)</small>
                                </div>
                            @endif
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-icon"></div>
                            <h6 class="font-weight-bold text-dark mb-1">Tahap 3: Penerimaan & Sangrai oleh Roastery Mitra</h6>
                            <p class="text-muted small mb-1">
                                Biji kopi diserap dan diolah secara profesional oleh <strong>{{ $coffeeShop->nama_toko }}</strong>. Status pasokan telah terverifikasi di inventori mitra.
                            </p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-icon"></div>
                            <h6 class="font-weight-bold text-dark mb-1">Tahap 4: Pengemasan & Penerbitan Label QR Code</h6>
                            <p class="text-muted small mb-1">
                                Kemasan berlabel QR digital resmi <code>{{ $kemasan->qr_code_url }}</code> diterbitkan pada <strong>{{ $kemasan->created_at->format('d F Y, H:i') }} WIB</strong> dan siap dinikmati di cangkir Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet.js Interactive Map Script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const lat = {{ $lahan->koordinat_y }};
        const lng = {{ $lahan->koordinat_x }};

        // Inisialisasi peta Leaflet
        const map = L.map('map').setView([lat, lng], 14);

        // Tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | KopiLink'
        }).addTo(map);

        // Custom Marker
        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(`
            <div style="font-family: 'Montserrat', sans-serif;">
                <strong style="color: #33211D;">Kebun Kopi {{ $lahan->jenis_kopi }}</strong><br>
                <span>Petani: {{ $petani->nama }}</span><br>
                <span>Kelompok: {{ $petani->kelompok_tani }}</span><br>
                <small style="color: #777;">Koordinat: ${lat}, ${lng}</small>
            </div>
        `).openPopup();
    });
</script>
@endpush
