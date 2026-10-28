@extends('layouts.app')

@section('title', 'Program Donasi & Keberlanjutan Kopi - KopiLink')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Program Donasi</h1>
        <div class="d-inline-flex mb-lg-5">
            <p class="m-0 text-white"><a class="text-white" href="{{ route('home') }}">Home</a></p>
            <p class="m-0 text-white px-2">/</p>
            <p class="m-0 text-white">Donasi</p>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Donasi Section Start -->
<div class="container-fluid py-5" style="background-color: #fbf7ee;">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Aksi Solidaritas & Keberlanjutan</h4>
            <h1 class="display-4">Dukung Petani & Ekosistem Kopi</h1>
            <p class="text-muted mt-2 mx-auto" style="max-width: 700px;">
                Dukung masa depan perkebunan kopi rakyat di Kalimantan Barat. Setiap kontribusi Anda langsung dialokasikan untuk penyediaan bibit unggul, apresiasi cangkir kopi petani, serta pendampingan budidaya tanpa biaya.
            </p>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show text-center mb-4 shadow-sm" role="alert" style="border-radius: 8px;">
                <i class="fa fa-check-circle mr-2 fa-lg text-success"></i>
                <strong>{{ session('status') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 8px;">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- 1. Donasi Bibit Kopi -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 12px; transition: transform 0.2s;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="fa fa-seedling fa-2x"></i>
                    </div>
                    <span class="badge badge-success px-3 py-2 font-weight-bold mx-auto mb-2" style="font-size: 13px;">
                        $1 USD (~Rp 16.000) = 1 Seedling
                    </span>
                    <h4 class="font-weight-bold text-dark mb-2">Donasi Bibit Kopi</h4>
                    <p class="text-muted small mb-4">
                        Menyediakan 1 bibit tanaman kopi varietas unggul (Robusta Pasak Piang / Liberika Padi Jaya) bersertifikat untuk ditanam petani di lahan kebun agroforestri ramah lingkungan demi konservasi gambut dan tanah.
                    </p>
                    <div class="mt-auto">
                        <button type="button" class="btn btn-primary btn-block font-weight-bold py-2 text-dark shadow-sm" style="background-color: #DA9F5B; border-color: #DA9F5B; border-radius: 6px;" data-toggle="modal" data-target="#modalDonasiHalaman" data-program="Donasi Bibit Kopi ($1 = 1 Seedling)">
                            <i class="fa fa-heart mr-1 text-danger"></i> Donasi Bibit Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Cup of Coffee -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 12px; transition: transform 0.2s;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(218, 159, 91, 0.2); color: #DA9F5B;">
                        <i class="fa fa-coffee fa-2x"></i>
                    </div>
                    <span class="badge badge-warning text-dark px-3 py-2 font-weight-bold mx-auto mb-2" style="background-color: #DA9F5B; font-size: 13px;">
                        Rp 200 = 1 Cup of Coffee
                    </span>
                    <h4 class="font-weight-bold text-dark mb-2">Cup of Coffee</h4>
                    <p class="text-muted small mb-4">
                        Donasi mikro Rp 200 dari setiap cangkir kopi yang dinikmati disisihkan sebagai dana abadi kesejahteraan sosial petani, dana darurat panen, dan perbaikan fasilitas sangrai mitra kebun.
                    </p>
                    <div class="mt-auto">
                        <button type="button" class="btn btn-primary btn-block font-weight-bold py-2 text-dark shadow-sm" style="background-color: #DA9F5B; border-color: #DA9F5B; border-radius: 6px;" data-toggle="modal" data-target="#modalDonasiHalaman" data-program="Cup of Coffee (Rp 200 = 1 Cup of Coffee)">
                            <i class="fa fa-mug-hot mr-1 text-secondary"></i> Donasi Per Cangkir
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. Pendampingan Petani -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 border-warning" style="border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #fffbf2 100%);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(23, 162, 184, 0.15); color: #17a2b8;">
                        <i class="fa fa-hands-helping fa-2x"></i>
                    </div>
                    <span class="badge badge-info px-3 py-2 font-weight-bold mx-auto mb-2" style="font-size: 13px;">
                        100% Gratis &bull; Bebas Biaya
                    </span>
                    <h4 class="font-weight-bold text-dark mb-2">Dampingi Petani</h4>
                    <p class="text-muted small mb-4">
                        Program pendampingan teknis budidaya, pelatihan sortasi petik merah, sertifikasi ketertelusuran, dan pengawasan agroforestri diberikan secara cuma-cuma tanpa dipungut biaya sepeserpun.
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('contact') }}" class="btn btn-outline-info btn-block font-weight-bold py-2">
                            <i class="fa fa-user-friends mr-1"></i> Gabung Relawan / Mitra
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alur Transparansi Penyaluran Donasi -->
        <div class="row mt-5 pt-3">
            <div class="col-12 text-center mb-4">
                <h3 class="font-weight-bold text-dark">Prinsip Ketertelusuran Donasi KopiLink</h3>
                <p class="text-muted">Setiap rupiah dan bibit yang disumbangkan dapat dilacak pemanfaatannya.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm h-100 border-left border-primary" style="border-left-width: 4px !important;">
                    <h5 class="font-weight-bold text-dark"><i class="fa fa-check-circle text-success mr-2"></i>Penyaluran Langsung</h5>
                    <p class="text-muted small mb-0">Donasi langsung dialokasikan ke kelompok tani terdaftar (Pasak Piang & Padi Jaya) tanpa potongan perantara.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm h-100 border-left border-primary" style="border-left-width: 4px !important;">
                    <h5 class="font-weight-bold text-dark"><i class="fa fa-map-marked-alt text-primary mr-2"></i>Plotting Lahan Terdata</h5>
                    <p class="text-muted small mb-0">Bibit yang ditanam dipetakan koordinat kebunnya pada sistem GIS KopiLink sehingga pertumbuhannya bisa dipantau.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm h-100 border-left border-primary" style="border-left-width: 4px !important;">
                    <h5 class="font-weight-bold text-dark"><i class="fa fa-award text-warning mr-2"></i>Dampak Sosial Terukur</h5>
                    <p class="text-muted small mb-0">Mendukung SDG 8 (Pekerjaan Layak & Pertumbuhan Ekonomi) dan SDG 15 (Konservasi Ekosistem Daratan).</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Donasi Section End -->

