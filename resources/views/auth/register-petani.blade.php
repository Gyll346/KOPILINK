@extends('layouts.app')

@section('title', 'Pendaftaran Akun Petani - KopiLink')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Portal Petani</h4>
            <h1 class="display-4">Daftar Akun Petani Kopi</h1>
            <p class="text-muted">Bergabunglah dalam ekosistem rantai pasok kopi transparan untuk kesejahteraan petani lokal.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg" style="background-color: #33211D; border-radius: 8px;">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('register.petani.submit') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">Nama Lengkap Petani <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control p-4 border-0" placeholder="Contoh: Pak Ahmad Subarjo" value="{{ old('nama') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control p-4 border-0" placeholder="Contoh: petani_pasak01" value="{{ old('username') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">Nomor Kontak WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" name="kontak" class="form-control p-4 border-0" placeholder="081234567890 atau 6281234567890" value="{{ old('kontak') }}" required>
                                    <small class="text-muted text-light">Akan dikirimkan kode OTP verifikasi WhatsApp.</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">Kelompok Tani <span class="text-danger">*</span></label>
                                    <select name="kelompok_tani" class="custom-select px-4 border-0" style="height: 48px;" required>
                                        <option value="" disabled selected>Pilih Kelompok Tani...</option>
                                        <option value="Pasak Piang" {{ old('kelompok_tani') == 'Pasak Piang' ? 'selected' : '' }}>Pasak Piang (Varietas Robusta)</option>
                                        <option value="Padi Jaya" {{ old('kelompok_tani') == 'Padi Jaya' ? 'selected' : '' }}>Padi Jaya (Varietas Liberika)</option>
                                    </select>
                                    <small class="text-muted text-light">Varietas kopi akan otomatis ditentukan berdasarkan kelompok tani.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-white font-weight-bold">Alamat / Lokasi Desa <span class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control border-0 p-3" rows="3" placeholder="Contoh: Desa Pasak Piang, RT 02 / RW 01, Kec. Sungai Ambawang" required>{{ old('alamat') }}</textarea>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control p-4 border-0" placeholder="Minimal 6 karakter" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="text-white font-weight-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control p-4 border-0" placeholder="Ulangi password" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary btn-block font-weight-bold py-3 text-secondary text-uppercase" type="submit" style="color: #33211D !important; font-size: 16px;">
                                    <i class="fa fa-paper-plane mr-2"></i> Daftar & Dapatkan Kode OTP WhatsApp
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-white mb-0">Sudah memiliki akun terdaftar? <a href="{{ route('login') }}" class="text-primary font-weight-bold">Masuk di sini</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
