<?php
/**
 * Proses Hapus Admin (Khusus Super Admin)
 * File: actions/admin_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login dan role super admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'superadmin') {
    $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini!';
    header('Location: ../pages/dashboard.php');
    exit();
}

// Ambil ID admin
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID admin tidak valid!';
    header('Location: ../pages/admin-list.php');
    exit();
}

// Cek agar tidak menghapus diri sendiri
if ($id == $_SESSION['admin_id']) {
    $_SESSION['error'] = 'Anda tidak dapat menghapus akun Anda sendiri!';
    header('Location: ../pages/admin-list.php');
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Ambil data admin terlebih dahulu
    $stmt = $pdo->prepare("SELECT * FROM admin_lab WHERE id = ?");
    $stmt->execute([$id]);
    $admin = $stmt->fetch();
    
    if (!$admin) {
        $_SESSION['error'] = 'Admin tidak ditemukan!';
        header('Location: ../pages/admin-list.php');
        exit();
    }
    
    // Hapus foto profil jika bukan default
    if ($admin['foto'] !== 'default-avatar.png') {
        $foto_path = '../assets/img/' . $admin['foto'];
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }
    
    // Hapus admin dari database
    $delete_stmt = $pdo->prepare("DELETE FROM admin_lab WHERE id = ?");
    $result = $delete_stmt->execute([$id]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Hapus Admin', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menghapus admin: " . $admin['nama_lengkap'] . " (" . $admin['username'] . ")",
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Admin berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus admin!';
    }
    
} catch (PDOException $e) {
    error_log("Error Delete Admin: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/admin-list.php');
exit();
?>
