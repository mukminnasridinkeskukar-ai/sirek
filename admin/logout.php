<?php
/**
 * Admin Logout Process
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 */

session_start();

// Destroy session
session_destroy();

// Redirect to login page
header('Location: ../login.php');
exit;
