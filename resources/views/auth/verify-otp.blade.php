@extends('layouts.app')

@section('title', 'Verifikasi WhatsApp OTP - KopiLink')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg text-center" style="background-color: #33211D; border-radius: 8px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4">
                            <span class="rounded-circle bg-primary text-secondary d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px; color: #33211D !important;">
                                <i class="fab fa-whatsapp fa-3x"></i>
                            </span>
                        </div>

                        <h3 class="text-white text-uppercase font-weight-bold mb-2">Verifikasi WhatsApp OTP</h3>
                        <p class="text-white-50 mb-4">
                            Kode 6 digit telah dikirimkan ke nomor WhatsApp: <br>
                            <strong class="text-primary font-weight-bold" style="font-size: 18px;">+{{ $petani->kontak }}</strong>
                        </p>

                        @if($petani->otp_code)
                            <div class="alert alert-info border-0 mb-4" style="background: rgba(218, 159, 91, 0.2); color: #DA9F5B;">
                                <i class="fa fa-info-circle mr-1"></i> Mode Pengujian Lokal: Kode OTP aktif Anda adalah <strong>{{ $petani->otp_code }}</strong>
                            </div>
                        @endif

                        <form action="{{ route('otp.verify.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_petani" value="{{ $petani->id_petani }}">

                            <div class="form-group mb-4">
                                <label class="text-white font-weight-bold d-block text-left">Masukkan 6-Digit Kode OTP</label>
                                <input type="text" name="otp_code" class="form-control text-center font-weight-bold border-0" 
                                       placeholder="••••••" maxlength="6" style="letter-spacing: 12px; font-size: 28px; height: 60px;" required autofocus>
                                <small class="text-muted text-light d-block text-left mt-1">Masa berlaku kode: 5 menit.</small>
                            </div>

                            <button class="btn btn-primary btn-block font-weight-bold py-3 text-secondary text-uppercase" type="submit" style="color: #33211D !important; font-size: 16px;">
                                <i class="fa fa-check mr-2"></i> Verifikasi Akun Sekarang
                            </button>
                        </form>

                        <div class="mt-4 pt-3 border-top border-dark">
                            <form action="{{ route('otp.resend', ['id_petani' => $petani->id_petani]) }}" method="POST">
                                @csrf
                                <p class="text-white mb-2">Belum menerima pesan WhatsApp?</p>
                                <button type="submit" class="btn btn-outline-primary btn-sm px-4">
                                    <i class="fa fa-redo mr-1"></i> Kirim Ulang Kode OTP
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
