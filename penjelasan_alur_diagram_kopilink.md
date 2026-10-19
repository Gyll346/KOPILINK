# Penjelasan Komprehensif Alur Diagram & Arsitektur Sistem KopiLink

Dokumen ini menyajikan uraian mendalam mengenai alur diagram yang digunakan dalam sistem **KopiLink** (*Traceability Kopi Berbasis SDGs*), menghubungkan antara **Use Case Diagram V.3**, **Class Diagram**, **ERD V.4**, serta ke-8 alur pada **Activity Diagram** dengan implementasi teknis pada framework **Laravel** dan **Docker**.

---

## 1. KORELASI ANTAR DIAGRAM SISTEM

```
+-----------------------------------------------------------------------------------+
|                        1. USE CASE DIAGRAM (Peran & Fitur)                        |
|        - Petani (Daftar, Login, Input Lahan, Input Produksi)                      |
|        - Coffee Shop (Login, Terima Pasokan, Generate QR)                         |
|        - Konsumen (Scan QR, Lihat Halaman Traceability)                           |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
|                     2. ACTIVITY DIAGRAM (Alur Prosedural Detail)                  |
|  Swimlane: Petani/CS/Konsumen <----> Sistem (Validasi, Logika Bisnis, Respon)     |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
|                      3. CLASS DIAGRAM (Struktur OOP Laravel)                      |
|  Model & Controller: Petani, Lahan, Produksi, CoffeeShop, KemasanKopi, Konsumen   |
+-----------------------------------------------------------------------------------+
                                          |
                                          v
+-----------------------------------------------------------------------------------+
|                       4. ERD V.4 (Relasi Tabel & Database)                        |
|  Tabel: petani (1) -> lahan (N) -> produksi (N) -> kemasan_kopi (N) <- coffee_shop|
+-----------------------------------------------------------------------------------+
```

---

## 2. PENJELASAN DETAIL SETIAP ALUR ACTIVITY DIAGRAM

### 2.1 Alur Pendaftaran Akun Petani & OTP WhatsApp
*(Merujuk pada: `Diagram Activity Daftar Petani V.2.jfif`)*

1. **Aksi Petani:**
   - Petani mengakses halaman formulir registrasi melalui perangkat seluler (`/register-petani`).
   - Petani mengisi data: `Username`, `Password`, `Nama Lengkap`, `Kontak` (Nomor WhatsApp), `Alamat`, dan memilih salah satu `Kelompok Tani` (`Pasak Piang` atau `Padi Jaya`).
2. **Logika Sistem & Laravel Backend:**
   - **Form Request Validation:** Memastikan username dan kontak bersifat unik, password minimal 8 karakter.
   - **Normalisasi Nomor Kontak:** Mengonversi nomor berawalan `08xx` menjadi format E.164 (`628xx`) sebelum disimpan ke kolom `BIGINT` di database MySQL.
   - **Penyimpanan Awal:** Sistem membuat rekaman di tabel `petani` dengan nilai atribut `is_verified = 0`.
   - **Pembuatan OTP:** Sistem mengacak 6-digit angka (`random_int(100000, 999999)`) dan menetapkan `otp_expired_at` selama 5 menit dari waktu sekarang.
   - **Dispatch WhatsApp API:** Laravel mengirim HTTP request secara asinkronus ke WhatsApp Gateway API untuk mengirimkan pesan otomatis ke nomor petani.
3. **Penyelesaian Verifikasi:**
   - Petani menerima pesan WhatsApp berisi kode OTP, lalu memasukkannya ke form input OTP.
   - Sistem mencocokkan kode OTP dan memeriksa apakah masa berlaku belum kedaluwarsa.
   - Jika cocok, sistem memperbarui nilai kolom `is_verified = 1`, menghapus token OTP, dan menampilkan pesan sukses serta mengarahkan ke form Login.

---

### 2.2 Alur Login Petani
*(Merujuk pada: `Diagram Activity Login Petani V.2.jfif`)*

