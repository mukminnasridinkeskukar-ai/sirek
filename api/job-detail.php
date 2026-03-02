<?php
/**
 * API for Job Detail
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

header('Content-Type: text/html; charset=utf-8');

include '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM lowongan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <div class="job-detail-content">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <span class="job-department"><?= htmlspecialchars($row['departemen']) ?></span>
                <span class="job-type"><?= htmlspecialchars($row['tipe_pekerjaan']) ?></span>
            </div>
        </div>
        
        <h3 class="mb-3"><?= htmlspecialchars($row['judul']) ?></h3>
        
        <div class="job-info mb-4" style="display: flex; gap: 20px; flex-wrap: wrap;">
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
                <?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($row['tanggal_akhir'])) ?>
            </div>
        </div>
        
        <div class="mb-4">
            <h4 class="mb-2">Deskripsi Pekerjaan</h4>
            <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
        </div>
        
        <div class="mb-4">
            <h4 class="mb-2">Persyaratan</h4>
            <p><?= nl2br(htmlspecialchars($row['requirements'])) ?></p>
        </div>
        
        <div class="alert alert-info">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span>Pastikan Anda memenuhi persyaratan sebelum melamar. Dokumentasi lengkap akan diminta pada tahap seleksi.</span>
        </div>
    </div>
    
    <style>
        .job-detail-content .job-department {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-blue);
            text-transform: uppercase;
            margin-right: 8px;
        }
        
        .job-detail-content .job-type {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(32, 201, 151, 0.1);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--secondary-teal);
        }
        
        .job-detail-content h3 {
            font-size: 1.5rem;
            color: var(--deep-navy);
        }
        
        .job-detail-content h4 {
            font-size: 1rem;
            color: var(--deep-navy);
            font-weight: 600;
        }
        
        .job-detail-content .job-info-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: #64748b;
        }
    </style>
    <?php
} else {
    echo '<p class="text-center">Lowongan tidak ditemukan.</p>';
}

$stmt->close();
$conn->close();
