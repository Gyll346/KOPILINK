@extends('layouts.app')

@section('title', 'KOPPEE - KopiLink Coffee Traceability System')

@section('content')
<!-- Carousel Start -->
<div class="container-fluid p-0 mb-5">
    <div id="blog-carousel" class="carousel slide overlay-bottom" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="{{ asset('img/carousel-1.jpg') }}" alt="Kopi Agroforestri">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <h2 class="text-primary font-weight-medium m-0">Dari Kebun Hingga Cangkir</h2>
                    <h1 class="display-1 text-white m-0">KOPILINK</h1>
                    <h2 class="text-white m-0">* TRACEABILITY SYSTEM *</h2>
                    <div class="mt-4">
                        <a href="{{ route('scan') }}" class="btn btn-primary px-4 py-3 font-weight-bold text-secondary mr-2" style="color: #33211D !important;">
                            <i class="fa fa-qrcode mr-2"></i> Pindai QR Kemasan
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light px-4 py-3 font-weight-bold">
                            <i class="fa fa-user mr-2"></i> Masuk Portal
                        </a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="w-100" src="{{ asset('img/carousel-2.jpg') }}" alt="Roastery Specialty Coffee">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <h2 class="text-primary font-weight-medium m-0">Agroforestri & Perdagangan Berkeadilan</h2>
                    <h1 class="display-1 text-white m-0">SPECIALTY</h1>
                    <h2 class="text-white m-0">* ROBUSTA & LIBERIKA *</h2>
                    <div class="mt-4">
                        <a href="{{ route('scan') }}" class="btn btn-primary px-4 py-3 font-weight-bold text-secondary mr-2" style="color: #33211D !important;">
                            <i class="fa fa-search mr-2"></i> Telusuri Jejak Kopi
                        </a>
                        <a href="{{ route('register.petani') }}" class="btn btn-outline-light px-4 py-3 font-weight-bold">
                            <i class="fa fa-seedling mr-2"></i> Daftar Sebagai Petani
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#blog-carousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#blog-carousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>
</div>
<!-- Carousel End -->

<!-- Traceability Live Stats Section -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded shadow-sm" style="background-color: #33211D;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 65px; height: 65px; background-color: #DA9F5B; color: #33211D;">
                        <i class="fa fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-weight-bold m-0">{{ $totalPetani }}</h2>
                        <span class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 13px;">Petani Terverifikasi</span>
                        <small class="d-block text-white-50">Pasak Piang & Padi Jaya</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded shadow-sm" style="background-color: #33211D;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 65px; height: 65px; background-color: #DA9F5B; color: #33211D;">
                        <i class="fa fa-weight-hanging fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-weight-bold m-0">{{ number_format($totalProduksi, 1) }} <small style="font-size: 16px;">Kg</small></h2>
                        <span class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 13px;">Green Bean Tercatat</span>
                        <small class="d-block text-white-50">Transparansi Penyerahan Panen</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded shadow-sm" style="background-color: #33211D;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 65px; height: 65px; background-color: #DA9F5B; color: #33211D;">
                        <i class="fa fa-qrcode fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-weight-bold m-0">{{ $totalKemasan }}</h2>
                        <span class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 13px;">Kemasan Berlabel QR</span>
                        <small class="d-block text-white-50">Beredar di Pasar & Kafe Mitra</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Traceability Section -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Mengenai KopiLink</h4>
            <h1 class="display-4">Transparansi Rantai Pasok Kopi</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 py-0 py-lg-5">
                <h1 class="mb-3">Filosofi Crop to Cup</h1>
                <h5 class="mb-3">Setiap cangkir kopi yang Anda nikmati membawa cerita keadilan dan pelestarian bumi.</h5>
                <p>KopiLink menghubungkan petani kopi hulu di pedesaan Kalimantan Barat dengan pecinta kopi di kafe perkotaan melalui teknologi QR Code yang transparan dan dapat diverifikasi secara publik tanpa registrasi akun.</p>
                <a href="{{ route('about') }}" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2">Pelajari Selengkapnya</a>
            </div>
            <div class="col-lg-4 py-5 py-lg-0" style="min-height: 450px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100" src="{{ asset('img/about.png') }}" style="object-fit: cover;" alt="Kopi KopiLink">
                </div>
            </div>
            <div class="col-lg-4 py-0 py-lg-5">
                <h1 class="mb-3">Inovasi Hijau</h1>
                <p>Sistem ini dirancang selaras dengan target Tujuan Pembangunan Berkelanjutan (Sustainable Development Goals / SDGs):</p>
                <ul class="list-inline mb-4">
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i><strong>SDG 15 (Life on Land):</strong> Pemetaan titik koordinat lahan kebun di luar kawasan lindung.</li>
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i><strong>SDG 8 (Decent Work):</strong> Keadilan ekonomi dan harga layak tanpa eksploitasi tengkulak.</li>
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i><strong>SDG 12 (Responsible Consumption):</strong> Anti-greenwashing & kepastian varietas single-origin.</li>
                </ul>
                <a href="{{ route('scan') }}" class="btn btn-primary font-weight-bold py-2 px-4" style="color: #33211D !important;">Uji Coba Scan QR Sekarang</a>
            </div>
        </div>
    </div>
