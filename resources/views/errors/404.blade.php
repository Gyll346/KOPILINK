@extends('layouts.app')

@section('title', ($status_code ?? '404') . ' - Halaman Tidak Ditemukan | KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 360px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">
            {{ $title ?? 'Halaman Tidak Ditemukan' }}
        </h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-primary font-weight-bold">Error {{ $status_code ?? '404' }}</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Error Body Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7 col-md-9">
                <!-- Coffee Cup Illustration / Icon -->
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle shadow" 
                          style="width: 130px; height: 130px; background: #33211D; border: 4px solid #DA9F5B;">
                        <i class="fa fa-coffee fa-4x text-primary"></i>
                    </span>
                </div>

                <!-- Big Code Badge -->
                <h1 class="display-1 font-weight-bold mb-2" style="color: #33211D; letter-spacing: 4px;">
                    {{ $status_code ?? '404' }}
                </h1>
                
                <h3 class="font-weight-bold text-dark mb-3">
                    {{ $heading ?? 'Ups! Cangkir Ini Tampak Kosong' }}
                </h3>

                <p class="text-muted mb-4 lead" style="font-size: 16px;">
                    {{ $message ?? 'Alamat tautan yang Anda tuju tidak ditemukan atau telah berpindah. Pastikan URL yang dimasukkan sudah sesuai dengan rute traceability KopiLink.' }}
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-primary font-weight-bold px-4 py-3 mr-sm-3 mb-2 mb-sm-0 text-uppercase" style="color: #33211D !important; border-radius: 4px;">
                        <i class="fa fa-home mr-2"></i> Kembali ke Beranda
                    </a>
                    <a href="{{ route('scan') }}" class="btn btn-outline-dark font-weight-bold px-4 py-3 mb-2 mb-sm-0 text-uppercase" style="border-radius: 4px; border-color: #33211D;">
                        <i class="fa fa-qrcode mr-2 text-primary"></i> Scan QR Kopi
                    </a>
                </div>

                <!-- Help Info -->
                <div class="card mt-5 border-0 shadow-sm p-4 text-left" style="background-color: #fbf7f4; border-left: 4px solid #DA9F5B !important; border-radius: 6px;">
                    <h6 class="font-weight-bold text-dark mb-2">
                        <i class="fa fa-compass text-primary mr-2"></i> Mencari Halaman Tertentu?
                    </h6>
                    <ul class="mb-0 pl-3 text-muted" style="font-size: 14px;">
                        <li>Bila Anda ingin memeriksa sertifikat kopi, silakan gunakan tombol <strong>Scan QR Kopi</strong>.</li>
                        <li>Bila Anda petani atau roastery mitra, silakan masuk melalui menu <strong>Login Akun</strong>.</li>
                        <li>Hubungi tim KopiLink melalui menu <strong>Kontak</strong> jika tautan yang Anda cari seharusnya dapat diakses.</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Error Body End -->
@endsection
