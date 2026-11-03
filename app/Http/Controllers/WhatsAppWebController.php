<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use Kstmostofa\LaravelWhatsApp\Web\SidecarManager;
use Throwable;

class WhatsAppWebController extends Controller
{
    /**
     * Tampilkan halaman status dan QR Code WhatsApp Web
     */
    public function index()
    {
        return view('admin.whatsapp_web');
    }

    /**
     * Dapatkan status dan QR code sesi sidecar (JSON API untuk polling frontend)
     */
    public function status(SidecarManager $manager)
    {
        try {
            if (!$manager->isRunning()) {
                return response()->json([
                    'success' => true,
                    'status' => 'disconnected',
                    'message' => 'Layanan WhatsApp Sidecar belum aktif.',
                ]);
            }

            $state = WhatsApp::web('main')->state();
            $status = $state['status'] ?? 'unknown';
            $qr = null;
            $info = null;

            if ($status === 'qr') {
                try {
                    $qrData = WhatsApp::web('main')->qr();
                    $qr = $qrData['qr'] ?? null;
                } catch (Throwable $e) {
                    // Ignore
                }
            } elseif ($status === 'ready' || $status === 'authenticated') {
                try {
                    $info = WhatsApp::web('main')->info();
                } catch (Throwable $e) {
                    // Ignore if info not ready
                }
            }

            return response()->json([
                'success' => true,
                'status' => $status,
                'qr' => $qr,
                'info' => $info,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 'disconnected',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Start/Inisialisasi sesi WhatsApp Web untuk mendapatkan QR code baru
     */
    public function start(SidecarManager $manager)
    {
        try {
            if (!$manager->isRunning()) {
                $manager->start();
                usleep(500_000); // 500ms
            }

            $status = null;
            try {
                $state = WhatsApp::web('main')->state();
                $status = $state['status'] ?? null;
            } catch (Throwable $e) {}

            // Jika sesi sebelumnya error atau disconnected, bersihkan sesi agar fresh
            if (in_array($status, ['error', 'disconnected'])) {
                try {
                    WhatsApp::web('main')->destroy();
                    usleep(300_000);
                } catch (Throwable $e) {}
            }

            // Jika sesi sudah dalam kondisi menunggu scan QR, langsung kembalikan QR code aktif
            if ($status === 'qr') {
                try {
                    $qrData = WhatsApp::web('main')->qr();
                    return response()->json([
                        'success' => true,
                        'status' => 'qr',
                        'qr' => $qrData['qr'] ?? null,
                    ]);
                } catch (Throwable $e) {}
            }

            $response = WhatsApp::web('main')->start();
            $qr = $response['qr'] ?? null;

            if (($response['status'] ?? '') === 'qr' && !$qr) {
                try {
                    $qrData = WhatsApp::web('main')->qr();
                    $qr = $qrData['qr'] ?? null;
                } catch (Throwable $e) {}
            }

            return response()->json([
                'success' => true,
                'status' => $response['status'] ?? 'initializing',
                'qr' => $qr,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout / Disconnect sesi WhatsApp Web
     */
    public function logout()
    {
        try {
            WhatsApp::web('main')->destroy();
            return response()->json([
                'success' => true,
                'message' => 'Sesi WhatsApp berhasil diputus.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
