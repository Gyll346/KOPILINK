<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    /**
     * Kompresi dan simpan berkas gambar ke format WebP / JPEG
     */
    public function compressAndStore(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid() . '.webp';
        $fullDirectory = storage_path('app/public/' . $directory);

        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }

        $destinationPath = $fullDirectory . '/' . $filename;
        $saved = false;

        // Coba konversi dan kompresi menggunakan ekstensi GD jika aktif
        if (extension_loaded('gd')) {
            try {
                $imageInfo = @getimagesize($file->getRealPath());
                if ($imageInfo) {
                    $mime = $imageInfo['mime'];
                    $src = match ($mime) {
                        'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($file->getRealPath()),
                        'image/png' => @imagecreatefrompng($file->getRealPath()),
                        'image/webp' => @imagecreatefromwebp($file->getRealPath()),
                        default => null,
                    };

                    if ($src) {
                        $width = imagesx($src);
                        $height = imagesy($src);
                        $maxDim = 1200;

                        if ($width > $maxDim || $height > $maxDim) {
                            $ratio = min($maxDim / $width, $maxDim / $height);
                            $newWidth = (int)($width * $ratio);
                            $newHeight = (int)($height * $ratio);

                            $dst = imagecreatetruecolor($newWidth, $newHeight);
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                            imagedestroy($src);
                            $src = $dst;
                        }

                        if (function_exists('imagewebp')) {
                            imagewebp($src, $destinationPath, 75);
                            $saved = true;
                        } elseif (function_exists('imagejpeg')) {
                            $filename = Str::uuid() . '.jpg';
                            $destinationPath = $fullDirectory . '/' . $filename;
                            imagejpeg($src, $destinationPath, 80);
                            $saved = true;
                        }
                        imagedestroy($src);
                    }
                }
            } catch (\Throwable $e) {
                // Fallback ke penyimpanan default jika GD gagal
                $saved = false;
            }
        }

        if (!$saved) {
            $path = $file->storeAs('public/' . $directory, $file->hashName());
            return str_replace('public/', '', $path);
        }

        return $directory . '/' . $filename;
    }
}
