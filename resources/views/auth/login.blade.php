@extends('layouts.app')

@section('title', 'Login Portal KopiLink - KOPPEE')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Portal Masuk</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Login</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Satu Pintu Akses</h4>
            <h1 class="display-4">Masuk ke Portal KopiLink</h1>
            <p class="text-muted">Satu portal terintegrasi untuk Petani Kopi, Mitra Coffee Shop / Roastery, dan Super Administrator.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg text-white" style="background-color: #33211D; border-radius: 12px; overflow: hidden;">
                    <div class="card-header border-0 text-center pt-4 pb-0" style="background: transparent;">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px; background: rgba(218, 159, 91, 0.2); color: #DA9F5B; font-size: 30px;">
                            <i class="fa fa-user-lock"></i>
                        </div>
                        <h4 class="text-white font-weight-bold mb-1">Login Akun</h4>
                        <p class="text-white-50 small mb-0">Sistem akan secara otomatis mengenali peran akun Anda.</p>
                    </div>

                    <div class="card-body p-4 p-md-5 pt-3">
                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="text-white font-weight-bold small text-uppercase" style="letter-spacing: 1px;">Username / Akun</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-0 text-muted"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control p-4 border-0" placeholder="Masukkan username Anda" value="{{ old('username') }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="text-white font-weight-bold small text-uppercase mb-0" style="letter-spacing: 1px;">Password</label>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-0 text-muted"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" id="unified_password" class="form-control p-4 border-0" placeholder="Masukkan kata sandi" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-light px-3" type="button" onclick="togglePasswordVisibility('unified_password', this)" style="border-radius: 0 4px 4px 0;">
                                            <i class="fa fa-eye text-dark"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-block font-weight-bold py-3 text-secondary text-uppercase shadow-sm" type="submit" style="color: #33211D !important; font-size: 15px; letter-spacing: 1px;">
                                <i class="fa fa-sign-in-alt mr-2"></i> Masuk Sekarang
                            </button>

                            <div class="text-center mt-4 pt-2 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                                <p class="text-white small mb-0">
                                    Belum terdaftar sebagai petani mitra? 
                                    <a href="{{ route('register.petani') }}" class="text-primary font-weight-bold ml-1">Daftar Petani Baru</a>
                                </p>
                            </div>

                            <!-- Petunjuk Aktor -->
                            <div class="mt-4 p-3 rounded" style="background: rgba(255,255,255,0.06); border-left: 3px solid #DA9F5B;">
                                <small class="text-warning d-block mb-1 font-weight-bold"><i class="fa fa-info-circle mr-1"></i> Informasi Akses:</small>
                                <small class="text-white-50 d-block" style="font-size: 11px;">
                                    &bull; <strong>Petani</strong> diarahkan ke Dashboard Manajemen Lahan & Panen Ceri/Green Bean.<br>
                                    &bull; <strong>Coffee Shop</strong> diarahkan ke Pasokan Kopi & Penerbitan Label QR Kemasan.<br>
                                    &bull; <strong>Super Admin</strong> diarahkan ke Panel Monitoring Panen & Manajemen User.
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    var input = document.getElementById(inputId);
    var icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa fa-eye-slash text-dark';
    } else {
        input.type = 'password';
        icon.className = 'fa fa-eye text-dark';
    }
}
</script>
@endsection
