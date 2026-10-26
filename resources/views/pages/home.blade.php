@extends('layouts.app')

@section('title', 'KOPPEE - KopiLink Coffee Traceability System')

@section('content')
<!-- Carousel Start -->
<div class="container-fluid p-0 mb-5">
    <div id="blog-carousel" class="carousel slide overlay-bottom" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="{{ asset('img/carousel-1.jpg') }}" alt="Kopi Agroforestri">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <h2 class="text-primary font-weight-medium m-0">Dari Kebun Hingga Cangkir</h2>
                    <h1 class="display-1 text-white m-0">KOPILINK</h1>
                    <h2 class="text-white m-0">* TRACEABILITY SYSTEM *</h2>
                    <div class="mt-4">
                        <a href="{{ route('scan') }}" class="btn btn-primary px-4 py-3 font-weight-bold text-secondary mr-2" style="color: #33211D !important;">
                            <i class="fa fa-qrcode mr-2"></i> Pindai QR Kemasan
                        </a>
                        <a href="#donasi" class="btn btn-warning px-4 py-3 font-weight-bold text-dark mr-2" style="background-color: #DA9F5B;">
                            <i class="fa fa-heart mr-2 text-danger"></i> Donasi Kopi
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light px-4 py-3 font-weight-bold">
                            <i class="fa fa-user mr-2"></i> Masuk Portal
                        </a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="w-100" src="{{ asset('img/carousel-2.jpg') }}" alt="Roastery Specialty Coffee">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <h2 class="text-primary font-weight-medium m-0">Agroforestri & Perdagangan Berkeadilan</h2>
                    <h1 class="display-1 text-white m-0">SPECIALTY</h1>
                    <h2 class="text-white m-0">* ROBUSTA & LIBERIKA *</h2>
                    <div class="mt-4">
                        <a href="{{ route('scan') }}" class="btn btn-primary px-4 py-3 font-weight-bold text-secondary mr-2" style="color: #33211D !important;">
                            <i class="fa fa-search mr-2"></i> Telusuri Jejak Kopi
                        </a>
                        <a href="{{ route('register.petani') }}" class="btn btn-outline-light px-4 py-3 font-weight-bold">
                            <i class="fa fa-seedling mr-2"></i> Daftar Sebagai Petani
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#blog-carousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#blog-carousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>
</div>
<!-- Carousel End -->

<!-- Traceability Live Stats Section -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded shadow-sm" style="background-color: #33211D;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 65px; height: 65px; background-color: #DA9F5B; color: #33211D;">
                        <i class="fa fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-weight-bold m-0">{{ $totalPetani }}</h2>
                        <span class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 13px;">Petani Terverifikasi</span>
                        <small class="d-block text-white-50">Pasak Piang & Padi Jaya</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded shadow-sm" style="background-color: #33211D;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 65px; height: 65px; background-color: #DA9F5B; color: #33211D;">
                        <i class="fa fa-weight-hanging fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-weight-bold m-0">{{ number_format($totalProduksi, 1) }} <small style="font-size: 16px;">Kg</small></h2>
                        <span class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 13px;">Green Bean Tercatat</span>
                        <small class="d-block text-white-50">Transparansi Penyerahan Panen</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded shadow-sm" style="background-color: #33211D;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 65px; height: 65px; background-color: #DA9F5B; color: #33211D;">
                        <i class="fa fa-qrcode fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-weight-bold m-0">{{ $totalKemasan }}</h2>
                        <span class="text-primary text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 13px;">Kemasan Berlabel QR</span>
                        <small class="d-block text-white-50">Beredar di Pasar & Kafe Mitra</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Traceability Section -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Mengenai KopiLink</h4>
            <h1 class="display-4">Transparansi Rantai Pasok Kopi</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 py-0 py-lg-5">
                <h1 class="mb-3">Filosofi Crop to Cup</h1>
                <h5 class="mb-3">Setiap cangkir kopi yang Anda nikmati membawa cerita keadilan dan pelestarian bumi.</h5>
                <p>KopiLink menghubungkan petani kopi hulu di pedesaan Kalimantan Barat dengan pecinta kopi di kafe perkotaan melalui teknologi QR Code yang transparan dan dapat diverifikasi secara publik tanpa registrasi akun.</p>
                <a href="{{ route('about') }}" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2">Pelajari Selengkapnya</a>
            </div>
            <div class="col-lg-4 py-5 py-lg-0" style="min-height: 450px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100" src="{{ asset('img/about.png') }}" style="object-fit: cover;" alt="Kopi KopiLink">
                </div>
            </div>
            <div class="col-lg-4 py-0 py-lg-5">
                <h1 class="mb-3">Inovasi Hijau</h1>
                <p>Sistem ini dirancang selaras dengan target Tujuan Pembangunan Berkelanjutan (Sustainable Development Goals / SDGs):</p>
                <ul class="list-inline mb-4">
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i><strong>SDG 15 (Life on Land):</strong> Pemetaan titik koordinat lahan kebun di luar kawasan lindung.</li>
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i><strong>SDG 8 (Decent Work):</strong> Keadilan ekonomi dan harga layak tanpa eksploitasi tengkulak.</li>
                    <li class="py-2"><i class="fa fa-check text-primary mr-3"></i><strong>SDG 12 (Responsible Consumption):</strong> Anti-greenwashing & kepastian varietas single-origin.</li>
                </ul>
                <a href="{{ route('scan') }}" class="btn btn-primary font-weight-bold py-2 px-4" style="color: #33211D !important;">Uji Coba Scan QR Sekarang</a>
            </div>
        </div>
    </div>
