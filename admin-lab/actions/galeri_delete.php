<?php
/**
 * Proses Hapus Galeri
 * File: actions/galeri_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID Galeri
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID galeri tidak valid!';
    header('Location: ../pages/galeri-list.php');
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Ambil data galeri terlebih dahulu
    $stmt = $pdo->prepare("SELECT * FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $galeri = $stmt->fetch();
    
    if (!$galeri) {
        $_SESSION['error'] = 'Galeri tidak ditemukan!';
        header('Location: ../pages/galeri-list.php');
        exit();
    }
    
    
    // Hapus galeri dari database
    $delete_stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
    $result = $delete_stmt->execute([$id]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Hapus Galeri', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menghapus galeri: " . $galeri['nama'],
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Galeri berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus galeri!';
    }
    
} catch (PDOException $e) {
    error_log("Error Delete Galeri: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/galeri-list.php');
exit();
?>
