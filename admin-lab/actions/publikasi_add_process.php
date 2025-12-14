<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/publikasi-list.php');
    exit();
}

$nama   = trim($_POST['nama_publikasi'] ?? '');
$link   = trim($_POST['link_publikasi'] ?? '');
$tahun  = (int)($_POST['tahun_publikasi'] ?? 0);
$id_dosen = (int)($_POST['id_dosen'] ?? 0);

if ($nama === '' || $link === '' || !$tahun || !$id_dosen) {
    $_SESSION['error'] = 'Semua field wajib diisi!';
    header('Location: ../pages/publikasi-add.php');
    exit();
}

try {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("
        INSERT INTO publikasi_dosen
        (nama_publikasi, link_publikasi, tahun_publikasi, id_dosen, created_at_publikasi)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$nama, $link, $tahun, $id_dosen]);

    // ambil ID publikasi terakhir
    $id_publikasi = $pdo->lastInsertId();

    // 🔥 CATAT LOG AKTIVITAS
    $log_stmt = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
        VALUES (?, 'Tambah Publikasi', ?, ?)
    ");
    $log_stmt->execute([
        $_SESSION['admin_id'],
        'Menambahkan publikasi: ' . $nama,
        $_SERVER['REMOTE_ADDR']
    ]);

    $_SESSION['success'] = 'Publikasi berhasil ditambahkan';
    header('Location: ../pages/publikasi-list.php');
    exit();

} catch (Exception $e) {
    error_log('Error tambah publikasi: ' . $e->getMessage());
    $_SESSION['error'] = 'Gagal menambahkan publikasi';
    header('Location: ../pages/publikasi-add.php');
    exit();
}
