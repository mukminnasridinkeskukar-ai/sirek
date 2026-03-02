<?php
/**
 * Database Configuration for SIREK (Sistem Informasi Rekrutmen Karyawan)
 * RSUD Aji Muhammad Idris
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_rsuid_ rekrutmen');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Session configuration
session_start();

// Base URL configuration
$base_url = "http://localhost:8000/";

// Helper function to sanitize input
function sanitize($conn, $data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Helper function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if user is logged in as admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Require admin login
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        redirect('login.php');
    }
}
?>
