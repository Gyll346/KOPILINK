# KopiLink - Sistem Ketertelusuran (Traceability) & Donasi Keberlanjutan Kopi

**KopiLink** adalah platform web terpadu untuk keterlacakan rantai pasok kopi dari hulu ke hilir (**"Crop to Cup" / Kebun ke Cangkir**) berbasis nilai **Sustainable Development Goals (SDGs)** di Kalimantan Barat. Platform ini menghubungkan Kelompok Tani (Robusta Pasak Piang & Liberika Padi Jaya), Roastery/Coffee Shop mitra, dan konsumen akhir dengan transparansi asal-usul kebun, pencatatan proses panen hingga sangrai melalui QR Code unik, serta didukung oleh sistem **Program Donasi & Buku Kas Terintegrasi (Midtrans QRIS)**.

---

## 🌟 Fitur Utama Sistem

### 1. 🚪 Portal Login Terpadu (Single Sign-On Entry)
- Seluruh aktor sistem (**Petani**, **Mitra Coffee Shop / Roastery**, dan **Super Admin**) masuk melalui satu pintu form login yang sama (`/login`).
- Sistem secara otomatis mendeteksi kredensial dan peran (role) pengguna, lalu mengarahkan ke dashboard masing-masing.

### 2. 👨‍🌾 Portal Petani Kopi
- **Registrasi Mandiri & OTP WhatsApp**: Pendaftaran akun menggunakan nomor WhatsApp aktif dengan verifikasi kode OTP 6 digit.
- **Manajemen Lahan Kebun (GIS Mapping)**: Pencatatan titik koordinat kebun (Latitude/Longitude), luas lahan, varietas tanaman (Robusta / Liberika), serta foto kebun terkompresi otomatis.
- **Pencatatan Panen Ceri**: Dokumentasi pemetikan buah ceri merah matang beserta tanggal panen dan galeri foto panen.
- **Produksi Green Bean (Biji Kering)**: Pencatatan batch berat gabah/biji beras kopi (Kg), metode pengolahan pascapanen (Natural, Full Wash, Honey, Wine, Anaerob), dan status ketersediaan untuk diserap roastery.
- **Optimasi Kuota Pedesaan**: Seluruh unggahan foto dikompresi otomatis di sisi browser (HTML5 Canvas) sebelum dikirim ke server.

### 3. ☕ Portal Mitra Roastery / Coffee Shop
- **Penyerapan Pasokan Petani**: Meninjau dan memverifikasi pasokan green bean yang tersedia dari petani mitra.
- **Pencatatan Profil Sangrai (Roasting)**: Mendokumentasikan profil sangrai, tanggal roasting, dan unggahan foto biji hasil sangrai.
- **Penerbitan Kemasan Ber-QR Code Unik**: Otomatis menghasilkan kode unik sertifikat digital dan label stiker QR Code berstandar cetak untuk ditempel pada kemasan kopi ritel.
- **Kendali Visibilitas QR**: Coffee shop dapat mengaktifkan/menonaktifkan publikasi kemasan jika petani perlu mengoreksi data produksi asal.

### 4. 🔍 Pelacakan Publik (Crop to Cup Traceability)
- **Pemindai QR Fleksibel (`/scan`)**: Konsumen dapat memindai stiker QR pada kemasan kopi secara langsung menggunakan kamera smartphone/laptop atau mengunggah foto/gambar QR.
- **Halaman Jejak Kopi Interaktif (`/trace/{slug}`)**:
  - Peta interaktif Leaflet.js yang menunjukkan lokasi persis lahan kebun petani.
  - Profil petani dan kelompok tani binaan.
  - Alur 5 tahapan transparan: Data Lahan &rarr; Panen Ceri &rarr; Olah Green Bean &rarr; Sangrai Roastery &rarr; Pengemasan QR.
  - Transparansi kepatuhan SDGs: **SDG 8** (Upah Layak Petani), **SDG 12** (Konsumsi Bertanggung Jawab Tanpa Greenwashing), dan **SDG 15** (Konservasi Lahan Gambut & Agroforestri).

### 5. 💚 Program Donasi & Keberlanjutan Petani (Midtrans QRIS)
- **Program Donasi Terstandarisasi**:
  - **$1 = 1 Seedling / Bibit Kopi**: Nilai donasi terkunci pada **$1 USD (~Rp 16.000)** untuk pengadaan 1 bibit pohon kopi varietas unggul konservasi agroforestri.
  - **Rp 200 = 1 Cup of Coffee**: Nilai donasi terkunci pada **Rp 200** per cangkir kopi untuk dana abadi kesejahteraan sosial petani dan perbaikan fasilitas kebun.
