<?php
/**
 * Proses Edit Anggota Dosen
 * File: actions/dosen_edit_process.php
 */

session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/anggota-list.php');
    exit();
}

$id_dosen = $_POST['id'] ?? null;
if (!$id_dosen) {
    $_SESSION['error'] = 'ID dosen tidak valid!';
    header('Location: ../pages/anggota-list.php');
    exit();
}

$pdo = getDBConnection();

try {
    $pdo->beginTransaction();

    /* ================= AMBIL DATA LAMA ================= */
    $stmtOld = $pdo->prepare("SELECT dosen_profile FROM dosen WHERE id = ?");
    $stmtOld->execute([$id_dosen]);
    $old = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if (!$old) {
        throw new Exception('Data dosen tidak ditemukan');
    }

    /* ================= DATA FORM ================= */
    $nama   = trim($_POST['nama_lengkap']);
    $email  = trim($_POST['email']);
    $nip    = trim($_POST['nip'] ?? '');
    $nidn   = trim($_POST['nidn'] ?? '');
    $role   = trim($_POST['role_lab']);
    $prodi  = trim($_POST['prodi_dosen'] ?? '');
    $lokasi = trim($_POST['lokasi_dosen'] ?? '');
    $sinta  = trim($_POST['link_sinta'] ?? '');
    $linkedin = trim($_POST['link_linkedin'] ?? '');
    $scholar  = trim($_POST['link_google_scholar'] ?? '');

    if (!$nama || !$email || !$role) {
        throw new Exception('Nama, Email, dan Role wajib diisi');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format email tidak valid');
    }

    /* ================= CEK EMAIL DUPLIKAT ================= */
    $check = $pdo->prepare("
        SELECT COUNT(*) FROM dosen
        WHERE email = ? AND id <> ?
    ");
    $check->execute([$email, $id_dosen]);
    if ($check->fetchColumn() > 0) {
        throw new Exception('Email sudah digunakan dosen lain');
    }

    /* ================= FOTO ================= */
    $foto = $old['dosen_profile'];
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png'])) {
            throw new Exception('Format foto tidak valid');
        }
        if ($_FILES['foto']['size'] > 1024 * 1024) {
            throw new Exception('Ukuran foto maksimal 1MB');
        }

        $dir = '../assets/img/logo/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $newName = 'dosen_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $newName);

        if ($foto && file_exists($dir . $foto)) {
            unlink($dir . $foto);
        }
        $foto = $newName;
    }

    /* ================= UPDATE DOSEN ================= */
    $pdo->prepare("
        UPDATE dosen SET
            nama = ?, email = ?, nip = ?, nidn = ?, role_lab = ?,
            prodi_dosen = ?, lokasi_dosen = ?, link_sinta_dosen = ?,
            linkedin_dosen = ?, google_scholar_dosen = ?,
            dosen_profile = ?, updated_at = NOW()
        WHERE id = ?
    ")->execute([
        $nama, $email, $nip, $nidn, $role,
        $prodi, $lokasi, $sinta,
        $linkedin, $scholar,
        $foto, $id_dosen
    ]);

    /* ================= PENDIDIKAN ================= */
    $pdo->prepare("DELETE FROM pendidikan WHERE dosen_id = ?")
        ->execute([$id_dosen]);

    $jenjang = $_POST['jenjang'] ?? [];
    $jurusan = $_POST['jurusan'] ?? [];
    $universitas = $_POST['universitas'] ?? [];
    $tahun = $_POST['tahun_lulus'] ?? [];

    $stmtEdu = $pdo->prepare("
        INSERT INTO pendidikan (dosen_id, jenjang, jurusan, universitas, tahun_lulus)
        VALUES (?, ?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($jenjang); $i++) {
        if (!empty($jenjang[$i])) {
            $stmtEdu->execute([
                $id_dosen,
                $jenjang[$i],
                $jurusan[$i],
                $universitas[$i],
                $tahun[$i]
            ]);
        }
    }

    /* ================= SERTIFIKAT ================= */
    $pdo->prepare("DELETE FROM sertifikat WHERE dosen_id = ?")
        ->execute([$id_dosen]);

    $nama_sertifikat = $_POST['nama_sertifikat'] ?? [];
    $tahun = $_POST['tahun'] ?? [];
    $penyelenggara = $_POST['penyelenggara'] ?? [];

    $stmtCert = $pdo->prepare("
        INSERT INTO sertifikat (dosen_id, nama_sertifikat, tahun, penyelenggara)
        VALUES (?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($nama_sertifikat); $i++) {
        if (!empty($nama_sertifikat[$i])) {
            $stmtCert->execute([
                $id_dosen,
                $nama_sertifikat[$i],
                $tahun[$i] ?? null,
                $penyelenggara[$i] ?? null
            ]);
        }
    }

    $pdo->commit();

    $_SESSION['success'] = 'Data dosen berhasil diperbarui';
    header('Location: ../pages/anggota-list.php');
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../pages/dosen-edit.php?id=$id_dosen");
    exit();
}
