@extends('layouts.dashboard')

@section('title', 'Input Produksi Baru - KopiLink')
@section('page_title', 'Form Pencatatan Panen Green Bean')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-stat">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-1">Catat Hasil Panen Biji Kopi</h5>
                <p class="text-muted small mb-0">Hasil panen yang dicatat akan berstatus 'Tersedia' dan langsung dapat dilihat oleh Coffee Shop mitra.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('petani.produksi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Pilih Lahan Kebun Sumber Panen <span class="text-danger">*</span></label>
                        <select name="id_lahan" class="custom-select" required>
                            <option value="" disabled selected>Pilih salah satu lahan Anda...</option>
                            @foreach($lahanList as $lahan)
                                <option value="{{ $lahan->id_lahan }}" {{ old('id_lahan') == $lahan->id_lahan ? 'selected' : '' }}>
                                    Plot #{{ $lahan->id_lahan }} &bull; Varietas: {{ $lahan->jenis_kopi }} &bull; Luas: {{ $lahan->luas_lahan }} Ha ({{ number_format($lahan->jumlah_tanaman) }} Pohon)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jumlah Green Bean (Kilogram) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.5" min="0.5" name="jumlah_green_bean" class="form-control" placeholder="Contoh: 150" value="{{ old('jumlah_green_bean') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">Kg</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tanggal Input / Panen <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input" class="form-control" value="{{ old('tanggal_input', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <!-- Foto Biji Kopi dengan Kompresi Gambar Klien -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Unggah Foto Biji Kopi (Green Bean)</label>
                        <input type="file" id="fotoBeanInput" accept="image/*" class="form-control-file">
                        <input type="file" name="foto_green_bean" id="compressedBeanFoto" class="d-none">
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-compress-arrows-alt text-primary mr-1"></i> 
                            Otomatis terkompresi di browser sebelum pengunggahan.
                        </small>
                        <div id="beanCompressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="beanPreviewContainer" class="mt-2 d-none">
                            <img id="beanImgPreview" src="" alt="Pratinjau Foto Biji Kopi" class="rounded border" style="max-height: 180px;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('petani.produksi.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-koppee px-4 py-2">
                            <i class="fa fa-check mr-1"></i> Simpan Hasil Panen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoBeanInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const info = document.getElementById('beanCompressionInfo');
        const previewContainer = document.getElementById('beanPreviewContainer');
        const preview = document.getElementById('beanImgPreview');
        const originalSizeKb = (file.size / 1024).toFixed(1);

        info.textContent = `Memproses kompresi gambar (${originalSizeKb} KB)...`;

        const reader = new FileReader();
        reader.onload = function (event) {
            const img = new Image();
            img.onload = function () {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const maxDim = 1200;

                if (width > maxDim || height > maxDim) {
                    if (width > height) {
                        height = Math.round((height * maxDim) / width);
                        width = maxDim;
                    } else {
                        width = Math.round((width * maxDim) / height);
                        height = maxDim;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(function (blob) {
                    const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    document.getElementById('compressedBeanFoto').files = dataTransfer.files;

                    const newSizeKb = (blob.size / 1024).toFixed(1);
                    const saving = (100 - (blob.size / file.size) * 100).toFixed(0);

                    info.textContent = `✓ Kompresi Berhasil: ${originalSizeKb} KB → ${newSizeKb} KB (Hemat ${saving}%)`;
                    preview.src = canvas.toDataURL('image/jpeg', 0.8);
                    previewContainer.classList.remove('d-none');
                }, 'image/jpeg', 0.75);
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
