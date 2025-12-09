<?php
/**
 * Proses Edit Anggota Dosen
 * File: actions/dosen_edit_process.php
 */

session_start();
require_once '../config/database.php';

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/dosen-edit.php');
    exit();
}

// Ambil ID dosen
$id_dosen = $_POST['id'] ?? '';
if (empty($id_dosen)) {
    $_SESSION['error'] = 'ID dosen tidak ditemukan!';
    header('Location: ../pages/dosen-list.php');
    exit();
}

// Ambil data form
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email = trim($_POST['email'] ?? '');
$lokasi_dosen = trim($_POST['lokasi_dosen'] ?? '');
$link_sinta = trim($_POST['link_sinta'] ?? '');
$biografi = trim($_POST['biografi_dosen'] ?? '');

$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi wajib
if (empty($nama_lengkap) || empty($email)) {
    $_SESSION['error'] = 'Nama lengkap dan email wajib diisi!';
    header("Location: ../pages/dosen-edit.php?id=$id_dosen");
    exit();
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header("Location: ../pages/dosen-edit.php?id=$id_dosen");
    exit();
}

try {
    $pdo = getDBConnection();

    // Cek email apakah digunakan dosen lain
    $check_stmt = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM dosen
        WHERE email = ? AND id != ?
    ");
    $check_stmt->execute([$email, $id_dosen]);

    if ($check_stmt->fetch()['total'] > 0) {
        $_SESSION['error'] = 'Email sudah digunakan oleh dosen lain!';
        header("Location: ../pages/dosen-edit.php?id=$id_dosen");
        exit();
    }

    // Ambil data foto lama
    $old_stmt = $pdo->prepare("SELECT dosen_profile FROM dosen WHERE id = ?");
    $old_stmt->execute([$id_dosen]);
    $old_foto = $old_stmt->fetchColumn();

    // ============================
    //  HANDLE UPLOAD FOTO BARU
    // ============================
    $foto = $old_foto;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/img/logo/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg','jpeg','png'];

        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = 'Format foto hanya boleh JPG atau PNG!';
            header("Location: ../pages/dosen-edit.php?id=$id_dosen");
            exit();
        }

        if ($file_size > 1 * 1024 * 1024) {
            $_SESSION['error'] = 'Ukuran foto maksimal 1MB!';
            header("Location: ../pages/dosen-edit.php?id=$id_dosen");
            exit();
        }

        $new_file_name = 'dosen_' . time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $foto = $new_file_name;

            // Hapus foto lama jika bukan default
            if ($old_foto !== 'default-avatar.png' && file_exists($upload_dir . $old_foto)) {
                unlink($upload_dir . $old_foto);
            }
        } else {
            $_SESSION['error'] = 'Gagal mengupload foto baru!';
            header("Location: ../pages/dosen-edit.php?id=$id_dosen");
            exit();
        }
    }

    // ============================
    //  HANDLE PASSWORD (Opsional)
    // ============================
    $password_sql = '';
    $password_param = [];

    if (!empty($password)) {
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter!';
            header("Location: ../pages/dosen-edit.php?id=$id_dosen");
            exit();
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Konfirmasi password tidak sama!';
            header("Location: ../pages/dosen-edit.php?id=$id_dosen");
            exit();
        }

        $password_sql = ", password = ?";
        $password_param[] = password_hash($password, PASSWORD_DEFAULT);
    }

    // ============================
    //  UPDATE TABEL DOSEN
    // ============================
    $sql = "
        UPDATE dosen
        SET nama = ?, email = ?, lokasi_dosen = ?, link_sinta_dosen = ?, 
            biografi_dosen = ?, dosen_profile = ?, updated_at = NOW()
            $password_sql
        WHERE id = ?
    ";

    $params = [
        $nama_lengkap, $email, $lokasi_dosen, $link_sinta,
        $biografi, $foto
    ];

    $params = array_merge($params, $password_param);
    $params[] = $id_dosen;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // ============================
    //  UPDATE PENDIDIKAN
    // ============================
    $pdo->prepare("DELETE FROM pendidikan WHERE dosen_id = ?")->execute([$id_dosen]);

    $jenjang = $_POST['jenjang'] ?? [];
    $jurusan = $_POST['jurusan'] ?? [];
    $universitas = $_POST['universitas'] ?? [];
    $tahun_lulus = $_POST['tahun_lulus'] ?? [];

    $stmtEdu = $pdo->prepare("
        INSERT INTO pendidikan (dosen_id, jenjang, jurusan, universitas, tahun_lulus)
        VALUES (?, ?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($jenjang); $i++) {
        if (!empty($jenjang[$i]) && !empty($jurusan[$i]) &&
            !empty($universitas[$i]) && !empty($tahun_lulus[$i])) {

            $stmtEdu->execute([
                $id_dosen,
                $jenjang[$i],
                $jurusan[$i],
                $universitas[$i],
                $tahun_lulus[$i]
            ]);
        }
    }

    // ============================
    //  UPDATE SERTIFIKAT
    // ============================
    $pdo->prepare("DELETE FROM sertifikat WHERE dosen_id = ?")->execute([$id_dosen]);

    $nama_sertifikat = $_POST['nama_sertifikat'] ?? [];
    $tahun = $_POST['tahun'] ?? [];
    $penyelenggara = $_POST['penyelenggara'] ?? [];

    $stmtCert = $pdo->prepare("
        INSERT INTO sertifikat (dosen_id, nama_sertifikat, tahun, penyelenggara)
        VALUES (?, ?, ?, ?)
    ");

for ($i = 0; $i < count($nama_sertifikat); $i++) {

    // Skip kalau nama sertifikat kosong
    if (empty($nama_sertifikat[$i])) {
        continue;
    }

    $stmtCert->execute([
        $id_dosen,
        $nama_sertifikat[$i],
        $tahun[$i] ?? null,
        $penyelenggara[$i] ?? null,
    ]);
}

    // ============================
    //  WRITE LOG
    // ============================
    if (isset($_SESSION['admin_id'])) {
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
            VALUES (?, 'Edit Dosen', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Mengedit data dosen: " . $nama_lengkap,
            $_SERVER['REMOTE_ADDR']
        ]);
    }

    $_SESSION['success'] = 'Data dosen berhasil diperbarui!';
    header('Location: ../pages/anggota-list.php');
    exit();

} catch (PDOException $e) {
    error_log("Error Edit Dosen: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem!';
    header("Location: ../pages/dosen-edit.php?id=$id_dosen");
    exit();
}
?>
