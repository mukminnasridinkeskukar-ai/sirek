<?php
/**
 * Admin Applications Management
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

// Check admin login
include '../config/database.php';
requireAdminLogin();

// Handle status update
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $catatan = trim($_POST['catatan']);
    
    $sql = "UPDATE lamaran SET status = ?, catatan = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $catatan, $id);
    
    if ($stmt->execute()) {
        $message = 'Status lamaran berhasil diperbarui!';
        $message_type = 'success';
    }
    $stmt->close();
}

// Get filter
$filter = $_GET['filter'] ?? 'all';

// Build query based on filter
$sql = "SELECT l.*, p.nama_lengkap, p.email, p.no_telepon, p.pendidikan_terakhir, 
        low.judul as lowongan_judul, low.departemen 
        FROM lamaran l 
        JOIN pelamar p ON l.pelamar_id = p.id 
        JOIN lowongan low ON l.lowongan_id = low.id";

if ($filter != 'all') {
    $sql .= " WHERE l.status = '" . $conn->real_escape_string($filter) . "'";
}

$sql .= " ORDER BY l.tanggal_lamar DESC";

$applications = $conn->query($sql);

// Get application detail if requested
$detail = null;
if (isset($_GET['action']) && $_GET['action'] == 'detail' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT l.*, p.nama_lengkap, p.email, p.no_telepon, p.tanggal_lahir, 
            p.jenis_kelamin, p.alamat, p.pendidikan_terakhir, p.pengalaman_kerja,
            low.judul as lowongan_judul, low.departemen 
            FROM lamaran l 
            JOIN pelamar p ON l.pelamar_id = p.id 
            JOIN lowongan low ON l.lowongan_id = low.id
            WHERE l.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $detail = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lamaran - SIREK Admin</title>
    
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
                <li><a href="lamaran.php" class="active"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg><span>Lamaran</span></a></li>
                <li><a href="pelamar.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Pelamar</span></a></li>
                <li><a href="../index.php" target="_blank"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg><span>Lihat Website</span></a></li>
                <li><a href="logout.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Logout</span></a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Kelola Lamaran</h1>
                <div class="admin-user">
                    <span><?= htmlspecialchars($_SESSION['admin_nama']) ?></span>
                    <div class="admin-user-avatar"><?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)) ?></div>
                </div>
            </div>
            
            <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span><?= $message ?></span>
            </div>
            <?php endif; ?>
            
            <!-- Filters -->
            <div class="admin-card mb-4">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="lamaran.php?filter=all" class="btn <?= $filter == 'all' ? 'btn-primary' : 'btn-outline' ?>">Semua</a>
                    <a href="lamaran.php?filter=Baru" class="btn <?= $filter == 'Baru' ? 'btn-primary' : 'btn-outline' ?>">Baru</a>
                    <a href="lamaran.php?filter=Diproses" class="btn <?= $filter == 'Diproses' ? 'btn-primary' : 'btn-outline' ?>">Diproses</a>
                    <a href="lamaran.php?filter=Diterima" class="btn <?= $filter == 'Diterima' ? 'btn-primary' : 'btn-outline' ?>">Diterima</a>
                    <a href="lamaran.php?filter=Ditolak" class="btn <?= $filter == 'Ditolak' ? 'btn-primary' : 'btn-outline' ?>">Ditolak</a>
                </div>
            </div>
            
            <!-- Applications Table -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Daftar Lamaran</h3>
                </div>
                
                <?php if ($applications->num_rows > 0): ?>
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
                            <?php $no = 1; while ($row = $applications->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong><br>
                                    <small><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['lowongan_judul']) ?></td>
                                <td><?= date('d M Y H:i', strtotime($row['tanggal_lamar'])) ?></td>
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
                    <p>Tidak ada lamaran dengan filter tersebut.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Detail Modal -->
            <?php if ($detail): ?>
            <div class="modal-overlay active" style="position: fixed;">
                <div class="modal" style="max-width: 700px;">
                    <div class="modal-header">
                        <h3>Detail Lamaran</h3>
                        <a href="lamaran.php" class="modal-close">&times;</a>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($detail['nama_lengkap']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($detail['email']) ?></p>
                                <p><strong>Telepon:</strong> <?= htmlspecialchars($detail['no_telepon']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Lowongan:</strong> <?= htmlspecialchars($detail['lowongan_judul']) ?></p>
                                <p><strong>Departemen:</strong> <?= htmlspecialchars($detail['departemen']) ?></p>
                                <p><strong>Tanggal Lamar:</strong> <?= date('d M Y H:i', strtotime($detail['tanggal_lamar'])) ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p><strong>Jenis Kelamin:</strong> <?= htmlspecialchars($detail['jenis_kelamin']) ?></p>
                            <p><strong>Tanggal Lahir:</strong> <?= $detail['tanggal_lahir'] ? date('d M Y', strtotime($detail['tanggal_lahir'])) : '-' ?></p>
                            <p><strong>Alamat:</strong> <?= htmlspecialchars($detail['alamat'] ?? '-') ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <p><strong>Pendidikan Terakhir:</strong> <?= htmlspecialchars($detail['pendidikan_terakhir'] ?? '-') ?></p>
                            <p><strong>Pengalaman Kerja:</strong></p>
                            <p><?= nl2br(htmlspecialchars($detail['pengalaman_kerja'] ?? '-')) ?></p>
                        </div>
                        
                        <hr>
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?= $detail['id'] ?>">
                            
                            <div class="form-group">
                                <label class="form-label">Update Status</label>
                                <select class="form-control form-select" name="status" required>
                                    <option value="Baru" <?= $detail['status'] == 'Baru' ? 'selected' : '' ?>>Baru</option>
                                    <option value="Diproses" <?= $detail['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="Diterima" <?= $detail['status'] == 'Diterima' ? 'selected' : '' ?>>Diterima</option>
                                    <option value="Ditolak" <?= $detail['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" rows="3"><?= htmlspecialchars($detail['catatan'] ?? '') ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="lamaran.php" class="btn btn-outline">Tutup</a>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>

<?php $conn->close(); ?>
