@extends('layouts.app')

@section('title', '403 - Akses Ditolak | KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 360px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Akses Ditolak</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-primary font-weight-bold">Error 403</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7 col-md-9">
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle shadow" 
                          style="width: 130px; height: 130px; background: #33211D; border: 4px solid #DA9F5B;">
                        <i class="fa fa-lock fa-4x text-primary"></i>
                    </span>
                </div>

                <h1 class="display-1 font-weight-bold mb-2" style="color: #33211D; letter-spacing: 4px;">403</h1>
                <h3 class="font-weight-bold text-dark mb-3">Area Khusus & Terbatas</h3>
                <p class="text-muted mb-4 lead" style="font-size: 16px;">
                    Anda tidak memiliki hak akses untuk membuka halaman ini. Silakan login menggunakan akun dengan peran yang sesuai (Petani, Coffee Shop, atau Super Admin).
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center">
                    <a href="{{ route('login') }}" class="btn btn-primary font-weight-bold px-4 py-3 mr-sm-3 mb-2 mb-sm-0 text-uppercase" style="color: #33211D !important; border-radius: 4px;">
                        <i class="fa fa-sign-in-alt mr-2"></i> Beralih ke Halaman Login
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-dark font-weight-bold px-4 py-3 mb-2 mb-sm-0 text-uppercase" style="border-radius: 4px; border-color: #33211D;">
                        <i class="fa fa-home mr-2 text-primary"></i> Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
