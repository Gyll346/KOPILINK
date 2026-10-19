<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Label Stiker - {{ $kemasan->qr_code_url }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .controls {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-print {
            background: #DA9F5B;
            color: #111;
        }

        .btn-back {
            background: #33211D;
            color: #fff;
        }

        /* 5cm x 5cm label sticker container */
        .sticker-card {
            width: 280px;
            height: 380px;
            background: #fff;
            border: 2px solid #33211D;
            border-radius: 8px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
        }

        .sticker-header {
            width: 100%;
            border-bottom: 2px solid #DA9F5B;
            padding-bottom: 6px;
        }

        .sticker-title {
            font-size: 14px;
            font-weight: 800;
            color: #33211D;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sticker-subtitle {
            font-size: 9px;
            color: #DA9F5B;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .qr-wrapper {
            margin: 10px 0;
            padding: 6px;
            background: #fff;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }

        .sticker-variety {
            background: #33211D;
            color: #DA9F5B;
            font-weight: 700;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .sticker-info {
            font-size: 9.5px;
            color: #333;
            line-height: 1.4;
            width: 100%;
        }

        .sticker-info strong {
            color: #111;
        }

        .sticker-footer {
            width: 100%;
            border-top: 1px solid #eee;
            padding-top: 4px;
            font-size: 8px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .controls {
                display: none;
            }
            .sticker-card {
                border: 1px solid #000;
                box-shadow: none;
                margin: 0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="controls">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fa fa-print" style="margin-right: 6px;"></i> Cetak Stiker Label (Print Ready)
        </button>
        <a href="{{ route('coffeeshop.kemasan.index') }}" class="btn btn-back">
            <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Kembali
        </a>
    </div>

    <!-- Sticker Label 5x5 cm / Standard Spec -->
    <div class="sticker-card">
        <div class="sticker-header">
            <div class="sticker-title">KOPILINK TRACEABILITY</div>
            <div class="sticker-subtitle">CROP TO CUP SPECIALTY COFFEE</div>
        </div>

        <div class="qr-wrapper">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(0)->generate($traceUrl) !!}
        </div>

        <div class="sticker-variety">
            {{ $kemasan->produksi->lahan->jenis_kopi }} &bull; {{ $kemasan->produksi->lahan->petani->kelompok_tani }}
        </div>

        <div class="sticker-info">
            <div>Petani: <strong>{{ $kemasan->produksi->lahan->petani->nama }}</strong></div>
            <div>Roastery: <strong>{{ $kemasan->coffeeShop->nama_toko }}</strong></div>
            <div style="font-size: 8.5px; color: #888; margin-top: 2px;">Kode: <code>{{ $kemasan->qr_code_url }}</code></div>
        </div>

        <div class="sticker-footer">
            Pindai QR Untuk Mengetahui Jejak Kebun Asal (SDGs 8, 12, 15)
        </div>
    </div>

</body>
</html>