</div>

<!-- Alur Kerja 4 Langkah KopiLink -->
<div class="container-fluid my-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Alur Kerja Sistem</h4>
            <h1 class="display-4">Bagaimana KopiLink Beroperasi</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        1
                    </div>
                    <h5 class="text-white font-weight-bold">Input Lahan Petani</h5>
                    <p class="text-white-50 small mb-0">Petani mendaftar dengan WhatsApp OTP, memetakan koordinat kebun, dan varietas (Robusta / Liberika) otomatis ditentukan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        2
                    </div>
                    <h5 class="text-white font-weight-bold">Pencatatan Panen</h5>
                    <p class="text-white-50 small mb-0">Hasil panen green bean dicatat dengan kompresi foto otomatis di browser untuk menghemat kuota internet perdesaan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        3
                    </div>
                    <h5 class="text-white font-weight-bold">Penerimaan Roastery</h5>
                    <p class="text-white-50 small mb-0">Coffee shop menerima pasokan yang tersedia dan menghasilkan kode unik sertifikat QR untuk setiap kemasan olahan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 rounded h-100" style="background-color: #33211D;">
                    <div class="rounded-circle bg-primary text-secondary mx-auto mb-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; color: #33211D !important; font-size: 20px;">
                        4
                    </div>
                    <h5 class="text-white font-weight-bold">Pemindaian Konsumen</h5>
                    <p class="text-white-50 small mb-0">Konsumen memindai QR stiker di bungkus kopi (via kamera atau unggah gambar) dan melihat peta interaktif kebun asal.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seksi Program Donasi KopiLink -->
