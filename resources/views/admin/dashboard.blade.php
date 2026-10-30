@extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard - KopiLink')
@section('page_title', 'Monitoring Perkembangan Panen & Rantai Pasok')

@section('content')
<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 text-white" style="background: linear-gradient(135deg, #1f1412 0%, #33211D 100%); border-radius: 8px;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h3 class="font-weight-bold text-white mb-1">
                        <i class="fa fa-user-shield text-warning mr-2"></i> Selamat Datang, {{ Auth::user()->name }}!
                    </h3>
                    <p class="mb-0 text-white-50">
                        Panel Kontrol Super Admin &bull; Pantau volume panen kopi petani dan kelola ekosistem pengguna.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-koppee mr-2 mb-2 mb-sm-0">
                        <i class="fa fa-users-cog mr-1"></i> Manajemen User
                    </a>
                    <a href="{{ route('scan') }}" target="_blank" class="btn btn-outline-light mb-2 mb-sm-0">
                        <i class="fa fa-camera mr-1"></i> Scan QR Kopi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-stat p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 52px; height: 52px; background: rgba(218, 159, 91, 0.2); color: #DA9F5B;">
                    <i class="fa fa-users fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Petani</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ $totalPetani }} <small style="font-size: 13px;" class="text-muted">Orang</small></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-stat p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 52px; height: 52px; background: rgba(40, 167, 69, 0.2); color: #28a745;">
                    <i class="fa fa-map-marked-alt fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Lahan Terdata</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ $totalLahan }} <small style="font-size: 13px;" class="text-muted">Kebun ({{ number_format($totalLuasLahan, 1) }} Ha)</small></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-stat p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 52px; height: 52px; background: rgba(23, 162, 184, 0.2); color: #17a2b8;">
                    <i class="fa fa-weight-hanging fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Produksi Panen</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ number_format($totalProduksiKg, 1) }} <small style="font-size: 13px;" class="text-muted">Kg Green Bean</small></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-stat p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 52px; height: 52px; background: rgba(111, 66, 193, 0.2); color: #6f42c1;">
                    <i class="fa fa-store fa-2x"></i>
                </div>
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Mitra Roastery</small>
                    <h3 class="m-0 font-weight-bold text-dark">{{ $totalCoffeeShop }} <small style="font-size: 13px;" class="text-muted">Coffee Shop</small></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Modal & Arus Kas (Uang Masuk & Keluar) -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card card-stat p-3 h-100 border-left-success" style="border-left: 4px solid #28a745;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Uang Masuk</small>
                    <h3 class="m-0 font-weight-bold text-success mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="fa fa-arrow-down text-success mr-1"></i> Donasi sistem & QRIS</small>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                    <i class="fa fa-hand-holding-usd fa-2x"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                <a href="#modalRincianKas" data-toggle="modal" class="small font-weight-bold text-success">
                    <i class="fa fa-list-alt mr-1"></i> Rincian Transaksi
                </a>
                <span class="badge badge-light border text-muted" style="font-size: 11px;">Otomatis</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card card-stat p-3 h-100 border-left-danger" style="border-left: 4px solid #dc3545;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Total Uang Keluar</small>
                    <h3 class="m-0 font-weight-bold text-danger mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="fa fa-arrow-up text-danger mr-1"></i> Penyaluran bibit & petani</small>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                    <i class="fa fa-donate fa-2x"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.donasi.index') }}" class="small font-weight-bold text-danger">
                    <i class="fa fa-plus-circle mr-1"></i> Catat Penyaluran &rarr;
                </a>
                <span class="badge badge-light border text-muted" style="font-size: 11px;">Penyaluran</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3 h-100 border-left-warning" style="border-left: 4px solid #DA9F5B;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase">Modal / Saldo Kas Tersedia</small>
                    <h3 class="m-0 font-weight-bold text-dark mt-1">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="fa fa-wallet text-warning mr-1"></i> Saldo siap disalurkan</small>
                </div>
                <div class="rounded-circle p-3" style="background: rgba(218, 159, 91, 0.15); color: #DA9F5B;">
                    <i class="fa fa-coins fa-2x"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.donasi.index') }}" class="small font-weight-bold text-dark">
                    <i class="fa fa-book mr-1 text-primary"></i> Buka Buku Kas Donasi &rarr;
                </a>
                <span class="badge badge-warning text-dark font-weight-bold" style="background-color: #DA9F5B; font-size: 11px;">Kas Aktif</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Line Chart: Tren Panen Bulanan -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 8px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-dark">
                    <i class="fa fa-chart-area mr-1 text-primary"></i> Grafik Perkembangan Panen Green Bean (Kg)
                </h6>
                <span class="badge badge-warning px-2 py-1" style="background-color: #DA9F5B; color: #111;">Produksi Bulanan</span>
            </div>
            <div class="card-body p-3">
                <div style="position: relative; height: 320px;">
                    <canvas id="monthlyHarvestChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart: Komposisi Varietas & Kelompok Tani -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 8px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 font-weight-bold text-dark">
                    <i class="fa fa-chart-pie mr-1 text-primary"></i> Komposisi Jenis Kopi
                </h6>
            </div>
            <div class="card-body p-3 d-flex flex-column justify-content-center">
                <div style="position: relative; height: 230px;">
                    <canvas id="coffeeTypeChart"></canvas>
                </div>
                <div class="mt-3 text-center small text-muted">
                    Proporsi produksi Robusta vs Liberika dari lahan petani
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Harvest Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 8px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-dark">
                    <i class="fa fa-history mr-1 text-primary"></i> Catatan Panen Petani Terbaru
                </h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-dark">
                    Kelola Petani <i class="fa fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-top-0 px-4">Tanggal Panen</th>
                                <th class="border-top-0">Petani & Kelompok</th>
                                <th class="border-top-0">Varietas Kopi</th>
                                <th class="border-top-0">Hasil Panen</th>
                                <th class="border-top-0 text-center">Status Distribusi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPanen as $p)
                                <tr>
                                    <td class="px-4 font-weight-bold text-dark">
                                        {{ \Carbon\Carbon::parse($p->tanggal_input)->format('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $p->lahan->petani->nama ?? '-' }}</div>
                                        <small class="text-muted"><i class="fa fa-map-marker-alt mr-1"></i>{{ $p->lahan->petani->kelompok_tani ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border">
                                            {{ $p->lahan->jenis_kopi ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($p->jumlah_green_bean, 1) }} Kg</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($p->status === 'Diterima Coffee Shop')
                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i>Diterima Roastery</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1" style="background-color: #DA9F5B; color: #111;">Tersedia di Petani</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data panen yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialog Rincian Arus Kas & Modal -->
<div class="modal fade" id="modalRincianKas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #33211D;">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="fa fa-wallet text-warning mr-2"></i> Rincian Arus Modal & Kas Donasi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-sm-4 text-center p-3 border-right">
                        <small class="text-muted font-weight-bold text-uppercase">Uang Masuk</small>
                        <h4 class="font-weight-bold text-success mt-1 mb-0">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-sm-4 text-center p-3 border-right">
                        <small class="text-muted font-weight-bold text-uppercase">Uang Keluar</small>
                        <h4 class="font-weight-bold text-danger mt-1 mb-0">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                    </div>
                    <div class="col-sm-4 text-center p-3">
                        <small class="text-muted font-weight-bold text-uppercase">Sisa Kas</small>
                        <h4 class="font-weight-bold text-dark mt-1 mb-0">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <h6 class="font-weight-bold text-dark mb-2">5 Transaksi Arus Kas Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Pihak / Donatur</th>
                                <th>Kategori</th>
                                <th class="text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransaksi as $rt)
                                <tr>
                                    <td>{{ $rt->tanggal->format('d/m/Y') }}</td>
                                    <td>
                                        @if($rt->jenis === 'masuk')
                                            <span class="badge badge-success px-1" style="font-size: 10px;">Masuk</span>
                                        @else
                                            <span class="badge badge-danger px-1" style="font-size: 10px;">Keluar</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $rt->nama_pihak }}</strong></td>
                                    <td><small class="text-muted">{{ $rt->kategori ?: 'Umum' }}</small></td>
                                    <td class="text-right font-weight-bold {{ $rt->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $rt->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($rt->nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Belum ada transaksi arus kas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                <a href="{{ route('admin.donasi.index') }}" class="btn btn-koppee btn-sm font-weight-bold">
                    <i class="fa fa-external-link-alt mr-1"></i> Buka Manajemen Donasi Lengkap
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Line / Bar Chart Perkembangan Panen Bulanan
        const monthlyData = @json($chartMonthly);
        const labelsMonthly = monthlyData.length ? monthlyData.map(d => d.periode) : ['Tidak Ada Data'];
        const valuesMonthly = monthlyData.length ? monthlyData.map(d => parseFloat(d.total_kg)) : [0];

        const ctxMonthly = document.getElementById('monthlyHarvestChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: labelsMonthly,
                datasets: [{
                    label: 'Hasil Panen (Kg)',
                    data: valuesMonthly,
                    borderColor: '#DA9F5B',
                    backgroundColor: 'rgba(218, 159, 91, 0.15)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#33211D',
                    pointBorderColor: '#DA9F5B',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' Kg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(val) {
                                return val + ' Kg';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Doughnut Chart Komposisi Jenis Kopi
        const jenisData = @json($chartByJenis);
        const labelsJenis = jenisData.length ? jenisData.map(d => d.jenis_kopi) : ['Robusta', 'Liberika'];
        const valuesJenis = jenisData.length ? jenisData.map(d => parseFloat(d.total_kg)) : [0, 0];

        const ctxJenis = document.getElementById('coffeeTypeChart').getContext('2d');
        new Chart(ctxJenis, {
            type: 'doughnut',
            data: {
                labels: labelsJenis,
                datasets: [{
                    data: valuesJenis,
                    backgroundColor: ['#33211D', '#DA9F5B', '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                return ' ' + context.label + ': ' + context.parsed.toLocaleString('id-ID') + ' Kg (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endpush
