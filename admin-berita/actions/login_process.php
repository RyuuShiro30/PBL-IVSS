<?php
/**
 * Proses Login Admin
 * File: actions/login_process.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

// Ambil data dari form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Username dan password harus diisi!';
    header('Location: ../index.php');
    exit();
}

try {
    // Cari user berdasarkan username
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM admin_berita WHERE username = ? AND status = 'aktif'");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    // Cek apakah user ditemukan dan password benar
    if ($admin && password_verify($password, $admin['password'])) {
        // Set session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
        $_SESSION['role'] = $admin['role'];
        $_SESSION['foto'] = $admin['foto'];
        $_SESSION['email'] = $admin['email'];
        
        // Catat log login
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $log_stmt = $pdo->prepare("
            INSERT INTO logs (admin_id, aksi, detail, ip_address, user_agent) 
            VALUES (?, 'Login', 'Admin berhasil login ke sistem', ?, ?)
        ");
        $log_stmt->execute([$admin['id'], $ip_address, $user_agent]);
        
        // Redirect ke dashboard
        header('Location: ../pages/dashboard.php');
        exit();
        
    } else {
        // Login gagal
        $_SESSION['error'] = 'Username atau password salah!';
        
        // Log failed login attempt jika user ditemukan
        if ($admin) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $log_stmt = $pdo->prepare("
                INSERT INTO logs (admin_id, aksi, detail, ip_address) 
                VALUES (?, 'Login Gagal', 'Percobaan login dengan password salah', ?)
            ");
            $log_stmt->execute([$admin['id'], $ip_address]);
        }
        
        header('Location: ../index.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../index.php');
    exit();
}
?>
