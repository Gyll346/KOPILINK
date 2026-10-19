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
    <div class="card-body p-0">
        @if($kemasanList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-qrcode fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada kemasan kopi yang diterbitkan.</h5>
                <a href="{{ route('coffeeshop.produksi.diterima') }}" class="btn btn-koppee mt-2">Generate Label dari Produksi Diterima</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Pratinjau QR</th>
                            <th>Kode Identifikasi (Slug)</th>
                            <th>Varietas Kopi</th>
                            <th>Petani Mitra</th>
                            <th>Kelompok Tani</th>
                            <th>Batch Panen</th>
                            <th>Diterbitkan Pada</th>
                            <th>Aksi Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kemasanList as $kem)
                            @php
                                $publicUrl = url('/trace/' . $kem->qr_code_url);
                            @endphp
                            <tr>
                                <td class="pl-4">
                                    <div class="p-1 bg-white border rounded d-inline-block text-center" style="width: 68px; height: 68px;">
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(58)->margin(0)->generate($publicUrl) !!}
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
                                <td>{{ $kem->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ $publicUrl }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Buka Halaman Jejak Kopi">
                                            <i class="fa fa-external-link-alt mr-1"></i> Jejak Kopi
                                        </a>
                                        <a href="{{ route('coffeeshop.kemasan.print', ['id_kemasan' => $kem->id_kemasan]) }}" target="_blank" class="btn btn-sm btn-koppee" title="Cetak Stiker Label Kemasan 5x5 cm">
                                            <i class="fa fa-print mr-1"></i> Cetak Label
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
@endsection
