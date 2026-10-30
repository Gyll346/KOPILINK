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
<div class="modal fade" id="modalDonasiHalaman" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #33211D;">
                <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-heart text-danger mr-2"></i> Donasi Kopi KopiLink</h5>
                <button type="button" class="close text-white" data-dismiss="modal" onclick="resetModalDonasi()">&times;</button>
            </div>

            <!-- Step 1: Input Data Donasi -->
            <div id="stepInputDonasi">
                <form id="formDonasiQris">
                    @csrf
                    <input type="hidden" name="program" id="donasiProgramInput" value="Donasi Bibit Kopi ($1 = 1 Seedling)">
                    <div class="modal-body p-4">
                        <div class="alert alert-warning mb-3 small" style="background-color: rgba(218, 159, 91, 0.2); border: 1px solid #DA9F5B; color: #33211D;">
                            <i class="fa fa-info-circle mr-1 text-primary"></i> 
                            Program: <strong id="modalDonasiHalamanProgramName">Donasi Bibit Kopi</strong>
                        </div>

                        @if(isset($rekeningAktif) && $rekeningAktif)
                            <div class="card bg-light border-0 p-3 mb-3 text-center">
                                <small class="text-muted font-weight-bold text-uppercase">Kas Rekening Resmi Penerima Donasi:</small>
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
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold text-dark m-0">Nominal Donasi (Rp) <span class="text-danger">*</span></label>
                                <span class="badge badge-light border text-muted small"><i class="fa fa-lock mr-1 text-primary"></i>Nilai Terkunci</span>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold">Rp</span>
                                </div>
                                <input type="number" name="nominal" id="donasiHalamanNominal" class="form-control font-weight-bold bg-light" min="200" step="100" value="16000" readonly required>
                            </div>
                            <small class="text-muted d-block mt-1 font-weight-bold" id="keteranganEstimasi">
                                Nilai terkunci: $1 USD = Rp 16.000 / Seedling
                            </small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Pesan / Doa Semangat untuk Petani</label>
                            <textarea name="pesan" id="donasiHalamanPesan" class="form-control" rows="2" placeholder="Tuliskan kata motivasi untuk petani kopi Kalimantan Barat..."></textarea>
                        </div>

                        <div id="qrisErrorAlert" class="alert alert-danger py-2 small d-none"></div>

                        <!-- Tombol Utama: Bayar via QRIS -->
                        <button type="button" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm text-dark" id="btnBayarQris" style="background-color: #DA9F5B; border-color: #DA9F5B;" onclick="prosesBayarQris()">
                            <i class="fa fa-qrcode mr-2"></i> Bayar Donasi via QRIS (Batas Waktu 5 Menit)
                        </button>

                        <!-- Opsi Alternatif: Transfer Manual dengan Unggah Bukti -->
                        <div class="text-center mt-3 pt-2 border-top">
                            <a href="#collapseManualTransfer" data-toggle="collapse" class="text-muted small">
                                <i class="fa fa-receipt mr-1"></i> Ingin transfer manual & unggah slip bukti? Klik di sini
                            </a>
                        </div>

                        <div class="collapse mt-3" id="collapseManualTransfer">
                            <div class="p-3 bg-light rounded border">
                                <small class="text-muted font-weight-bold d-block mb-2">Upload Bukti Transfer Manual:</small>
                                <input type="file" id="rawBuktiDonasi" class="form-control-file border p-2 rounded bg-white" accept="image/*" onchange="compressBuktiDonasi(this)">
                                <div id="buktiCompressStatus" class="mt-2 small text-success font-weight-bold"></div>
                                <div id="buktiPreviewContainer" class="mt-2"></div>
                                <button type="button" class="btn btn-secondary btn-sm btn-block mt-3 font-weight-bold" onclick="kirimTransferManual()">
                                    <i class="fa fa-paper-plane mr-1"></i> Kirim Bukti Transfer Manual
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" onclick="resetModalDonasi()">Batal</button>
                    </div>
                </form>

                <!-- Form tersembunyi untuk submit manual transfer -->
                <form id="formManualSubmit" action="{{ route('donasi.send') }}" method="POST" enctype="multipart/form-data" class="d-none">
                    @csrf
                    <input type="hidden" name="nama_donatur" id="manualNama">
                    <input type="hidden" name="program" id="manualProgram">
                    <input type="hidden" name="nominal" id="manualNominal">
                    <input type="hidden" name="pesan" id="manualPesan">
                    <input type="file" name="bukti_transfer" id="compressedBuktiDonasi">
                </form>
            </div>

            <!-- Step 2: Tampilan QR Code QRIS (5 Menit) -->
            <div id="stepQrisDonasi" class="d-none">
                <div class="modal-body p-4 text-center">
                    <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                        <span class="badge badge-success px-2 py-1"><i class="fa fa-shield-alt mr-1"></i> QRIS Standar Nasional</span>
                        <span class="badge badge-dark px-2 py-1 font-weight-bold text-monospace" id="qrisOrderIdBadge"></span>
                    </div>

                    <h4 class="font-weight-bold text-dark mt-2 mb-1" id="qrisNominalDisplay">Rp 0</h4>
                    <p class="text-muted small mb-3" id="qrisProgramDisplay"></p>

                    <!-- QR Image Container -->
                    <div class="p-3 bg-white rounded border shadow-sm d-inline-block mx-auto position-relative" style="max-width: 270px;">
                        <img id="qrisImageSrc" src="" alt="QRIS Code" class="img-fluid" style="width: 230px; height: 230px; object-fit: contain;">
                        
                        <!-- Overlay Expired -->
                        <div id="qrisExpiredOverlay" class="position-absolute d-flex flex-column align-items-center justify-content-center d-none" style="top:0;left:0;right:0;bottom:0; background: rgba(255,255,255,0.92); border-radius: 8px;">
                            <i class="fa fa-hourglass-end text-danger fa-3x mb-2"></i>
                            <h6 class="font-weight-bold text-danger mb-1">QR Code Kedaluwarsa</h6>
                            <small class="text-muted px-2">Batas waktu 5 menit telah berakhir.</small>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="mt-3">
                        <div class="badge badge-warning text-dark px-3 py-2 font-weight-bold" style="background-color: #DA9F5B; font-size: 15px;">
                            <i class="fa fa-stopwatch mr-1"></i> Batas Waktu Bayar: <span id="countdownTimer">05:00</span>
                        </div>
                    </div>

                    <!-- Status Text -->
                    <div id="qrisStatusBox" class="mt-3 small font-weight-bold text-muted">
                        <i class="fa fa-spinner fa-spin mr-1 text-primary"></i> Menunggu pemindaian & pembayaran dari Anda...
                    </div>

                    @if(isset($rekeningAktif) && $rekeningAktif)
                        <div class="card bg-light border-0 p-2 mt-3 text-center small text-muted">
                            <i class="fa fa-university text-primary mr-1"></i> Kas disalurkan ke: <strong>{{ $rekeningAktif->nama_bank }}</strong> ({{ $rekeningAktif->nomor_rekening }}) a.n {{ $rekeningAktif->atas_nama }}
                        </div>
                    @endif

                    <div class="small text-muted mt-2">
                        Pindai via <strong>GoPay, OVO, Dana, ShopeePay, BCA, BRI, Mandiri</strong> atau aplikasi mobile banking apa pun yang berlogo QRIS.
                        @if(!config('midtrans.is_production'))
                            <div class="mt-2 p-2 bg-light rounded text-left border">
                                <span class="badge badge-info mb-1"><i class="fa fa-flask mr-1"></i> Sandbox Test Mode</span><br>
                                <span class="text-secondary" style="font-size: 11px;">
                                    Untuk menguji pembayaran di environment sandbox, Anda dapat membuka <a href="https://simulator.sandbox.midtrans.com/qris/index" target="_blank" class="text-primary font-weight-bold">QRIS Simulator Midtrans</a> dan memasukkan gambar QR di atas.
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="kembaliKeInput()">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali / Ubah Nominal
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="resetModalDonasi()">
                        Tutup
                    </button>
                </div>
            </div>

            <!-- Step 3: Tampilan Sukses Pembayaran -->
            <div id="stepSuksesDonasi" class="d-none">
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.15); color: #28a745;">
                        <i class="fa fa-check-circle fa-3x"></i>
                    </div>
                    <h4 class="font-weight-bold text-success mb-2">Pembayaran QRIS Berhasil!</h4>
                    <p class="text-dark mb-1 font-weight-bold" id="suksesNominalDisplay">Rp 0</p>
                    <p class="text-muted small mb-3">Terima kasih banyak atas donasi Anda. Dana telah tercatat otomatis ke dalam kas sistem dan disalurkan untuk petani kopi.</p>
                    <button type="button" class="btn btn-primary font-weight-bold px-4 py-2 text-dark" style="background-color: #DA9F5B; border-color: #DA9F5B;" onclick="window.location.reload()">
                        <i class="fa fa-check mr-1"></i> Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;
