<?php
/**
 * Action untuk update profil admin
 * File: actions/profile_update.php
 */

require __DIR__ . '/../config.php';
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Cek apakah request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../pages/profile.php');
    exit();
}

try {
    // Validasi input
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Validasi nama lengkap
    if (empty($nama_lengkap)) {
        throw new Exception('Nama lengkap tidak boleh kosong');
    }
    
    // Validasi email
    if (empty($email)) {
        throw new Exception('Email tidak boleh kosong');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format email tidak valid');
    }
    
    // Cek apakah email sudah digunakan oleh user lain
    $stmt = $pdo->prepare("SELECT id FROM kepalalab WHERE email = :email AND id != :id");
    $stmt->execute([
        'email' => $email,
        'id' => $_SESSION['admin_id']
    ]);
    
    if ($stmt->fetch()) {
        throw new Exception('Email sudah digunakan oleh user lain');
    }
    
    // Validasi password jika diisi
    $updatePassword = false;
    if (!empty($password)) {
        if (strlen($password) < 6) {
            throw new Exception('Password minimal 6 karakter');
        }
        
        if ($password !== $confirm_password) {
            throw new Exception('Password dan konfirmasi password tidak sama');
        }
        
        $updatePassword = true;
    }
    
    // Ambil data admin saat ini
    $stmt = $pdo->prepare("SELECT * FROM kepalalab WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['admin_id']]);
    $current_admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$current_admin) {
        throw new Exception('Data admin tidak ditemukan');
    }
    
    // Handle upload foto
    $fotoName = $current_admin['foto'];
    $fotoUpdated = false;
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        
        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $foto['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowedTypes)) {
            throw new Exception('Format foto harus JPG atau PNG');
        }
        
        // Validasi ukuran file (max 1MB)
        if ($foto['size'] > 1 * 1024 * 1024) {
            throw new Exception('Ukuran foto maksimal 1MB');
        }
        
        // Generate nama file unik
        $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $fotoName = 'admin_' . $_SESSION['admin_id'] . '_' . time() . '.' . $extension;
        
        // Path upload
        $uploadPath = __DIR__ . '/../assets/img/' . $fotoName;
        
        // Upload file
        if (!move_uploaded_file($foto['tmp_name'], $uploadPath)) {
            throw new Exception('Gagal mengupload foto');
        }
        
        // Hapus foto lama jika bukan default
        if ($current_admin['foto'] && $current_admin['foto'] !== 'default-avatar.png') {
            $oldPath = __DIR__ . '/../assets/img/' . $current_admin['foto'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        
        $fotoUpdated = true;
    }
    
    // Mulai transaksi
    $pdo->beginTransaction();
    
    // Update data profil
    if ($updatePassword && $fotoUpdated) {
        // Update semua: nama, email, password, foto
        $sql = "UPDATE kepalalab 
                SET nama_lengkap = :nama_lengkap, 
                    email = :email, 
                    password = :password,
                    foto = :foto,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'password' => $password,
            'foto' => $fotoName,
            'id' => $_SESSION['admin_id']
        ]);
    } elseif ($updatePassword) {
        // Update nama, email, password (tanpa foto)
        $sql = "UPDATE kepalalab 
                SET nama_lengkap = :nama_lengkap, 
                    email = :email, 
                    password = :password,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'password' => $password,
            'id' => $_SESSION['admin_id']
        ]);
    } elseif ($fotoUpdated) {
        // Update nama, email, foto (tanpa password)
        $sql = "UPDATE kepalalab 
                SET nama_lengkap = :nama_lengkap, 
                    email = :email,
                    foto = :foto,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'foto' => $fotoName,
            'id' => $_SESSION['admin_id']
        ]);
    } else {
        // Update nama dan email saja
        $sql = "UPDATE kepalalab 
                SET nama_lengkap = :nama_lengkap, 
                    email = :email,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'id' => $_SESSION['admin_id']
        ]);
    }
    
    // Commit transaksi
    $pdo->commit();
    
    // Update session data
    $_SESSION['nama_lengkap'] = $nama_lengkap;
    $_SESSION['email'] = $email;
    if ($fotoUpdated) {
        $_SESSION['foto'] = $fotoName;
    }
    
    // Set session success message
    $_SESSION['success'] = 'Profil berhasil diupdate';
    
    // Redirect ke halaman profile
    header('Location: ../pages/profile.php');
    exit();
    
} catch (Exception $e) {
    // Rollback jika ada error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Hapus file yang sudah diupload jika ada error
    if (isset($fotoUpdated) && $fotoUpdated && isset($fotoName)) {
        $uploadPath = __DIR__ . '/../assets/img/' . $fotoName;
        if (file_exists($uploadPath)) {
            unlink($uploadPath);
        }
    }
    
    // Log error untuk debugging
    error_log("Error Update Profile: " . $e->getMessage());
    
    // Set session error message
    $_SESSION['error'] = $e->getMessage();
    
    // Redirect ke halaman profile
    header('Location: ../pages/profile.php');
    exit();
}