<!-- Modal Dialog Donasi Halaman -->
<div class="modal fade" id="modalDonasiHalaman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #33211D;">
                <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-heart text-danger mr-2"></i> Konfirmasi Donasi Kopi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('donasi.send') }}" method="POST" enctype="multipart/form-data" id="formDonasiPublik">
                @csrf
                <input type="hidden" name="program" id="donasiProgramInput" value="Donasi Bibit Kopi">
                <div class="modal-body p-4">
                    <div class="alert alert-warning mb-3 small" style="background-color: rgba(218, 159, 91, 0.2); border: 1px solid #DA9F5B; color: #33211D;">
                        <i class="fa fa-info-circle mr-1 text-primary"></i> 
                        Program terpilih: <strong id="modalDonasiHalamanProgramName">Donasi Bibit Kopi</strong>
                    </div>

                    @if(isset($rekeningAktif) && $rekeningAktif)
                        <div class="card bg-light border-0 p-3 mb-3 text-center">
                            <small class="text-muted font-weight-bold text-uppercase">Tujuan Transfer Resmi KopiLink:</small>
                            <h5 class="font-weight-bold text-dark my-1">{{ $rekeningAktif->nama_bank }}</h5>
                            <div class="badge badge-dark p-2 font-weight-bold text-monospace mx-auto my-1" style="font-size: 14px; letter-spacing: 1px;">
                                {{ $rekeningAktif->nomor_rekening }}
                            </div>
                            <small class="text-muted">a.n <strong>{{ $rekeningAktif->atas_nama }}</strong></small>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Donatur / Pembayar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_donatur" id="donasiHalamanNama" class="form-control" placeholder="Contoh: Budi Santoso (atau Hamba Allah)" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nominal Donasi (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light font-weight-bold">Rp</span>
                            </div>
                            <input type="number" name="nominal" id="donasiHalamanNominal" class="form-control font-weight-bold" placeholder="Contoh: 50000" min="1000" step="1000" value="50000" required>
                        </div>
                        <small class="text-muted d-block mt-1" id="keteranganEstimasi">
                            Estimasi: ~3 Bibit Pohon Kopi Agroforestri
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Pesan / Doa Semangat untuk Petani</label>
                        <textarea name="pesan" id="donasiHalamanPesan" class="form-control" rows="2" placeholder="Tuliskan kata motivasi untuk petani kopi Kalimantan Barat..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Upload Bukti Transfer / Resi (Opsional)</label>
                        {{-- Input file mentah yang dipilih user --}}
                        <input type="file" id="rawBuktiDonasi" class="form-control-file border p-2 rounded bg-light" accept="image/*" onchange="compressBuktiDonasi(this)">
                        {{-- Input terkompresi yang dikirimkan ke server --}}
                        <input type="file" name="bukti_transfer" id="compressedBuktiDonasi" class="d-none">
                        <div id="buktiCompressStatus" class="mt-2 small text-success font-weight-bold"></div>
                        <div id="buktiPreviewContainer" class="mt-2"></div>
                        <small class="form-text text-muted">Gambar otomatis dikompresi langsung demi kecepatan dan keandalan upload.</small>
                    </div>

                    <div class="p-2 bg-white border rounded text-center small text-muted">
                        <i class="fa fa-shield-alt text-success mr-1"></i> Donasi Anda langsung diverifikasi dan dicatat pada buku kas Super Admin.
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2 text-dark" style="background-color: #DA9F5B; border-color: #DA9F5B;">
                        <i class="fa fa-paper-plane mr-1"></i> Kirim Donasi ke Super Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalDonasiHalaman').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var program = button.data('program') || 'Donasi Bibit Kopi ($1 = 1 Seedling)';
        $('#modalDonasiHalamanProgramName').text(program);
        $('#donasiProgramInput').val(program);

        var nominalInput = document.getElementById('donasiHalamanNominal');
        var estimasi = document.getElementById('keteranganEstimasi');
        if (program.indexOf('Bibit') > -1) {
            nominalInput.value = '50000';
            estimasi.innerText = 'Estimasi: ~3 Bibit Pohon Kopi Agroforestri';
        } else if (program.indexOf('Cup') > -1) {
            nominalInput.value = '20000';
            estimasi.innerText = 'Estimasi: Setara apresiasi 100 cangkir kopi petani';
        } else {
            nominalInput.value = '25000';
            estimasi.innerText = 'Dukungan operasional & keberlanjutan kebun';
        }
    });

    document.getElementById('donasiHalamanNominal').addEventListener('input', function() {
        var val = parseFloat(this.value) || 0;
        var program = $('#donasiProgramInput').val();
        var estimasi = document.getElementById('keteranganEstimasi');
        if (program.indexOf('Bibit') > -1) {
            var bibit = Math.floor(val / 16000);
            estimasi.innerText = 'Estimasi: ~' + (bibit > 0 ? bibit : 0) + ' Bibit Pohon Kopi Agroforestri';
        } else if (program.indexOf('Cup') > -1) {
            var cup = Math.floor(val / 200);
            estimasi.innerText = 'Estimasi: Setara apresiasi ' + cup + ' cangkir kopi';
        }
    });
});

