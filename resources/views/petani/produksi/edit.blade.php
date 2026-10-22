@extends('layouts.dashboard')

@section('title', 'Edit Catatan Panen - KopiLink')
@section('page_title', 'Edit Hasil Panen Green Bean')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-stat">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5 class="font-weight-bold mb-1">Edit Catatan Panen #PRD-{{ $produksi->id_produksi }}</h5>
                    <p class="text-muted small mb-0">Ubah kuantitas kilogram, tanggal panen, lahan sumber, atau foto biji kopi.</p>
                </div>
                <a href="{{ route('petani.produksi.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar Panen
                </a>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('petani.produksi.update', ['id_produksi' => $produksi->id_produksi]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Pilih Lahan Kebun Sumber Panen <span class="text-danger">*</span></label>
                        <select name="id_lahan" class="custom-select" required>
                            @foreach($lahanList as $lahan)
                                <option value="{{ $lahan->id_lahan }}" {{ old('id_lahan', $produksi->id_lahan) == $lahan->id_lahan ? 'selected' : '' }}>
                                    Plot #{{ $lahan->id_lahan }} &bull; Varietas: {{ $lahan->jenis_kopi }} &bull; Luas: {{ $lahan->luas_lahan }} Ha ({{ number_format($lahan->jumlah_tanaman) }} Pohon)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jumlah Green Bean (Kilogram) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.5" min="0.5" name="jumlah_green_bean" class="form-control" placeholder="Contoh: 150" value="{{ old('jumlah_green_bean', $produksi->jumlah_green_bean) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">Kg</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tanggal Input / Panen <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input" class="form-control" value="{{ old('tanggal_input', $produksi->tanggal_input->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <!-- Foto Biji Kopi -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Foto Biji Kopi (Green Bean)</label>
                        @if($produksi->foto_green_bean)
                            <div class="mb-3">
                                <span class="small font-weight-bold text-muted d-block mb-1">Foto Panen Saat Ini:</span>
                                <img src="{{ asset('storage/' . $produksi->foto_green_bean) }}" alt="Foto Panen Saat Ini" class="rounded border shadow-sm" style="max-height: 140px; object-fit: cover;">
                            </div>
                        @endif

                        <input type="file" id="fotoBeanInput" accept="image/*" class="form-control-file">
                        <input type="file" name="foto_green_bean" id="compressedBeanFoto" class="d-none">
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-compress-arrows-alt text-primary mr-1"></i> 
                            Pilih foto baru untuk mengganti foto lama. Gambar otomatis dikompresi agar hemat kuota.
                        </small>
                        <div id="beanCompressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="beanPreviewContainer" class="mt-2 d-none">
                            <span class="small font-weight-bold text-muted d-block mb-1">Pratinjau Foto Baru:</span>
                            <img id="beanImgPreview" src="" alt="Pratinjau Foto Biji Kopi" class="rounded border" style="max-height: 180px;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('petani.produksi.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-koppee px-4 py-2 font-weight-bold">
                            <i class="fa fa-save mr-1"></i> Simpan Perubahan Panen
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
        const imgPreview = document.getElementById('beanImgPreview');
        const compressedInput = document.getElementById('compressedBeanFoto');

        info.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengompresi gambar biji kopi...';

        const originalSizeKB = (file.size / 1024).toFixed(1);
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
                    const newSizeKB = (blob.size / 1024).toFixed(1);
                    const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".webp", {
                        type: 'image/webp',
                        lastModified: Date.now()
                    });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    compressedInput.files = dataTransfer.files;

                    imgPreview.src = URL.createObjectURL(blob);
                    previewContainer.classList.remove('d-none');
                    info.innerHTML = `<i class="fa fa-check-circle"></i> Terkompresi: ${originalSizeKB} KB &rarr; ${newSizeKB} KB (Format WebP)`;
                }, 'image/webp', 0.8);
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
