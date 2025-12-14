<?php
/**
 * Proses Tambah Anggota Dosen
 * File: actions/dosen_add_process.php
 */

session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/anggota-add.php');
    exit();
}

// ================== AMBIL DATA FORM ==================
$nama_lengkap        = trim($_POST['nama_lengkap'] ?? '');
$email               = trim($_POST['email'] ?? '');
$lokasi_dosen        = trim($_POST['lokasi_dosen'] ?? '');
$link_sinta          = trim($_POST['link_sinta'] ?? '');
$biografi             = trim($_POST['biografi'] ?? '');
$link_google_scholar = trim($_POST['link_google_scholar'] ?? '');
$link_linkedin       = trim($_POST['link_linkedin'] ?? '');

// ================== VALIDASI EMAIL ==================
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

// ================== UPLOAD FOTO ==================
$foto = 'default-avatar.png';
$upload_dir = '../assets/img/';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_tmp  = $_FILES['foto']['tmp_name'];
    $file_name = $_FILES['foto']['name'];
    $file_size = $_FILES['foto']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png'];

    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file harus JPG atau PNG!';
        header('Location: ../pages/anggota-add.php');
        exit();
    }

    if ($file_size > 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran foto maksimal 1MB!';
        header('Location: ../pages/anggota-add.php');
        exit();
    }

    $foto = 'dosen_' . time() . '_' . uniqid() . '.' . $file_ext;
    move_uploaded_file($file_tmp, $upload_dir . $foto);
}

try {
    $pdo = getDBConnection();

    // ================== CEK EMAIL ==================
    $check = $pdo->prepare("SELECT COUNT(*) FROM dosen WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetchColumn() > 0) {
        if ($foto !== 'default-avatar.png') {
            unlink($upload_dir . $foto);
        }
        $_SESSION['error'] = 'Email sudah terdaftar!';
        header('Location: ../pages/anggota-add.php');
        exit();
    }

    // ================== INSERT DOSEN ==================
    $stmt = $pdo->prepare("
        INSERT INTO dosen (
            nama,
            email,
            lokasi_dosen,
            link_sinta_dosen,
            biografi_dosen,
            dosen_profile,
            google_scholar_dosen,
            linkedin_dosen,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");

    $stmt->execute([
        $nama_lengkap,
        $email,
        $lokasi_dosen,
        $link_sinta,
        $biografi,
        $foto,
        $link_google_scholar,
        $link_linkedin
    ]);

    $id_dosen = $pdo->lastInsertId();

    // ================== PENDIDIKAN ==================
    $jenjang      = $_POST['jenjang'] ?? [];
    $jurusan      = $_POST['jurusan'] ?? [];
    $universitas  = $_POST['universitas'] ?? [];
    $tahun_lulus  = $_POST['tahun_lulus'] ?? [];

    $stmtEdu = $pdo->prepare("
        INSERT INTO pendidikan (dosen_id, jenjang, jurusan, universitas, tahun_lulus)
        VALUES (?, ?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($jenjang); $i++) {
        if ($jenjang[$i]) {
            $stmtEdu->execute([
                $id_dosen,
                $jenjang[$i],
                $jurusan[$i],
                $universitas[$i],
                $tahun_lulus[$i]
            ]);
        }
    }

    // ================== SERTIFIKAT ==================
    $nama_sertifikasi  = $_POST['nama_sertifikasi'] ?? [];
    $tahun_sertifikasi = $_POST['tahun_sertifikasi'] ?? [];
    $penerbit          = $_POST['penerbit'] ?? [];

    $stmtCert = $pdo->prepare("
        INSERT INTO sertifikat (dosen_id, nama_sertifikat, tahun, penyelenggara)
        VALUES (?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($nama_sertifikasi); $i++) {
        if ($nama_sertifikasi[$i]) {
            $stmtCert->execute([
                $id_dosen,
                $nama_sertifikasi[$i],
                $tahun_sertifikasi[$i] ?? null,
                $penerbit[$i] ?? null
            ]);
        }
    }

    // ================== LOG ADMIN ==================
    if (isset($_SESSION['admin_id'])) {
        $log = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
            VALUES (?, 'Tambah Dosen', ?, ?)
        ");
        $log->execute([
            $_SESSION['admin_id'],
            "Menambahkan dosen: $nama_lengkap",
            $_SERVER['REMOTE_ADDR']
        ]);
    }

    $_SESSION['success'] = "Dosen <b>$nama_lengkap</b> berhasil ditambahkan!";
    header('Location: ../pages/anggota-list.php');
    exit();

} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem!';
    header('Location: ../pages/anggota-add.php');
    exit();
}
