<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
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
    public function status()
    {
        try {
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
    public function start()
    {
        try {
            $response = WhatsApp::web('main')->start();
            return response()->json([
                'success' => true,
                'status' => $response['status'] ?? 'initializing',
                'qr' => $response['qr'] ?? null,
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
