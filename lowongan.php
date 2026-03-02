<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lowongan Kerja - SIREK RSUD Aji Muhammad Idris</title>
    <meta name="description" content="Daftar lengkap lowongan kerja di RSUD Aji Muhammad Idris Gersik">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%230D6EFD' rx='15' width='100' height='100'/%3E%3Cpath fill='white' d='M50 20v60M20 50h60' stroke='white' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar scrolled">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%230D6EFD' rx='15' width='100' height='100'/%3E%3Cpath fill='white' d='M50 20v60M20 50h60' stroke='white' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E" alt="Logo RSUD AMI">
                <div class="navbar-brand-text">
                    <h1>RSUD Aji Muhammad Idris</h1>
                    <span>Sistem Rekrutmen Karyawan</span>
                </div>
            </a>
            
            <ul class="navbar-menu">
                <li><a href="index.php#beranda">Beranda</a></li>
                <li><a href="index.php#lowongan">Lowongan</a></li>
                <li><a href="index.php#tentang">Tentang</a></li>
                <li><a href="index.php#kontak">Kontak</a></li>
            </ul>
            
            <div class="navbar-actions">
                <a href="login.php" class="btn btn-glass btn-sm">Login Admin</a>
                <a href="register.php" class="btn btn-primary btn-sm">Daftar Sekarang</a>
                <div class="navbar-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="section" style="padding-top: 120px; padding-bottom: 40px;">
        <div class="container">
            <div class="text-center">
                <h1>Lowongan Kerja</h1>
                <p class="text-muted">Temukan karir terbaik Anda di RSUD Aji Muhammad Idris</p>
            </div>
        </div>
    </section>

    <!-- Jobs Section -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <!-- Filters -->
            <div class="glass-card p-4 mb-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Departemen</label>
                        <select class="form-control form-select" name="departemen">
                            <option value="">Semua Departemen</option>
                            <?php
                            include 'config/database.php';
                            $dept_sql = "SELECT DISTINCT departemen FROM lowongan WHERE status = 'Aktif' ORDER BY departemen";
                            $dept_result = $conn->query($dept_sql);
                            while ($dept = $dept_result->fetch_assoc()):
                            ?>
                            <option value="<?= htmlspecialchars($dept['departemen']) ?>" <?= ($_GET['departemen'] ?? '') == $dept['departemen'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['departemen']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipe Pekerjaan</label>
                        <select class="form-control form-select" name="tipe">
                            <option value="">Semua Tipe</option>
                            <option value="Full Time" <?= ($_GET['tipe'] ?? '') == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                            <option value="Part Time" <?= ($_GET['tipe'] ?? '') == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                            <option value="Kontrak" <?= ($_GET['tipe'] ?? '') == 'Kontrak' ? 'selected' : '' ?>>Kontrak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Jobs Grid -->
            <div class="job-grid" id="job-list">
                <?php
                // Build query with filters
                $sql = "SELECT * FROM lowongan WHERE status = 'Aktif'";
                
                if (!empty($_GET['departemen'])) {
                    $dept = $conn->real_escape_string($_GET['departemen']);
                    $sql .= " AND departemen = '$dept'";
                }
                
                if (!empty($_GET['tipe'])) {
                    $tipe = $conn->real_escape_string($_GET['tipe']);
                    $sql .= " AND tipe_pekerjaan = '$tipe'";
                }
                
                $sql .= " ORDER BY created_at DESC";
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $tanggal_akhir = new DateTime($row['tanggal_akhir']);
                        $today = new DateTime();
                        $days_left = $today->diff($tanggal_akhir)->days;
                ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <span class="job-department"><?= htmlspecialchars($row['departemen']) ?></span>
                        <span class="job-type"><?= htmlspecialchars($row['tipe_pekerjaan']) ?></span>
                    </div>
                    <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 120)) ?>...</p>
                    <div class="job-card-footer">
                        <div class="job-info">
                            <div class="job-info-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <?= htmlspecialchars($row['lokasi']) ?>
                            </div>
                            <div class="job-info-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?= $days_left ?> hari lagi
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm" data-modal="job-detail" data-job-id="<?= $row['id'] ?>">
                            Lihat Detail
                        </button>
                    </div>
                </div>
                <?php 
                    endwhile;
                else:
                ?>
                <div class="col-12 text-center">
                    <div class="glass-card p-5">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.5" class="mb-3">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <h3>Tidak Ada Lowongan</h3>
                        <p class="text-muted">Tidak ada lowongan yang sesuai dengan filter Anda.</p>
                        <a href="lowongan.php" class="btn btn-outline mt-2">Reset Filter</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php $conn->close(); ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);">
        <div class="container">
            <div class="glass-card p-5 text-center">
                <h2>Siap Melamar?</h2>
                <p class="mb-4">Daftar sekarang dan bergabung dengan tim profesional healthcare kami</p>
                <a href="register.php" class="btn btn-primary btn-lg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <div class="footer-brand">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%23fff' rx='15' width='100' height='100'/%3E%3Cpath fill='%230D6EFD' d='M50 20v60M20 50h60' stroke='%230D6EFD' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E" alt="Logo">
                        <h3>RSUD Aji Muhammad Idris</h3>
                    </div>
                    <p>Menjadi rumah sakit pilihan utama masyarakat Gersik dengan pelayanan yang profesional, bermutu, dan terjangkau.</p>
                </div>
                
                <div>
                    <h4 class="footer-title">Tautan Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="index.php#beranda">Beranda</a></li>
                        <li><a href="index.php#lowongan">Lowongan Kerja</a></li>
                        <li><a href="index.php#tentang">Tentang Kami</a></li>
                        <li><a href="index.php#kontak">Kontak</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="footer-title">Informasi</h4>
                    <ul class="footer-links">
                        <li><a href="#">Persyaratan Umum</a></li>
                        <li><a href="#">Proses Rekrutmen</a></li>
                        <li><a href="#">Jadwal Seleksi</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="footer-title">Hubungi Kami</h4>
                    <ul class="footer-contact">
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            Jl. Pahlawan No. 1, Gersik
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            (031) 123-4567
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            rekrutmen@rsuid.ac.id
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 RSUD Aji Muhammad Idris. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Job Detail Modal -->
    <div class="modal-overlay" id="job-detail">
        <div class="modal">
            <div class="modal-header">
                <h3>Detail Lowongan</h3>
                <button class="modal-close" onclick="SIREK.closeModal(document.getElementById('job-detail'))">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="job-detail-content">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="SIREK.closeModal(document.getElementById('job-detail'))">Tutup</button>
                <a href="register.php" class="btn btn-primary">Lamar Sekarang</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    
    <script>
        document.querySelectorAll('[data-modal="job-detail"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const jobId = this.getAttribute('data-job-id');
                loadJobDetail(jobId);
            });
        });
        
        function loadJobDetail(jobId) {
            const contentDiv = document.getElementById('job-detail-content');
            
            fetch('api/job-detail.php?id=' + jobId)
                .then(response => response.text())
                .then(html => {
                    contentDiv.innerHTML = html;
                })
                .catch(error => {
                    contentDiv.innerHTML = '<p class="text-center">Gagal memuat detail lowongan.</p>';
                });
        }
    </script>
</body>
</html>
