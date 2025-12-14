<?php
/**
 * Proses Hapus Mahasiswa
 * File: actions/mhs_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID
$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    $_SESSION['error'] = 'ID Mahasiswa tidak valid!';
    header('Location: ../pages/anggota-list.php');
    exit();
}

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data mahasiswa
    $stmt = $pdo->prepare("SELECT nama, mahasiswa_profile FROM mahasiswa WHERE id = ?");
    $stmt->execute([$id]);
    $mhs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mhs) {
        $_SESSION['error'] = 'Mahasiswa tidak ditemukan!';
        header('Location: ../pages/anggota-list.php');
        exit();
    }

    // Hapus foto jika ada & bukan default
    $upload_dir = '../assets/img/';
    $profile = $mhs['mahasiswa_profile'] ?? '';

    if (
        !empty($profile) &&
        $profile !== 'default-avatar.png' &&
        is_file($upload_dir . $profile)
    ) {
        @unlink($upload_dir . $profile);
    }

    // Hapus data pendukung (FK riset_member)
    $stmt_rm = $pdo->prepare("DELETE FROM riset_mahasiswa WHERE id_mahasiswa = ?");
    $stmt_rm->execute([$id]);

    // Hapus data mahasiswa
    $delete = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
    $delete->execute([$id]);

    // Insert ke logs
    $log = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
        VALUES (?, 'Hapus Mahasiswa', ?, ?)
    ");
    $log->execute([
        $_SESSION['admin_id'],
        'Menghapus mahasiswa: ' . $mhs['nama'],
        $_SERVER['REMOTE_ADDR']
    ]);

    $_SESSION['success'] = 'Mahasiswa beserta seluruh datanya berhasil dihapus!';

} catch (PDOException $e) {
    die("ERROR: " . $e->getMessage());  
}

header('Location: ../pages/anggota-list.php');
exit();
