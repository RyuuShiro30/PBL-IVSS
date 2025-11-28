<?php
/**
 * Proses Hapus Berita
 * File: actions/berita_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID berita
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID berita tidak valid!';
    header('Location: ../pages/berita-list.php');
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Ambil data berita terlebih dahulu
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $berita = $stmt->fetch();
    
    if (!$berita) {
        $_SESSION['error'] = 'Berita tidak ditemukan!';
        header('Location: ../pages/berita-list.php');
        exit();
    }
    
    // Hapus thumbnail jika ada
    if ($berita['thumbnail']) {
        $thumbnail_path = '../assets/img/thumbnails/' . $berita['thumbnail'];
        if (file_exists($thumbnail_path)) {
            unlink($thumbnail_path);
        }
    }
    
    // Hapus berita dari database
    $delete_stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
    $result = $delete_stmt->execute([$id]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Hapus Berita', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menghapus berita: " . $berita['judul'],
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Berita berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus berita!';
    }
    
} catch (PDOException $e) {
    error_log("Error Delete Berita: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/berita-list.php');
exit();
?>
