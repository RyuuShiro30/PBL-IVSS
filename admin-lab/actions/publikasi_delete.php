<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    $_SESSION['error'] = 'ID tidak valid';
    header('Location: ../pages/publikasi-list.php');
    exit();
}

try {
    $pdo = getDBConnection();

    // Ambil nama publikasi (untuk log)
    $get = $pdo->prepare("SELECT nama_publikasi FROM publikasi_dosen WHERE id_publikasi = ?");
    $get->execute([$id]);
    $publikasi = $get->fetch();

    if (!$publikasi) {
        $_SESSION['error'] = 'Data publikasi tidak ditemukan';
        header('Location: ../pages/publikasi-list.php');
        exit();
    }

    // Hapus publikasi
    $stmt = $pdo->prepare("DELETE FROM publikasi_dosen WHERE id_publikasi = ?");
    $stmt->execute([$id]);

    // 🔥 LOG AKTIVITAS
    $log_stmt = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
        VALUES (?, 'Hapus Publikasi', ?, ?)
    ");
    $log_stmt->execute([
        $_SESSION['admin_id'],
        'Menghapus publikasi: ' . $publikasi['nama_publikasi'],
        $_SERVER['REMOTE_ADDR']
    ]);

    $_SESSION['success'] = 'Publikasi berhasil dihapus';

} catch (Exception $e) {
    error_log('Error delete publikasi: ' . $e->getMessage());
    $_SESSION['error'] = 'Gagal menghapus data';
}

header('Location: ../pages/publikasi-list.php');
exit();
