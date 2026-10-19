@extends('layouts.app')

@section('title', 'Tentang Kami - KOPPEE KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Tentang Kami</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Tentang Kami</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- About Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Tentang Kami</h4>
            <h1 class="display-4">Menghubungkan Rantai Pasok Kopi</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 py-0 py-lg-5">
                <h1 class="mb-3">Kisah Kami</h1>
                <h5 class="mb-3">Berakar dari komitmen pelestarian hutan agroforestri dan keadilan ekonomi bagi petani.</h5>
                <p>KOPPEE KopiLink hadir untuk menjembatani kesenjangan informasi antara petani hulu (Desa Pasak Piang & Desa Padi Jaya) dengan penikmat kopi di hilir melalui sertifikat digital berbasis QR Code.</p>
                <a href="{{ route('scan') }}" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2">Pindai QR Kemasan</a>
            </div>
            <div class="col-lg-4 py-5 py-lg-0" style="min-height: 450px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100" src="{{ asset('img/about.png') }}" style="object-fit: cover;" alt="Tentang KopiLink">
                </div>
            </div>
            <div class="col-lg-4 py-0 py-lg-5">
                <h1 class="mb-3">Visi Keberlanjutan</h1>
                <p>Kami percaya transparansi adalah kunci masa depan industri kopi Indonesia:</p>
                <ul class="list-inline mb-4">
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>SDG 8: Upah adil untuk petani mandiri.</li>
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>SDG 12: Konsumsi bertanggung jawab bebas rekayasa.</li>
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>SDG 15: Konservasi tutupan hutan agroforestri.</li>
                </ul>
                <a href="{{ route('register.petani') }}" class="btn btn-primary font-weight-bold py-2 px-4" style="color: #33211D !important;">Daftar Petani Kopi</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->
@endsection