<div class="container-fluid py-5" id="donasi" style="background-color: #fbf7ee;">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Aksi Solidaritas & Keberlanjutan</h4>
            <h1 class="display-4">Program Donasi KopiLink</h1>
            <p class="text-muted mt-2 mx-auto" style="max-width: 650px;">
                Dukung masa depan perkebunan kopi rakyat di Kalimantan Barat. Setiap kontribusi Anda langsung disalurkan untuk bibit unggul, apresiasi cangkir kopi, dan pendampingan budidaya tanpa biaya bagi petani.
            </p>
        </div>
        <div class="row">
            <!-- 1. Donasi Bibit Kopi -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 12px; transition: transform 0.2s;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 75px; height: 75px; background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="fa fa-seedling fa-2x"></i>
                    </div>
                    <span class="badge badge-success px-3 py-2 font-weight-bold mx-auto mb-2" style="font-size: 13px;">
                        $1 USD (~Rp 16.000) = 1 Seedling
                    </span>
                    <h4 class="font-weight-bold text-dark mb-2">Donasi Bibit Kopi</h4>
                    <p class="text-muted small mb-4">
                        Menyediakan 1 bibit tanaman kopi varietas unggul (Robusta Pasak Piang / Liberika Padi Jaya) bersertifikat untuk ditanam petani di lahan kebun agroforestri ramah lingkungan.
                    </p>
                    <div class="mt-auto">
                        <button type="button" class="btn btn-koppee btn-block font-weight-bold py-2" data-toggle="modal" data-target="#modalDonasi" data-program="Donasi Bibit Kopi ($1 = 1 Seedling)">
                            <i class="fa fa-heart mr-1 text-danger"></i> Donasi Bibit
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Cup of Coffee -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 12px; transition: transform 0.2s;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 75px; height: 75px; background: rgba(218, 159, 91, 0.2); color: #DA9F5B;">
                        <i class="fa fa-coffee fa-2x"></i>
                    </div>
                    <span class="badge badge-warning text-dark px-3 py-2 font-weight-bold mx-auto mb-2" style="background-color: #DA9F5B; font-size: 13px;">
                        Rp 200 = 1 Cup of Coffee
                    </span>
                    <h4 class="font-weight-bold text-dark mb-2">Cup of Coffee</h4>
                    <p class="text-muted small mb-4">
                        Donasi mikro Rp 200 dari setiap cangkir kopi yang dinikmati disisihkan sebagai dana abadi kesejahteraan sosial petani, dana darurat panen, dan perbaikan fasilitas sangrai mitra.
                    </p>
                    <div class="mt-auto">
                        <button type="button" class="btn btn-koppee btn-block font-weight-bold py-2" data-toggle="modal" data-target="#modalDonasi" data-program="Cup of Coffee (Rp 200 = 1 Cup of Coffee)">
                            <i class="fa fa-mug-hot mr-1"></i> Donasi Per Cangkir
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. Pendampingan Petani -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 border-warning" style="border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #fffbf2 100%);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 75px; height: 75px; background: rgba(23, 162, 184, 0.15); color: #17a2b8;">
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
    </div>
</div>

<!-- Modal Dialog Donasi KopiLink -->
<div class="modal fade" id="modalDonasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #33211D;">
                <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-heart text-danger mr-2"></i> Konfirmasi Donasi Kopi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning mb-3 small" style="background-color: rgba(218, 159, 91, 0.2); border: 1px solid #DA9F5B; color: #33211D;">
                    <i class="fa fa-info-circle mr-1 text-primary"></i> 
                    Program terpilih: <strong id="modalProgramName">-</strong>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Nama Donatur / Pemerhati Kopi</label>
                    <input type="text" id="donaturNama" class="form-control" placeholder="Contoh: Budi Santoso (atau Anonim)">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Jumlah Partisipasi</label>
                    <input type="number" id="donaturJumlah" class="form-control" placeholder="Contoh: 10 bibit / 50 cangkir" min="1" value="5">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Pesan Semangat untuk Petani</label>
                    <textarea id="donaturPesan" class="form-control" rows="2" placeholder="Tuliskan kata motivasi untuk petani kopi Kalimantan Barat..."></textarea>
                </div>
                <div class="p-3 bg-light rounded text-center small text-muted">
                    <i class="fa fa-shield-alt text-success mr-1"></i> 100% donasi dialokasikan langsung kepada kelompok tani <strong>Pasak Piang & Padi Jaya</strong>.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-koppee px-4" onclick="submitDonasiSimulasi()">
                    <i class="fa fa-check mr-1"></i> Kirim Donasi Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalDonasi').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var program = button.data('program') || 'Donasi Kopi';
        $('#modalProgramName').text(program);
    });
});

