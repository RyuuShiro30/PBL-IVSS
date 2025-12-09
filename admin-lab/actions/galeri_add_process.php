<?php
/**
 * Proses Tambah Galeri
 * File: actions/galeri_add_process.php
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
    header('Location: ../pages/galeri-add.php');
    exit();
}

// Ambil data dari form
$admin_id = $_SESSION['admin_id'];
$deskripsi_galeri = trim($_POST['deskripsi_galeri'] ?? '');

// Ambil status upload file dari $_FILES
$gambar_uploaded = isset($_FILES['gambar_galeri']) && $_FILES['gambar_galeri']['error'] === UPLOAD_ERR_OK;

// Validasi input: Cek apakah deskripsi dan file gambar ada
if (!$gambar_uploaded || empty($deskripsi_galeri)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/galeri-add.php');
    exit();
}

// Handle upload gambar
$gambar_galeri = null;
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
        header('Location: ../pages/galeri-add.php');
        exit();
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/galeri-add.php');
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $gambar_galeri = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload gambar!';
        header('Location: ../pages/galeri-add.php');
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("
    INSERT INTO galeri (gambar_galeri, deskripsi_galeri, created_at, updated_at) 
    VALUES (?, ?, NOW(), NOW())");

    $result = $stmt->execute([
        $gambar_galeri,
        $deskripsi_galeri
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Tambah Galeri', ?, ?)
        ");
        $log_stmt->execute([
            $admin_id,
            "Menambahkan galeri: " . $deskripsi_galeri,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Galeri berhasil ditambahkan!';
        header('Location: ../pages/galeri-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan galeri!';
        header('Location: ../pages/galeri-add.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Add Galeri: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/galeri-add.php');
    exit();
}
?>
