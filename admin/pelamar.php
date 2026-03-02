<?php
/**
 * Admin Applicants Database
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

// Check admin login
include '../config/database.php';
requireAdminLogin();

// Get search
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT * FROM pelamar";

if ($search) {
    $sql .= " WHERE nama_lengkap LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR email LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR nik LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sql .= " ORDER BY created_at DESC";

$applicants = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelamar - SIREK Admin</title>
    
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
                <li><a href="index.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Dashboard</span></a></li>
                <li><a href="lowongan.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg><span>Lowongan</span></a></li>
                <li><a href="lamaran.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg><span>Lamaran</span></a></li>
                <li><a href="pelamar.php" class="active"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Pelamar</span></a></li>
                <li><a href="../index.php" target="_blank"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline></svg><span>Lihat Website</span></a></li>
                <li><a href="logout.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline></svg><span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Data Pelamar</h1>
                <div class="admin-user">
                    <span><?= htmlspecialchars($_SESSION['admin_nama']) ?></span>
                    <div class="admin-user-avatar"><?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)) ?></div>
                </div>
            </div>
            
            <!-- Search -->
            <div class="admin-card mb-4">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" class="form-control" name="search" placeholder="Cari berdasarkan nama, email, atau NIK..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <?php if ($search): ?>
                    <a href="pelamar.php" class="btn btn-outline">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Applicants Table -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Daftar Pelamar (<?= $applicants->num_rows ?>)</h3>
                </div>
                
                <?php if ($applicants->num_rows > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Pendidikan</th>
                                <th>Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $applicants->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nik']) ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td><?= htmlspecialchars($row['pendidikan_terakhir'] ?? '-') ?></td>
                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-4">
                    <p>Tidak ada pelamar ditemukan.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>

<?php $conn->close(); ?>
