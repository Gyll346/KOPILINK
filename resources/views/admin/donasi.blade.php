@extends('layouts.dashboard')

@section('title', 'Manajemen Donasi & Kas Masuk/Keluar - KopiLink')
@section('page_title', 'Pusat Donasi & Arus Kas Kebaikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 10px;">
    <div>
        <h4 class="font-weight-bold mb-1">Manajemen Donasi & Rekening Penerima</h4>
        <p class="text-muted mb-0">Kelola rekening tujuan transfer donatur, catat uang masuk (donasi) dan uang keluar (penyaluran bibit/kesejahteraan petani).</p>
    </div>
    <div class="d-flex flex-wrap" style="gap: 8px;">
        <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#modalTambahRekening">
            <i class="fa fa-university mr-1 text-primary"></i> Atur Rekening Tujuan
        </button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCatatTransaksi" data-jenis="masuk">
            <i class="fa fa-arrow-down mr-1"></i> Catat Uang Masuk
        </button>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCatatTransaksi" data-jenis="keluar">
            <i class="fa fa-arrow-up mr-1"></i> Catat Uang Keluar
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card card-stat p-3 h-100 border-left-success" style="border-left: 4px solid #28a745;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Donasi Masuk</small>
                    <h3 class="m-0 font-weight-bold text-success mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="fa fa-arrow-circle-down text-success mr-1"></i> Dari donatur & pecinta kopi</small>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                    <i class="fa fa-hand-holding-usd fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card card-stat p-3 h-100 border-left-danger" style="border-left: 4px solid #dc3545;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Penyaluran Keluar</small>
                    <h3 class="m-0 font-weight-bold text-danger mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="fa fa-arrow-circle-up text-danger mr-1"></i> Bibit, pupuk, & santunan</small>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                    <i class="fa fa-donate fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3 h-100 border-left-warning" style="border-left: 4px solid #DA9F5B;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Sisa Saldo Kas Donasi</small>
                    <h3 class="m-0 font-weight-bold text-dark mt-1">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="fa fa-wallet text-warning mr-1"></i> Saldo siap disalurkan</small>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(218, 159, 91, 0.15); color: #DA9F5B;">
                    <i class="fa fa-coins fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bagian 1: Rekening Tujuan Penerima Donasi -->
<div class="card card-stat mb-4">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h6 class="font-weight-bold m-0 text-dark"><i class="fa fa-university text-primary mr-2"></i> Rekening Penerima Donasi yang Tersedia</h6>
            <small class="text-muted">Pilih rekening mana yang akan diaktifkan sebagai rekening tujuan utama donasi.</small>
        </div>
        <button type="button" class="btn btn-sm btn-koppee" data-toggle="modal" data-target="#modalTambahRekening">
            <i class="fa fa-plus-circle mr-1"></i> Tambah Rekening
        </button>
    </div>
    <div class="card-body p-0">
        @if($rekeningList->isEmpty())
            <div class="text-center py-4">
                <i class="fa fa-university fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">Belum ada rekening penerima yang didaftarkan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Bank / Provider</th>
                            <th>Nomor Rekening / E-Wallet</th>
                            <th>Atas Nama</th>
                            <th>Keterangan</th>
                            <th>Status Penerima</th>
                            <th class="text-center pr-4">Aksi Pengaturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekeningList as $rek)
                            <tr class="{{ $rek->is_active ? 'table-warning' : '' }}">
                                <td class="pl-4 font-weight-bold text-dark">
                                    <i class="fa fa-money-check text-muted mr-1"></i> {{ $rek->nama_bank }}
                                </td>
                                <td>
                                    <span class="badge badge-dark p-2 font-weight-bold text-monospace" style="font-size: 13px;">
                                        {{ $rek->nomor_rekening }}
                                    </span>
                                </td>
                                <td><strong>{{ $rek->atas_nama }}</strong></td>
                                <td><small class="text-muted">{{ $rek->keterangan ?: '-' }}</small></td>
                                <td>
                                    @if($rek->is_active)
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fa fa-check-circle mr-1"></i> Rekening Utama (Aktif)
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">Cadangan</span>
                                    @endif
                                </td>
                                <td class="text-center pr-4">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                        @if(!$rek->is_active)
                                            <form action="{{ route('admin.donasi.rekening.toggle', ['id_rekening' => $rek->id_rekening]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success font-weight-bold" title="Jadikan Rekening Utama">
                                                    <i class="fa fa-check mr-1"></i> Jadikan Utama
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.donasi.rekening.destroy', ['id_rekening' => $rek->id_rekening]) }}" method="POST" onsubmit="return confirm('Hapus rekening {{ $rek->nama_bank }} - {{ $rek->nomor_rekening }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Rekening">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Bagian 2: Riwayat Arus Kas Donasi (Uang Masuk & Keluar) -->
<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h6 class="font-weight-bold m-0 text-dark"><i class="fa fa-exchange-alt text-primary mr-2"></i> Buku Kas Donasi (Uang Masuk & Penyaluran Keluar)</h6>
            <small class="text-muted">Pencatatan akuntabel untuk menjamin integritas donasi ketertelusuran.</small>
        </div>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchKasInput" placeholder="Cari donatur, penerima, kategori..." onkeyup="filterKasTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($transaksiList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada catatan transaksi uang masuk atau keluar.</h5>
                <p class="small text-muted">Tekan tombol "Catat Uang Masuk" atau "Catat Uang Keluar" di atas untuk memulai pencatatan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="kasTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Tanggal</th>
                            <th>Arus Kas</th>
                            <th>Nominal</th>
                            <th>Pihak Terkait (Donatur / Penerima)</th>
                            <th>Kategori Program</th>
                            <th>Rekening Terkait</th>
                            <th>Keterangan & Bukti</th>
                            <th class="text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiList as $trx)
                            <tr>
                                <td class="pl-4 text-nowrap font-weight-bold">{{ $trx->tanggal->format('d M Y') }}</td>
                                <td>
                                    @if($trx->jenis === 'masuk')
                                        <span class="badge badge-success px-2 py-1 font-weight-bold">
                                            <i class="fa fa-arrow-down mr-1"></i> Masuk (Donasi)
                                        </span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1 font-weight-bold">
                                            <i class="fa fa-arrow-up mr-1"></i> Keluar (Penyaluran)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $trx->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $trx->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ $trx->nama_pihak }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark">{{ $trx->kategori ?: 'Umum' }}</span>
                                </td>
                                <td>
                                    @if($trx->rekening)
                                        <small class="d-block font-weight-bold text-dark">{{ $trx->rekening->nama_bank }}</small>
                                        <small class="text-muted">{{ $trx->rekening->nomor_rekening }}</small>
                                    @else
                                        <span class="text-muted small">Tunai / Kas Langsung</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="d-block text-muted">{{ $trx->keterangan ?: '-' }}</small>
                                    @if($trx->bukti_transfer)
                                        <a href="{{ asset('storage/' . $trx->bukti_transfer) }}" target="_blank" class="badge badge-info mt-1">
                                            <i class="fa fa-image mr-1"></i> Lihat Bukti
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center pr-4">
                                    <form action="{{ route('admin.donasi.transaksi.destroy', ['id_transaksi' => $trx->id_transaksi]) }}" method="POST" onsubmit="return confirm('Hapus catatan transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Transaksi">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Rekening (Di luar .card-stat agar bebas flicker) -->
<div class="modal fade" id="modalTambahRekening" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title font-weight-bold text-white">
                    <i class="fa fa-university text-warning mr-2"></i> Tambah Rekening Tujuan Donasi
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.donasi.rekening.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-left">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Bank / Dompet Digital <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: Bank BCA, Bank BRI, Bank Mandiri, QRIS" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nomor Rekening / ID Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_rekening" class="form-control" placeholder="Contoh: 8210982341" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Atas Nama (Pemilik Rekening) <span class="text-danger">*</span></label>
                        <input type="text" name="atas_nama" class="form-control" placeholder="Contoh: Yayasan KopiLink / Koperasi Tani Pasak Piang" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Catatan Rekening</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Rekening utama donasi bibit agroforestri">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="checkActiveRek" checked>
                        <label class="form-check-label font-weight-bold text-dark small" for="checkActiveRek">
                            Jadikan sebagai rekening penerima donasi utama saat ini
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-koppee btn-sm font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Simpan Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Catat Transaksi Kas Masuk / Keluar -->
<div class="modal fade" id="modalCatatTransaksi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white" id="modalTrxHeader">
                <h6 class="modal-title font-weight-bold text-white" id="modalTrxTitle">
                    <i class="fa fa-edit text-warning mr-2"></i> Catat Transaksi Kas Donasi
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.donasi.transaksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" id="trxJenisInput" value="masuk">
                <div class="modal-body p-4 text-left">
                    <div class="alert alert-light border small mb-3" id="trxAlertInfo">
                        Pencatatan arus dana donasi secara transparan dan akuntabel.
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold text-dark">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold text-dark">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal" class="form-control font-weight-bold" placeholder="Contoh: 100000" min="1000" step="500" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark" id="lblNamaPihak">Nama Donatur / Pengirim <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pihak" class="form-control" placeholder="Contoh: Budi Santoso / CV Kopi Jaya" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold text-dark">Kategori Program</label>
                            <select name="kategori" class="form-control">
                                <option value="Donasi Bibit Kopi">Donasi Bibit Kopi ($1 = 1 Seedling)</option>
                                <option value="Cup of Coffee">Cup of Coffee (Rp 200 / Cup)</option>
                                <option value="Bantuan Sarana Kebun">Bantuan Sarana/Pupuk Kebun</option>
                                <option value="Santunan Kesejahteraan">Santunan Kesejahteraan Petani</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold text-dark">Rekening Bank Tujuan</label>
                            <select name="id_rekening" class="form-control">
                                <option value="">-- Tanpa Rekening (Tunai) --</option>
                                @foreach($rekeningList as $r)
                                    <option value="{{ $r->id_rekening }}" {{ $r->is_active ? 'selected' : '' }}>
                                        {{ $r->nama_bank }} - {{ $r->nomor_rekening }} ({{ $r->atas_nama }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Keterangan / Berita Acara</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Tuliskan catatan detail transaksi..."></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Foto / Slip Bukti Transfer (Opsional)</label>
                        <input type="file" name="bukti_transfer" class="form-control-file border p-2 rounded bg-light" accept="image/*">
                        <small class="form-text text-muted">Format gambar JPG, PNG, WEBP. Maks 10MB (otomatis dikompresi).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-koppee btn-sm font-weight-bold" id="btnSubmitTrx">
                        <i class="fa fa-save mr-1"></i> Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterKasTable() {
    const input = document.getElementById("searchKasInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("kasTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    $('#modalCatatTransaksi').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const jenis = button.data('jenis') || 'masuk';
        const inputJenis = document.getElementById('trxJenisInput');
        const modalTitle = document.getElementById('modalTrxTitle');
        const lblNamaPihak = document.getElementById('lblNamaPihak');
        const alertInfo = document.getElementById('trxAlertInfo');
        const btnSubmit = document.getElementById('btnSubmitTrx');

        inputJenis.value = jenis;

        if (jenis === 'masuk') {
            modalTitle.innerHTML = '<i class="fa fa-arrow-down text-success mr-2"></i> Catat Uang Masuk (Donasi Diterima)';
            lblNamaPihak.innerHTML = 'Nama Donatur / Pembayar <span class="text-danger">*</span>';
            alertInfo.innerHTML = '<i class="fa fa-info-circle text-success mr-1"></i> Mencatat donasi yang masuk ke rekening atau kas yayasan/koperasi tani.';
            btnSubmit.className = 'btn btn-success btn-sm font-weight-bold';
            btnSubmit.innerHTML = '<i class="fa fa-check mr-1"></i> Simpan Donasi Masuk';
        } else {
            modalTitle.innerHTML = '<i class="fa fa-arrow-up text-danger mr-2"></i> Catat Uang Keluar (Penyaluran Donasi)';
            lblNamaPihak.innerHTML = 'Nama Penerima / Kelompok Tani / Vendor <span class="text-danger">*</span>';
            alertInfo.innerHTML = '<i class="fa fa-info-circle text-danger mr-1"></i> Mencatat dana donasi yang disalurkan untuk bibit, pupuk, atau santunan petani.';
            btnSubmit.className = 'btn btn-danger btn-sm font-weight-bold';
            btnSubmit.innerHTML = '<i class="fa fa-check mr-1"></i> Simpan Penyaluran Keluar';
        }
    });
});
</script>
@endsection
