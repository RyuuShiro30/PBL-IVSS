<?php
/**
 * Proses Hapus Mahasiswa
 * File: actions/mhs_delete.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID Mahasiswa
$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    $_SESSION['error'] = 'ID Mahasiswa tidak valid!';
    header('Location: ../pages/anggota-list.php');
    exit();
}


try {
    $pdo = getDBConnection();

    // Ambil data mahasiswa terlebih dahulu
    //$stmt = $pdo->prepare("SELECT nama FROM mahasiswa WHERE id = ?");
    $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id=$34', [$id]);
    var_dump($stmt);
    //die();
    $stmt->execute([$id]);
    $mhs = $stmt->fetch();
    var_dump($mhs);
    die();
    if (!$mhs) {
        $_SESSION['error'] = 'Mahasiswa tidak ditemukan!';
        header('Location: ../pages/anggota-list.php');
        exit();
    }
    try {
    qparams('DELETE FROM mahasiswa WHERE id=$1', [$id]);
    header('Location: index.php');
    exit;
    var_dump($mhs);
    die();
    
    $upload_dir = '../assets/img/';
    if ($mhs['mahasiswa_profile'] !== 'default-avatar.png' && 
        file_exists($upload_dir . $mhs['mahasiswa_profile'])) {
        unlink($upload_dir . $mhs['mahasiswa_profile']);
    }

    $pdo->prepare("DELETE FROM riset_member WHERE id_mahasiswa = ?")
        ->execute([$id]);

    $delete_stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
    $result = $delete_stmt->execute([$id]);

    if ($result) {
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
            VALUES (?, 'Hapus Mahasiswa', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menghapus mahasiswa: " . $mhs['nama'],
            $_SERVER['REMOTE_ADDR']
        //]);

        $_SESSION['success'] = 'Mahasiswa beserta seluruh data pendukung berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus mahasiswa!';
    }

} catch (PDOException $e) {
    error_log("Error Delete Mahasiswa: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
}

header('Location: ../pages/anggota-list.php');
//exit();
?>