let countdownInterval = null;
let statusPollInterval = null;
let remainingSeconds = 300; // 5 menit

document.addEventListener('DOMContentLoaded', function () {
    $('#modalDonasiHalaman').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        if (!button.length) return;
        var program = button.data('program') || 'Donasi Bibit Kopi ($1 = 1 Seedling)';
        $('#modalDonasiHalamanProgramName').text(program);
        $('#donasiProgramInput').val(program);

        var nominalInput = document.getElementById('donasiHalamanNominal');
        var estimasi = document.getElementById('keteranganEstimasi');
        if (program.indexOf('Bibit') > -1 || program.indexOf('Seedling') > -1) {
            nominalInput.value = '16000';
            nominalInput.readOnly = true;
            estimasi.innerHTML = '<i class="fa fa-lock text-success mr-1"></i> Nilai terkunci: <strong>$1 USD = Rp 16.000</strong> (1 Bibit / Seedling)';
        } else if (program.indexOf('Cup') > -1 || program.indexOf('Cangkir') > -1) {
            nominalInput.value = '200';
            nominalInput.readOnly = true;
            estimasi.innerHTML = '<i class="fa fa-lock text-warning mr-1"></i> Nilai terkunci: <strong>Rp 200</strong> (1 Cup of Coffee)';
        } else {
            nominalInput.value = '16000';
            nominalInput.readOnly = true;
            estimasi.innerHTML = 'Dukungan operasional & keberlanjutan kebun';
        }
    });

    document.getElementById('donasiHalamanNominal').addEventListener('input', function() {
        var val = parseFloat(this.value) || 0;
        var program = $('#donasiProgramInput').val();
        var estimasi = document.getElementById('keteranganEstimasi');
        if (program.indexOf('Bibit') > -1 || program.indexOf('Seedling') > -1) {
            var bibit = Math.floor(val / 16000);
            estimasi.innerHTML = '<i class="fa fa-lock text-success mr-1"></i> Nilai terkunci: <strong>$1 USD = Rp 16.000</strong> (' + (bibit > 0 ? bibit : 1) + ' Bibit / Seedling)';
        } else if (program.indexOf('Cup') > -1 || program.indexOf('Cangkir') > -1) {
            var cup = Math.floor(val / 200);
            estimasi.innerHTML = '<i class="fa fa-lock text-warning mr-1"></i> Nilai terkunci: <strong>Rp 200</strong> (' + (cup > 0 ? cup : 1) + ' Cup of Coffee)';
        }
    });
});

