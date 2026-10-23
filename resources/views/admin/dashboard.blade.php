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
