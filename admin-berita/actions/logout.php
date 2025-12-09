<?php
/**
 * Proses Logout Admin
 * File: actions/logout.php
 */

session_start();
require_once '../config/database.php';

// Catat log logout sebelum destroy session
if (isset($_SESSION['admin_id'])) {
    try {
        $pdo = getDBConnection();
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        $stmt = $pdo->prepare("
            INSERT INTO logs_berita (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Logout', 'Admin keluar dari sistem', ?)
        ");
        $stmt->execute([$_SESSION['admin_id'], $ip_address]);
    } catch (PDOException $e) {
        error_log("Logout Log Error: " . $e->getMessage());
    }
}

// Hapus semua session
session_unset();
session_destroy();

// Set pesan sukses
session_start();
$_SESSION['success'] = 'Anda berhasil logout. Sampai jumpa!';

// Redirect ke halaman login
header('Location: ../index.php');
exit();
?>