function prosesBayarQris() {
    const nama = document.getElementById('donasiHalamanNama').value.trim();
    const nominal = parseInt(document.getElementById('donasiHalamanNominal').value, 10);
    const program = document.getElementById('donasiProgramInput').value;
    const pesan = document.getElementById('donasiHalamanPesan').value.trim();
    const errAlert = document.getElementById('qrisErrorAlert');
    const btn = document.getElementById('btnBayarQris');

    errAlert.classList.add('d-none');
    errAlert.innerText = '';

    if (!nama) {
        errAlert.innerText = 'Mohon isi nama Anda terlebih dahulu.';
        errAlert.classList.remove('d-none');
        document.getElementById('donasiHalamanNama').focus();
        return;
    }
    if (!nominal || nominal < 200) {
        errAlert.innerText = 'Nominal donasi minimal Rp 200.';
        errAlert.classList.remove('d-none');
        document.getElementById('donasiHalamanNominal').focus();
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Menyiapkan QRIS Midtrans...';

    fetch('{{ route("donasi.qris") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            nama_donatur: nama,
            nominal: nominal,
            program: program,
            pesan: pesan,
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-qrcode mr-2"></i> Bayar Donasi via QRIS (Batas Waktu 5 Menit)';

        if (!data.success) {
            errAlert.innerText = data.message || 'Gagal membuat QRIS. Silakan coba lagi.';
            errAlert.classList.remove('d-none');
            return;
        }

        // Tampilkan Step 2 (QRIS)
        currentOrderId = data.order_id;
        document.getElementById('qrisImageSrc').src = data.qr_url;
        document.getElementById('qrisOrderIdBadge').innerText = data.order_id;
        document.getElementById('qrisNominalDisplay').innerText = 'Rp ' + Number(data.nominal).toLocaleString('id-ID');
        document.getElementById('qrisProgramDisplay').innerText = program;

        document.getElementById('stepInputDonasi').classList.add('d-none');
        document.getElementById('stepQrisDonasi').classList.remove('d-none');
        document.getElementById('qrisExpiredOverlay').classList.add('d-none');

        startTimerAndPolling(data.order_id);
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-qrcode mr-2"></i> Bayar Donasi via QRIS (Batas Waktu 5 Menit)';
        errAlert.innerText = 'Terjadi gangguan jaringan saat menghubungi server Midtrans.';
        errAlert.classList.remove('d-none');
    });
}

