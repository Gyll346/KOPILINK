@extends('layouts.dashboard')

@section('title', 'Panen Ceri Kopi - KopiLink')
@section('page_title', 'Daftar Hasil Panen Ceri Kopi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-weight-bold mb-1">Catatan Panen Buah Kopi (Ceri)</h4>
        <p class="text-muted mb-0">Riwayat pencatatan panen buah kopi ceri basah/merah dari petak kebun petani.</p>
    </div>
    <a href="{{ route('petani.ceri.create') }}" class="btn btn-koppee">
        <i class="fa fa-plus-circle mr-1"></i> Catat Panen Ceri Baru
    </a>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0"><i class="fa fa-apple-alt text-danger mr-2"></i> Data Panen Ceri Basah</h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchCeriInput" placeholder="Cari batch, plot, catatan..." onkeyup="filterCeriTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($ceriList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-apple-alt fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                <h5 class="text-muted">Belum ada catatan panen ceri kopi.</h5>
                <a href="{{ route('petani.ceri.create') }}" class="btn btn-koppee mt-2">+ Input Panen Ceri Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="ceriTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">ID Batch</th>
                            <th>Foto Ceri</th>
                            <th>Lahan Sumber</th>
                            <th>Varietas</th>
                            <th>Jumlah Ceri Basah</th>
                            <th>Tanggal Panen</th>
                            <th>Catatan Sortasi</th>
                            <th class="text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ceriList as $ceri)
                            <tr>
                                <td class="pl-4 font-weight-bold">#CRI-{{ $ceri->id_panen_ceri }}</td>
                                <td>
                                    @if($ceri->foto_pertama)
                                        <div class="position-relative d-inline-block">
                                            <a href="{{ asset('storage/' . $ceri->foto_pertama) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $ceri->foto_pertama) }}" alt="Foto Ceri Kopi" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            </a>
                                            @if(count($ceri->foto_list) > 1)
                                                <span class="badge badge-dark position-absolute" style="top: -5px; right: -5px; font-size: 10px; border-radius: 10px; padding: 2px 5px;" title="{{ count($ceri->foto_list) }} Foto Tersedia">
                                                    +{{ count($ceri->foto_list) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                            <i class="fa fa-apple-alt"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>Plot #{{ $ceri->id_lahan }}</strong>
                                    <small class="d-block text-muted">({{ $ceri->lahan->luas_lahan }} Ha - {{ number_format($ceri->lahan->jumlah_tanaman) }} pohon)</small>
                                </td>
                                <td>
                                    <span class="badge badge-warning font-weight-bold px-2 py-1 text-dark" style="background-color: #DA9F5B;">
                                        {{ $ceri->lahan->jenis_kopi }}
                                    </span>
                                </td>
                                <td>
                                    <h5 class="m-0 font-weight-bold text-dark">{{ $ceri->jumlah_ceri }} <small class="text-muted" style="font-size: 13px;">Kg</small></h5>
                                </td>
                                <td>{{ $ceri->tanggal_panen->format('d F Y') }}</td>
                                <td>
                                    <small class="text-muted">{{ $ceri->catatan ?: '-' }}</small>
                                </td>
                                <td class="text-center pr-4">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                        <a href="{{ route('petani.ceri.edit', ['id_panen_ceri' => $ceri->id_panen_ceri]) }}" class="btn btn-sm btn-koppee">
                                            <i class="fa fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('petani.ceri.destroy', ['id_panen_ceri' => $ceri->id_panen_ceri]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan panen ceri #CRI-{{ $ceri->id_panen_ceri }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Catatan Panen Ceri">
                                                <i class="fa fa-trash-alt mr-1"></i> Hapus
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

<script>
function filterCeriTable() {
    const input = document.getElementById("searchCeriInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("ceriTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}
</script>
@endsection
