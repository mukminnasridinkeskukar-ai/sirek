<?php
/**
 * Admin Dashboard
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

// Check admin login
include '../config/database.php';
requireAdminLogin();

// Get statistics
$stats = [];

// Total lowongan aktif
$sql = "SELECT COUNT(*) as total FROM lowongan WHERE status = 'Aktif'";
$result = $conn->query($sql);
$stats['lowongan_aktif'] = $result->fetch_assoc()['total'] ?? 0;

// Total pelamar
$sql = "SELECT COUNT(*) as total FROM pelamar";
$result = $conn->query($sql);
$stats['total_pelamar'] = $result->fetch_assoc()['total'] ?? 0;

// Total lamaran
$sql = "SELECT COUNT(*) as total FROM lamaran";
$result = $conn->query($sql);
$stats['total_lamaran'] = $result->fetch_assoc()['total'] ?? 0;

// Lamaran baru
$sql = "SELECT COUNT(*) as total FROM lamaran WHERE status = 'Baru'";
$result = $conn->query($sql);
$stats['lamaran_baru'] = $result->fetch_assoc()['total'] ?? 0;

// Get recent applications
$sql = "SELECT l.*, p.nama_lengkap, p.email, low.judul as lowongan_judul 
        FROM lamaran l 
        JOIN pelamar p ON l.pelamar_id = p.id 
        JOIN lowongan low ON l.lowongan_id = low.id 
        ORDER BY l.tanggal_lamar DESC LIMIT 5";
$recent_applications = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIREK RSUD Aji Muhammad Idris</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%230D6EFD' rx='15' width='100' height='100'/%3E%3Cpath fill='white' d='M50 20v60M20 50h60' stroke='white' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%23fff' rx='15' width='100' height='100'/%3E%3Cpath fill='%230D6EFD' d='M50 20v60M20 50h60' stroke='%230D6EFD' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E" alt="Logo">
                <h2>SIREK Admin</h2>
            </div>
            
            <ul class="admin-sidebar-menu">
                <li>
                    <a href="index.php" class="active">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="lowongan.php">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <span>Lowongan</span>
                    </a>
                </li>
                <li>
                    <a href="lamaran.php">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span>Lamaran</span>
                    </a>
                </li>
                <li>
                    <a href="pelamar.php">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Pelamar</span>
                    </a>
                </li>
                <li>
                    <a href="../index.php" target="_blank">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        <span>Lihat Website</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span><?= htmlspecialchars($_SESSION['admin_nama']) ?></span>
                    <div class="admin-user-avatar">
                        <?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)) ?>
                    </div>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['lowongan_aktif'] ?></h3>
                        <p>Lowongan Aktif</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon teal">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_pelamar'] ?></h3>
                        <p>Total Pelamar</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_lamaran'] ?></h3>
                        <p>Total Lamaran</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['lamaran_baru'] ?></h3>
                        <p>Lamaran Baru</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Applications -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Lamaran Terbaru</h3>
                    <a href="lamaran.php" class="btn btn-sm btn-outline">Lihat Semua</a>
                </div>
                
                <?php if ($recent_applications->num_rows > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pelamar</th>
                                <th>Lowongan</th>
                                <th>Tanggal Lamar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $recent_applications->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong><br>
                                    <small><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['lowongan_judul']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal_lamar'])) ?></td>
                                <td>
                                    <span class="status-badge <?= strtolower($row['status']) ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="lamaran.php?action=detail&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline">Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-4">
                    <p>Belum ada lamaran masuk.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="admin-card">
                        <h3 class="admin-card-title mb-3">Aksi Cepat</h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="lowongan.php?action=add" class="btn btn-primary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Lowongan
                            </a>
                            <a href="lamaran.php" class="btn btn-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                Kelola Lamaran
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="admin-card">
                        <h3 class="admin-card-title mb-3">Informasi Sistem</h3>
                        <p class="mb-2"><strong>Total Lowongan:</strong> <?= $stats['lowongan_aktif'] ?> aktif</p>
                        <p class="mb-2"><strong>Total Pelamar:</strong> <?= $stats['total_pelamar'] ?> orang</p>
                        <p class="mb-0"><strong>Total Lamaran:</strong> <?= $stats['total_lamaran'] ?> lamaran</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/main.js"></script>
</body>
</html>

<?php
$conn->close();
