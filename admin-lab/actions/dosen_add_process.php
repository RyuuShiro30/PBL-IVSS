<?php
/**
 * Proses Tambah Anggota Dosen
 * File: actions/dosen_add_process.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/anggota-add.php');
    exit();
}

// Ambil data dari form
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email = trim($_POST['email'] ?? '');
$lokasi_dosen = trim($_POST['lokasi_dosen'] ?? '');
$link_sinta = trim($_POST['link_sinta'] ?? '');
$biografi = trim($_POST['biografi'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// --- Validasi Input Wajib ---
if (empty($nama_lengkap) || empty($email) || empty($password)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi (Nama, Email, Password)!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

// --- Validasi Password ---
if (strlen($password) < 6) {
    $_SESSION['error'] = 'Password minimal 6 karakter!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Password dan konfirmasi password tidak sama!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

// --- Validasi Email ---
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

// --- Handle Upload Foto Profil ---
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
        header('Location: ../pages/anggota-add.php');
        exit();
    }
    
    // Validasi ukuran file (max 1MB)
    if ($file_size > 1 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 1MB.';
        header('Location: ../pages/anggota-add.php');
        exit();
    }
    
    // Generate nama file unik
    $new_file_name = 'dosen_' . time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $foto = $new_file_name;
    } else {
        $_SESSION['error'] = 'Gagal mengupload foto profil!';
        header('Location: ../pages/anggota-add.php');
        exit();
    }
}

try {
    $pdo = getDBConnection();
    
    // --- Cek apakah email sudah ada di tabel dosen_lab (diasumsikan Email sebagai ID unik Dosen) ---
    $check_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM dosen WHERE email = ?");
    $check_stmt->execute([$email]);
    if ($check_stmt->fetch()['total'] > 0) {
        // Jika ada konflik email, hapus foto yang baru saja diunggah untuk menghindari sampah file
        if ($foto !== 'default-avatar.png' && file_exists($upload_dir . $foto)) {
            unlink($upload_dir . $foto);
        }
        
        $_SESSION['error'] = 'Email sudah digunakan oleh anggota lain!';
        header('Location: ../pages/anggota-add.php');
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO dosen (
            nama, email, password, lokasi_dosen, link_sinta_dosen, biografi_dosen, dosen_profile, created_at, updated_at
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $result = $stmt->execute([
        $nama_lengkap,
        $email,
        $hashed_password,
        $lokasi_dosen,
        $link_sinta,
        $biografi,
        $foto,
    ]);
    
    if ($result) {

        $id_dosen = $pdo->lastInsertId();

        $jenjang = $_POST['jenjang'] ?? [];
        $jurusan = $_POST['jurusan'] ?? [];
        $universitas = $_POST['universitas'] ?? [];
        $tahun_lulus = $_POST['tahun_lulus'] ?? [];

        $stmtEdu = $pdo->prepare("
            INSERT INTO pendidikan (dosen_id, jenjang, jurusan, universitas, tahun_lulus)
            VALUES (?, ?, ?, ?, ?)
        ");

        for ($i = 0; $i < count($jenjang); $i++) {
            if (
                !empty($jenjang[$i]) &&
                !empty($jurusan[$i]) &&
                !empty($universitas[$i]) &&
                !empty($tahun_lulus[$i])
            ) {
                $stmtEdu->execute([
                    $id_dosen,
                    $jenjang[$i],
                    $jurusan[$i],
                    $universitas[$i],
                    $tahun_lulus[$i]
                ]);
            }
        }

        $nama_sertifikasi = $_POST['nama_sertifikasi'] ?? [];
        $tahun_sertifikasi = $_POST['tahun_sertifikasi'] ?? [];
        $penerbit = $_POST['penerbit'] ?? [];

        $stmtCert = $pdo->prepare("
            INSERT INTO sertifikat (dosen_id, nama_sertifikat, tahun, penyelenggara)
            VALUES (?, ?, ?, ?)
        ");

        for ($i = 0; $i < count($nama_sertifikasi); $i++) {
            if (!empty($nama_sertifikasi[$i])) {
                $stmtCert->execute([
                    $id_dosen,
                    $nama_sertifikasi[$i],
                    $tahun_sertifikasi[$i] ?? null,
                    $penerbit[$i] ?? null
                ]);
            }
        }


        if (isset($_SESSION['admin_id'])) {
           $log_stmt = $pdo->prepare("
                INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
                VALUES (?, 'Tambah Dosen', ?, ?)
            ");
            $log_stmt->execute([
                $_SESSION['admin_id'],
                "Menambahkan dosen baru: " . $nama_lengkap,
                $_SERVER['REMOTE_ADDR']
            ]);

        }
        
        $_SESSION['success'] = 'Anggota Dosen **' . htmlspecialchars($nama_lengkap) . '** berhasil ditambahkan!';
        header('Location: ../pages/anggota-list.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan anggota dosen ke database!';
        header('Location: ../pages/anggota-add.php');
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error Add Dosen: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem saat menyimpan data. Silakan coba lagi.';
    header('Location: ../pages/anggota-add.php');
    exit();
}
?>