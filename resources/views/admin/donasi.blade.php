@extends('layouts.dashboard')

@section('title', 'Manajemen Donasi & Kas Masuk/Keluar - KopiLink')
@section('page_title', 'Pusat Donasi & Arus Kas Kebaikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 10px;">
    <div>
        <h4 class="font-weight-bold mb-1">Manajemen Donasi & Rekening Penerima</h4>
        <p class="text-muted mb-0">Kelola rekening tujuan transfer donatur, pantau kas masuk otomatis dari sistem ($1 = Seedling & Rp 200 = One Cup of Coffee), dan catat penyaluran uang keluar untuk petani.</p>
    </div>
    <div class="d-flex flex-wrap" style="gap: 8px;">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalGatewayConfig">
            <i class="fa fa-qrcode mr-1"></i> API Duitku QRIS
            @if(!empty($duitkuConfig['is_configured']))
                <span class="badge badge-success ml-1">Aktif</span>
            @else
                <span class="badge badge-warning text-dark ml-1">Belum Disetel</span>
            @endif
        </button>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCatatKeluar">
            <i class="fa fa-arrow-up mr-1"></i> Catat Uang Keluar
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card card-stat p-3 h-100 border-left-success" style="border-left: 4px solid #28a745;">
            <div class="d-flex justify-content-between align-items-start">
                <div class="w-100 pr-2">
                    <small class="text-muted font-weight-bold text-uppercase">Total Kas Donasi Masuk</small>
                    <h3 class="m-0 font-weight-bold text-success mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
                    <div class="mt-2 pt-2 border-top" style="font-size: 11px; line-height: 1.5;">
                        <div class="d-flex justify-content-between text-muted">
                            <span><i class="fa fa-seedling text-success mr-1"></i>$1 = Seedling:</span>
                            <span class="font-weight-bold text-dark">Rp {{ number_format($totalMasukSeedling, 0, ',', '.') }} <span class="text-muted font-weight-normal">({{ number_format($countSeedling, 0, ',', '.') }} bibit)</span></span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span><i class="fa fa-coffee mr-1" style="color: #DA9F5B;"></i>Rp 200 = One Cup:</span>
                            <span class="font-weight-bold text-dark">Rp {{ number_format($totalMasukCup, 0, ',', '.') }} <span class="text-muted font-weight-normal">({{ number_format($countCup, 0, ',', '.') }} cup)</span></span>
                        </div>
                    </div>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.1); color: #28a745; flex-shrink: 0;">
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