1. **Aksi Petani:** Membuka halaman login dan menginput `Username` dan `Password`.
2. **Logika Sistem:**
   - Laravel melakukan verifikasi kredensial melalui fungsi hashing `Hash::check()` terhadap data di tabel `petani`.
   - **Pengecekan Kredensial:** Jika username atau password tidak cocok, sistem me-reset form dan menampilkan pesan *"Username atau Password Salah"*.
   - **Pengecekan Verifikasi Akun (`is_verified`):** 
     - Jika kredensial benar tetapi `is_verified == 0`, sistem menolak akses login dan menampilkan pesan *"Akun belum diverifikasi via WhatsApp OTP"*, serta memberikan tombol kirim ulang OTP.
     - Jika `is_verified == 1`, sesi otentikasi dibuat (`id_petani` disimpan di session/token) dan petani diarahkan ke Dashboard Petani.

---

### 2.3 Alur Menginput Data Lahan & Kompresi Gambar
*(Merujuk pada: `Diagram Activity Menginput Data Lahan Petani.jfif`)*

1. **Aksi Petani:**
   - Membuka menu *"Lahan Saya"* atau *"Input Lahan Baru"*.
   - Mengisi data: `Koordinat X` (Longitude), `Koordinat Y` (Latitude), `Luas Lahan` (satuan Hektar), dan `Jumlah Tanaman` (populasi pohon).
   - Memilih foto kebun/kondisi tanaman dari kamera ponsel.
2. **Fitur Khusus - Kompresi Gambar Klien & Server:**
   - Sebelum diunggah ke jaringan, JavaScript di sisi peramban melakukan kompresi awal pada elemen `<canvas>` agar ukuran file kamera (sering kali 5MB+) berkurang di bawah 400KB.
   - Di sisi backend, Laravel menggunakan package `Intervention Image` untuk menstandarkan ukuran (maksimum 1200x1200 px), mengonversi format ke WebP (kualitas 75%), dan menyimpannya di direktori `storage/app/public/lahan/`.
3. **Logika Otomatisasi Varietas Kopi (Business Rule):**
   - Sistem membaca data profil petani yang sedang login.
   - Jika `kelompok_tani == 'Pasak Piang'`, kolom `jenis_kopi` otomatis diisi `'Robusta'`.
   - Jika `kelompok_tani == 'Padi Jaya'`, kolom `jenis_kopi` otomatis diisi `'Liberika'`.
   - Data berhasil disimpan ke tabel `lahan` dengan foreign key `id_petani`.

---

### 2.4 Alur Menginput Data Produksi (Green Bean)
*(Merujuk pada: `Diagram Activity Menginput Data Produksi Petani V.2.jfif`)*

1. **Aksi Petani:**
   - Membuka menu *"Produksi Saya"* -> *"Input Produksi Baru"*.
   - Memilih asal lahan kebun yang terdaftar.
   - Memasukkan `Jumlah Green Bean` (dalam satuan Kilogram), `Tanggal Input / Panen`, serta unggah foto biji kopi.
2. **Logika Sistem:**
   - Validasi data input numerik (Kg > 0).
   - Kompresi gambar foto biji kopi.
   - Menyimpan data ke tabel `produksi` dengan status default **`'Tersedia'`**.
   - Menampilkan notifikasi bahwa biji kopi siap diserap oleh *Coffee Shop*.

---

### 2.5 Alur Menerima Data Produksi oleh Coffee Shop
*(Merujuk pada: `Diagram Activity Menerima Data Produksi Coffee Shop.jfif`)*

1. **Aksi Coffee Shop:**
   - Login menggunakan kredensial khusus yang diberikan oleh Admin sistem (tidak ada registrasi publik untuk buyer demi menjaga validitas mitra).
   - Membuka menu *"Daftar Pasokan Kopi"*.
2. **Logika Sistem:**
   - Menampilkan tabel pasokan biji kopi yang berstatus `'Tersedia'` dari tabel `produksi` yang di-*join* dengan tabel `lahan` dan `petani`.
   - Menampilkan identitas petani, asal kebun, varietas kopi, dan total Kg.
3. **Konfirmasi Penerimaan:**
   - Pihak *Coffee Shop* menekan tombol *"Terima Data"*.
   - Sistem memperbarui status record pada tabel `produksi` dari `'Tersedia'` menjadi **`'Diterima Coffee Shop'`**.

---

### 2.6 Alur Melakukan Generate QR Code Kemasan
*(Merujuk pada: `Diagram Activity Generate QR.jfif`)*

