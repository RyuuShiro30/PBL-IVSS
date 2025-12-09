<?php
/**
 * Proses Tambah Admin (Khusus Super Admin)
 * File: actions/admin_add_process.php
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
    header('Location: ../pages/admin-add.php');
    exit();
}

// Ambil data dari form
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = $_POST['role'] ?? 'admin';
$status = $_POST['status'] ?? 'aktif';

// Validasi input
if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($role)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/admin-add.php');
    exit();
}

// Validasi password
if (strlen($password) < 6) {
    $_SESSION['error'] = 'Password minimal 6 karakter!';
    header('Location: ../pages/admin-add.php');
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Password dan konfirmasi password tidak sama!';
    header('Location: ../pages/admin-add.php');
    exit();
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header('Location: ../pages/admin-add.php');
    exit();
}

// Handle upload foto profil
$foto = 'default-avatar.png';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../assets/img/';
    
    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_tmp = $_FILES['foto']['tmp_name'];
    $file_name = $_FILES['foto']['name'];
    $file_size = $_FILES['foto']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    
    // Validasi ekstensi file
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file tidak diizinkan! Hanya JPG, PNG yang diperbolehkan.';
        header('Location: ../pages/admin-add.php');
        exit();
    }
    
    // Validasi ukuran file (max 1MB)
    if ($file_size > 1 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 1MB.';
        header('Location: ../pages/admin-add.php');
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = 'profile_' . time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $foto = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload foto profil!';
        header('Location: ../pages/admin-add.php');
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // Cek apakah username sudah ada
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM admin_lab WHERE username = ?");
    $check_stmt->execute([$username]);
    if ($check_stmt->fetch()['total'] > 0) {
        $_SESSION['error'] = 'Username sudah digunakan!';
        header('Location: ../pages/admin-add.php');
        exit();
    }
    
    // Cek apakah email sudah ada
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM admin_lab WHERE email = ?");
    $check_stmt->execute([$email]);
    if ($check_stmt->fetch()['total'] > 0) {
        $_SESSION['error'] = 'Email sudah digunakan!';
        header('Location: ../pages/admin-add.php');
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert admin ke database
    $stmt = $pdo->prepare("
        INSERT INTO admin_lab (username, password, nama_lengkap, email, role, foto, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $result = $stmt->execute([
        $username,
        $hashed_password,
        $nama_lengkap,
        $email,
        $role,
        $foto,
        $status
    ]);
    
    if ($result) {
        // Catat log aktivitas
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
            VALUES (?, 'Tambah Admin', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Menambahkan admin baru: " . $nama_lengkap . " (" . $username . ")",
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Admin baru berhasil ditambahkan!';
        header('Location: ../pages/admin-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan admin!';
        header('Location: ../pages/admin-add.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Add Admin: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/admin-add.php');
    exit();
}
?>
