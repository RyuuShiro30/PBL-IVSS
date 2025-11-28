<?php
/**
 * Proses Tambah Berita
 * File: actions/berita_add_process.php
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
    header('Location: ../pages/berita-add.php');
    exit();
}

// Ambil data dari form
$judul = trim($_POST['judul'] ?? '');
$link_berita = trim($_POST['link_berita'] ?? '');
$status = $_POST['status'] ?? 'draft';
$author_id = $_SESSION['admin_id'];

// Validasi input
if (empty($judul) || empty($link_berita) || empty($status)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/berita-add.php');
    exit();
}

// Generate slug dari judul
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul), '-'));

// Handle upload thumbnail
$thumbnail = null;
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../assets/img/thumbnails/';
    
    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_tmp = $_FILES['thumbnail']['tmp_name'];
    $file_name = $_FILES['thumbnail']['name'];
    $file_size = $_FILES['thumbnail']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Validasi ekstensi file
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file tidak diizinkan! Hanya JPG, PNG, GIF yang diperbolehkan.';
        header('Location: ../pages/berita-add.php');
        exit();
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/berita-add.php');
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $thumbnail = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload thumbnail!';
        header('Location: ../pages/berita-add.php');
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // Cek apakah slug sudah ada
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM berita WHERE slug = ?");
    $check_stmt->execute([$slug]);
    $slug_count = $check_stmt->fetch()['total'];
    
    // Jika slug sudah ada, tambahkan angka
    if ($slug_count > 0) {
        $slug = $slug . '-' . time();
    }
    
    // Insert berita ke database
    $stmt = $pdo->prepare("
        INSERT INTO berita (judul, slug, link_berita, thumbnail, status, author_id, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $result = $stmt->execute([
        $judul,
        $slug,
        $link_berita,
        $thumbnail,
        $status,
        $author_id
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Tambah Berita', ?, ?)
        ");
        $log_stmt->execute([
            $author_id,
            "Menambahkan berita: " . $judul,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Berita berhasil ditambahkan!';
        header('Location: ../pages/berita-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan berita!';
        header('Location: ../pages/berita-add.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Add Berita: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/berita-add.php');
    exit();
}
?>
