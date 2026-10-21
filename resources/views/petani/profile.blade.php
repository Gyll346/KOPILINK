@extends('layouts.dashboard')

@section('title', 'Profil Petani - KopiLink')
@section('page_title', 'Profil Petani')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- Kartu Ringkasan Akun -->
        <div class="card card-stat text-center p-4">
            <div class="position-relative d-inline-block mx-auto mb-3">
                @if($petani->foto_profil)
                    <img src="{{ asset('storage/' . $petani->foto_profil) }}" alt="Foto {{ $petani->nama }}" class="rounded-circle shadow" style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #DA9F5B;">
                @else
                    <div class="rounded-circle bg-primary text-secondary d-flex align-items-center justify-content-center shadow mx-auto" style="width: 130px; height: 130px; font-size: 55px; color: #33211D !important; border: 4px solid #DA9F5B;">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
            </div>

            <h4 class="font-weight-bold text-dark mb-1">{{ $petani->nama }}</h4>
            <p class="text-muted small mb-2"><i class="fa fa-user-tag mr-1 text-primary"></i> @<span>{{ $petani->username }}</span></p>

            <div class="mb-3">
                <span class="badge badge-success px-3 py-2 font-weight-bold">
                    <i class="fab fa-whatsapp mr-1"></i> Terverifikasi WhatsApp
                </span>
            </div>

            <hr class="my-3">

            <div class="text-left small">
                <div class="mb-2">
                    <strong class="text-muted d-block">Kelompok Tani:</strong>
                    <span class="font-weight-bold text-dark">{{ $petani->kelompok_tani }}</span>
                </div>
                <div class="mb-2">
                    <strong class="text-muted d-block">Nomor Kontak WhatsApp:</strong>
                    <span class="font-weight-bold text-dark">+{{ $petani->kontak }}</span>
                </div>
                <div>
                    <strong class="text-muted d-block">Alamat Domisili:</strong>
                    <span class="text-dark">{{ $petani->alamat }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Formulir Perbarui Profil & Foto -->
        <div class="card card-stat p-4">
            <h5 class="font-weight-bold text-dark mb-4">
                <i class="fa fa-user-edit text-primary mr-2"></i> Perbarui Data & Foto Profil
            </h5>

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('petani.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark">Foto Profil Petani</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewProfileImage(this)">
                        <label class="custom-file-label" for="foto_profil" id="fotoLabel">Pilih foto profil baru...</label>
                    </div>
                    <small class="form-text text-muted">Format: JPG, PNG, WEBP. Maksimal 10MB (sistem akan mengompresi otomatis).</small>

                    <!-- Image Preview -->
                    <div class="mt-3 d-none" id="previewContainer">
                        <span class="small font-weight-bold text-muted d-block mb-1">Pratinjau Foto Baru:</span>
                        <img id="imagePreview" src="#" alt="Pratinjau Foto" class="rounded shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $petani->nama) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold text-dark">Username Akun</label>
                        <input type="text" class="form-control bg-light" value="{{ $petani->username }}" readonly>
                        <small class="form-text text-muted">Username unik tidak dapat diubah.</small>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold text-dark">Kelompok Tani</label>
                        <input type="text" class="form-control bg-light" value="{{ $petani->kelompok_tani }}" readonly>
                        <small class="form-text text-muted">Terdaftar sesuai wilayah binaan.</small>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" rows="3" class="form-control" required>{{ old('alamat', $petani->alamat) }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-koppee px-4 py-2 font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewProfileImage(input) {
    const label = document.getElementById('fotoLabel');
    const previewContainer = document.getElementById('previewContainer');
    const previewImg = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