function submitDonasiSimulasi() {
    var nama = document.getElementById('donaturNama').value || 'Pemerhati Kopi';
    var program = document.getElementById('modalProgramName').textContent;
    alert('Terima kasih banyak, ' + nama + '! Partisipasi Anda dalam "' + program + '" telah berhasil dicatat. Dukungan Anda sangat berarti bagi keberlanjutan perkebunan kopi petani mitra KopiLink.');
    $('#modalDonasi').modal('hide');
    document.getElementById('donaturNama').value = '';
    document.getElementById('donaturPesan').value = '';
}
</script>

<!-- Kemasan Ber-QR Terbaru -->
@if($recentTraces->isNotEmpty())
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Kemasan Ber-QR Terbaru</h4>
            <h1 class="display-4">Jejak Kopi Terverifikasi</h1>
        </div>
        <div class="row" id="recent-traces-container">
            @foreach($recentTraces as $kem)
                <div class="col-lg-4 col-md-6 mb-4 recent-trace-item">
                    <div class="card border-0 shadow-sm p-4 text-center rounded h-100" style="background-color: #33211D;">
                        <div class="mb-3">
                            <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="background-color: #DA9F5B; font-size: 13px;">
                                {{ $kem->produksi->lahan->jenis_kopi }} &bull; {{ $kem->produksi->lahan->petani->kelompok_tani }}
                            </span>
                        </div>
                        <div class="p-2 bg-white rounded d-inline-block mx-auto mb-3">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(0)->generate(url('/trace/' . $kem->qr_code_url)) !!}
                        </div>
                        <h5 class="text-white font-weight-bold mb-1">{{ $kem->produksi->lahan->petani->nama }}</h5>
                        <p class="text-white-50 small mb-1"><i class="fa fa-map-marker-alt text-primary mr-1"></i> {{ $kem->produksi->lahan->petani->alamat }}</p>
                        <p class="text-white-50 small mb-2">Roastery: <strong class="text-white">{{ $kem->coffeeShop->nama_toko }}</strong></p>
                        <small class="text-primary font-weight-bold d-block mb-3">
                            <i class="fa fa-clock mr-1"></i> {{ $kem->created_at ? $kem->created_at->format('d M Y, H:i') : 'Baru saja' }}
                        </small>
                        <a href="{{ route('trace.show', ['slug' => $kem->qr_code_url]) }}" class="btn btn-outline-primary btn-sm btn-block mt-auto">
                            <i class="fa fa-eye mr-1"></i> Buka Kisah Single-Origin
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Clearfix -->
        <div class="clearfix mt-3" id="recent-traces-pagination-wrapper">
            <ul class="pagination float-right mb-0" id="recent-traces-pagination"></ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const items = Array.from(document.querySelectorAll('.recent-trace-item'));
    const paginationUl = document.getElementById('recent-traces-pagination');
    if (!items.length || !paginationUl) return;

    let currentPage = 1;

    function getItemsPerPage() {
        return window.innerWidth >= 768 ? 6 : 3;
    }

    function render() {
        const perPage = getItemsPerPage();
        const totalPages = Math.ceil(items.length / perPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;

        items.forEach((el, idx) => {
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            el.style.display = (idx >= start && idx < end) ? '' : 'none';
        });

        if (totalPages <= 1) {
            paginationUl.innerHTML = '';
            return;
        }

        let html = '';
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" data-page="${currentPage - 1}">Previous</a>
                 </li>`;

        for (let i = 1; i <= totalPages; i++) {
            html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>
                     </li>`;
        }

        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" data-page="${currentPage + 1}">Next</a>
                 </li>`;

        paginationUl.innerHTML = html;
    }

    paginationUl.addEventListener('click', function (e) {
        const link = e.target.closest('a[data-page]');
        if (!link || link.parentElement.classList.contains('disabled')) return;
        e.preventDefault();
        currentPage = parseInt(link.getAttribute('data-page'), 10);
        render();
    });

    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(render, 150);
    });

    render();
});
</script>
@endif
@endsection
