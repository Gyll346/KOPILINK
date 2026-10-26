@extends('layouts.dashboard')

@section('title', 'Daftar Pasokan Kopi - KopiLink')
@section('page_title', 'Pasokan Biji Kopi Tersedia')

@section('content')
<div class="mb-4">
    <h4 class="font-weight-bold mb-1">Pasokan Green Bean dari Kelompok Tani</h4>
    <p class="text-muted mb-0">Daftar biji kopi yang berstatus 'Tersedia'. Tekan tombol "Terima Data" untuk menyerap pasokan ke inventori pengolahan / sangrai Anda.</p>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0"><i class="fa fa-inbox text-primary mr-2"></i> Data Pasokan Biji Kopi</h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchTableInput" placeholder="Cari petani, kelompok, varietas..." onkeyup="filterPasokanTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pasokanList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada pasokan biji kopi berstatus Tersedia saat ini.</h5>
                <p class="text-muted small">Semua hasil panen yang terdaftar telah terserap oleh mitra roastery.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pasokanTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">ID Batch</th>
                            <th>Foto Biji</th>
                            <th>Nama Petani</th>
                            <th>Kelompok Tani</th>
                            <th>Varietas Kopi</th>
                            <th>Jumlah (Kg)</th>
                            <th>Tanggal Panen</th>
                            <th>Koordinat Lahan</th>
                            <th>Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pasokanList as $pasokan)
                            <tr>
                                <td class="pl-4 font-weight-bold">#PRD-{{ $pasokan->id_produksi }}</td>
                                <td>
                                    @if($pasokan->foto_pertama)
                                        <div class="position-relative d-inline-block">
                                            <a href="{{ asset('storage/' . $pasokan->foto_pertama) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $pasokan->foto_pertama) }}" alt="Foto Biji" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            </a>
                                            @if(count($pasokan->foto_list) > 1)
                                                <span class="badge badge-dark position-absolute" style="top: -5px; right: -5px; font-size: 10px; border-radius: 10px; padding: 2px 5px;" title="{{ count($pasokan->foto_list) }} Foto Tersedia">
                                                    +{{ count($pasokan->foto_list) }}
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
                                    <strong>{{ $pasokan->lahan->petani->nama }}</strong>
                                    <small class="d-block text-muted">{{ $pasokan->lahan->petani->alamat }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-secondary p-1">
                                        {{ $pasokan->lahan->petani->kelompok_tani }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="background-color: #DA9F5B;">
                                        {{ $pasokan->lahan->jenis_kopi }}
                                    </span>
                                </td>
                                <td>
                                    <h5 class="m-0 font-weight-bold text-dark">{{ $pasokan->jumlah_green_bean }} <small class="text-muted">Kg</small></h5>
                                </td>
                                <td>{{ $pasokan->tanggal_input->format('d M Y') }}</td>
                                <td>
                                    <small class="d-block font-mono">{{ number_format($pasokan->lahan->koordinat_y, 4) }}, {{ number_format($pasokan->lahan->koordinat_x, 4) }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('coffeeshop.terima.produksi', ['id_produksi' => $pasokan->id_produksi]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-koppee btn-sm px-3 font-weight-bold">
                                            <i class="fa fa-hand-holding-heart mr-1"></i> Terima Data
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
function filterPasokanTable() {
    const input = document.getElementById("searchTableInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("pasokanTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}
</script>
@endsection
