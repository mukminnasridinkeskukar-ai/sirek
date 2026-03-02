<?php
/**
 * Admin Login Process
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        header('Location: ../login.php?error=Username dan password harus diisi');
        exit;
    }
    
    // Check credentials
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $admin['password'])) {
            // Set session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];
            
            // Redirect to admin dashboard
            header('Location: index.php');
            exit;
        } else {
            header('Location: ../login.php?error=Password salah');
            exit;
        }
    } else {
        header('Location: ../login.php?error=Username tidak ditemukan');
        exit;
    }
    
    $stmt->close();
} else {
    header('Location: ../login.php');
    exit;
}
