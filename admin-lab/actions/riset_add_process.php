<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Cek request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/riset-add.php');
    exit();
}

// Ambil data form
$admin_id = $_SESSION['admin_id'];
$judul = trim($_POST['judul'] ?? '');
$link_riset = trim($_POST['link_riset'] ?? '');
$tahun = trim($_POST['tahun'] ?? '');

$id_dosen = $_POST['id_dosen'] ?? [];
$id_mahasiswa = $_POST['id_mahasiswa'] ?? [];

// Validasi wajib
if (empty($judul) || empty($link_riset) || empty($tahun) || empty($id_dosen)) {
    $_SESSION['error'] = 'Harap isi semua field wajib dan pilih dosen!';
    header('Location: ../pages/riset-add.php');
    exit();
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // INSERT riset
    $stmt = $pdo->prepare("
        INSERT INTO riset (judul, link_riset, tahun, created_at, updated_at) 
        VALUES (?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([$judul, $link_riset, $tahun]);
    $id_riset = $pdo->lastInsertId();

    // INSERT dosen ke pivot
    $stmtDosen = $pdo->prepare("
        INSERT INTO riset_dosen (id_riset, id_dosen) VALUES (?, ?)
    ");
    foreach ($id_dosen as $dosenID) {
        $stmtDosen->execute([$id_riset, $dosenID]);
    }

    // INSERT mahasiswa ke pivot
    if (!empty($id_mahasiswa)) {
        $stmtMhs = $pdo->prepare("
            INSERT INTO riset_mahasiswa (id_riset, id_mahasiswa) VALUES (?, ?)
        ");
        foreach ($id_mahasiswa as $mhsID) {
            $stmtMhs->execute([$id_riset, $mhsID]);
        }
    }

    // LOG aktivitas
    $log_stmt = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
        VALUES (?, 'Tambah Riset', ?, ?)
    ");
    $log_stmt->execute([
        $admin_id,
        "Menambahkan riset: {$judul}",
        $_SERVER['REMOTE_ADDR']
    ]);

    // Semua OK → commit
    $pdo->commit();

    $_SESSION['success'] = 'Riset berhasil ditambahkan!';
    header('Location: ../pages/riset-list.php');
    exit();

} catch (PDOException $e) {
    // Jika gagal → rollback
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("Error Add Riset: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/riset-add.php');
    exit();
}
?>
