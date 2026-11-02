@extends('layouts.app')

@section('title', 'Kontak Kami - KOPPEE KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Kontak Kami</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Kontak</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Contact Start -->
<div class="container-fluid pt-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Hubungi Kami</h4>
            <h1 class="display-4">Kemitraan Petani & Roastery</h1>
        </div>
        <div class="row px-3 pb-2">
            <div class="col-sm-4 text-center mb-3">
                <i class="fa fa-2x fa-map-marker-alt mb-3 text-primary"></i>
                <h4 class="font-weight-bold">Alamat</h4>
                <p>Sentra Kopi Agroforestri, Kalimantan Barat, Indonesia</p>
            </div>
            <div class="col-sm-4 text-center mb-3">
                <i class="fa fa-2x fa-phone-alt mb-3 text-primary"></i>
                <h4 class="font-weight-bold">Telepon / WhatsApp</h4>
                <p>+62 812-3456-7890</p>
            </div>
            <div class="col-sm-4 text-center mb-3">
                <i class="fa fa-2x fa-envelope mb-3 text-primary"></i>
                <h4 class="font-weight-bold">Email</h4>
                <p>traceability@kopilink.id</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg text-white p-4" style="background-color: #33211D; border-radius: 8px;">
                    <div class="card-body">
                        <h4 class="text-white mb-3">Kirim Pesan Kemitraan</h4>

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #DA9F5B; color: #111;">
                                <i class="fa fa-check-circle mr-2"></i> {{ session('status') }}
                                <button type="button" class="close text-dark" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                                <i class="fa fa-exclamation-triangle mr-2"></i> <strong>Perhatian:</strong>
                                <ul class="mb-0 mt-1 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form id="contactForm" action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            {{-- Token Keamanan Anti-Spam & Honeypot Bot --}}
                            <input type="hidden" name="_form_ts" value="{{ encrypt(time()) }}">
                            <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none; width: 0; height: 0;" aria-hidden="true">
                                <input type="text" name="_hp_security_check" tabindex="-1" autocomplete="off" value="" />
                            </div>

                            <div class="form-group">
                                <input type="text" name="nama" class="form-control p-4 border-0" placeholder="Nama Lengkap Anda" value="{{ old('nama') }}" required="required" maxlength="100" />
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control p-4 border-0" placeholder="Alamat Email Anda" value="{{ old('email') }}" required="required" maxlength="100" />
                            </div>
                            <div class="form-group">
                                <input type="text" name="subjek" class="form-control p-4 border-0" placeholder="Subjek Kemitraan / Pertanyaan" value="{{ old('subjek') }}" required="required" maxlength="150" />
                            </div>
                            <div class="form-group">
                                <textarea name="pesan" class="form-control border-0 p-3" rows="4" placeholder="Tuliskan pesan lengkap Anda di sini (minimal 5 karakter)..." required="required" minlength="5" maxlength="3000">{{ old('pesan') }}</textarea>
                            </div>
                            <div>
                                <button id="btnSubmitContact" class="btn btn-primary font-weight-bold py-3 px-5 text-secondary" type="submit" style="color: #33211D !important;">
                                    <i class="fa fa-paper-plane mr-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

@push('scripts')
<script>
document.getElementById('contactForm')?.addEventListener('submit', function() {
    var btn = document.getElementById('btnSubmitContact');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Mengirim Pesan...';
    }
});
</script>
@endpush
@endsection
