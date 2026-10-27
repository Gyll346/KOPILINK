@extends('layouts.dashboard')

@section('title', 'Produksi Diterima - KopiLink')
@section('page_title', 'Data Pasokan Kopi Diterima')

@section('content')
<div class="mb-4">
    <h4 class="font-weight-bold mb-1">Inventori Biji Kopi Diterima</h4>
    <p class="text-muted mb-0">Biji kopi yang telah berhasil Anda serap. Tekan "Generate QR Code" untuk menerbitkan sertifikat digital dan label kemasan ber-QR.</p>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0"><i class="fa fa-clipboard-check text-primary mr-2"></i> Data Pasokan Diterima</h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchTableInput" placeholder="Cari petani, batch, varietas..." onkeyup="filterDiterimaTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($produksiList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pasokan yang diterima.</h5>
                <a href="{{ route('coffeeshop.pasokan.index') }}" class="btn btn-koppee mt-2">Buka Menu Pasokan Kopi</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="diterimaTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">ID Batch</th>
                            <th>Petani Asal</th>
                            <th>Varietas</th>
                            <th>Kuantitas</th>
                            <th>Tanggal Panen</th>
                            <th>Status Kemasan QR</th>
                            <th>Aksi Penerbitan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produksiList as $prod)
                            <tr>
                                <td class="pl-4 font-weight-bold">#PRD-{{ $prod->id_produksi }}</td>
                                <td>
                                    <strong>{{ $prod->lahan->petani->nama }}</strong>
                                    <small class="d-block text-muted">{{ $prod->lahan->petani->kelompok_tani }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="background-color: #DA9F5B;">
                                        {{ $prod->lahan->jenis_kopi }}
                                    </span>
                                </td>
                                <td><strong>{{ $prod->jumlah_green_bean }}</strong> Kg</td>
                                <td>{{ $prod->tanggal_input->format('d M Y') }}</td>
                                <td>
                                    @if($prod->kemasan->isNotEmpty())
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fa fa-check mr-1"></i> {{ $prod->kemasan->count() }} Label Diterbitkan
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">
                                            <i class="fa fa-hourglass-half mr-1"></i> Siap Dikemas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-koppee btn-sm px-3 font-weight-bold" data-toggle="modal" data-target="#modalSangrai{{ $prod->id_produksi }}">
                                        <i class="fa fa-fire mr-1"></i> Sangrai & Generate QR
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Render semua Modal di luar elemen .card-stat dan <table> agar tidak terpengaruh CSS transform: translateY saat hover pointer --}}
@foreach($produksiList as $prod)
    <div class="modal fade" id="modalSangrai{{ $prod->id_produksi }}" tabindex="-1" role="dialog" aria-labelledby="modalSangraiLabel{{ $prod->id_produksi }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h6 class="modal-title font-weight-bold" id="modalSangraiLabel{{ $prod->id_produksi }}">
                        <i class="fa fa-fire text-warning mr-2"></i> Sangrai Green Bean #PRD-{{ $prod->id_produksi }}
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('coffeeshop.generate.qr', ['id_produksi' => $prod->id_produksi]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 text-left">
                        <div class="alert alert-light border small mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Petani Asal:</span>
                                <strong>{{ $prod->lahan->petani->nama }} ({{ $prod->lahan->petani->kelompok_tani }})</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Varietas Biji:</span>
                                <span class="badge badge-warning text-dark font-weight-bold">{{ $prod->lahan->jenis_kopi }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Kuantitas Pasokan:</span>
                                <strong>{{ $prod->jumlah_green_bean }} Kg</strong>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark">
                                <i class="fa fa-camera text-primary mr-1"></i> Foto Green Bean Hasil Sangrai <span class="text-danger">*</span>
                            </label>
                            {{-- Input file mentah yang dipilih user --}}
                            <input type="file" class="form-control-file border p-2 rounded bg-light" accept="image/*" multiple id="rawInputSangrai{{ $prod->id_produksi }}" onchange="handleSangraiCompression(this, {{ $prod->id_produksi }})">
                            {{-- Input file terkompresi yang akan dikirim ke server --}}
                            <input type="file" name="foto_sangrai[]" id="compressedInputSangrai{{ $prod->id_produksi }}" class="d-none" multiple required>
                            
                            <div id="sangraiCompressStatus{{ $prod->id_produksi }}" class="mt-2 small text-success font-weight-bold"></div>
                            <small class="form-text text-muted">
                                Foto otomatis dikompresi langsung di browser secara instan demi kecepatan upload.
                            </small>
                            <div id="previewSangraiContainer{{ $prod->id_produksi }}" class="d-flex flex-wrap mt-2" style="gap: 8px;"></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-koppee btn-sm font-weight-bold" id="btnSubmitSangrai{{ $prod->id_produksi }}">
                            <i class="fa fa-qrcode mr-1"></i> Simpan Sangrai & Terbitkan QR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
function filterDiterimaTable() {
    const input = document.getElementById("searchTableInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("diterimaTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}

// Kompresi otomatis gambar sangrai di sisi browser (Client-side HTML5 Canvas Pre-compression)
function handleSangraiCompression(input, id) {
    const statusEl = document.getElementById('sangraiCompressStatus' + id);
    const container = document.getElementById('previewSangraiContainer' + id);
    const hiddenInput = document.getElementById('compressedInputSangrai' + id);
    const submitBtn = document.getElementById('btnSubmitSangrai' + id);

    if (!input.files || input.files.length === 0) return;

    const files = Array.from(input.files);
    container.innerHTML = '';
    statusEl.innerHTML = `<span class="text-info"><i class="fa fa-spinner fa-spin mr-1"></i> Sedang mengompresi ${files.length} foto secara otomatis...</span>`;
    if (submitBtn) submitBtn.disabled = true;

    const dataTransfer = new DataTransfer();
    let processedCount = 0;
    let totalOrig = 0;
    let totalComp = 0;

    files.forEach((file, index) => {
        totalOrig += file.size;

        if (!file.type.startsWith('image/')) {
            processedCount++;
            if (processedCount === files.length) finalizeCompression();
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const maxDim = 1200; // Standar resolusi optimal

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

                canvas.toBlob(function(blob) {
                    totalComp += blob.size;
                    const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    dataTransfer.items.add(compressedFile);

                    // Buat pratinjau thumbnail
                    const thumb = document.createElement('img');
                    thumb.src = canvas.toDataURL('image/jpeg', 0.8);
                    thumb.className = 'rounded border shadow-sm';
                    thumb.style.width = '70px';
                    thumb.style.height = '70px';
                    thumb.style.objectFit = 'cover';
                    container.appendChild(thumb);

                    processedCount++;
                    if (processedCount === files.length) {
                        finalizeCompression();
                    }
                }, 'image/jpeg', 0.8);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    function finalizeCompression() {
        hiddenInput.files = dataTransfer.files;
        if (submitBtn) submitBtn.disabled = false;

        const origKb = (totalOrig / 1024).toFixed(0);
        const compKb = (totalComp / 1024).toFixed(0);
        const saving = totalOrig > 0 ? (100 - (totalComp / totalOrig) * 100).toFixed(0) : 0;

        statusEl.innerHTML = `<span class="text-success"><i class="fa fa-check-circle mr-1"></i> Berhasil dikompresi: ${origKb} KB &rarr; ${compKb} KB (Hemat ${saving}%)</span>`;
    }
}
</script>
@endsection
