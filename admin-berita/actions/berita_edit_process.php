<?php
/**
 * Proses Edit Berita
 * File: actions/berita_edit_process.php
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
    header('Location: ../pages/berita-list.php');
    exit();
}

// Ambil data dari form
$id = $_POST['id'] ?? 0;
$judul = trim($_POST['judul'] ?? '');
$link_berita = trim($_POST['link_berita'] ?? '');
$status = $_POST['status'] ?? 'draft';
$old_thumbnail = $_POST['old_thumbnail'] ?? '';
$author_id = $_SESSION['admin_id'];

// Validasi input
if (!$id || empty($judul) || empty($link_berita) || empty($status)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/berita-edit.php?id=' . $id);
    exit();
}

// Generate slug dari judul
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul), '-'));

// Handle upload thumbnail baru
$thumbnail = $old_thumbnail;
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
        header('Location: ../pages/berita-edit.php?id=' . $id);
        exit();
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/berita-edit.php?id=' . $id);
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Hapus thumbnail lama jika ada
        if ($old_thumbnail && file_exists($upload_dir . $old_thumbnail)) {
            unlink($upload_dir . $old_thumbnail);
        }
        $thumbnail = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload thumbnail!';
        header('Location: ../pages/berita-edit.php?id=' . $id);
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // Cek apakah slug sudah ada (kecuali untuk berita ini sendiri)
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM berita WHERE slug = ? AND id != ?");
    $check_stmt->execute([$slug, $id]);
    $slug_count = $check_stmt->fetch()['total'];
    
    // Jika slug sudah ada, tambahkan angka
    if ($slug_count > 0) {
        $slug = $slug . '-' . time();
    }
    
    // Update berita di database
    $stmt = $pdo->prepare("
        UPDATE berita 
        SET judul = ?, slug = ?, link_berita = ?, thumbnail = ?, status = ?, updated_at = NOW()
        WHERE id = ?
    ");
    
    $result = $stmt->execute([
        $judul,
        $slug,
        $link_berita,
        $thumbnail,
        $status,
        $id
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_berita (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Edit Berita', ?, ?)
        ");
        $log_stmt->execute([
            $author_id,
            "Mengedit berita: " . $judul,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Berita berhasil diupdate!';
        header('Location: ../pages/berita-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal mengupdate berita!';
        header('Location: ../pages/berita-edit.php?id=' . $id);
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Edit Berita: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/berita-edit.php?id=' . $id);
    exit();
}
?>
