<?php
/**
 * Proses Hapus Fasilitas
 * File: actions/fasilitas_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID Fasilitas
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID fasilitas tidak valid!';
    header('Location: ../pages/fasilitas-list.php');
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Ambil data fasilitas terlebih dahulu
    $stmt = $pdo->prepare("SELECT * FROM fasilitas WHERE id = ?");
    $stmt->execute([$id]);
    $fasilitas = $stmt->fetch();
    
    if (!$fasilitas) {
        $_SESSION['error'] = 'Fasilitas tidak ditemukan!';
        header('Location: ../pages/fasilitas-list.php');
        exit();
    }
    
    
    // Hapus fasilitas dari database
    $delete_stmt = $pdo->prepare("DELETE FROM fasilitas WHERE id = ?");
    $result = $delete_stmt->execute([$id]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Hapus Fasilitas', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menghapus fasilitas: " . $fasilitas['nama'],
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Fasilitas berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus fasilitas!';
    }
    
} catch (PDOException $e) {
    error_log("Error Delete Fasilitas: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/fasilitas-list.php');
exit();
?>
