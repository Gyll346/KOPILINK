@extends('layouts.app')

@section('title', 'Layanan Kami - KOPPEE KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Layanan Kami</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Layanan</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Service Start -->
<div class="container-fluid pt-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Layanan KopiLink</h4>
            <h1 class="display-4">Solusi Rantai Pasok Digital</h1>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-5">
                <div class="row align-items-center">
                    <div class="col-sm-5">
                        <img class="img-fluid mb-3 mb-sm-0" src="{{ asset('img/service-1.jpg') }}" alt="WhatsApp OTP">
                    </div>
                    <div class="col-sm-7">
                        <h4><i class="fab fa-whatsapp service-icon"></i>Autentikasi WhatsApp OTP</h4>
                        <p class="m-0">Pendaftaran mudah tanpa email rumit bagi petani pedesaan dengan verifikasi nomor WhatsApp otomatis.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-5">
                <div class="row align-items-center">
                    <div class="col-sm-5">
                        <img class="img-fluid mb-3 mb-sm-0" src="{{ asset('img/service-2.jpg') }}" alt="Peta GPS">
                    </div>
                    <div class="col-sm-7">
                        <h4><i class="fa fa-map-marked-alt service-icon"></i>Pemetaan Geospasial GPS</h4>
                        <p class="m-0">Pencatatan koordinat latitude & longitude kebun untuk sertifikasi kelestarian lingkungan dan agroforestri.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-5">
                <div class="row align-items-center">
                    <div class="col-sm-5">
                        <img class="img-fluid mb-3 mb-sm-0" src="{{ asset('img/service-3.jpg') }}" alt="QR Generator">
                    </div>
                    <div class="col-sm-7">
                        <h4><i class="fa fa-qrcode service-icon"></i>Penerbitan Label QR Code</h4>
                        <p class="m-0">Pembuatan QR code resolusi tinggi untuk kemasan kopi fisik yang langsung siap cetak label ukuran stiker standar.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-5">
                <div class="row align-items-center">
                    <div class="col-sm-5">
                        <img class="img-fluid mb-3 mb-sm-0" src="{{ asset('img/service-4.jpg') }}" alt="Scanner Kamera">
                    </div>
                    <div class="col-sm-7">
                        <h4><i class="fa fa-camera service-icon"></i>Pemindai Kamera & Galeri</h4>
                        <p class="m-0">Konsumen dapat memindai via kamera langsung atau mengunggah tangkapan layar gambar QR tanpa perlu login akun.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->
@endsection