<!-- Bagian Status Integrasi Duitku QRIS -->
<div class="card card-stat mb-4">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
        <div>
            <h6 class="font-weight-bold m-0 text-dark">
                <i class="fa fa-qrcode text-primary mr-2"></i> Integrasi Duitku API (QRIS Dinamis Otomatis)
            </h6>
            <small class="text-muted">
                Status Integrasi: 
                @if(!empty($duitkuConfig['is_configured']))
                    <span class="badge badge-success"><i class="fa fa-check-circle mr-1"></i>Aktif & Terhubung ({{ !empty($duitkuConfig['is_sandbox']) ? 'Sandbox' : 'Production' }})</span>
                @else
                    <span class="badge badge-warning text-dark"><i class="fa fa-exclamation-triangle mr-1"></i>Kredensial Belum Dikonfigurasi</span>
                @endif
            </small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" data-toggle="modal" data-target="#modalGatewayConfig">
            <i class="fa fa-cog mr-1"></i> Konfigurasi Kunci API Duitku
        </button>
    </div>
    <div class="card-body px-3 py-2 bg-light border-top">
        <div class="row small text-muted">
            <div class="col-md-6 mb-2 mb-md-0">
                <span class="d-block font-weight-bold text-dark">Merchant Code:</span>
                <code>{{ $duitkuConfig['merchant_code'] ?: 'Belum disetel' }}</code>
            </div>
            <div class="col-md-6">
                <span class="d-block font-weight-bold text-dark">API Key:</span>
                <code>{{ !empty($duitkuConfig['api_key']) ? substr($duitkuConfig['api_key'], 0, 8) . '...' . substr($duitkuConfig['api_key'], -4) : 'Belum disetel' }}</code>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Arus Kas Donasi (Uang Masuk & Keluar) -->
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
                <h5 class="text-muted">Belum ada catatan transaksi arus kas donasi.</h5>
                <p class="small text-muted">Donasi masuk tercatat otomatis dari donatur di website publik. Gunakan tombol "Catat Uang Keluar" di atas untuk mencatat penyaluran dana.</p>
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
                                            <i class="fa fa-arrow-down mr-1"></i> Masuk (Sistem)
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
                                    @if($trx->order_id)
                                        <div class="mt-1">
                                            <small class="d-block text-monospace text-muted" style="font-size: 10px;">{{ $trx->order_id }}</small>
                                            @if($trx->status === 'settlement')
                                                <span class="badge badge-success px-1" style="font-size: 10px;"><i class="fa fa-check-circle mr-1"></i>Lunas (QRIS)</span>
                                            @elseif($trx->status === 'pending')
                                                <span class="badge badge-warning text-dark px-1" style="font-size: 10px;"><i class="fa fa-clock mr-1"></i>Menunggu Bayar</span>
                                            @elseif($trx->status === 'expire')
                                                <span class="badge badge-secondary px-1" style="font-size: 10px;"><i class="fa fa-hourglass-end mr-1"></i>Kedaluwarsa</span>
                                            @else
                                                <span class="badge badge-danger px-1" style="font-size: 10px;">{{ ucfirst($trx->status) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if(str_contains(strtolower($trx->kategori ?? ''), 'seedling') || str_contains(strtolower($trx->kategori ?? ''), 'bibit'))
                                        <span class="badge badge-success px-2 py-1"><i class="fa fa-seedling mr-1"></i> $1 = Seedling</span>
                                    @elseif(str_contains(strtolower($trx->kategori ?? ''), 'cup') || str_contains(strtolower($trx->kategori ?? ''), 'cangkir'))
                                        <span class="badge badge-warning text-dark px-2 py-1" style="background-color: #DA9F5B;"><i class="fa fa-coffee mr-1"></i> Rp 200 = One Cup</span>
                                    @else
                                        <span class="badge badge-light border text-dark">{{ $trx->kategori ?: 'Umum' }}</span>
                                    @endif
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

<!-- Modal Edit Rekening Tujuan Donasi -->
<div class="modal fade" id="modalEditRekening" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title font-weight-bold text-white">
                    <i class="fa fa-edit text-warning mr-2"></i> Edit Rekening Penerima Donasi
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditRekening" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-left">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Bank / Dompet Digital <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bank" id="edit_nama_bank" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nomor Rekening / ID Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_rekening" id="edit_nomor_rekening" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Atas Nama (Pemilik Rekening) <span class="text-danger">*</span></label>
                        <input type="text" name="atas_nama" id="edit_atas_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Catatan Rekening</label>
                        <input type="text" name="keterangan" id="edit_keterangan" class="form-control">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="edit_is_active">
                        <label class="form-check-label font-weight-bold text-dark small" for="edit_is_active">
                            Jadikan sebagai rekening penerima donasi utama saat ini
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-koppee btn-sm font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Perbarui Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Catat Penyaluran Donasi (Uang Keluar) -->
<div class="modal fade" id="modalCatatKeluar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title font-weight-bold text-white">
                    <i class="fa fa-arrow-up text-danger mr-2"></i> Catat Uang Keluar (Penyaluran Donasi)
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.donasi.transaksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="keluar">
                <div class="modal-body p-4 text-left">
                    <div class="alert alert-light border small mb-3">
                        <i class="fa fa-info-circle text-danger mr-1"></i> Mencatat dana donasi yang disalurkan untuk bibit, pupuk, alat kebun, atau santunan petani.
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold text-dark">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold text-dark">Nominal Penyaluran (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal" class="form-control font-weight-bold" placeholder="Contoh: 100000" min="1000" step="500" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Penerima / Kelompok Tani / Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pihak" class="form-control" placeholder="Contoh: Kelompok Tani Pasak Piang / Toko Bibit Agro" required>
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
                            <label class="font-weight-bold text-dark">Sumber Kas / Rekening</label>
                            <select name="id_rekening" class="form-control">
                                <option value="">-- Kas Tunai Langsung --</option>
                                @foreach($rekeningList as $r)
                                    <option value="{{ $r->id_rekening }}" {{ $r->is_active ? 'selected' : '' }}>
                                        {{ $r->nama_bank }} - {{ $r->nomor_rekening }} ({{ $r->atas_nama }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Keterangan / Berita Acara Penyaluran</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Tuliskan catatan detail penyaluran dana..."></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Foto / Slip / Dokumentasi Penyaluran (Opsional)</label>
                        <input type="file" name="bukti_transfer" class="form-control-file border p-2 rounded bg-light" accept="image/*">
                        <small class="form-text text-muted">Format gambar JPG, PNG, WEBP. Maks 10MB (otomatis dikompresi).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Simpan Penyaluran Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pengaturan Duitku QRIS -->
<div class="modal fade" id="modalGatewayConfig" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-qrcode text-warning mr-2"></i> Pengaturan Duitku QRIS
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.donasi.gateway.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small mb-3">
                        <i class="fa fa-info-circle mr-1"></i> Dapatkan Merchant Code & API Key dari Dashboard Duitku: <a href="https://sandbox.duitku.com" target="_blank" class="font-weight-bold text-primary">sandbox.duitku.com</a> atau <a href="https://passport.duitku.com" target="_blank" class="font-weight-bold text-primary">passport.duitku.com</a> (Menu: <em>Proyek Saya</em>).
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small">Merchant Code Duitku <span class="text-danger">*</span></label>
                        <input type="text" name="duitku_merchant_code" class="form-control" value="{{ $duitkuConfig['merchant_code'] }}" placeholder="Contoh: DS37706 atau D1234" required>
                        <small class="text-muted">Kode unik merchant yang terdaftar di proyek Duitku Anda.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small">API Key Duitku <span class="text-danger">*</span></label>
                        <input type="text" name="duitku_api_key" class="form-control" value="{{ $duitkuConfig['api_key'] }}" placeholder="Contoh: 5222dae2a2ecaafc..." required>
                        <small class="text-muted">Kunci API proyek dari dashboard Duitku.</small>
                    </div>

                    <div class="form-group form-check mb-2">
                        <input type="checkbox" name="duitku_is_sandbox" value="1" class="form-check-input" id="checkSandbox" {{ !empty($duitkuConfig['is_sandbox']) ? 'checked' : '' }}>
                        <label class="form-check-label font-weight-bold text-dark small" for="checkSandbox">
                            Gunakan Mode Sandbox (Testing / Uji Coba)
                        </label>
                        <small class="form-text text-muted">Hapus centang ini jika akun sudah disetujui untuk transaksi Production (Live uang sungguhan).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-koppee btn-sm font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Simpan Pengaturan
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


</script>
@endsection
