<?php
/**
 * Proses Edit Galeri
 * File: actions/galeri_edit_process.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/galeri-list.php');
    exit();
}

// Ambil data dari form
$id = $_POST['id'] ?? 0;
$admin_id = $_SESSION['admin_id'];
$deskripsi_galeri = trim($_POST['deskripsi_galeri'] ?? '');

// Validasi input
if (!$id || empty($nama) || empty($deskripsi_galeri)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/galeri-edit.php?id=' . $id);
    exit();
}

// Handle upload gambar baru
$gambar_galeri = $old_gambar_galeri;
if (isset($_FILES['gambar_galeri']) && $_FILES['gambar_galeri']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../assets/img/galeri/';
    
    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_tmp = $_FILES['gambar_galeri']['tmp_name'];
    $file_name = $_FILES['gambar_galeri']['name'];
    $file_size = $_FILES['gambar_galeri']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    
    // Validasi ekstensi file
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file tidak diizinkan! Hanya JPG, PNG yang diperbolehkan.';
        header('Location: ../pages/galeri-edit.php?id=' . $id);
        exit();
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/galeri-edit.php?id=' . $id);
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Hapus gambar lama jika ada
        if ($old_gambar_galeri && file_exists($upload_dir . $old_gambar_galeri)) {
            unlink($upload_dir . $old_gambar_galeri);
        }
        $gambar_galeri = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload gambar!';
        header('Location: ../pages/gambar-edit.php?id=' . $id);
        exit();
    }
}


try {
    $pdo = getDBConnection();
    
    // Update galeri di database
    $stmt = $pdo->prepare("
        UPDATE galeri 
        SET deskripsi_galeri = ?, gambar_galeri = ?, updated_at = NOW()
        WHERE id = ?
    ");
    
    $result = $stmt->execute([
        $deskripsi_galeri,
        $gambar_galeri,
        $id
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Edit Galeri', ?, ?)
        ");
        $log_stmt->execute([
            $admin_id,
            "Mengedit galeri: " . $deskripsi_galeri,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Galeri berhasil diupdate!';
        header('Location: ../pages/galeri-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal mengupdate galeri!';
        header('Location: ../pages/galeri-edit.php?id=' . $id);
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Edit Galeri: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/galeri-edit.php?id=' . $id);
    exit();
}
?>
