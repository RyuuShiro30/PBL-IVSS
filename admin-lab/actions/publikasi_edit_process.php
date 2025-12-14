<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

$id    = (int)($_POST['id_publikasi'] ?? 0);
$nama  = trim($_POST['nama_publikasi'] ?? '');
$link  = trim($_POST['link_publikasi'] ?? '');
$tahun = (int)($_POST['tahun_publikasi'] ?? 0);
$id_dosen = (int)($_POST['id_dosen'] ?? 0);

if (!$id || $nama === '' || $link === '' || !$tahun || !$id_dosen) {
    $_SESSION['error'] = 'Data tidak lengkap';
    header("Location: ../pages/publikasi-edit.php?id=$id");
    exit();
}

try {
    $pdo = getDBConnection();

    // Update data
    $stmt = $pdo->prepare("
        UPDATE publikasi_dosen
        SET nama_publikasi = ?, 
            link_publikasi = ?, 
            tahun_publikasi = ?, 
            id_dosen = ?, 
            updated_at = NOW()
        WHERE id_publikasi = ?
    ");
    $stmt->execute([$nama, $link, $tahun, $id_dosen, $id]);

    // 🔥 LOG AKTIVITAS
    $log_stmt = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
        VALUES (?, 'Update Publikasi', ?, ?)
    ");
    $log_stmt->execute([
        $_SESSION['admin_id'],
        'Memperbarui publikasi: ' . $nama,
        $_SERVER['REMOTE_ADDR']
    ]);

    $_SESSION['success'] = 'Publikasi berhasil diperbarui';
    header('Location: ../pages/publikasi-list.php');
    exit();

} catch (Exception $e) {
    error_log('Error update publikasi: ' . $e->getMessage());
    $_SESSION['error'] = 'Gagal update data';
    header("Location: ../pages/publikasi-edit.php?id=$id");
    exit();
}