</div>

<!-- Alur Kerja 4 Langkah KopiLink -->
<div class="container-fluid my-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Alur Kerja Sistem</h4>
            <h1 class="display-4">Bagaimana KopiLink Beroperasi</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        1
                    </div>
                    <h5 class="text-white font-weight-bold">Input Lahan Petani</h5>
                    <p class="text-white-50 small mb-0">Petani mendaftar dengan WhatsApp OTP, memetakan koordinat kebun, dan varietas (Robusta / Liberika) otomatis ditentukan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        2
                    </div>
                    <h5 class="text-white font-weight-bold">Pencatatan Panen</h5>
                    <p class="text-white-50 small mb-0">Hasil panen green bean dicatat dengan kompresi foto otomatis di browser untuk menghemat kuota internet perdesaan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        3
                    </div>
                    <h5 class="text-white font-weight-bold">Penerimaan Roastery</h5>
                    <p class="text-white-50 small mb-0">Coffee shop menerima pasokan yang tersedia dan menghasilkan kode unik sertifikat QR untuk setiap kemasan olahan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        4
                    </div>
                    <h5 class="text-white font-weight-bold">Pemindaian Konsumen</h5>
                    <p class="text-white-50 small mb-0">Konsumen memindai QR stiker di bungkus kopi (via kamera atau unggah gambar) dan melihat peta interaktif kebun asal.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kemasan Ber-QR Terbaru -->
@if($recentTraces->isNotEmpty())
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Kemasan Ber-QR Terbaru</h4>
            <h1 class="display-4">Jejak Kopi Terverifikasi</h1>
        </div>
        <div class="row">
            @foreach($recentTraces as $kem)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm p-4 text-center rounded h-100" style="background-color: #33211D;">
                        <div class="mb-3">
                            <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="background-color: #DA9F5B; font-size: 13px;">
                                {{ $kem->produksi->lahan->jenis_kopi }} &bull; {{ $kem->produksi->lahan->petani->kelompok_tani }}
                            </span>
                        </div>
                        <div class="p-2 bg-white rounded d-inline-block mx-auto mb-3">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(0)->generate(url('/trace/' . $kem->qr_code_url)) !!}
                        </div>
                        <h5 class="text-white font-weight-bold mb-1">{{ $kem->produksi->lahan->petani->nama }}</h5>
                        <p class="text-white-50 small mb-1"><i class="fa fa-map-marker-alt text-primary mr-1"></i> {{ $kem->produksi->lahan->petani->alamat }}</p>
                        <p class="text-white-50 small mb-2">Roastery: <strong class="text-white">{{ $kem->coffeeShop->nama_toko }}</strong></p>
                        <small class="text-primary font-weight-bold d-block mb-3">
                            <i class="fa fa-clock mr-1"></i> {{ $kem->created_at ? $kem->created_at->format('d M Y, H:i') : 'Baru saja' }}
                        </small>
                        <a href="{{ route('trace.show', ['slug' => $kem->qr_code_url]) }}" class="btn btn-outline-primary btn-sm btn-block mt-auto">
                            <i class="fa fa-eye mr-1"></i> Buka Kisah Single-Origin
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
