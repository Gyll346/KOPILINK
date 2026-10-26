@extends('layouts.dashboard')

@section('title', 'Dashboard Petani - KopiLink')
@section('page_title', 'Ringkasan Portal Petani')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 text-white" style="background: linear-gradient(135deg, #33211D 0%, #1c110f 100%); border-radius: 8px;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h3 class="font-weight-bold text-white mb-1">Halo, {{ $petani->nama }}! 👋</h3>
                    <p class="mb-0 text-white-50">
                        Kelompok Tani: <strong class="text-primary">{{ $petani->kelompok_tani }}</strong> &bull; 
                        Varietas Utama: <strong class="text-white">{{ $petani->kelompok_tani === 'Pasak Piang' ? 'Robusta Pasak Piang' : 'Liberika Padi Jaya' }}</strong>
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('petani.lahan.create') }}" class="btn btn-koppee mr-2 mb-2 mb-sm-0">
                        <i class="fa fa-plus-circle mr-1"></i> Input Lahan
                    </a>
                    <a href="{{ route('petani.ceri.create') }}" class="btn btn-outline-warning mr-2 mb-2 mb-sm-0">
                        <i class="fa fa-apple-alt mr-1"></i> Panen Ceri
                    </a>
                    <a href="{{ route('petani.produksi.create') }}" class="btn btn-outline-light mb-2 mb-sm-0">
                        <i class="fa fa-plus-circle mr-1"></i> Input Green Bean
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background: rgba(218, 159, 91, 0.2); color: #DA9F5B;">
                    <i class="fa fa-map-marked-alt fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Lahan</small>
                    <h4 class="m-0 font-weight-bold text-dark">{{ $lahanList->count() }} <small style="font-size: 13px;" class="text-muted">Plot ({{ number_format($totalLahanHa, 2) }} Ha)</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background: rgba(40, 167, 69, 0.2); color: #28a745;">
                    <i class="fa fa-tree fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Populasi Pohon</small>
                    <h4 class="m-0 font-weight-bold text-dark">{{ number_format($totalPohon) }} <small style="font-size: 13px;" class="text-muted">Pohon</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background: rgba(220, 53, 69, 0.2); color: #dc3545;">
                    <i class="fa fa-apple-alt fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Panen Ceri Basah</small>
                    <h4 class="m-0 font-weight-bold text-dark">{{ number_format($totalCeri, 1) }} <small style="font-size: 13px;" class="text-muted">Kg</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background: rgba(23, 162, 184, 0.2); color: #17a2b8;">
                    <i class="fa fa-weight-hanging fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Panen Green Bean</small>
                    <h4 class="m-0 font-weight-bold text-dark">{{ number_format($totalGreenBean, 1) }} <small style="font-size: 13px;" class="text-muted">Kg</small></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lahan & Produksi Terkini -->
<div class="row">
    <!-- Lahan Terdaftar -->
    <div class="col-lg-6 mb-4">
        <div class="card card-stat h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold m-0"><i class="fa fa-map-pin text-primary mr-2"></i> Lahan Kebun Terdaftar</h5>
                <a href="{{ route('petani.lahan.index') }}" class="small font-weight-bold text-primary">Lihat Semua &rarr;</a>
            </div>
            <div class="card-body p-4">
                @if($lahanList->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fa fa-map-marked-alt fa-3x mb-3 text-muted"></i>
                        <p class="mb-2">Belum ada lahan kebun yang terdaftar.</p>
                        <a href="{{ route('petani.lahan.create') }}" class="btn btn-sm btn-koppee">+ Daftarkan Lahan Pertama</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Titik Koordinat</th>
                                    <th>Luas</th>
                                    <th>Varietas</th>
                                    <th>Pohon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lahanList->take(4) as $lahan)
                                    <tr>
                                        <td>
                                            <small class="d-block font-weight-bold">{{ number_format($lahan->koordinat_y, 4) }}, {{ number_format($lahan->koordinat_x, 4) }}</small>
                                        </td>
                                        <td>{{ $lahan->luas_lahan }} Ha</td>
                                        <td>
                                            <span class="badge badge-warning text-dark font-weight-bold" style="background-color: #DA9F5B;">
                                                {{ $lahan->jenis_kopi }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($lahan->jumlah_tanaman) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Catatan Produksi Terkini -->
    <div class="col-lg-6 mb-4">
        <div class="card card-stat h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold m-0"><i class="fa fa-boxes text-primary mr-2"></i> Hasil Panen Terkini</h5>
                <a href="{{ route('petani.produksi.index') }}" class="small font-weight-bold text-primary">Lihat Semua &rarr;</a>
            </div>
            <div class="card-body p-4">
                @if($produksiList->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fa fa-seedling fa-3x mb-3 text-muted"></i>
                        <p class="mb-2">Belum ada catatan panen green bean.</p>
                        <a href="{{ route('petani.produksi.create') }}" class="btn btn-sm btn-koppee">+ Catat Panen Baru</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Panen</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Status Rantai Pasok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produksiList->take(4) as $prod)
                                    <tr>
                                        <td>{{ $prod->tanggal_input->format('d M Y') }}</td>
                                        <td class="font-weight-bold">{{ $prod->jumlah_green_bean }} Kg</td>
                                        <td>
                                            @if($prod->status === 'Tersedia')
                                                <span class="badge badge-info px-2 py-1">Tersedia</span>
                                            @else
                                                <span class="badge badge-success px-2 py-1">Diterima Roastery</span>
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
    </div>
</div>
@endsection
