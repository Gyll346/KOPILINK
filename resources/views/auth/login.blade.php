@extends('layouts.app')

@section('title', 'Login Portal KopiLink - KOPPEE')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Autentikasi Sistem</h4>
            <h1 class="display-4">Masuk ke Portal KopiLink</h1>
            <p class="text-muted">Pilih peran akun Anda untuk mengelola rantai pasok kopi dari hulu ke hilir.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <!-- Nav Tabs -->
                <ul class="nav nav-pills nav-justified mb-4" id="loginTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-3 text-uppercase" id="petani-tab" data-toggle="pill" href="#petani" role="tab" style="border-radius: 4px;">
                            <i class="fa fa-user mr-2"></i> Portal Petani
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3 text-uppercase" id="coffeeshop-tab" data-toggle="pill" href="#coffeeshop" role="tab" style="border-radius: 4px;">
                            <i class="fa fa-store mr-2"></i> Coffee Shop / Roastery
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="loginTabContent">
                    <!-- Tab Petani -->
                    <div class="tab-pane fade show active" id="petani" role="tabpanel">
                        <div class="card border-0 shadow-lg" style="background-color: #33211D; border-radius: 8px;">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="text-white text-center font-weight-bold mb-4">Masuk Sebagai Petani Kopi</h4>
                                <form action="{{ route('login.petani') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="text-white font-weight-bold">Username Petani</label>
                                        <input type="text" name="username" class="form-control p-4 border-0" placeholder="Username Anda" value="{{ old('username') }}" required autofocus>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold">Password</label>
                                        <input type="password" name="password" class="form-control p-4 border-0" placeholder="Kata sandi" required>
                                    </div>

                                    <button class="btn btn-primary btn-block font-weight-bold py-3 text-secondary text-uppercase" type="submit" style="color: #33211D !important; font-size: 16px;">
                                        <i class="fa fa-sign-in-alt mr-2"></i> Masuk ke Dashboard Petani
                                    </button>

                                    <div class="text-center mt-4">
                                        <p class="text-white mb-0">Belum memiliki akun petani? <a href="{{ route('register.petani') }}" class="text-primary font-weight-bold">Daftar sekarang</a></p>
                                    </div>

                                    <div class="mt-4 p-3 rounded" style="background: rgba(255,255,255,0.05); border-left: 3px solid #DA9F5B;">
                                        <small class="text-light d-block mb-1 font-weight-bold">Akun Demo Petani:</small>
                                        <small class="text-muted text-light d-block">Username: <code>petani_pasak</code> | Password: <code>password123</code> (Robusta)</small>
                                        <small class="text-muted text-light d-block">Username: <code>petani_padi</code> | Password: <code>password123</code> (Liberika)</small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Coffee Shop -->
                    <div class="tab-pane fade" id="coffeeshop" role="tabpanel">
                        <div class="card border-0 shadow-lg" style="background-color: #33211D; border-radius: 8px;">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="text-white text-center font-weight-bold mb-4">Masuk Sebagai Mitra Coffee Shop</h4>
                                <form action="{{ route('login.coffeeshop') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="text-white font-weight-bold">Username Mitra Roastery</label>
                                        <input type="text" name="username" class="form-control p-4 border-0" placeholder="Username Roastery / Toko" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold">Password</label>
                                        <input type="password" name="password" class="form-control p-4 border-0" placeholder="Kata sandi mitra" required>
                                    </div>

                                    <button class="btn btn-primary btn-block font-weight-bold py-3 text-secondary text-uppercase" type="submit" style="color: #33211D !important; font-size: 16px;">
                                        <i class="fa fa-store mr-2"></i> Masuk ke Portal Coffee Shop
                                    </button>

                                    <div class="mt-4 p-3 rounded" style="background: rgba(255,255,255,0.05); border-left: 3px solid #DA9F5B;">
                                        <small class="text-light d-block mb-1 font-weight-bold">Akun Demo Mitra Roastery:</small>
                                        <small class="text-muted text-light d-block">Username: <code>roastery_senja</code> | Password: <code>KopiLink2026!</code></small>
                                        <small class="text-muted text-light d-block">Username: <code>biji_bumi_coffee</code> | Password: <code>BumiHijau2026!</code></small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