- **Pembayaran QRIS Midtrans (Batas Waktu 5 Menit)**:
  - Integrasi Midtrans Core API QRIS langsung via HTTP standar.
  - Menampilkan QR Code dinamis dengan countdown timer batas waktu bayar **05:00 menit**.
  - Polling status otomatis (`pending`, `settlement`, `expire`) setiap 3 detik dan webhook notifikasi otomatis.
  - Mode simulasi otomatis untuk pengujian di lingkungan lokal/development.
  - Opsi transfer manual ke rekening resmi bagi donatur yang memilih upload slip resi/bukti transfer.

### 6. 🛡️ Pusat Kendali Super Admin
- **Dashboard Monitoring & Panen (`/admin/dashboard`)**:
  - Statistik volume panen green bean, luas kebun, jumlah petani terverifikasi, dan mitra roastery.
  - Grafik tren produksi panen bulanan dan proporsi varietas kopi (Chart.js).
  - **Card Modal & Arus Kas Donasi**: Menampilkan akumulasi Total Uang Masuk, Total Uang Keluar (Penyaluran), dan Sisa Modal/Kas Aktif, dilengkapi modal rincian mutasi transaksi terakhir.
- **Manajemen Donasi & Rekening (`/admin/donasi`)**:
  - Pengelolaan rekening bank dan e-wallet tujuan resmi (mengaktifkan rekening utama, tambah/edit rekening).
  - Kas masuk terhitung otomatis dari sistem website & QRIS yang berstatus lunas (`settlement`).
  - Pencatatan kas keluar manual khusus untuk penyaluran bantuan bibit, pupuk, atau santunan petani.
- **Manajemen Pengguna & Verifikasi (`/admin/users`)**: Verifikasi akun petani baru, reset password, dan aktivasi/nonaktifkan status akun pengguna.
- **Pusat Pesan Pengunjung (`/admin/pesan`)**: Kotak masuk pesan kemitraan yang dikirim melalui halaman kontak publik (`/contact`).
- **Integrasi WhatsApp Gateway (`/admin/whatsapp`)**: Manajemen koneksi WhatsApp Web via pairing QR Code untuk pengiriman OTP otomatis.

---

## 🛠️ Arsitektur & Tumpukan Teknologi

- **Backend Framework**: Laravel 11.x
- **Bahasa Pemrograman**: PHP 8.2+
- **Database**: MySQL 8.0 (Relational schema dengan foreign key constraints)
- **Payment Gateway**: Midtrans Core API (QRIS Standar Nasional)
- **Frontend**: Blade Templating, Bootstrap 4, FontAwesome 5, Chart.js, Leaflet.js
- **QR Code Engine**: `simplesoftwareio/simple-qrcode` & HTML5-QRCode Scanner
- **Containerization**: Docker & Docker Compose
- **Web Server**: PHP Built-in Server / Nginx Container

---

## 🔒 Pembagian Hak Akses & URL

| Peran Pengguna | Pintu Akses | Halaman Utama | Hak & Tanggung Jawab |
| :--- | :--- | :--- | :--- |
| **Publik / Konsumen** | `/` | `/`, `/scan`, `/donasi`, `/contact` | Pindai QR kemasan, cek peta kebun, kirim donasi QRIS, hubungi kemitraan |
| **Petani Kopi** | `/login` | `/petani/dashboard` | Kelola kebun, catat panen ceri & green bean, monitor status serap |
| **Mitra Roastery** | `/login` | `/coffeeshop/dashboard` | Terima pasokan, catat profil sangrai, terbitkan label QR kemasan |
| **Super Admin** | `/login` | `/admin/dashboard` | Monitoring panen, kelola buku kas donasi, atur rekening, verifikasi user |

---

## 🚀 Panduan Instalasi & Menjalankan di Lokal

### Prasyarat:
- Docker & Docker Compose sudah terpasang.
- Git.

### Langkah-langkah:

1. **Jalankan Container Docker**:
   ```bash
   docker-compose up -d
   ```

2. **Jalankan Migrasi Database**:
   ```bash
   docker exec kopilink_app php artisan migrate
   ```

3. **Akses Aplikasi**:
   - Beranda Publik: [http://localhost:8000](http://localhost:8000)
   - Pindai QR Kopi: [http://localhost:8000/scan](http://localhost:8000/scan)
   - Program Donasi: [http://localhost:8000/donasi](http://localhost:8000/donasi)
   - Portal Login: [http://localhost:8000/login](http://localhost:8000/login)
   - Database Admin (phpMyAdmin): [http://localhost:8080](http://localhost:8080)

---

## 📜 Lisensi & Kontribusi

Sistem ini dikembangkan untuk mendukung transparansi perdagangan berkeadilan (*Fair Trade*) dan keberlanjutan perkebunan kopi rakyat di Indonesia. Dilindungi di bawah lisensi open-source MIT.