function startTimerAndPolling(orderId) {
    clearInterval(countdownInterval);
    clearInterval(statusPollInterval);

    remainingSeconds = 300; // 5 Menit
    updateTimerDisplay();

    countdownInterval = setInterval(function () {
        remainingSeconds--;
        updateTimerDisplay();

        if (remainingSeconds <= 0) {
            clearInterval(countdownInterval);
            clearInterval(statusPollInterval);

            // Mark expired
            document.getElementById('qrisExpiredOverlay').classList.remove('d-none');
            document.getElementById('qrisStatusBox').innerHTML = '<span class="text-danger"><i class="fa fa-times-circle mr-1"></i> Batas waktu pembayaran habis (5 menit).</span>';

            fetch('{{ route("donasi.expire") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            });
        }
    }, 1000);

    // Poll status setiap 3 detik
    statusPollInterval = setInterval(function () {
        fetch('{{ route("donasi.status") }}?order_id=' + encodeURIComponent(orderId))
            .then(res => res.json())
            .then(data => {
                if (data.status === 'settlement') {
                    clearInterval(countdownInterval);
                    clearInterval(statusPollInterval);

                    document.getElementById('stepQrisDonasi').classList.add('d-none');
                    document.getElementById('stepSuksesDonasi').classList.remove('d-none');
                    document.getElementById('suksesNominalDisplay').innerText = document.getElementById('qrisNominalDisplay').innerText;
                } else if (data.status === 'expire' || data.status === 'cancel') {
                    clearInterval(countdownInterval);
                    clearInterval(statusPollInterval);
                    document.getElementById('qrisExpiredOverlay').classList.remove('d-none');
                }
            })
            .catch(() => {});
    }, 3000);
}

function updateTimerDisplay() {
    const mins = Math.floor(remainingSeconds / 60);
    const secs = remainingSeconds % 60;
    const formatted = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    const el = document.getElementById('countdownTimer');
    if (el) el.innerText = formatted;
}

function kembaliKeInput() {
    clearInterval(countdownInterval);
    clearInterval(statusPollInterval);
    document.getElementById('stepQrisDonasi').classList.add('d-none');
    document.getElementById('stepInputDonasi').classList.remove('d-none');
}

function resetModalDonasi() {
    clearInterval(countdownInterval);
    clearInterval(statusPollInterval);
    currentOrderId = null;
    document.getElementById('stepInputDonasi').classList.remove('d-none');
    document.getElementById('stepQrisDonasi').classList.add('d-none');
    document.getElementById('stepSuksesDonasi').classList.add('d-none');
}

function kirimTransferManual() {
    const nama = document.getElementById('donasiHalamanNama').value.trim();
    const nominal = document.getElementById('donasiHalamanNominal').value;
    const program = document.getElementById('donasiProgramInput').value;
    const pesan = document.getElementById('donasiHalamanPesan').value.trim();

    if (!nama) {
        alert('Mohon isi nama donatur.');
        document.getElementById('donasiHalamanNama').focus();
        return;
    }
    if (!nominal || nominal < 200) {
        alert('Nominal minimal Rp 200.');
        document.getElementById('donasiHalamanNominal').focus();
        return;
    }

    document.getElementById('manualNama').value = nama;
    document.getElementById('manualProgram').value = program;
    document.getElementById('manualNominal').value = nominal;
    document.getElementById('manualPesan').value = pesan;

    document.getElementById('formManualSubmit').submit();
}

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
