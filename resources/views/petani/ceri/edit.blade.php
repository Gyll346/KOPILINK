@extends('layouts.dashboard')

@section('title', 'Edit Panen Ceri - KopiLink')
@section('page_title', 'Edit Hasil Panen Ceri Kopi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-stat">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5 class="font-weight-bold mb-1">Edit Catatan Panen Ceri #CRI-{{ $ceri->id_panen_ceri }}</h5>
                    <p class="text-muted small mb-0">Ubah kuantitas kilogram, tanggal panen, lahan sumber, catatan, atau foto buah ceri.</p>
                </div>
                <a href="{{ route('petani.ceri.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar Panen Ceri
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

                <form action="{{ route('petani.ceri.update', ['id_panen_ceri' => $ceri->id_panen_ceri]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Pilih Lahan Kebun Sumber Panen <span class="text-danger">*</span></label>
                        <select name="id_lahan" class="custom-select" required>
                            @foreach($lahanList as $lahan)
                                <option value="{{ $lahan->id_lahan }}" {{ old('id_lahan', $ceri->id_lahan) == $lahan->id_lahan ? 'selected' : '' }}>
                                    Plot #{{ $lahan->id_lahan }} &bull; Varietas: {{ $lahan->jenis_kopi }} &bull; Luas: {{ $lahan->luas_lahan }} Ha ({{ number_format($lahan->jumlah_tanaman) }} Pohon)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jumlah Ceri Basah (Kilogram) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.5" min="0.5" name="jumlah_ceri" class="form-control" placeholder="Contoh: 300" value="{{ old('jumlah_ceri', $ceri->jumlah_ceri) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">Kg</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tanggal Panen <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_panen" class="form-control" value="{{ old('tanggal_panen', $ceri->tanggal_panen->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Catatan Kualitas / Sortasi (Opsional)</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Contoh: 95% Petik Merah Sempurna" value="{{ old('catatan', $ceri->catatan) }}">
                    </div>

                    <!-- Foto Ceri Kopi (Multi-Foto) -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">
                            Foto Buah Ceri Kopi
                            @if(count($ceri->foto_list) === 0)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        @if(count($ceri->foto_list) > 0)
                            <div class="mb-3">
                                <span class="small font-weight-bold text-muted d-block mb-1">
                                    <i class="fa fa-images mr-1"></i> Foto Panen Saat Ini ({{ count($ceri->foto_list) }} foto):
                                </span>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @foreach($ceri->foto_list as $idx => $fPath)
                                        <div class="position-relative rounded border overflow-hidden shadow-sm" style="width: 120px; height: 95px;">
                                            <a href="{{ asset('storage/' . $fPath) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $fPath) }}" alt="Foto Panen Ceri {{ $idx + 1 }}" class="w-100 h-100" style="object-fit: cover;">
                                            </a>
                                            <span class="badge badge-dark position-absolute" style="bottom: 2px; left: 2px; font-size: 9px;">
                                                Foto {{ $idx + 1 }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <input type="file" id="fotoCeriInput" accept="image/*" class="form-control-file" multiple>
                        <input type="file" name="foto_ceri[]" id="compressedCeriFoto" class="d-none" multiple>
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-info-circle text-primary mr-1"></i> 
                            Pilih gambar baru jika ingin mengganti seluruh foto panen ceri. Bisa pilih lebih dari 1 foto (foto pertama wajib, tambahan opsional).
                        </small>
                        <div id="ceriCompressionInfo" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="ceriPreviewContainer" class="mt-2 d-none">
                            <span class="small font-weight-bold text-muted d-block mb-1">Pratinjau Foto Pengganti:</span>
                            <div id="ceriPreviewGrid" class="d-flex flex-wrap" style="gap: 10px;"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('petani.ceri.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-koppee px-4 py-2 font-weight-bold">
                            <i class="fa fa-save mr-1"></i> Simpan Perubahan Panen Ceri
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
    document.getElementById('fotoCeriInput').addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        if (!files || files.length === 0) return;

        const info = document.getElementById('ceriCompressionInfo');
        const previewContainer = document.getElementById('ceriPreviewContainer');
        const previewGrid = document.getElementById('ceriPreviewGrid');

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
                            document.getElementById('compressedCeriFoto').files = dataTransfer.files;
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
