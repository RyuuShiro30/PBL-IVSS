<?php
/**
 * Proses Hapus Dosen
 * File: actions/dosen_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID Dosen
$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    $_SESSION['error'] = 'ID Dosen tidak valid!';
    header('Location: ../pages/anggota-list.php');
    exit();
}

try {
    $pdo = getDBConnection();

    // Ambil data dosen terlebih dahulu
    $stmt = $pdo->prepare("SELECT nama, dosen_profile FROM dosen WHERE id = ?");
    $stmt->execute([$id]);
    $dosen = $stmt->fetch();

    if (!$dosen) {
        $_SESSION['error'] = 'Dosen tidak ditemukan!';
        header('Location: ../pages/anggota-list.php');
        exit();
    }

    $upload_dir = '../assets/img/';
    if ($dosen['dosen_profile'] !== 'default-avatar.png' && 
        file_exists($upload_dir . $dosen['dosen_profile'])) {
        unlink($upload_dir . $dosen['dosen_profile']);
    }

    $pdo->prepare("DELETE FROM riset_member WHERE id_dosen = ?")
        ->execute([$id]);

    $pdo->prepare("DELETE FROM publikasi_member WHERE id_dosen = ?")
        ->execute([$id]);

    $pdo->prepare("DELETE FROM pendidikan WHERE dosen_id = ?")
        ->execute([$id]);

    $pdo->prepare("DELETE FROM sertifikat WHERE dosen_id = ?")
        ->execute([$id]);


    $delete_stmt = $pdo->prepare("DELETE FROM dosen WHERE id = ?");
    $result = $delete_stmt->execute([$id]);

    if ($result) {
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
            VALUES (?, 'Hapus Dosen', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menghapus dosen: " . $dosen['nama'],
            $_SERVER['REMOTE_ADDR']
        ]);

        $_SESSION['success'] = 'Dosen beserta seluruh data pendukung berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus dosen!';
    }

} catch (PDOException $e) {
    error_log("Error Delete Dosen: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/anggota-list.php');
exit();
