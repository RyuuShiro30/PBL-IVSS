<?php
/**
 * Proses Hapus Riset
 * File: actions/riset_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID riset tidak valid!';
    header('Location: ../pages/riset-list.php');
    exit();
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // Ambil data riset
    $stmt = $pdo->prepare("SELECT * FROM riset WHERE id = ?");
    $stmt->execute([$id]);
    $riset = $stmt->fetch();

    if (!$riset) {
        $_SESSION['error'] = 'Riset tidak ditemukan!';
        header('Location: ../pages/riset-list.php');
        exit();
    }

    // Hapus relasi dosen
    $pdo->prepare("DELETE FROM riset_dosen WHERE id_riset = ?")->execute([$id]);

    // Hapus relasi mahasiswa
    $pdo->prepare("DELETE FROM riset_mahasiswa WHERE id_riset = ?")->execute([$id]);

    // Hapus riset utama
    $delete_stmt = $pdo->prepare("DELETE FROM riset WHERE id = ?");
    $delete_stmt->execute([$id]);

    // Log aktivitas
    $log_stmt = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
        VALUES (?, 'Hapus Riset', ?, ?)
    ");
    $log_stmt->execute([
        $_SESSION['admin_id'],
        "Menghapus riset: " . $riset['judul'],
        $_SERVER['REMOTE_ADDR']
    ]);

    $pdo->commit();
    $_SESSION['success'] = 'Riset berhasil dihapus!';

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error Delete Riset: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/riset-list.php');
exit();
