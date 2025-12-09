<?php
/**
 * Proses Edit Fasilitas
 * File: actions/fasilitas_edit_process.php
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
    header('Location: ../pages/fasilitas-list.php');
    exit();
}

// Ambil data dari form
$id = $_POST['id'] ?? 0;
$admin_id = $_SESSION['admin_id'];
$nama = trim($_POST['nama'] ?? '');
$deskripsi_fasilitas = trim($_POST['deskripsi_fasilitas'] ?? '');

// Validasi input
if (!$id || empty($nama) || empty($deskripsi_fasilitas)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/fasilitas-edit.php?id=' . $id);
    exit();
}

try {
    $pdo = getDBConnection();
    
    // Ambil data lama fasilitas
$stmtOld = $pdo->prepare("SELECT gambar_fasilitas, logo FROM fasilitas WHERE id = ?");
$stmtOld->execute([$id]);
$old = $stmtOld->fetch(PDO::FETCH_ASSOC);

$old_gambar_fasilitas = $old['gambar_fasilitas'];
$old_logo = $old['logo'];

$gambar_fasilitas = $old_gambar_fasilitas;
$logo = $old_logo;
// Handle upload gambar baru
$gambar_fasilitas = $old_gambar_fasilitas;
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
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Hapus gambar lama jika ada
        if ($old_gambar_fasilitas && file_exists($upload_dir . $old_gambar_fasilitas)) {
            unlink($upload_dir . $old_gambar_fasilitas);
        }
        $gambar_fasilitas = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload gambar!';
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }
}

$logo_baru = $old_logo;

if (isset($_FILES['logo_fasilitas']) && $_FILES['logo_fasilitas']['error'] === UPLOAD_ERR_OK) {

    $dir = '../assets/img/logo/';

    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $tmp = $_FILES['logo_fasilitas']['tmp_name'];
    $name = $_FILES['logo_fasilitas']['name'];
    $size = $_FILES['logo_fasilitas']['size'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png', 'svg'];

    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = 'Format logo tidak valid!';
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }

    if ($size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran logo maksimal 2MB!';
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }

    $new_file = time() . '_logo_' . uniqid() . '.' . $ext;

    if (move_uploaded_file($tmp, $dir . $new_file)) {
        if ($old_logo && file_exists($dir . $old_logo)) {
            unlink($dir . $old_logo);
        }
        $logo = $new_file;
    } else {
        $_SESSION['error'] = 'Gagal upload logo!';
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }
}



    // Update fasilitas di database
    $stmt = $pdo->prepare("
        UPDATE fasilitas 
        SET nama = ?, deskripsi_fasilitas = ?, gambar_fasilitas = ?, logo = ?, updated_at = NOW()
        WHERE id = ?
    ");
    
    $result = $stmt->execute([
        $nama,
        $deskripsi_fasilitas,
        $gambar_fasilitas,
        $logo,
        $id
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Edit Fasilitas', ?, ?)
        ");
        $log_stmt->execute([
            $admin_id,
            "Mengedit fasilitas: " . $nama,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Fasilitas berhasil diupdate!';
        header('Location: ../pages/fasilitas-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal mengupdate fasilitas!';
        header('Location: ../pages/fasilitas-edit.php?id=' . $id);
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Edit Fasilitas: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/fasilitas-edit.php?id=' . $id);
    exit();
}
?>
