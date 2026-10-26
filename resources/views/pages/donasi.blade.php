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
            <div class="modal-body p-4">
                <div class="alert alert-warning mb-3 small" style="background-color: rgba(218, 159, 91, 0.2); border: 1px solid #DA9F5B; color: #33211D;">
                    <i class="fa fa-info-circle mr-1 text-primary"></i> 
                    Program terpilih: <strong id="modalDonasiHalamanProgramName">-</strong>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Nama Donatur / Pemerhati Kopi</label>
                    <input type="text" id="donasiHalamanNama" class="form-control" placeholder="Contoh: Budi Santoso (atau Anonim)">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Jumlah Partisipasi</label>
                    <input type="number" id="donasiHalamanJumlah" class="form-control" placeholder="Contoh: 10 bibit / 50 cangkir" min="1" value="5">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Pesan Semangat untuk Petani</label>
                    <textarea id="donasiHalamanPesan" class="form-control" rows="2" placeholder="Tuliskan kata motivasi untuk petani kopi Kalimantan Barat..."></textarea>
                </div>
                <div class="p-3 bg-light rounded text-center small text-muted">
                    <i class="fa fa-shield-alt text-success mr-1"></i> 100% donasi dialokasikan langsung kepada kelompok tani <strong>Pasak Piang & Padi Jaya</strong>.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary font-weight-bold px-4 py-2 text-dark" style="background-color: #DA9F5B; border-color: #DA9F5B;" onclick="submitDonasiHalaman()">
                    <i class="fa fa-check mr-1"></i> Kirim Donasi Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalDonasiHalaman').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var program = button.data('program') || 'Donasi Kopi';
        $('#modalDonasiHalamanProgramName').text(program);
    });
});

function submitDonasiHalaman() {
    var nama = document.getElementById('donasiHalamanNama').value || 'Pemerhati Kopi';
    var program = document.getElementById('modalDonasiHalamanProgramName').textContent;
    alert('Terima kasih banyak, ' + nama + '! Partisipasi Anda dalam "' + program + '" telah berhasil dicatat. Dukungan Anda sangat berarti bagi keberlanjutan perkebunan kopi petani mitra KopiLink.');
    $('#modalDonasiHalaman').modal('hide');
    document.getElementById('donasiHalamanNama').value = '';
    document.getElementById('donasiHalamanPesan').value = '';
}
</script>
@endsection
