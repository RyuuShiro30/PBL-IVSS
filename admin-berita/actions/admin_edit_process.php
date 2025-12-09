<?php
/**
 * Proses Edit Admin (Khusus Super Admin)
 * File: actions/admin_edit_process.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login dan role super admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'superadmin') {
    $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini!';
    header('Location: ../pages/dashboard.php');
    exit();
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/admin-list.php');
    exit();
}

// Ambil data dari form
$id = $_POST['id'] ?? 0;
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['role'] ?? 'admin';
$status = $_POST['status'] ?? 'aktif';
$old_foto = $_POST['old_foto'] ?? 'default-avatar.png';

// Password (opsional untuk update)
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi input
if (!$id || empty($nama_lengkap) || empty($email) || empty($role)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/admin-edit.php?id=' . $id);
    exit();
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header('Location: ../pages/admin-edit.php?id=' . $id);
    exit();
}

// Validasi password jika diisi
if (!empty($password)) {
    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password minimal 6 karakter!';
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Password dan konfirmasi password tidak sama!';
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }
}

// Handle upload foto profil baru
$foto = $old_foto;
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
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }
    
    // Validasi ukuran file (max 1MB)
    if ($file_size > 1 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 1MB.';
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = 'profile_' . time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Hapus foto lama jika bukan default
        if ($old_foto !== 'default-avatar.png' && file_exists($upload_dir . $old_foto)) {
            unlink($upload_dir . $old_foto);
        }
        $foto = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload foto profil!';
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // Cek apakah email sudah digunakan admin lain
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM admin_berita WHERE email = ? AND id != ?");
    $check_stmt->execute([$email, $id]);
    if ($check_stmt->fetch()['total'] > 0) {
        $_SESSION['error'] = 'Email sudah digunakan admin lain!';
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }
    
    // Update admin di database
    if (!empty($password)) {
        // Jika password diisi, update dengan password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            UPDATE admin_berita
            SET nama_lengkap = ?, email = ?, password = ?, role = ?, foto = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $result = $stmt->execute([$nama_lengkap, $email, $hashed_password, $role, $foto, $status, $id]);
    } else {
        // Jika password tidak diisi, update tanpa mengubah password
        $stmt = $pdo->prepare("
            UPDATE admin_berita
            SET nama_lengkap = ?, email = ?, role = ?, foto = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $result = $stmt->execute([$nama_lengkap, $email, $role, $foto, $status, $id]);
    }
    
    if ($result) {
        // Update session jika admin mengedit dirinya sendiri
        if ($id == $_SESSION['admin_id']) {
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['foto'] = $foto;
        }
        
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_berita (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Edit Admin', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Mengedit data admin: " . $nama_lengkap,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Data admin berhasil diupdate!';
        header('Location: ../pages/admin-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal mengupdate data admin!';
        header('Location: ../pages/admin-edit.php?id=' . $id);
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Edit Admin: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/admin-edit.php?id=' . $id);
    exit();
}
?>
