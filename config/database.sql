-- ========================================
-- SIREK Database Schema
-- Sistem Informasi Rekrutmen Karyawan
-- RSUD Aji Muhammad Idris
-- ========================================

-- Create database
CREATE DATABASE IF NOT EXISTS db_rsuid_rekrutmen;
USE db_rsuid_rekrutmen;

-- ========================================
-- Admins Table
-- ========================================
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
-- Password hash for 'admin123'
INSERT INTO admins (username, password, nama_lengkap, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Sistem', 'admin@rsuid.ac.id');

-- ========================================
-- Lowongan (Job Vacancies) Table
-- ========================================
CREATE TABLE IF NOT EXISTS lowongan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(200) NOT NULL,
    departemen VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    requirements TEXT,
    lokasi VARCHAR(100) DEFAULT 'Kabupaten Gersik',
    tipe_pekerjaan ENUM('Full Time', 'Part Time', 'Kontrak') DEFAULT 'Full Time',
    tanggal_mulai DATE,
    tanggal_akhir DATE,
    status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample job vacancies
INSERT INTO lowongan (judul, departemen, deskripsi, requirements, lokasi, tipe_pekerjaan, tanggal_mulai, tanggal_akhir, status) VALUES
('Dokter Umum', 'Pelayanan Medis', 'Melayani pasien rawat jalan dan rawat inap dengan standar pelayanan medis tertinggi.', 'S1 Kedokteran, STR Aktif, memiliki pengalaman minimal 2 tahun di rumah sakit', 'Kabupaten Gersik', 'Full Time', '2026-01-01', '2026-04-30', 'Aktif'),
('Perawat Gigi', 'Pelayanan Gigi', 'Melakukan perawatan gigi dan mulut pasien sesuai prosedur standar.', 'S1 Keperawatan Gigi, STR Aktif, pengalaman di klinik gigi', 'Kabupaten Gersik', 'Full Time', '2026-01-15', '2026-05-31', 'Aktif'),
('Apoteker', 'Farmasi', 'Melayani resep obat dan konsultasi obat kepada pasien.', 'S1 Farmasi, SIPA Apoteker, pengalaman di apotek rumah sakit', 'Kabupaten Gersik', 'Full Time', '2026-02-01', '2026-06-30', 'Aktif'),
('Tenaga Laboratorium', 'Laboratorium', 'Melakukan pemeriksaan laboratorium sesuai standar Quality Control.', 'D3/S1 Analis Kesehatan, pengalaman di laboratorium klinis', 'Kabupaten Gersik', 'Full Time', '2026-02-15', '2026-05-15', 'Aktif'),
('Staf Administrasi', 'Administrasi', 'Mengelola administrasi pasien dan dokumen rumah sakit.', 'D3/S1 Administrasi/Perkantoran, MS Office', 'Kabupaten Gersik', 'Full Time', '2026-03-01', '2026-06-30', 'Aktif'),
('Petugas Keamanan', 'Keamanan', 'Menjaga keamanan dan ketertiban area rumah sakit.', 'SMA/SMK, pengalaman security', 'Kabupaten Gersik', 'Full Time', '2026-01-01', '2026-12-31', 'Aktif');

-- ========================================
-- Pelamar (Applicants) Table
-- ========================================
CREATE TABLE IF NOT EXISTS pelamar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nik VARCHAR(16) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_telepon VARCHAR(15) NOT NULL,
    tanggal_lahir DATE,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    alamat TEXT,
    pendidikan_terakhir VARCHAR(50),
    pengalaman_kerja TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- Lamaran (Applications) Table
-- ========================================
CREATE TABLE IF NOT EXISTS lamaran (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pelamar_id INT NOT NULL,
    lowongan_id INT NOT NULL,
    tanggal_lamar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Baru', 'Diproses', 'Diterima', 'Ditolak') DEFAULT 'Baru',
    catatan TEXT,
    FOREIGN KEY (pelamar_id) REFERENCES pelamar(id) ON DELETE CASCADE,
    FOREIGN KEY (lowongan_id) REFERENCES lowongan(id) ON DELETE CASCADE
);

-- ========================================
-- Create indexes for better performance
-- ========================================
CREATE INDEX idx_lowongan_status ON lowongan(status);
CREATE INDEX idx_lamaran_status ON lamaran(status);
CREATE INDEX idx_lamaran_pelamar ON lamaran(pelamar_id);
CREATE INDEX idx_lamaran_lowongan ON lamaran(lowongan_id);