function compressBuktiDonasi(input) {
    const statusEl = document.getElementById('buktiCompressStatus');
    const container = document.getElementById('buktiPreviewContainer');
    const hiddenInput = document.getElementById('compressedBuktiDonasi');

    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];
    if (!file.type.startsWith('image/')) return;

    statusEl.innerHTML = '<span class="text-info"><i class="fa fa-spinner fa-spin mr-1"></i> Sedang mengompresi gambar...</span>';
    container.innerHTML = '';

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
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

            canvas.toBlob(function(blob) {
                const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                hiddenInput.files = dataTransfer.files;

                const origKb = (file.size / 1024).toFixed(0);
                const compKb = (blob.size / 1024).toFixed(0);
                const saving = file.size > 0 ? (100 - (blob.size / file.size) * 100).toFixed(0) : 0;

                statusEl.innerHTML = '<span class="text-success"><i class="fa fa-check-circle mr-1"></i> Terkompresi: ' + origKb + ' KB &rarr; ' + compKb + ' KB (Hemat ' + saving + '%)</span>';

                const thumb = document.createElement('img');
                thumb.src = canvas.toDataURL('image/jpeg', 0.8);
                thumb.className = 'rounded border shadow-sm';
                thumb.style.maxHeight = '90px';
                thumb.style.maxWidth = '100%';
                thumb.style.objectFit = 'contain';
                container.appendChild(thumb);
            }, 'image/jpeg', 0.8);
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
