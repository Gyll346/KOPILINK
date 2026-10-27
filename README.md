# KopiLink - Sistem Ketertelusuran (Traceability) Kopi

**KopiLink** adalah platform berbasis web untuk melacak rantai pasok kopi dari hulu (Petani) ke hilir (Mitra Coffee Shop/Roastery) hingga ke konsumen akhir, serta didukung oleh tata kelola Super Admin. Platform ini memastikan transparansi asal-usul biji kopi, metode proses, fermentasi, hingga penyajian dan pengemasan melalui sistem kode unik dan QR Code.

---

## 🌟 Fitur Utama

### 1. 🚪 Portal Login Terpadu (Single Sign-On Entry)
- Seluruh aktor (**Petani**, **Mitra Coffee Shop/Roastery**, dan **Super Admin**) masuk melalui satu pintu form login yang sama (`/login`).
- Sistem secara cerdas mendeteksi kredensial dan peran pengguna, lalu mengarahkan ke dashboard yang sesuai secara otomatis.

### 2. 👨‍🌾 Aktor Petani
- **Registrasi & Verifikasi**: Pendaftaran mandiri dengan verifikasi nomor WhatsApp menggunakan kode OTP.
- **Pencatatan Panen (Traceability Batch)**: Pencatatan varietas kopi, ketinggian kebun, metode olah (Natural, Honey, Full Wash, Anaerob, dll.), tanggal petik, dan estimasi berat.
- **Penerbitan Batch & QR Code**: Menghasilkan kode batch dan QR Code yang dapat ditelusuri riwayatnya.

### 3. ☕ Aktor Mitra Coffee Shop / Roastery
- **Pengolahan & Roasting**: Mencatat penerimaan biji kopi mentah (green beans) dari petani dan memasukkan profil sangrai (roast level, roast date, flavor notes).
- **Katalog Produk & Kemasan**: Menautkan batch kopi sangrai ke produk siap seduh atau kemasan ritel.
- **Penjualan & Distribusi**: Melacak distribusi kopi ke pelanggan akhir.

### 4. 🛡️ Super Admin
- **Dashboard & Analitik**: Monitoring total pengguna, petani aktif, mitra roastery, batch panen, dan transaksi ketertelusuran.
- **Pusat Pesan Kemitraan**: Membaca, memfilter, dan mengelola pesan yang dikirimkan pengunjung dari halaman kontak publik.
- **Integrasi WhatsApp**: Konfigurasi gateway WhatsApp dan sesi pemindaian QR WhatsApp Web untuk notifikasi sistem otomatis (OTP, pembaruan status).
- **Manajemen Pengguna**: Verifikasi, aktivasi, dan kontrol akses aktor di dalam sistem.

### 5. 🔍 Pelanggan / Publik (Traceability Scanner)
- **Pelacakan Jejak Kopi (Trace Scan)**: Konsumen cukup memindai QR code atau memasukkan kode batch pada beranda untuk melihat keseluruhan perjalanan kopi:
  - Asal kebun & profil petani
  - Ketinggian tanam (mdpl) & varietas
  - Tanggal panen & metode proses pasca-panen
  - Profil sangrai roastery & tanggal roasting

---

## 🛠️ Arsitektur & Teknologi

- **Framework**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL 8.x
- **Frontend**: Blade Templating, Bootstrap / Vanilla CSS, Responsive UI
- **Containerization**: Docker & Docker Compose
- **Notifikasi**: Integrasi WhatsApp API / Baileys Gateway

---

## 🚀 Panduan Menjalankan Sistem

### Menggunakan Docker:

1. **Jalankan Container**:
   ```bash
   docker-compose up -d
   ```

2. **Migrasi & Seeder Database**:
   ```bash
   docker exec kopilink_app php artisan migrate --seed
   ```

3. **Akses Aplikasi**:
   - Web Publik: `http://localhost:8000`
   - Portal Login: `http://localhost:8000/login`

---

## 🔒 Hak Akses & Peran

| Peran | Pintu Masuk | Halaman Dashboard | Fitur Kunci |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `/login` | `/admin/dashboard` | Kontrol sistem, kelola pesan kemitraan, integrasi WhatsApp |
| **Mitra Roastery** | `/login` | `/coffeeshop/dashboard` | Penerimaan biji kopi, profil roasting, katalog produk |
| **Petani** | `/login` | `/petani/dashboard` | Pencatatan kebun, data panen, penerbitan batch kopi |
