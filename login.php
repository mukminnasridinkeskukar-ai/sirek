<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIREK RSUD Aji Muhammad Idris</title>
    <meta name="description" content="Halaman login administrator sistem rekrutmen karyawan RSUD Aji Muhammad Idris">
    
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
    <!-- Login Page -->
    <div class="login-page">
        <div class="login-card glass-card">
            <div class="login-header">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%230D6EFD' rx='15' width='100' height='100'/%3E%3Cpath fill='white' d='M50 20v60M20 50h60' stroke='white' stroke-width='12' stroke-linecap='round'/%3E%3C/svg%3E" alt="Logo" class="login-logo">
                <h2>Login Admin</h2>
                <p>Masukkan username dan password Anda</p>
            </div>
            
            <?php
            // Display error message if any
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-error">';
                echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                echo '<span>' . htmlspecialchars($_GET['error']) . '</span>';
                echo '</div>';
            }
            ?>
            
            <form action="admin/process-login.php" method="POST" class="needs-validation" novalidate>
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                </div>
                
                <div class="form-group d-flex justify-content-between align-items-center">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember">
                        <span class="form-check-label">Ingat saya</span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Login
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-outline btn-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            
            <div class="mt-4 p-3" style="background: #f8fafc; border-radius: 8px; font-size: 0.85rem; color: #64748b; text-align: center;">
                <strong>Demo Login:</strong><br>
                Username: admin<br>
                Password: admin123
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>
