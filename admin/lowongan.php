<?php
/**
 * Admin Job Vacancies Management
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

// Check admin login
include '../config/database.php';
requireAdminLogin();

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add' || $_POST['action'] == 'edit') {
            $judul = trim($_POST['judul']);
            $departemen = trim($_POST['departemen']);
            $deskripsi = trim($_POST['deskripsi']);
            $requirements = trim($_POST['requirements']);
            $lokasi = trim($_POST['lokasi']);
            $tipe_pekerjaan = $_POST['tipe_pekerjaan'];
            $tanggal_mulai = $_POST['tanggal_mulai'];
            $tanggal_akhir = $_POST['tanggal_akhir'];
            $status = $_POST['status'];
            
            if ($_POST['action'] == 'add') {
                $sql = "INSERT INTO lowongan (judul, departemen, deskripsi, requirements, lokasi, tipe_pekerjaan, tanggal_mulai, tanggal_akhir, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssss", $judul, $departemen, $deskripsi, $requirements, $lokasi, $tipe_pekerjaan, $tanggal_mulai, $tanggal_akhir, $status);
            } else {
                $id = (int)$_POST['id'];
                $sql = "UPDATE lowongan SET judul=?, departemen=?, deskripsi=?, requirements=?, lokasi=?, tipe_pekerjaan=?, tanggal_mulai=?, tanggal_akhir=?, status=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssi", $judul, $departemen, $deskripsi, $requirements, $lokasi, $tipe_pekerjaan, $tanggal_mulai, $tanggal_akhir, $status, $id);
            }
            
            if ($stmt->execute()) {
                $message = $_POST['action'] == 'add' ? 'Lowongan berhasil ditambahkan!' : 'Lowongan berhasil diperbarui!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menyimpan lowongan.';
                $message_type = 'error';
            }
            $stmt->close();
        }
    }
}

// Delete job
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM lowongan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = 'Lowongan berhasil dihapus!';
        $message_type = 'success';
    }
    $stmt->close();
}

// Get job for editing
$edit_job = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM lowongan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_job = $result->fetch_assoc();
    $stmt->close();
}

// Get all jobs
$sql = "SELECT * FROM lowongan ORDER BY created_at DESC";
$jobs = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lowongan - SIREK Admin</title>
    
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
                    <a href="index.php">
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
                    <a href="lowongan.php" class="active">
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
                <h1>Kelola Lowongan Kerja</h1>
                <div class="admin-user">
                    <span><?= htmlspecialchars($_SESSION['admin_nama']) ?></span>
                    <div class="admin-user-avatar">
                        <?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)) ?>
                    </div>
                </div>
            </div>
            
            <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span><?= $message ?></span>
            </div>
            <?php endif; ?>
            
            <!-- Add/Edit Form -->
            <?php if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
            <div class="admin-card mb-4">
                <h3 class="admin-card-title mb-4"><?= $_GET['action'] == 'add' ? 'Tambah' : 'Edit' ?> Lowongan</h3>
                
                <form method="POST" action="lowongan.php">
                    <input type="hidden" name="action" value="<?= $_GET['action'] ?>">
                    <?php if ($edit_job): ?>
                    <input type="hidden" name="id" value="<?= $edit_job['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Judul Posisi</label>
                                <input type="text" class="form-control" name="judul" value="<?= $edit_job['judul'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Departemen</label>
                                <input type="text" class="form-control" name="departemen" value="<?= $edit_job['departemen'] ?? '' ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tipe Pekerjaan</label>
                                <select class="form-control form-select" name="tipe_pekerjaan" required>
                                    <option value="Full Time" <?= (isset($edit_job['tipe_pekerjaan']) && $edit_job['tipe_pekerjaan'] == 'Full Time') ? 'selected' : '' ?>>Full Time</option>
                                    <option value="Part Time" <?= (isset($edit_job['tipe_pekerjaan']) && $edit_job['tipe_pekerjaan'] == 'Part Time') ? 'selected' : '' ?>>Part Time</option>
                                    <option value="Kontrak" <?= (isset($edit_job['tipe_pekerjaan']) && $edit_job['tipe_pekerjaan'] == 'Kontrak') ? 'selected' : '' ?>>Kontrak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Lokasi</label>
                                <input type="text" class="form-control" name="lokasi" value="<?= $edit_job['lokasi'] ?? 'Kabupaten Gersik' ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai" value="<?= $edit_job['tanggal_mulai'] ?? date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Berakhir</label>
                                <input type="date" class="form-control" name="tanggal_akhir" value="<?= $edit_job['tanggal_akhir'] ?? '' ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="4" required><?= $edit_job['deskripsi'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Persyaratan</label>
                        <textarea class="form-control" name="requirements" rows="4" required><?= $edit_job['requirements'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-control form-select" name="status" required>
                            <option value="Aktif" <?= (isset($edit_job['status']) && $edit_job['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="Nonaktif" <?= (isset($edit_job['status']) && $edit_job['status'] == 'Nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="lowongan.php" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
            <?php else: ?>
            
            <!-- Jobs List -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Daftar Lowongan</h3>
                    <a href="lowongan.php?action=add" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Lowongan
                    </a>
                </div>
                
                <?php if ($jobs->num_rows > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Departemen</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $jobs->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                <td><?= htmlspecialchars($row['departemen']) ?></td>
                                <td><?= htmlspecialchars($row['tipe_pekerjaan']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal_akhir'])) ?></td>
                                <td>
                                    <span class="status-badge <?= strtolower($row['status']) ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="lowongan.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                                        <a href="lowongan.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline" onclick="return confirm('Yakin ingin menghapus lowongan ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-4">
                    <p>Belum ada lowongan kerja.</p>
                    <a href="lowongan.php?action=add" class="btn btn-primary">Tambah Lowongan Pertama</a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/main.js"></script>
</body>
</html>

<?php
$conn->close();
