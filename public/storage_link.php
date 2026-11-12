<?php
/**
 * Helper Sekali Pakai untuk Hostinger Shared Hosting (tanpa SSH)
 * Jalankan sekali di browser: https://namadomain.com/storage_link.php
 * Setelah berhasil, hapus file ini demi keamanan.
 */

$target = dirname(__DIR__) . '/storage/app/public';
$link = __DIR__ . '/storage';

header('Content-Type: text/plain; charset=utf-8');

if (file_exists($link)) {
    echo "Status: Shortcut 'public/storage' sudah ada.\n";
    if (is_link($link)) {
        echo "Tipe: Symbolic Link (Target: " . readlink($link) . ")\n";
    } else {
        echo "Tipe: Folder biasa.\n";
    }
} else {
    if (@symlink($target, $link)) {
        echo "Sukses: Symbolic link 'public/storage' berhasil dibuat ke 'storage/app/public'!\n";
    } else {
        echo "Gagal: Server hosting tidak mengizinkan fungsi symlink().\n";
        echo "Solusi alternatif: Duplikat/salin isi folder 'storage/app/public' langsung ke dalam folder 'public/storage'.\n";
    }
}

echo "\nCatatan keamanan: Silakan hapus file storage_link.php ini dari File Manager setelah selesai.";
