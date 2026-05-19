# 📚 Sistem Manajemen Pelanggaran dan Poin Siswa
### SMPN 16 Gresik

Aplikasi web berbasis Laravel untuk membantu Guru BK (Bimbingan Konseling) dalam mencatat dan mengelola pelanggaran siswa secara digital, dilengkapi dengan sistem poin otomatis dan penerbitan Surat Peringatan (SP) secara otomatis.

---

## 🧰 Teknologi yang Digunakan

| Teknologi | Versi |
|-----------|-------|
| PHP | >= 8.2 |
| Laravel | 13.x |
| MySQL | 8.0 |
| Tailwind CSS | via CDN |
| Laravel Breeze | (dimodifikasi — login pakai `username`) |

---

## ✨ Fitur Utama

### 👤 Guru BK (Admin)
- **Dashboard** — statistik sistem: total siswa, pelanggaran hari ini, jumlah SP aktif
- **Manajemen Kelas** — tambah, edit, hapus data kelas beserta wali kelas
- **Manajemen Siswa** — CRUD data siswa, pencarian, filter per kelas, rekap pelanggaran per siswa
- **Jenis Pelanggaran** — kelola kategori (ringan / sedang / berat) dan bobot poin
- **Transaksi Pelanggaran** — catat pelanggaran siswa; poin diperbarui **otomatis**
- **Log Peringatan (SP)** — riwayat surat peringatan dengan timeline visual; SP diterbitkan **otomatis**
- **Manajemen User** — tambah dan kelola akun Guru BK maupun Wali Kelas

### 👩‍🏫 Wali Kelas (Read-only)
- **Dashboard** — ringkasan kelas yang diampu
- **Daftar Siswa** — lihat data siswa beserta total poin dan status SP
- **Riwayat Pelanggaran** — pantau catatan pelanggaran siswa di kelas sendiri
- **Status SP** — monitor surat peringatan aktif untuk siswa di kelasnya

---

## ⚙️ Logika SP Otomatis

Setiap kali pelanggaran dicatat atau dihapus, sistem akan:
1. Menghitung ulang **total poin** siswa dari seluruh transaksi
2. Memeriksa ambang batas dan menerbitkan SP secara otomatis:

| Ambang Batas | Surat Peringatan |
|:---:|:---:|
| ≥ 25 poin | **SP1** |
| ≥ 50 poin | **SP2** |
| ≥ 75 poin | **SP3** |

> Setiap level SP hanya diterbitkan **satu kali**. Jika poin turun di bawah ambang (misal setelah data dihapus), SP terkait otomatis ditandai **selesai**.

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL (Laragon / XAMPP)
- Git

### Langkah-langkah

```bash
# 1. Clone repositori
git clone https://github.com/ArifbillahKamil/Sistem-Pencatatan-BK.git
cd Sistem-Pencatatan-BK

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate
```

### Konfigurasi Database

Edit file `.env` sesuai konfigurasi MySQL kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_bk
DB_USERNAME=root
DB_PASSWORD=
```

> Buat database `sistem_bk` terlebih dahulu di MySQL/phpMyAdmin.

```bash
# 5. Jalankan migrasi dan seeder
php artisan migrate --seed

# 6. Jalankan server lokal
php artisan serve
```

Buka browser dan akses: **http://127.0.0.1:8000**

---

## 🔑 Akun Default (Seeder)

| Role | Username | Password |
|------|----------|----------|
| Guru BK | `gurubk` | `password` |
| Wali Kelas | `walikelas` | `password` |

---

## 🗄️ Struktur Database

```
users               — akun pengguna (guru_bk / wali_kelas)
kelas               — data kelas dengan relasi ke wali kelas
siswa               — data siswa dengan total_poin
jenis_pelanggaran   — master jenis pelanggaran & bobot poin
transaksi_pelanggaran — catatan pelanggaran per siswa
log_peringatan      — riwayat surat peringatan (SP1 / SP2 / SP3)
```

---

## 📁 Struktur Direktori Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── KelasController.php
│   │   ├── SiswaController.php
│   │   ├── JenisPelanggaranController.php
│   │   ├── TransaksiPelanggaranController.php   ← inti sistem
│   │   ├── LogPeringatanController.php
│   │   ├── UserController.php
│   │   └── WaliKelasController.php
│   └── Middleware/
│       └── CheckRole.php                        ← role-based access
├── Models/
│   ├── Siswa.php
│   ├── Kelas.php
│   ├── JenisPelanggaran.php
│   ├── TransaksiPelanggaran.php
│   └── LogPeringatan.php
resources/views/
├── layouts/app.blade.php                        ← sidebar + navbar
├── dashboard/{guru_bk,wali_kelas}.blade.php
├── kelas/         siswa/         jenis-pelanggaran/
├── transaksi/     log-peringatan/    users/     wali/
routes/web.php                                   ← semua route
database/seeders/DatabaseSeeder.php              ← data awal
```

---

## 🔒 Hak Akses per Role

| Fitur | Guru BK | Wali Kelas |
|-------|:-------:|:----------:|
| Dashboard sistem | ✅ | ✅ (kelas sendiri) |
| CRUD Kelas | ✅ | ❌ |
| CRUD Siswa | ✅ | 👁️ baca |
| CRUD Jenis Pelanggaran | ✅ | ❌ |
| Catat / Edit Pelanggaran | ✅ | ❌ |
| Lihat Riwayat Pelanggaran | ✅ | 👁️ (kelas sendiri) |
| Log Peringatan + Toggle SP | ✅ | 👁️ (kelas sendiri) |
| Manajemen User | ✅ | ❌ |

---

## 🔄 Reset Data

Jika ingin mengulang data dari awal:

```bash
php artisan migrate:fresh --seed
```

---

## 👨‍💻 Dikembangkan oleh

**Arifbillah Kamil** — Kerja Praktik SMPN 16 Gresik  
Framework: [Laravel](https://laravel.com) · UI: [Tailwind CSS](https://tailwindcss.com)
