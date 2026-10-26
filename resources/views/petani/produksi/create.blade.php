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

                    <!-- Foto Biji Kopi dengan Kompresi Gambar Klien (Bisa Lebih Dari 1 Foto) -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">
                            Unggah Foto Biji Kopi (Green Bean) <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="fotoBeanInput" accept="image/*" class="form-control-file" multiple>
                        <input type="file" name="foto_green_bean[]" id="compressedBeanFoto" class="d-none" multiple>
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-info-circle text-primary mr-1"></i> 
                            <strong>Upload 1 foto wajib</strong>, upload foto tambahan bersifat <strong>opsional</strong> (bisa pilih beberapa foto sekaligus). Foto otomatis dikompresi sebelum diunggah.
                        </small>
                        <div id="beanCompressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="beanPreviewContainer" class="mt-2 d-none">
                            <span class="small font-weight-bold text-muted d-block mb-1">Pratinjau Foto Terpilih:</span>
                            <div id="beanPreviewGrid" class="d-flex flex-wrap" style="gap: 10px;"></div>
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
        const files = Array.from(e.target.files);
        if (!files || files.length === 0) return;

        const info = document.getElementById('beanCompressionInfo');
        const previewContainer = document.getElementById('beanPreviewContainer');
        const previewGrid = document.getElementById('beanPreviewGrid');

        previewGrid.innerHTML = '';
        info.textContent = `Sedang memproses & mengompresi ${files.length} foto...`;

        const dataTransfer = new DataTransfer();
        let processedCount = 0;
        let totalOriginalSize = 0;
        let totalNewSize = 0;

        files.forEach((file, index) => {
            totalOriginalSize += file.size;
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
                        totalNewSize += blob.size;
                        const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });

                        dataTransfer.items.add(compressedFile);

                        // Buat thumbnail pratinjau
                        const thumbWrapper = document.createElement('div');
                        thumbWrapper.className = 'position-relative rounded border overflow-hidden shadow-sm';
                        thumbWrapper.style.width = '120px';
                        thumbWrapper.style.height = '100px';

                        const thumbImg = document.createElement('img');
                        thumbImg.src = canvas.toDataURL('image/jpeg', 0.8);
                        thumbImg.className = 'w-100 h-100';
                        thumbImg.style.objectFit = 'cover';

                        const badge = document.createElement('span');
                        badge.className = 'badge badge-dark position-absolute';
                        badge.style.bottom = '2px';
                        badge.style.left = '2px';
                        badge.style.fontSize = '9px';
                        badge.textContent = `Foto ${index + 1}`;

                        thumbWrapper.appendChild(thumbImg);
                        thumbWrapper.appendChild(badge);
                        previewGrid.appendChild(thumbWrapper);

                        processedCount++;
                        if (processedCount === files.length) {
                            document.getElementById('compressedBeanFoto').files = dataTransfer.files;
                            const origKb = (totalOriginalSize / 1024).toFixed(1);
                            const newKb = (totalNewSize / 1024).toFixed(1);
                            const saving = totalOriginalSize > 0 ? (100 - (totalNewSize / totalOriginalSize) * 100).toFixed(0) : 0;
                            info.textContent = `✓ Berhasil mengompresi ${files.length} foto: ${origKb} KB → ${newKb} KB (Hemat ${saving}%)`;
                            previewContainer.classList.remove('d-none');
                        }
                    }, 'image/jpeg', 0.75);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
