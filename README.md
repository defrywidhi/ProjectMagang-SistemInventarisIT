# Sistem Inventory & Pengadaan Barang (RAB) - Rumah Sakit

Sistem informasi manajemen inventory IT dan pengadaan barang (RAB) berbasis web yang dibangun menggunakan **Laravel**. Aplikasi ini menangani siklus hidup aset mulai dari pengajuan anggaran, persetujuan berjenjang (tanda tangan digital & PIN), pengadaan barang, hingga audit stok (Stock Opname).

## 🚀 Fitur Unggulan

### 1. Pengajuan RAB (Hybrid Input)
- **Master Data:** Input barang langsung dari database gudang.
- **Custom Input:** Input barang baru (yang belum ada di gudang) lengkap dengan foto dan spesifikasi.
- **Validasi:** Status RAB (Draft, Menunggu Manager, Menunggu Direktur, Disetujui, Ditolak).

### 2. Approval System Berjenjang
- **Role:** Admin (Input), Manajer (Review), Direktur (Final).
- **Keamanan Ganda:** Persetujuan menggunakan **PIN 6 Digit** (bukan password login) dan **Tanda Tangan Digital**.
- **Notifikasi:** Status approval tercatat real-time.

### 3. Konversi Barang Otomatis (The Checkpoint)
- Sistem mendeteksi jika ada barang "Custom" di RAB yang disetujui.
- **Konversi:** Sebelum mencatat transaksi masuk, Admin *wajib* mendaftarkan barang custom tersebut ke Master Barang (memilih kategori, merk, dll).
- Foto dari RAB otomatis dicopy ke Master Barang.

### 4. Transaksi Masuk (Auto-Fill)
- Integrasi langsung dengan RAB.
- **Auto-Fill:** Admin tidak perlu mengetik ulang item, sistem mengambil data dari RAB.
- **Progress Tracking:** Bar status (persentase) untuk memantau apakah semua barang di RAB sudah dibeli/masuk gudang.

### 5. Stok Opname (Audit Cerdas)
- **Metode Full:** Cek semua aset.
- **Metode Random Sampling:** Cek acak sejumlah sampel.
- **Cooldown Logic:** Barang yang sudah di-opname dalam **1 bulan terakhir** otomatis disembunyikan dari daftar sampling berikutnya untuk efisiensi kerja.


## 🛠️ Instalasi & Setup

Ikuti langkah ini untuk menjalankan project pertama kali:

1. **Clone Repository**
git clone <url-repo-anda>
cd nama-folder

2. **Install Dependency**
composer install
npm install && npm run build


3. **Environment Setup**
Copy file `.env.example` menjadi `.env`, lalu atur koneksi database.
cp .env.example .env
php artisan key:generate


4. **Database Migration & Seeder**
php artisan migrate --seed


*(Pastikan migration tabel `users` sudah memiliki kolom `pin_approval` dan `tanda_tangan`)*.
5. **Storage Link (PENTING!)**
Agar foto barang dan tanda tangan muncul.
php artisan storage:link



## 📚 Handbook: Alur Logika Sistem

Bagian ini menjelaskan logika "belakang layar" yang penting diketahui developer.

### A. Logika Approval (PIN & TTD)

User (Manajer/Direktur) **wajib** melakukan setup di menu **Profil** terlebih dahulu:

1. Upload Tanda Tangan (format `.png` transparan).
2. Atur PIN Approval (6 digit angka).
*Tanpa kedua ini, tombol "Setujui" di halaman RAB tidak akan berfungsi.*

### B. Logika Barang Baru (Custom -> Master)

Saat RAB dibuat dengan item "Custom":

1. Item disimpan di tabel `rab_details` dengan `barang_it_id = NULL`.
2. Saat RAB Disetujui -> Admin klik "Catat Pembelian".
3. **Controller Interceptor:** `TransaksiMasukController` akan mengecek apakah ada item dengan `barang_it_id == NULL`.
4. Jika ada, Admin dilempar ke halaman **Konversi**.
5. Setelah dikonversi, item di `rab_details` diupdate `barang_it_id`-nya sesuai ID Master baru.

### C. Logika Hapus RAB (Data Integrity)

RAB yang sudah memiliki **Transaksi Masuk** tidak bisa dihapus sembarangan.

* **Sistem Mencegah:** Jika `rab->transaksiMasuks()->exists()`, penghapusan ditolak.
* **Solusi:** Admin harus menghapus data di menu *Transaksi Masuk* terlebih dahulu (agar stok gudang sinkron), baru bisa menghapus RAB.

### D. Logika Stok Opname (Cooldown)

Untuk mencegah pemeriksaan barang yang sama berulang-ulang:

* Sistem menghitung `Carbon::now()->subMonth()`.
* Barang yang ada di `stok_opname_details` dalam rentang waktu tersebut akan di-**exclude** (`whereNotIn`) dari daftar pengambilan sampel.


## 👤 Role & Hak Akses (Spatie)

1. **Admin IT**
* Membuat RAB, CRUD Barang, Transaksi Masuk/Keluar, Stok Opname.


2. **Manajer**
* Melihat RAB, Melakukan Approval Tahap 1.


3. **Direktur**
* Melihat RAB, Melakukan Approval Tahap 2 (Final).




## 📝 Catatan Tambahan

* Pastikan folder `storage/app/public` memiliki subfolder: `tanda_tangan`, `gambar_barang`, dan `rab_custom`.
* Jika terjadi error "Image not found", jalankan ulang `php artisan storage:link`.



*Dibuat & Dikembangkan untuk RSU Kertha Usada.*
