@extends('layouts.dashboard')

@section('title', 'WhatsApp Gateway Web Sidecar - KopiLink')
@section('page_title', 'WhatsApp Gateway & QR Pairing')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <div class="card-header text-white p-4" style="background: linear-gradient(135deg, #1f4229 0%, #0d2818 100%);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white font-weight-bold mb-1">
                            <i class="fab fa-whatsapp text-success mr-2"></i> WhatsApp Web Sidecar Gateway
                        </h4>
                        <p class="mb-0 text-white-50" style="font-size: 14px;">
                            Hubungkan nomor WhatsApp Admin/Roastery untuk pengiriman OTP asli kepada Petani kopi.
                        </p>
                    </div>
                    <span id="badge-status" class="badge badge-secondary px-3 py-2" style="font-size: 13px;">
                        Memeriksa Status...
                    </span>
                </div>
            </div>

            <div class="card-body p-4 text-center">
                <!-- State: Initializing / Loading -->
                <div id="section-loading" class="py-5">
                    <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Sedang memeriksa status sesi WhatsApp...</p>
                </div>

                <!-- State: QR Pairing Needed -->
                <div id="section-qr" style="display: none;" class="py-3">
                    <div class="alert alert-warning text-left d-flex align-items-center mb-4" style="border-radius: 8px;">
                        <i class="fa fa-info-circle fa-2x mr-3 text-warning"></i>
                        <div>
                            <strong>Langkah Menghubungkan WhatsApp:</strong>
                            <ol class="mb-0 pl-3 mt-1" style="font-size: 13px;">
                                <li>Buka aplikasi WhatsApp di Smartphone Anda.</li>
                                <li>Ketuk menu Titik Tiga (Android) atau Pengaturan (iPhone) &gt; <strong>Perangkat Tertaut (Linked Devices)</strong>.</li>
                                <li>Ketuk <strong>Tautkan Perangkat</strong> dan arahkan kamera HP Anda ke QR Code di bawah ini.</li>
                            </ol>
                        </div>
                    </div>

                    <div class="p-3 d-inline-block bg-white shadow-sm border rounded mb-3">
                        <img id="qr-image" src="" alt="Scan WhatsApp QR" style="max-width: 280px; width: 100%; height: auto;" />
                    </div>

                    <p class="text-muted" style="font-size: 13px;">
                        <i class="fa fa-sync fa-spin mr-1"></i> Menunggu pindaian kamera WhatsApp... Status diperbarui secara otomatis.
                    </p>
                </div>

                <!-- State: Connected / Ready -->
                <div id="section-connected" style="display: none;" class="py-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success text-white shadow" style="width: 70px; height: 70px;">
                            <i class="fa fa-check fa-3x"></i>
                        </div>
                    </div>
                    <h4 class="font-weight-bold text-success mb-2">WhatsApp Web Terhubung & Aktif!</h4>
                    <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto; font-size: 14px;">
                        Nomor WhatsApp Gateway sudah tertaut. Sistem KopiLink siap mengirimkan kode OTP secara real-time ke nomor petani saat registrasi.
                    </p>

                    <div id="device-info-card" class="bg-light p-3 rounded mb-4 text-left mx-auto" style="max-width: 420px; font-size: 14px;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Nama Akun:</span>
                            <strong id="device-pushname">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Nomor WhatsApp:</span>
                            <strong id="device-phone">-</strong>
                        </div>
                    </div>

                    <button type="button" id="btn-logout" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-unlink mr-1"></i> Putuskan Tautan (Logout WhatsApp)
                    </button>
                </div>

                <!-- State: Disconnected / Error -->
                <div id="section-disconnected" style="display: none;" class="py-4">
                    <div class="mb-3 text-secondary">
                        <i class="fa fa-qrcode fa-4x text-muted"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Sesi Belum Aktif</h5>
                    <p class="text-muted" style="font-size: 14px;">
                        Klik tombol di bawah ini untuk menginisialisasi sesi dan menampilkan QR Code pairing.
                    </p>
                    <button type="button" id="btn-start" class="btn btn-success px-4 py-2 font-weight-bold">
                        <i class="fa fa-play mr-1"></i> Mulai Inisialisasi WhatsApp
                    </button>
                </div>
            </div>

            <div class="card-footer bg-light text-muted p-3 text-center" style="font-size: 12px;">
                <i class="fa fa-shield-alt mr-1 text-primary"></i> Ditenagai oleh <strong>Laravel WhatsApp Web Sidecar</strong> (Node.js & Chromium). Sesi tersimpan aman di server lokal.
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let pollInterval = null;

    function checkStatus() {
        fetch("{{ route('coffeeshop.whatsapp.status') }}")
            .then(res => res.json())
            .then(data => {
                const statusBadge = document.getElementById('badge-status');
                const loadingSec = document.getElementById('section-loading');
                const qrSec = document.getElementById('section-qr');
                const connSec = document.getElementById('section-connected');
                const discSec = document.getElementById('section-disconnected');

                loadingSec.style.display = 'none';

                if (data.status === 'ready' || data.status === 'authenticated') {
                    statusBadge.className = 'badge badge-success px-3 py-2';
                    statusBadge.innerText = 'TERHUBUNG (READY)';
                    qrSec.style.display = 'none';
                    discSec.style.display = 'none';
                    connSec.style.display = 'block';

                    if (data.info) {
                        document.getElementById('device-pushname').innerText = data.info.pushname || 'WhatsApp Device';
                        document.getElementById('device-phone').innerText = '+' + (data.info.phone || data.info.wid?.user || '-');
                    }
                } else if (data.status === 'qr') {
                    statusBadge.className = 'badge badge-warning px-3 py-2 text-dark font-weight-bold';
                    statusBadge.innerText = 'MENUNGGU SCAN QR';
                    connSec.style.display = 'none';
                    discSec.style.display = 'none';
                    qrSec.style.display = 'block';

                    if (data.qr) {
                        document.getElementById('qr-image').src = data.qr;
                    }
                } else if (data.status === 'initializing') {
                    statusBadge.className = 'badge badge-info px-3 py-2';
                    statusBadge.innerText = 'MEMBUAT QR CODE...';
                    connSec.style.display = 'none';
                    discSec.style.display = 'none';
                    loadingSec.style.display = 'block';
                } else {
                    statusBadge.className = 'badge badge-secondary px-3 py-2';
                    statusBadge.innerText = 'BELUM TERHUBUNG';
                    connSec.style.display = 'none';
                    qrSec.style.display = 'none';
                    discSec.style.display = 'block';
                }
            })
            .catch(err => {
                console.error(err);
            });
    }

    // Event listener untuk tombol mulai & logout
    document.addEventListener('DOMContentLoaded', function() {
        checkStatus();
        pollInterval = setInterval(checkStatus, 3000);

        const btnStart = document.getElementById('btn-start');
        if (btnStart) {
            btnStart.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Menyiapkan...';
                fetch("{{ route('coffeeshop.whatsapp.start') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(() => {
                    checkStatus();
                })
                .finally(() => {
                    btnStart.disabled = false;
                    btnStart.innerHTML = '<i class="fa fa-play mr-1"></i> Mulai Inisialisasi WhatsApp';
                });
            });
        }

        const btnLogout = document.getElementById('btn-logout');
        if (btnLogout) {
            btnLogout.addEventListener('click', function() {
                if (!confirm('Apakah Anda yakin ingin memutuskan tautan WhatsApp?')) return;
                this.disabled = true;
                fetch("{{ route('coffeeshop.whatsapp.logout') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(() => {
                    checkStatus();
                })
                .finally(() => {
                    btnLogout.disabled = false;
                });
            });
        }
    });
</script>
@endpush
