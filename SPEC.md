# Sistem Informasi Rekrutmen Karyawan – RSUD Aji Muhammad Idris

## 1. Project Overview

**Project Name**: Sistem Informasi Rekrutmen Karyawan (SIREK)
**Project Type**: Web-based Employee Recruitment System
**Hospital**: RSUD Aji Muhammad Idris (Regional Public Hospital)
**Target Users**: Hospital HR Admin & Job Applicants

## 2. Technical Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 8.x
- **Database**: MySQL 8.x
- **Design**: Glassmorphism + Clean Medical Theme

## 3. UI/UX Specification

### 3.1 Color Palette

| Color Name | Hex Code | Usage |
|------------|----------|-------|
| Primary Blue | #0D6EFD | Main buttons, links |
| Secondary Teal | #20C997 | Success states, accents |
| Deep Navy | #0F172A | Headers, text |
| Pure White | #FFFFFF | Cards, modals |
| Light Gray | #F8FAFC | Background |
| Soft Blue Glass | rgba(255, 255, 255, 0.15) | Glass effect background |
| Border Glass | rgba(255, 255, 255, 0.3) | Glass border |
| Shadow Blue | rgba(13, 110, 253, 0.15) | Glow effects |

### 3.2 Typography

- **Primary Font**: "Plus Jakarta Sans" (Google Fonts)
- **Heading Sizes**: H1: 3rem, H2: 2.25rem, H3: 1.5rem
- **Body Text**: 1rem (16px)
- **Small Text**: 0.875rem

### 3.3 Glassmorphism Design

```css
.glass-card {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
}
```

### 3.4 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 4. Page Structure

### 4.1 Public Pages (No Login Required)

1. **index.php** - Landing Page
   - Hero section with hospital branding
   - Available job vacancies carousel
   - Quick stats (total openings, applicants)
   - Call-to-action buttons
   - Footer with hospital info

2. **lowongan.php** - Job Vacancies List
   - Filterable job listings
   - Job detail modal (lightbox)
   - Apply now button

3. **register.php** - Applicant Registration
   - Registration form with validation
   - Document upload fields

4. **login.php** - Admin Login (Only for Admin Panel)

### 4.2 Admin Panel Pages (Login Required)

1. **admin/index.php** - Dashboard
   - Overview statistics
   - Recent applications
   - Quick actions

2. **admin/lowongan.php** - Job Management
   - Create/Edit/Delete job vacancies

3. **admin/lamaran.php** - Application Management
   - View all applications
   - Filter by status
   - Update application status

4. **admin/pelamar.php** - Applicant Database
   - View all registered applicants
   - Search and filter

## 5. Database Schema

### 5.1 Tables

```sql
-- Admin Users Table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Job Vacancies Table
CREATE TABLE lowongan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(200) NOT NULL,
    departemen VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    requirements TEXT,
    lokasi VARCHAR(100),
    tipe_pekerjaan ENUM('Full Time', 'Part Time', 'Kontrak') DEFAULT 'Full Time',
    tanggal_mulai DATE,
    tanggal_akhir DATE,
    status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Applicants Table
CREATE TABLE pelamar (
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

-- Applications Table
CREATE TABLE lamaran (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pelamar_id INT NOT NULL,
    lowongan_id INT NOT NULL,
    tanggal_lamar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Baru', 'Diproses', 'Diterima', 'Ditolak') DEFAULT 'Baru',
    catatan TEXT,
    FOREIGN KEY (pelamar_id) REFERENCES pelamar(id) ON DELETE CASCADE,
    FOREIGN KEY (lowongan_id) REFERENCES lowongan(id) ON DELETE CASCADE
);
```

## 6. Features

### 6.1 Landing Page Features
- Animated hero section with hospital info
- Job vacancy carousel/grid
- Statistics counter animation
- Glassmorphism cards
- Modal for job details
- Mobile-friendly navigation

### 6.2 Admin Panel Features
- Secure login with session management
- Dashboard with charts/stats
- CRUD operations for job vacancies
- Application status management
- Applicant database search
- Export functionality (optional)

### 6.3 Applicant Features
- View job vacancies
- Register as applicant
- Submit application
- Track application status

## 7. Security Requirements

- Password hashing (PHP password_hash)
- Session-based authentication
- SQL injection prevention (prepared statements)
- XSS prevention (htmlspecialchars)
- CSRF protection for forms

## 8. Acceptance Criteria

1. ✅ Landing page loads without login
2. ✅ Glassmorphism UI visible on all pages
3. ✅ Job vacancies display in grid/list
4. ✅ Job detail modal works (lightbox)
5. ✅ Applicant registration form submits to database
6. ✅ Admin login required for admin panel
7. ✅ Admin can create/edit/delete jobs
8. ✅ Admin can view all applications
9. ✅ Responsive on mobile and desktop
10. ✅ Database properly stores all data
