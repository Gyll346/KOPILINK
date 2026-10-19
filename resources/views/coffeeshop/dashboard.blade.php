@extends('layouts.dashboard')

@section('title', 'Dashboard Roastery - KopiLink')
@section('page_title', 'Ringkasan Portal Coffee Shop')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 text-white" style="background: linear-gradient(135deg, #33211D 0%, #1c110f 100%); border-radius: 8px;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h3 class="font-weight-bold text-white mb-1">Selamat Datang, {{ $coffeeShop->nama_toko }}! ☕</h3>
                    <p class="mb-0 text-white-50">
                        Mitra Roastery & Coffee Shop Resmi &bull; Penerbit Sertifikat Digital Keterlacakan Kopi.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('coffeeshop.pasokan.index') }}" class="btn btn-koppee mr-2 mb-2 mb-sm-0">
                        <i class="fa fa-inbox mr-1"></i> Periksa Pasokan Petani ({{ $totalTersedia }})
                    </a>
                    <a href="{{ route('coffeeshop.kemasan.index') }}" class="btn btn-outline-light mb-2 mb-sm-0">
                        <i class="fa fa-qrcode mr-1"></i> Label Kemasan Kopi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 54px; height: 54px; background: rgba(23, 162, 184, 0.2); color: #17a2b8;">
                    <i class="fa fa-inbox fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Pasokan Biji Tersedia</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ $totalTersedia }} <small style="font-size: 14px;" class="text-muted">Batch Siap Klaim</small></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 54px; height: 54px; background: rgba(40, 167, 69, 0.2); color: #28a745;">
                    <i class="fa fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Biji Kopi Diterima</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ $totalDiterima }} <small style="font-size: 14px;" class="text-muted">Batch Diolah</small></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 54px; height: 54px; background: rgba(218, 159, 91, 0.2); color: #DA9F5B;">
                    <i class="fa fa-qrcode fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Kemasan Ber-QR Aktif</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ $totalKemasan }} <small style="font-size: 14px;" class="text-muted">Label Diterbitkan</small></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Kemasan Ber-QR -->
<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold m-0"><i class="fa fa-qrcode text-primary mr-2"></i> Kemasan Ber-QR Code Terbaru</h5>
        <a href="{{ route('coffeeshop.kemasan.index') }}" class="small font-weight-bold text-primary">Lihat Seluruh Kemasan &rarr;</a>
    </div>
    <div class="card-body p-4">
        @if($recentKemasan->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fa fa-qrcode fa-3x text-muted mb-3"></i>
                <p class="mb-2">Belum ada kemasan kopi yang diterbitkan.</p>
                <a href="{{ route('coffeeshop.pasokan.index') }}" class="btn btn-sm btn-koppee">Periksa Pasokan Petani</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Slug QR</th>
                            <th>Varietas Kopi</th>
                            <th>Petani Asal</th>
                            <th>Kelompok Tani</th>
                            <th>Tgl Penerbitan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentKemasan as $kem)
                            <tr>
                                <td>
                                    <span class="badge badge-dark p-2 font-weight-bold" style="letter-spacing: 1px;">
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
                                <td>{{ $kem->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('trace.show', ['slug' => $kem->qr_code_url]) }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1">
                                        <i class="fa fa-eye"></i> Jejak Kopi
                                    </a>
                                    <a href="{{ route('coffeeshop.kemasan.print', ['id_kemasan' => $kem->id_kemasan]) }}" target="_blank" class="btn btn-sm btn-koppee">
                                        <i class="fa fa-print"></i> Cetak Label
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
@endsection
