<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelamar - SIREK RSUD Aji Muhammad Idris</title>
    <meta name="description" content="Daftar sebagai pelamar kerja di RSUD Aji Muhammad Idris Gersik">
    
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
    <nav class="navbar">
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
                <div class="navbar-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Registration Section -->
    <section class="section" style="padding-top: 120px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="glass-card p-5">
                        <div class="text-center mb-5">
                            <h2>Daftar sebagai Pelamar</h2>
                            <p class="text-muted">Isi data diri Anda dengan lengkap dan benar</p>
                        </div>
                        
                        <?php
                        // Process registration
                        include 'config/database.php';
                        
                        $message = '';
                        $message_type = '';
                        
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $nik = trim($_POST['nik'] ?? '');
                            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
                            $email = trim($_POST['email'] ?? '');
                            $no_telepon = trim($_POST['no_telepon'] ?? '');
                            $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
                            $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
                            $alamat = trim($_POST['alamat'] ?? '');
                            $pendidikan_terakhir = trim($_POST['pendidikan_terakhir'] ?? '');
                            $pengalaman_kerja = trim($_POST['pengalaman_kerja'] ?? '');
                            $id_lowongan = (int)($_POST['id_lowongan'] ?? 0);
                            
                            // Validation
                            $errors = [];
                            if (empty($nik) || strlen($nik) < 16) {
                                $errors[] = 'NIK harus 16 digit';
                            }
                            if (empty($nama_lengkap)) {
                                $errors[] = 'Nama lengkap wajib diisi';
                            }
                            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = 'Email tidak valid';
                            }
                            if (empty($no_telepon)) {
                                $errors[] = 'Nomor telepon wajib diisi';
                            }
                            if (empty($jenis_kelamin)) {
                                $errors[] = 'Jenis kelamin wajib dipilih';
                            }
                            
                            if (empty($errors)) {
                                // Check if NIK or email already exists
                                $check_sql = "SELECT id FROM pelamar WHERE nik = ? OR email = ?";
                                $check_stmt = $conn->prepare($check_sql);
                                $check_stmt->bind_param("ss", $nik, $email);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();
                                
                                if ($check_result->num_rows > 0) {
                                    $message = 'NIK atau email sudah terdaftar';
                                    $message_type = 'error';
                                } else {
                                    // Insert pelamar
                                    $insert_sql = "INSERT INTO pelamar (nik, nama_lengkap, email, no_telepon, tanggal_lahir, jenis_kelamin, alamat, pendidikan_terakhir, pengalaman_kerja) 
                                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                    $insert_stmt = $conn->prepare($insert_sql);
                                    $insert_stmt->bind_param("sssssssss", $nik, $nama_lengkap, $email, $no_telepon, $tanggal_lahir, $jenis_kelamin, $alamat, $pendidikan_terakhir, $pengalaman_kerja);
                                    
                                    if ($insert_stmt->execute()) {
                                        $pelamar_id = $conn->insert_id;
                                        
                                        // If lowongan selected, create application
                                        if ($id_lowongan > 0) {
                                            $lamaran_sql = "INSERT INTO lamaran (pelamar_id, lowongan_id) VALUES (?, ?)";
                                            $lamaran_stmt = $conn->prepare($lamaran_sql);
                                            $lamaran_stmt->bind_param("ii", $pelamar_id, $id_lowongan);
                                            $lamaran_stmt->execute();
                                            $lamaran_stmt->close();
                                        }
                                        
                                        $message = 'Pendaftaran berhasil! Kami akan menghubungi Anda melalui email.';
                                        $message_type = 'success';
                                    } else {
                                        $message = 'Gagal menyimpan data. Silakan coba lagi.';
                                        $message_type = 'error';
                                    }
                                    $insert_stmt->close();
                                }
                                $check_stmt->close();
                            } else {
                                $message = implode('<br>', $errors);
                                $message_type = 'error';
                            }
                        }
                        
                        // Store message for display
                        $final_message = $message;
                        $final_message_type = $message_type;
                        ?>
                        
                        <?php if ($final_message): ?>
                        <div class="alert alert-<?= $final_message_type ?> mb-4">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <?php if ($final_message_type == 'success'): ?>
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                <?php else: ?>
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                <?php endif; ?>
                            </svg>
                            <span><?= $final_message ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!isset($final_message_type) || $final_message_type != 'success'): ?>
                        
                        <?php
                        // Get active job vacancies for selection
                        $jobs_sql = "SELECT id, judul, departemen FROM lowongan WHERE status = 'Aktif' ORDER BY created_at DESC";
                        $jobs_result = $conn->query($jobs_sql);
                        ?>
                        
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="nik">NIK (16 Digit) *</label>
                                        <input type="text" class="form-control" id="nik" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="nama_lengkap">Nama Lengkap *</label>
                                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="no_telepon">Nomor Telepon *</label>
                                        <input type="tel" class="form-control" id="no_telepon" name="no_telepon" placeholder="0812xxxxxxx" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin *</label>
                                        <select class="form-control form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih jenis kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                <select class="form-control form-select" id="pendidikan_terakhir" name="pendidikan_terakhir">
                                    <option value="">Pilih pendidikan</option>
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="pengalaman_kerja">Pengalaman Kerja</label>
                                <textarea class="form-control" id="pengalaman_kerja" name="pengalaman_kerja" rows="3" placeholder="Ceritakan pengalaman kerja Anda"></textarea>
                            </div>
                            
                            <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
                            <div class="form-group">
                                <label class="form-label" for="id_lowongan">Pilih Lowongan (Opsional)</label>
                                <select class="form-control form-select" id="id_lowongan" name="id_lowongan">
                                    <option value="">-- Pilih lowongan yang ingin dilamar --</option>
                                    <?php while ($job = $jobs_result->fetch_assoc()): ?>
                                    <option value="<?= $job['id'] ?>"><?= htmlspecialchars($job['judul']) ?> - <?= htmlspecialchars($job['departemen']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-muted">Anda juga dapat melamar lowongan lain setelah terdaftar.</small>
                            </div>
                            <?php endif; ?>
                                <small class="text-muted">Anda juga dapat melamar lowongan lain setelah terdaftar.</small>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" required>
                                    <span class="form-check-label">Saya menyatakan data yang saya isi adalah benar</span>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                Daftar Sekarang
                            </button>
                        </form>
                        
                        <?php else: ?>
                        
                        <div class="text-center">
                            <div class="mb-4">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--secondary-teal)" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <h3>Pendaftaran Berhasil!</h3>
                            <p class="mb-4">Terima kasih telah mendaftar. Kami akan memproses aplikasi Anda dan menghubungi melalui email.</p>
                            <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                        </div>
                        
                        <?php endif; ?>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">Sudah punya akun? <a href="login.php">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php $conn->close(); ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2026 RSUD Aji Muhammad Idris. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>
