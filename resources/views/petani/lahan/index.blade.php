@extends('layouts.dashboard')

@section('title', 'Lahan Kebun Saya - KopiLink')
@section('page_title', 'Daftar Lahan Kebun Petani')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-weight-bold mb-1">Plot Lahan Terdaftar</h4>
        <p class="text-muted mb-0">Pemetaan geospasial lahan kebun untuk memastikan budidaya kopi bebas deforestasi (SDG 15).</p>
    </div>
    <a href="{{ route('petani.lahan.create') }}" class="btn btn-koppee">
        <i class="fa fa-plus-circle mr-1"></i> Daftarkan Lahan Baru
    </a>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0"><i class="fa fa-list text-primary mr-2"></i> Data Plot Lahan</h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchTableInput" placeholder="Cari ID, varietas, koordinat..." onkeyup="filterLahanTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($lahanList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada lahan yang didaftarkan.</h5>
                <a href="{{ route('petani.lahan.create') }}" class="btn btn-koppee mt-2">+ Input Lahan Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="lahanTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">ID</th>
                            <th>Foto</th>
                            <th>Garis Lintang (Latitude Y)</th>
                            <th>Garis Bujur (Longitude X)</th>
                            <th>Luas Lahan</th>
                            <th>Jumlah Tanaman</th>
                            <th>Varietas Kopi (Auto)</th>
                            <th>Peta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lahanList as $lahan)
                            <tr>
                                <td class="pl-4 font-weight-bold">#{{ $lahan->id_lahan }}</td>
                                <td>
                                    @if($lahan->foto_lahan)
                                        <img src="{{ asset('storage/' . $lahan->foto_lahan) }}" alt="Foto Lahan" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                            <i class="fa fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><code>{{ $lahan->koordinat_y }}</code></td>
                                <td><code>{{ $lahan->koordinat_x }}</code></td>
                                <td><strong>{{ $lahan->luas_lahan }}</strong> Hektar</td>
                                <td>{{ number_format($lahan->jumlah_tanaman) }} Pohon</td>
                                <td>
                                    <span class="badge badge-warning font-weight-bold px-2 py-1 text-dark" style="background-color: #DA9F5B;">
                                        {{ $lahan->jenis_kopi }}
                                    </span>
                                </td>
                                <td>
                                    <a href="https://www.openstreetmap.org/?mlat={{ $lahan->koordinat_y }}&mlon={{ $lahan->koordinat_x }}#map=16/{{ $lahan->koordinat_y }}/{{ $lahan->koordinat_x }}" 
                                       target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-map-pin"></i> Lihat OSM
                                    </a>
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
function filterLahanTable() {
    const input = document.getElementById("searchTableInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("lahanTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}
</script>
@endsection
