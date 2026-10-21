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
                                    <form action="{{ route('coffeeshop.generate.qr', ['id_produksi' => $prod->id_produksi]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-koppee btn-sm px-3 font-weight-bold">
                                            <i class="fa fa-qrcode mr-1"></i> Generate QR Code
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
</script>
@endsection
