<?php
/**
 * Proses Tambah Fasilitas
 * File: actions/fasilitas_add_process.php
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
    header('Location: ../pages/fasilitas-add.php');
    exit();
}

// Ambil data dari form
$admin_id = $_SESSION['admin_id'];
$nama = trim($_POST['nama'] ?? '');
$deskripsi_fasilitas = trim($_POST['deskripsi_fasilitas'] ?? '');

// Validasi input
if (empty($nama) || empty($deskripsi_fasilitas)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/fasilitas-add.php');
    exit();
}

// Handle upload gambar
$gambar_fasilitas = null;
if (isset($_FILES['gambar_fasilitas']) && $_FILES['gambar_fasilitas']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../assets/img/fasilitas/';
    
    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_tmp = $_FILES['gambar_fasilitas']['tmp_name'];
    $file_name = $_FILES['gambar_fasilitas']['name'];
    $file_size = $_FILES['gambar_fasilitas']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed_ext = ['jpg', 'jpeg', 'png', 'svg'];
    
    // Validasi ekstensi file
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file tidak diizinkan! Hanya JPG, PNG yang diperbolehkan.';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $gambar_fasilitas = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload gambar!';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }
}

$logo = null;
if (isset($_FILES['logo_fasilitas']) && $_FILES['logo_fasilitas']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../assets/img/logo/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_tmp = $_FILES['logo_fasilitas']['tmp_name'];
    $file_name = $_FILES['logo_fasilitas']['name'];
    $file_size = $_FILES['logo_fasilitas']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'svg'];

    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file logo tidak diizinkan! Hanya JPG & PNG.';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }

    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran logo maksimal 2MB!';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }

    $new_file_name = time() . '_logo_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $logo = $new_file_name; // ✔ benar!
    } else {
        $_SESSION['error'] = 'Gagal mengupload logo!';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }
}


try {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("
    INSERT INTO fasilitas (nama, deskripsi_fasilitas, gambar_fasilitas, logo, created_at, updated_at) 
    VALUES (?, ?, ?, ?, NOW(), NOW())");

    $result = $stmt->execute([
        $nama,
        $deskripsi_fasilitas,
        $gambar_fasilitas,
        $logo
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Tambah Fasilitas', ?, ?)
        ");
        $log_stmt->execute([
            $admin_id,
            "Menambahkan fasilitas: " . $nama,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Fasilitas berhasil ditambahkan!';
        header('Location: ../pages/fasilitas-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan fasilitas!';
        header('Location: ../pages/fasilitas-add.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Add Fasilitas: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/fasilitas-add.php');
    exit();
}
?>
