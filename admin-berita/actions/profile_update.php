<?php
/**
 * Proses Update Profil
 * File: actions/profile_update.php
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
    header('Location: ../pages/profile.php');
    exit();
}

// Ambil data dari form
$admin_id = $_SESSION['admin_id'];
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi input
if (empty($nama_lengkap) || empty($email)) {
    $_SESSION['error'] = 'Nama lengkap dan email harus diisi!';
    header('Location: ../pages/profile.php');
    exit();
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header('Location: ../pages/profile.php');
    exit();
}

// Validasi password jika diisi
if (!empty($password)) {
    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password minimal 6 karakter!';
        header('Location: ../pages/profile.php');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Password dan konfirmasi password tidak sama!';
        header('Location: ../pages/profile.php');
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // Ambil data admin saat ini
    $stmt = $pdo->prepare("SELECT * FROM admin_berita WHERE id = ?");
    $stmt->execute([$admin_id]);
    $current_admin = $stmt->fetch();
    
    if (!$current_admin) {
        $_SESSION['error'] = 'Data admin tidak ditemukan!';
        header('Location: ../pages/profile.php');
        exit();
    }
    
    // Cek apakah email sudah digunakan admin lain
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM admin_berita WHERE email = ? AND id != ?");
    $check_stmt->execute([$email, $admin_id]);
    if ($check_stmt->fetch()['total'] > 0) {
        $_SESSION['error'] = 'Email sudah digunakan admin lain!';
        header('Location: ../pages/profile.php');
        exit();
    }
    
    // Handle upload foto profil baru
    $foto = $current_admin['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/img/';
        
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        // Validasi ekstensi file
        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = 'Format file tidak diizinkan! Hanya JPG, PNG yang diperbolehkan.';
            header('Location: ../pages/profile.php');
            exit();
        }
        
        // Validasi ukuran file (max 1MB)
        if ($file_size > 1 * 1024 * 1024) {
            $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 1MB.';
            header('Location: ../pages/profile.php');
            exit();
        }
        
        // Generate nama file unik
        $new_file_name = 'profile_' . time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;
        
        // Upload file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Hapus foto lama jika bukan default
            if ($current_admin['foto'] !== 'default-avatar.png' && file_exists($upload_dir . $current_admin['foto'])) {
                unlink($upload_dir . $current_admin['foto']);
            }
            $foto = $new_file_name;
        } else {
            $_SESSION['error'] = 'Gagal mengupload foto profil!';
            header('Location: ../pages/profile.php');
            exit();
        }
    }
    
    // Update profil di database
    if (!empty($password)) {
        // Jika password diisi, update dengan password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            UPDATE admin_berita 
            SET nama_lengkap = ?, email = ?, password = ?, foto = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $result = $stmt->execute([$nama_lengkap, $email, $hashed_password, $foto, $admin_id]);
    } else {
        // Jika password tidak diisi, update tanpa mengubah password
        $stmt = $pdo->prepare("
            UPDATE admin_berita
            SET nama_lengkap = ?, email = ?, foto = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $result = $stmt->execute([$nama_lengkap, $email, $foto, $admin_id]);
    }
    
    if ($result) {
        // Update session
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        $_SESSION['email'] = $email;
        $_SESSION['foto'] = $foto;
        
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Update Profil', 'Mengubah data profil', ?)
        ");
        $log_stmt->execute([$admin_id, $_SERVER['REMOTE_ADDR']]);
        
        $_SESSION['success'] = 'Profil berhasil diupdate!';
        header('Location: ../pages/profile.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal mengupdate profil!';
        header('Location: ../pages/profile.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Update Profile: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/profile.php');
    exit();
}
?>
