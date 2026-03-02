# Sistem Informasi Rekrutmen Karyawan - RSUD Aji Muhammad Idris

<p align="center">
  <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%230D6EFD' rx='15' width='100' height='100'/%3E%3Cpath fill='white' d='M50 20v60M20 50h60' stroke='white' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E" width="100" alt="Logo">
</p>

<p align="center">
  <strong>Sistem Rekrutmen Karyawan Super Enterprise</strong><br>
  Rumah Sakit Umum Daerah Aji Muhammad Idris Gersik
</p>

<p align="center">
  <a href="https://github.com"><img src="https://img.shields.io/badge/Platform-GitHub-blue?style=flat-square" alt="Platform"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License"></a>
  <a href="https://rsuid.ac.id"><img src="https://img.shields.io/badge/Rumah%20Sakit-RSUD%20AMI-orange?style=flat-square" alt="RSUD AMI"></a>
</p>

---

## 📋 Deskripsi

SIREK adalah aplikasi web untuk mengelola proses rekrutmen karyawan di RSUD Aji Muhammad Idris. Tersedia dalam 3 versi:

1. **PHP Version** (Production) - Full-stack PHP dengan MySQL database
2. **HTML Version** (`SIREK-RSUPAMI.html`) - All-in-one file, siap upload ke GitHub Pages
3. **Google Apps Script** (`SIREK.gs`) - Terintegrasi dengan Google Sheets

---

## 🚀 Cara Mengupload ke GitHub

### Langkah 1: Buat Repository di GitHub

1. Buka [GitHub](https://github.com) dan login
2. Klik tombol **+** di pojok kanan atas → **New repository**
3. Isi nama repository: `Rekrutmen-RSUD-AMI`
4. Pilih **Public** atau **Private**
5. Klik **Create repository**
6. **Jangan** centang "Add a README file" karena kita sudah punya

### Langkah 2: Install Git (jika belum ada)

```bash
# Windows (via Chocolatey)
choco install git

# Windows (Manual)
# Download dari https://git-scm.com
```

### Langkah 3: Clone Repository ke Lokal

```bash
# Buka terminal/command prompt
git clone https://github.com/USERNAME/Rekrutmen-RSUD-AMI.git
cd Rekrutmen-RSUD-AMI
```

### Langkah 4: Salin File Project

Salin semua file dari folder project lokal ke folder repository yang sudah di-clone:

```
Rekrutmen-RSUD-AMI/
├── .gitignore
├── LICENSE
├── README.md
├── SIREK-RSUPAMI.html
├── SIREK.gs
├── SPEC.md
├── admin/
│   ├── index.php
│   ├── lamaran.php
│   ├── logout.php
│   ├── lowongan.php
│   ├── pelamar.php
│   └── process-login.php
├── api/
│   └── job-detail.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── config/
│   ├── database.php
│   └── database.sql
├── index.php
├── login.php
├── lowongan.php
└── register.php
```

### Langkah 5: Commit dan Push

```bash
# Tambahkan semua file
git add .

# Commit dengan pesan
git commit -m "Initial commit - SIREK System"

# Push ke GitHub
git push -u origin main
```

---

## 📂 Struktur File

```
Rekrutmen-RSUD-AMI/
├── .gitignore              # File ignore untuk Git
├── LICENSE                 # Lisensi MIT
├── README.md               # Dokumentasi ini
├── SPEC.md                 # Spesifikasi proyek
├── SIREK-RSUPAMI.html     # Versi HTML (GitHub Pages)
├── SIREK.gs               # Versi Google Apps Script
├── index.php              # Halaman utama
├── login.php              # Halaman login admin
├── register.php           # Halaman pendaftaran pelamar
├── lowongan.php           # Halaman lihat lowongan
├── admin/                 # Folder admin
│   ├── index.php          # Dashboard admin
│   ├── lamaran.php        # Kelola lamaran
│   ├── logout.php         # Logout
│   ├── lowongan.php       # Kelola lowongan
│   ├── pelamar.php        # Kelola pelamar
│   └── process-login.php  # Proses login
├── api/                   # API endpoints
│   └── job-detail.php
├── assets/                # Asset statis
│   ├── css/
│   │   └── style.css      # Styling
│   └── js/
│       └── main.js        # JavaScript
└── config/
    ├── database.php        # Konfigurasi database
    └── database.sql       # Schema database
```

---

## 🚀 Cara Menggunakan (PHP Version)

### Persiapan

1. Pastikan sudah install **XAMPP** atau **WAMP**
2. Aktkan **Apache** dan **MySQL**
3. Buat database dengan import `config/database.sql`

### Konfigurasi Database

Edit [`config/database.php`](config/database.php):

```php
$host = "localhost";
$user = "root";          // Sesuaikan
$pass = "";              // Sesuaikan
$db   = "db_rsuid_rekrutmen";
```

### Jalankan Aplikasi

```bash
# Buka browser
http://localhost/Rekrutmen-RSUD-AMI
```

### Login Admin

- **URL**: [`login.php`](login.php)
- **Username**: `admin`
- **Password**: `admin123`

---

## 🚀 Cara Menggunakan (Versi Lain)

### Versi HTML (GitHub Pages)

```bash
# Upload SIREK-RSUPAMI.html ke repository GitHub
# Aktifkan GitHub Pages di Settings > Pages
# Akses di: https://USERNAME.github.io/Rekrutmen-RSUD-AMI/SIREK-RSUPAMI.html
```

### Versi Google Apps Script

1. Buat Google Spreadsheet baru
2. Ekstensi → Apps Script
3. Copy kode dari `SIREK.gs`
4. Ganti `SPREADSHEET_ID` dengan ID spreadsheet Anda
5. Run `setupSheets()` untuk membuat sheet
6. Deploy sebagai Web App

---

## 📱 Demo Screenshots

### Dashboard
- Total Pelamar, Lolos, Cadangan
- Grafik Statistik
- Timeline Rekrutmen
- Countdown Pengumuman

---

## 🛠️ API Endpoints (Google Apps Script)

| Function | Deskripsi |
|---------|-----------|
| `getPelamar()` | Ambil semua data pelamar |
| `searchPelamar(query)` | Cari pelamar |
| `addPelamar(data)` | Tambah pelamar baru |
| `updateVerifikasi(id, status, catatan)` | Update status verifikasi |
| `updateNilai(id, kompt, wawancara)` | Update nilai |
| `getStatistics()` | Ambil statistik |
| `sendNotificationEmail(email, subject, body)` | Kirim email |
| `setupSheets()` | Setup sheet |

---

## 🛠️ Teknologi

| Versi | Teknologi |
|--------|-----------|
| PHP | PHP 8+, MySQL, HTML5, CSS3, JavaScript |
| HTML | HTML5, CSS3, JavaScript, Chart.js, jsPDF, SheetJS |
| GAS | Google Apps Script, Google Sheets, MailApp |

---

## 🤝 Cara Berkontribusi

1. **Fork** repository ini
2. Buat **branch** baru (`git checkout -b fitur-anda`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur-anda`)
5. Buat **Pull Request**

---

## 📝 Lisensi

MIT License - Copyright © 2026 RSUD Aji Muhammad Idris

---

## 📞 Kontak

**RSUD Aji Muhammad Idris**
- Alamat: Jl. Pahlawan No. 1, Gersik, Jawa Timur
- Telepon: (031) 123-4567
- Email: rekrutmen@rsuid.ac.id

---

<p align="center">
  <sub>Made with ❤️ for Healthcare</sub>
</p>
