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

/* ================== AMBIL DATA FORM ================== */
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email        = trim($_POST['email'] ?? '');
$nip          = trim($_POST['nip'] ?? '');
$nidn         = trim($_POST['nidn'] ?? '');
$role         = trim($_POST['role_lab'] ?? '');
$prodi_dosen  = trim($_POST['prodi_dosen'] ?? '');
$lokasi_dosen = trim($_POST['lokasi_dosen'] ?? '');
$link_sinta   = trim($_POST['link_sinta'] ?? '');
$link_google  = trim($_POST['link_google_scholar'] ?? '');
$link_linkedin= trim($_POST['link_linkedin'] ?? '');

/* ================== VALIDASI ================== */
if ($nama_lengkap === '' || $email === '' || $role === '') {
    $_SESSION['error'] = 'Nama, Email, dan Role wajib diisi!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header('Location: ../pages/anggota-add.php');
    exit();
}

/* ================== UPLOAD FOTO ================== */
$foto = 'default-avatar.png';
$upload_dir = '../assets/img/logo/';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $tmp  = $_FILES['foto']['tmp_name'];
    $name = $_FILES['foto']['name'];
    $size = $_FILES['foto']['size'];
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png'];

    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = 'Format foto harus JPG / PNG';
        header('Location: ../pages/anggota-add.php');
        exit();
    }

    if ($size > 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran foto maksimal 1MB';
        header('Location: ../pages/anggota-add.php');
        exit();
    }

    $foto = 'dosen_' . time() . '_' . uniqid() . '.' . $ext;
    move_uploaded_file($tmp, $upload_dir . $foto);
}

try {
    $pdo = getDBConnection();

    /* ================== CEK EMAIL ================== */
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

    /* ================== INSERT DOSEN ================== */
    $stmt = $pdo->prepare("
        INSERT INTO dosen (
            nama,
            email,
            nip,
            nidn,
            role_lab,
            prodi_dosen,
            lokasi_dosen,
            link_sinta_dosen,
            google_scholar_dosen,
            linkedin_dosen,
            dosen_profile,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");

    $stmt->execute([
        $nama_lengkap,
        $email,
        $nip ?: null,
        $nidn ?: null,
        $role,
        $prodi_dosen ?: null,
        $lokasi_dosen ?: null,
        $link_sinta ?: null,
        $link_google ?: null,
        $link_linkedin ?: null,
        $foto
    ]);

    $id_dosen = $pdo->lastInsertId();

    /* ================== PENDIDIKAN ================== */
    $stmtEdu = $pdo->prepare("
        INSERT INTO pendidikan (dosen_id, jenjang, jurusan, universitas, tahun_lulus)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($_POST['jenjang'] ?? [] as $i => $jenjang) {
        if ($jenjang) {
            $stmtEdu->execute([
                $id_dosen,
                $jenjang,
                $_POST['jurusan'][$i] ?? null,
                $_POST['universitas'][$i] ?? null,
                $_POST['tahun_lulus'][$i] ?? null
            ]);
        }
    }

    /* ================== SERTIFIKAT ================== */
    $stmtCert = $pdo->prepare("
        INSERT INTO sertifikat (dosen_id, nama_sertifikat, tahun, penyelenggara)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($_POST['nama_sertifikasi'] ?? [] as $i => $nama) {
        if ($nama) {
            $stmtCert->execute([
                $id_dosen,
                $nama,
                $_POST['tahun_sertifikasi'][$i] ?? null,
                $_POST['penerbit'][$i] ?? null
            ]);
        }
    }

    /* ================== LOG ================== */
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
