<?php
/**
 * Proses Edit Anggota Dosen (Tanpa Password)
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

    /* =========================
     * AMBIL DATA DOSEN LAMA
     * ========================= */
    $stmtOld = $pdo->prepare("
        SELECT dosen_profile, linkedin_dosen, google_scholar_dosen
        FROM dosen WHERE id = ?
    ");
    $stmtOld->execute([$id_dosen]);
    $old = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if (!$old) {
        throw new Exception('Data dosen tidak ditemukan');
    }

    /* =========================
     * DATA FORM
     * ========================= */
    $nama   = trim($_POST['nama_lengkap'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $lokasi = trim($_POST['lokasi_dosen'] ?? '');
    $sinta  = trim($_POST['link_sinta'] ?? '');
    $bio    = trim($_POST['biografi_dosen'] ?? '');

    // ⬇️ PENTING: JANGAN TIMPA DENGAN STRING KOSONG
    $linkedin = !empty($_POST['link_linkedin'])
        ? trim($_POST['link_linkedin'])
        : $old['linkedin_dosen'];

    $scholar = !empty($_POST['link_google_scholar'])
        ? trim($_POST['link_google_scholar'])
        : $old['google_scholar_dosen'];

    if (empty($nama) || empty($email)) {
        throw new Exception('Nama dan email wajib diisi');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format email tidak valid');
    }

    /* =========================
     * CEK EMAIL DUPLIKAT
     * ========================= */
    $stmtCheck = $pdo->prepare("
        SELECT COUNT(*) FROM dosen
        WHERE email = ? AND id <> ?
    ");
    $stmtCheck->execute([$email, $id_dosen]);
    if ($stmtCheck->fetchColumn() > 0) {
        throw new Exception('Email sudah digunakan dosen lain');
    }

    /* =========================
     * HANDLE FOTO
     * ========================= */
    $foto = $old['dosen_profile'];

    if (!empty($_FILES['foto']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            throw new Exception('Format foto tidak valid');
        }

        if ($_FILES['foto']['size'] > 1024 * 1024) {
            throw new Exception('Ukuran foto maksimal 1MB');
        }

        $dir = '../assets/img/logo/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $newName = 'dosen_' . uniqid() . '.' . $ext;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $newName)) {
            throw new Exception('Upload foto gagal');
        }

        if ($foto && file_exists($dir . $foto)) {
            unlink($dir . $foto);
        }

        $foto = $newName;
    }

    /* =========================
     * UPDATE DOSEN
     * ========================= */
    $sql = "
        UPDATE dosen SET
            nama = ?,
            email = ?,
            lokasi_dosen = ?,
            link_sinta_dosen = ?,
            biografi_dosen = ?,
            linkedin_dosen = ?,
            google_scholar_dosen = ?,
            dosen_profile = ?,
            updated_at = NOW()
        WHERE id = ?
    ";

    $params = [
        $nama,
        $email,
        $lokasi,
        $sinta,
        $bio,
        $linkedin,
        $scholar,
        $foto,
        $id_dosen
    ];

    $pdo->prepare($sql)->execute($params);

    /* =========================
     * UPDATE SERTIFIKAT (AMAN)
     * ========================= */
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
        if (!empty(trim($nama_sertifikat[$i]))) {
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
    error_log('Edit Dosen Error: ' . $e->getMessage());

    $_SESSION['error'] = $e->getMessage();
    header("Location: ../pages/dosen-edit.php?id=$id_dosen");
    exit();
}