1. **Aksi Coffee Shop:**
   - Membuka menu *"Data Produksi Diterima"*.
   - Memilih data produksi yang telah diolah/disangrai dan menekan tombol *"Generate QR Code"*.
2. **Logika Sistem:**
   - Membuat pengenal unik (UUID / token hash singkat).
   - Menyusun URL jejak kopi: `https://kopilink.id/trace/{kode_unik}`.
   - Menggunakan package `simplesoftwareio/simple-qrcode` untuk memproduksi berkas grafis QR Code beresolusi tinggi.
   - Menyimpan rekaman baru ke tabel `kemasan_kopi` dengan atribut `id_coffee_shop`, `id_produksi`, dan `qr_code_url`.
   - Menampilkan gambar QR Code di layar dengan tombol *"Unduh File PNG"* dan *"Cetak Stiker Label"* untuk ditempelkan pada kemasan kopi fisik.

---

### 2.7 Alur Pemindaian QR Code Konsumen (Dual-Mode Scanner)
*(Merujuk pada: `Diagram Activity Scan QR Code Konsumen.jfif`)*

Konsumen tidak perlu mengunduh aplikasi atau membuat akun; mereka cukup membuka peramban web di ponsel:
1. **Pilihan 1: Kamera Langsung (Live Camera):**
   - Menggunakan pustaka JavaScript `html5-qrcode`.
   - Mengakses kamera belakang (*environment mode*), memindai stiker pada kemasan secara *real-time*, dan mengekstrak tautan URL.
2. **Pilihan 2: Unggah Berkas Gambar (Upload Image):**
   - Jika kamera memiliki kendala fokus atau konsumen memiliki foto kemasan di galeri, konsumen dapat menekan tombol *"Pilih Gambar QR"*.
   - Sistem membaca berkas gambar, mendeteksi pola QR, dan mengambil tautan URL.
3. **Validasi URL:**
   - Sistem memastikan bahwa link yang dipindai berdomain resmi KopiLink. Jika valid, sistem langsung mengarahkan peramban ke halaman publik keterlacakan.

---

### 2.8 Alur Melihat Halaman Traceability (Jejak Kopi Konsumen)
*(Merujuk pada: `Diagram Activity Melihat Halaman Treacebility Konsumen.jfif`)*

1. **Aksi Konsumen:** Mengakses URL `/trace/{kode_unik}` hasil pemindaian.
2. **Logika Sistem:**
   - Controller melakukan query relasional:
     `kemasan_kopi` -> `produksi` -> `lahan` -> `petani` dan `coffee_shop`.
   - Jika kode tidak valid atau tidak ditemukan, sistem menyajikan halaman 404 bersahabat.
   - Jika ditemukan, sistem merender halaman *Single-Origin Coffee Story* yang kaya visual:
     - **Kartu Petani:** Nama lengkap, foto terkompresi, alamat desa, dan kelompok tani.
     - **Peta Lokasi Interaktif:** Menampilkan pin koordinat kebun via Leaflet.js di atas peta OpenStreetMap.
     - **Informasi Varietas:** Menjelaskan karakteristik Robusta (Pasak Piang) atau Liberika (Padi Jaya).
     - **Jejak Pengolahan:** Kapan dipanen, jumlah batch, dan profil *Roastery/Coffee Shop* yang mengolahnya.
     - **Nilai SDGs:** Mengedukasi konsumen mengenai kontribusi pembelian kopi ini terhadap pelestarian hutan (SDG 15) dan keadilan ekonomi petani lokal (SDG 8 & 12).

---

## 3. PANDUAN IMPLEMENTASI LINGKUNGAN DOCKER LOKAL

Untuk menjalankan sistem ini secara lokal di Docker:

```bash
# 1. Pastikan Docker Desktop dalam keadaan aktif di Windows
# 2. Buka terminal di direktori proyek dan jalankan perintah:
docker compose up -d --build

# 3. Masuk ke container aplikasi Laravel untuk inisialisasi:
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link

# 4. Akses sistem melalui peramban:
# - Aplikasi Utama KopiLink : http://localhost:8000
# - phpMyAdmin Database GUI  : http://localhost:8080
```
