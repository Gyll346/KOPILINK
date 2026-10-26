@extends('layouts.dashboard')

@section('title', 'Kemasan & Label QR - KopiLink')
@section('page_title', 'Daftar Kemasan Kopi Ber-QR Code')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-weight-bold mb-1">Kemasan Kopi Berlabel QR Digital</h4>
        <p class="text-muted mb-0">Label digital unik yang menghubungkan kemasan fisik dengan transparansi asal-usul kebun kopi (Crop to Cup).</p>
    </div>
    <a href="{{ route('coffeeshop.produksi.diterima') }}" class="btn btn-koppee">
        <i class="fa fa-plus-circle mr-1"></i> Terbitkan Label QR Baru
    </a>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0"><i class="fa fa-qrcode text-primary mr-2"></i> Data Kemasan Ber-QR</h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchTableInput" placeholder="Cari kode slug, varietas, petani..." onkeyup="filterKemasanTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($kemasanList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-qrcode fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada kemasan kopi yang diterbitkan.</h5>
                <a href="{{ route('coffeeshop.produksi.diterima') }}" class="btn btn-koppee mt-2">Generate Label dari Produksi Diterima</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="kemasanTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Pratinjau QR</th>
                            <th>Kode Identifikasi (Slug)</th>
                            <th>Varietas Kopi</th>
                            <th>Petani Mitra</th>
                            <th>Kelompok Tani</th>
                            <th>Batch Panen</th>
                            <th>Status Publik</th>
                            <th>Diterbitkan Pada</th>
                            <th class="text-center pr-4">Aksi Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kemasanList as $kem)
                            @php
                                $publicUrl = url('/trace/' . $kem->qr_code_url);
                                $isActive = $kem->is_active ?? true;
                            @endphp
                            <tr class="{{ !$isActive ? 'table-light text-muted' : '' }}">
                                <td class="pl-4">
                                    <div class="p-1 bg-white border rounded d-inline-block text-center position-relative" style="width: 68px; height: 68px;">
                                        <div style="{{ !$isActive ? 'filter: grayscale(100%); opacity: 0.5;' : '' }}">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(58)->margin(0)->generate($publicUrl) !!}
                                        </div>
                                        @if(!$isActive)
                                            <span class="badge badge-danger position-absolute" style="top: 2px; right: 2px; font-size: 9px;" title="QR Nonaktif di Publik">OFF</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-dark p-2 font-weight-bold text-monospace" style="font-size: 13px;">
                                        {{ $kem->qr_code_url }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="background-color: #DA9F5B;">
                                        {{ $kem->produksi->lahan->jenis_kopi }}
                                    </span>
                                </td>
                                <td><strong>{{ $kem->produksi->lahan->petani->nama }}</strong></td>
                                <td>{{ $kem->produksi->lahan->petani->kelompok_tani }}</td>
                                <td>#PRD-{{ $kem->id_produksi }} ({{ $kem->produksi->jumlah_green_bean }} Kg)</td>
                                <td>
                                    @if($isActive)
                                        <span class="badge badge-success px-2 py-1" style="font-size: 11px;">
                                            <i class="fa fa-eye mr-1"></i> Tampil di Publik
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 11px;">
                                            <i class="fa fa-eye-slash mr-1"></i> Dinonaktifkan
                                        </span>
                                        <small class="d-block text-warning font-weight-bold mt-1" style="font-size: 10px;">
                                            <i class="fa fa-info-circle"></i> Petani dapat edit
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $kem->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center pr-4">
                                    <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 5px;">
                                        {{-- Toggle Status Button --}}
                                        <form action="{{ route('coffeeshop.kemasan.toggle', ['id_kemasan' => $kem->id_kemasan]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $isActive ? 'Nonaktifkan tampilan QR di publik? Petani akan dapat mengoreksi/mengedit data panen terkait.' : 'Aktifkan kembali tampilan QR di halaman publik?' }}');">
                                            @csrf
                                            @if($isActive)
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Nonaktifkan QR di publik agar petani bisa edit data yang salah">
                                                    <i class="fa fa-toggle-on mr-1"></i> Nonaktifkan
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Aktifkan kembali QR ke halaman publik">
                                                    <i class="fa fa-toggle-off mr-1"></i> Aktifkan
                                                </button>
                                            @endif
                                        </form>

                                        <a href="{{ $publicUrl }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Buka Halaman Jejak Kopi">
                                            <i class="fa fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('coffeeshop.kemasan.print', ['id_kemasan' => $kem->id_kemasan]) }}" target="_blank" class="btn btn-sm btn-koppee" title="Cetak Stiker Label Kemasan 5x5 cm">
                                            <i class="fa fa-print"></i>
                                        </a>
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

<script>
function filterKemasanTable() {
    const input = document.getElementById("searchTableInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("kemasanTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}
</script>
@endsection
