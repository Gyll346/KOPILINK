@extends('layouts.dashboard')

@section('title', 'Produksi Green Bean - KopiLink')
@section('page_title', 'Daftar Hasil Panen Green Bean')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-weight-bold mb-1">Catatan Produksi Biji Kopi (Green Bean)</h4>
        <p class="text-muted mb-0">Riwayat penyerahan hasil panen kopi yang siap disalurkan ke mitra roastery / coffee shop.</p>
    </div>
    <a href="{{ route('petani.produksi.create') }}" class="btn btn-koppee">
        <i class="fa fa-plus-circle mr-1"></i> Catat Panen Baru
    </a>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0"><i class="fa fa-boxes text-primary mr-2"></i> Data Panen Green Bean</h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchTableInput" placeholder="Cari batch, varietas, status..." onkeyup="filterProduksiTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($produksiList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-boxes fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada catatan produksi panen.</h5>
                <a href="{{ route('petani.produksi.create') }}" class="btn btn-koppee mt-2">+ Input Panen Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="produksiTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">ID Batch</th>
                            <th>Foto Biji</th>
                            <th>Lahan Sumber</th>
                            <th>Varietas</th>
                            <th>Jumlah Green Bean</th>
                            <th>Tanggal Panen</th>
                            <th>Status Rantai Pasok</th>
                            <th class="text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produksiList as $prod)
                            <tr>
                                <td class="pl-4 font-weight-bold">#PRD-{{ $prod->id_produksi }}</td>
                                <td>
                                    @if($prod->foto_pertama)
                                        <div class="position-relative d-inline-block">
                                            <a href="{{ asset('storage/' . $prod->foto_pertama) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $prod->foto_pertama) }}" alt="Foto Biji Kopi" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            </a>
                                            @if(count($prod->foto_list) > 1)
                                                <span class="badge badge-dark position-absolute" style="top: -5px; right: -5px; font-size: 10px; border-radius: 10px; padding: 2px 5px;" title="{{ count($prod->foto_list) }} Foto Tersedia">
                                                    +{{ count($prod->foto_list) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                            <i class="fa fa-seedling"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>Plot #{{ $prod->id_lahan }}</strong>
                                    <small class="d-block text-muted">({{ $prod->lahan->luas_lahan }} Ha - {{ number_format($prod->lahan->jumlah_tanaman) }} pohon)</small>
                                </td>
                                <td>
                                    <span class="badge badge-warning font-weight-bold px-2 py-1 text-dark" style="background-color: #DA9F5B;">
                                        {{ $prod->lahan->jenis_kopi }}
                                    </span>
                                </td>
                                <td>
                                    <h5 class="m-0 font-weight-bold text-dark">{{ $prod->jumlah_green_bean }} <small class="text-muted" style="font-size: 13px;">Kg</small></h5>
                                </td>
                                <td>{{ $prod->tanggal_input->format('d F Y') }}</td>
                                <td>
                                    @php
                                        $hasActiveQr = $prod->kemasan->where('is_active', true)->count() > 0;
                                        $hasKemasan = $prod->kemasan->count() > 0;
                                        $canEdit = ($prod->status === 'Tersedia') || ($hasKemasan && !$hasActiveQr);
                                    @endphp

                                    @if($prod->status === 'Tersedia')
                                        <span class="badge badge-info px-3 py-2 font-weight-bold" style="font-size: 12px;">
                                            <i class="fa fa-clock mr-1"></i> Tersedia Untuk Roastery
                                        </span>
                                    @elseif($hasKemasan && !$hasActiveQr)
                                        <span class="badge badge-warning px-3 py-2 font-weight-bold text-dark" style="font-size: 12px; background-color: #ffd166;">
                                            <i class="fa fa-edit mr-1"></i> Mode Revisi (QR Dinonaktifkan)
                                        </span>
                                    @else
                                        <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 12px;">
                                            <i class="fa fa-check-circle mr-1"></i> Diterima Coffee Shop
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pr-4">
                                    @if($canEdit)
                                        <a href="{{ route('petani.produksi.edit', ['id_produksi' => $prod->id_produksi]) }}" class="btn btn-sm btn-koppee" title="Edit data produksi">
                                            <i class="fa fa-edit mr-1"></i> Edit
                                        </a>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1" title="Data sudah berlabel QR aktif di publik. Minta Coffee Shop menonaktifkan QR jika perlu revisi data.">
                                            <i class="fa fa-lock mr-1"></i> Terkunci
                                        </span>
                                    @endif
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
function filterProduksiTable() {
    const input = document.getElementById("searchTableInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("produksiTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}
</script>
@endsection
