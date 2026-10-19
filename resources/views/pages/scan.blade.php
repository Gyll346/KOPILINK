@extends('layouts.app')

@section('title', 'Pemindai QR Kemasan Kopi - KopiLink')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Traceability Scanner</h4>
            <h1 class="display-4">Pindai QR Code Kemasan Kopi</h1>
            <p class="text-muted">Arahkan kamera ke stiker kemasan atau unggah gambar QR dari galeri ponsel Anda untuk membuka jejak kebun asal.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <!-- Nav Tabs Dual-Mode -->
                <ul class="nav nav-pills nav-justified mb-4" id="scanTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-3 text-uppercase" id="cam-tab" data-toggle="pill" href="#camera-mode" role="tab" style="border-radius: 4px;">
                            <i class="fa fa-camera mr-2"></i> Kamera Langsung
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-3 text-uppercase" id="file-tab" data-toggle="pill" href="#file-mode" role="tab" style="border-radius: 4px;">
                            <i class="fa fa-file-image mr-2"></i> Unggah Berkas Gambar
                        </a>
                    </li>
                </ul>

                <div class="card border-0 shadow-lg text-center" style="background-color: #33211D; border-radius: 8px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="tab-content" id="scanTabContent">
                            <!-- Tab 1: Live Camera Scanner -->
                            <div class="tab-pane fade show active" id="camera-mode" role="tabpanel">
                                <div id="reader" style="width: 100%; min-height: 280px; background: #1c110f; border-radius: 8px; overflow: hidden;" class="mb-3"></div>

                                <div class="d-flex justify-content-center mt-3">
                                    <button id="startCamBtn" class="btn btn-primary px-4 py-2 font-weight-bold text-secondary mr-2" style="color: #33211D !important;">
                                        <i class="fa fa-video mr-1"></i> Mulai Kamera
                                    </button>
                                    <button id="stopCamBtn" class="btn btn-outline-light px-4 py-2 font-weight-bold d-none">
                                        <i class="fa fa-stop mr-1"></i> Matikan Kamera
                                    </button>
                                </div>
                                <small class="text-white-50 d-block mt-2">Pastikan memberi izin akses kamera saat diminta oleh peramban.</small>
                            </div>

                            <!-- Tab 2: Upload Image File Scanner -->
                            <div class="tab-pane fade" id="file-mode" role="tabpanel">
                                <div class="p-4 rounded border border-secondary mb-3" style="background: #1c110f;">
                                    <i class="fa fa-qrcode fa-4x text-primary mb-3"></i>
                                    <h5 class="text-white mb-2">Pilih Foto atau Screenshot QR Code</h5>
                                    <p class="text-white-50 small mb-3">Pilih berkas dari galeri perangkat Anda (JPG, PNG, WebP).</p>
                                    <input type="file" id="qrFileInput" accept="image/*" class="d-none">
                                    <button type="button" class="btn btn-primary px-4 py-2 font-weight-bold text-secondary" style="color: #33211D !important;" onclick="document.getElementById('qrFileInput').click();">
                                        <i class="fa fa-folder-open mr-1"></i> Pilih Berkas Gambar
                                    </button>
                                </div>
                                <div id="fileScanStatus" class="small text-white font-weight-bold"></div>
                            </div>
                        </div>

                        <!-- Status Result Alert -->
                        <div id="scanFeedback" class="alert d-none mt-4 text-left" role="alert"></div>

                        <!-- Manual Code Quick Test Box -->
                        <div class="mt-4 pt-3 border-top border-dark text-left">
                            <label class="text-white small font-weight-bold mb-2">
                                <i class="fa fa-keyboard text-primary mr-1"></i> Atau Masukkan Kode Kemasan Secara Manual:
                            </label>
                            <form id="manualForm" onsubmit="event.preventDefault(); goToTrace();" class="d-flex">
                                <input type="text" id="manualCode" class="form-control border-0 mr-2" placeholder="Contoh: KPL-ROB-2026-BBF01" value="KPL-ROB-2026-BBF01">
                                <button type="submit" class="btn btn-primary font-weight-bold text-secondary px-3" style="color: #33211D !important;">
                                    Buka
                                </button>
                            </form>
                            <small class="text-white-50 mt-1 d-block">
                                Sampel aktif: 
                                <a href="javascript:void(0)" onclick="setSample('KPL-ROB-2026-BBF01')" class="text-primary font-weight-bold">KPL-ROB-2026-BBF01</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- HTML5-QRCode Library CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let cameraActive = false;

    function handleScanSuccess(decodedText, decodedResult) {
        const feedback = document.getElementById('scanFeedback');
        feedback.className = "alert alert-success d-block";
        feedback.innerHTML = `<i class="fa fa-check-circle mr-2"></i> QR Code Terdeteksi: <strong>${decodedText}</strong>. Mengalihkan ke halaman jejak kopi...`;

        if (cameraActive && html5QrCode) {
            html5QrCode.stop().then(() => {
                cameraActive = false;
            }).catch(err => console.error(err));
        }

        setTimeout(() => {
            // Jika hasil scan berupa URL lengkap
            if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                window.location.href = decodedText;
            } else {
                // Jika hasil scan berupa slug saja
                window.location.href = "{{ url('/trace') }}/" + encodeURIComponent(decodedText);
            }
        }, 1000);
    }

    function handleScanFailure(error) {
        // Kegagalan scanning frame individual diabaikan untuk kelancaran video
    }

    // Kamera Langsung
    document.getElementById('startCamBtn').addEventListener('click', function () {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            handleScanSuccess,
            handleScanFailure
        ).then(() => {
            cameraActive = true;
            document.getElementById('startCamBtn').classList.add('d-none');
            document.getElementById('stopCamBtn').classList.remove('d-none');
        }).catch(err => {
            const feedback = document.getElementById('scanFeedback');
            feedback.className = "alert alert-danger d-block";
            feedback.innerHTML = `<i class="fa fa-exclamation-triangle mr-2"></i> Gagal membuka kamera: ${err}. Pastikan peramban memiliki izin kamera.`;
        });
    });

    document.getElementById('stopCamBtn').addEventListener('click', function () {
        if (html5QrCode && cameraActive) {
            html5QrCode.stop().then(() => {
                cameraActive = false;
                document.getElementById('startCamBtn').classList.remove('d-none');
                document.getElementById('stopCamBtn').classList.add('d-none');
            });
        }
    });

    // Scanner Berkas Gambar
    document.getElementById('qrFileInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const status = document.getElementById('fileScanStatus');
        status.textContent = "Sedang memindai gambar QR...";

        const fileScanner = new Html5Qrcode("fileScanStatus");
        fileScanner.scanFile(file, true)
            .then(decodedText => {
                status.textContent = "✓ QR Code berhasil dipindai!";
                handleScanSuccess(decodedText, null);
            })
            .catch(err => {
                status.textContent = "QR Code tidak ditemukan dalam gambar. Coba gambar lain.";
            });
    });

    function setSample(slug) {
        document.getElementById('manualCode').value = slug;
    }

    function goToTrace() {
        const code = document.getElementById('manualCode').value.trim();
        if (code) {
            window.location.href = "{{ url('/trace') }}/" + encodeURIComponent(code);
        }
    }
</script>
@endpush
