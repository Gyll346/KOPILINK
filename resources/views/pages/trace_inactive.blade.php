@extends('layouts.app')

@section('title', 'Label QR Sedang Dinonaktifkan - KopiLink')

@section('content')
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 250px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Informasi QR Kemasan</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Beranda</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Status Publikasi</p>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 text-center">
            <div class="card shadow-sm border-0 p-4 p-md-5" style="border-radius: 16px;">
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle" style="width: 80px; height: 80px; font-size: 36px;">
                        <i class="fa fa-tools"></i>
                    </span>
                </div>
                <h3 class="font-weight-bold text-dark mb-3">Tampilan QR Code Sedang Dinonaktifkan Sementara</h3>
                <p class="text-muted leading-relaxed mb-4">
                    Label QR kemasan dengan kode <strong class="text-dark font-monospace">[{{ $kemasan->qr_code_url }}]</strong> saat ini sedang dinonaktifkan oleh <strong>{{ $kemasan->coffeeShop->nama_coffee_shop }}</strong> untuk memberi kesempatan petani mitra melakukan verifikasi dan penyesuaian/koreksi data panen.
                </p>
                <div class="alert alert-light border text-left p-3 mb-4 rounded">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-primary fa-2x mr-3"></i>
                        <div>
                            <strong class="d-block text-dark">Mengapa halaman ini tampil?</strong>
                            <small class="text-muted">KopiLink menjaga integritas dan keaslian data ketertelusuran rantai pasok kopi. Selama data dalam tahap revisi, halaman jejak publik diistirahatkan sejenak.</small>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-koppee px-4 py-2 mr-2">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali ke Beranda
                    </a>
                    <a href="{{ route('scan') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fa fa-qrcode mr-1"></i> Pindai QR Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
