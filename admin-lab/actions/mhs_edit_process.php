<?php
/**
 * Proses Edit Anggota Mahasiswa
 * File: actions/mahasiswa_edit_process.php
 */

session_start();
require_once '../config/database.php';

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/mhs-edit.php');
    exit();
}

// Ambil ID mahasiswa
$id_mhs = $_POST['id'] ?? '';
if (empty($id_mhs)) {
    $_SESSION['error'] = 'ID mahasiswa tidak ditemukan!';
    header('Location: ../pages/anggota-list.php');
    exit();
}

// Ambil data input
$nama = trim($_POST['nama'] ?? '');
$nim  = trim($_POST['nim'] ?? '');
$prodi = trim($_POST['prodi'] ?? '');
$email = trim($_POST['email'] ?? '');
$tahun_lulus = $_POST['tahun_lulus'] ?? null;

$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$old_foto = $_POST['old_foto'] ?? 'default-avatar.png';

// Validasi wajib
if (empty($nama) || empty($nim) || empty($email) || empty($prodi)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib!';
    header("Location: ../pages/mhs-edit.php?id=$id_mhs");
    exit();
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid!';
    header("Location: ../pages/mhs-edit.php?id=$id_mhs");
    exit();
}

try {
    $pdo = getDBConnection();

    // Cek email & NIM yang sudah dipakai mahasiswa lain
    $check_stmt = $pdo->prepare("
        SELECT COUNT(*) AS total
        FROM mahasiswa
        WHERE (email = ? OR nim = ?) AND id != ?
    ");
    $check_stmt->execute([$email, $nim, $id_mhs]);

    if ($check_stmt->fetch()['total'] > 0) {
        $_SESSION['error'] = 'Email atau NIM sudah digunakan mahasiswa lain!';
        header("Location: ../pages/mhs-edit.php?id=$id_mhs");
        exit();
    }

    // ============================
    //  HANDLE FOTO
    // ============================
    $foto = $old_foto;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../assets/img/mahasiswa/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = 'Format foto harus JPG atau PNG!';
            header("Location: ../pages/mhs-edit.php?id=$id_mhs");
            exit();
        }

        if ($file_size > 1 * 1024 * 1024) {
            $_SESSION['error'] = 'Ukuran foto maksimal 1MB!';
            header("Location: ../pages/mhs-edit.php?id=$id_mhs");
            exit();
        }

        $new_file_name = 'mhs_' . time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Hapus foto lama jika bukan default
            if ($old_foto !== 'default-avatar.png' && file_exists($upload_dir . $old_foto)) {
                unlink($upload_dir . $old_foto);
            }
            $foto = $new_file_name;
        } else {
            $_SESSION['error'] = 'Gagal mengupload foto baru!';
            header("Location: ../pages/mhs-edit.php?id=$id_mhs");
            exit();
        }
    }

    // ============================
    //  HANDLE PASSWORD OPSIONAL
    // ============================
    $password_sql = '';
    $password_param = [];

    if (!empty($password)) {
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter!';
            header("Location: ../pages/mhs-edit.php?id=$id_mhs");
            exit();
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Konfirmasi password tidak sesuai!';
            header("Location: ../pages/mhs-edit.php?id=$id_mhs");
            exit();
        }

        $password_sql = ", password = ?";
        $password_param[] = password_hash($password, PASSWORD_DEFAULT);
    }

    // ============================
    //  UPDATE DATA MAHASISWA
    // ============================
    $sql = "
        UPDATE mahasiswa
        SET nama = ?, nim = ?, prodi = ?, email = ?, tahun_lulus = ?, 
            mahasiswa_profile = ?, updated_at = NOW()
            $password_sql
        WHERE id = ?
    ";

    $params = [$nama, $nim, $prodi, $email, $tahun_lulus, $foto];
    $params = array_merge($params, $password_param);
    $params[] = $id_mhs;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // ============================
    //  WRITE LOG
    // ============================
    if (isset($_SESSION['admin_id'])) {
        $log_stmt = $pdo->prepare("
            INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
            VALUES (?, 'Edit Mahasiswa', ?, ?)
        ");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            "Mengedit data mahasiswa: " . $nama,
            $_SERVER['REMOTE_ADDR']
        ]);
    }

    $_SESSION['success'] = 'Data mahasiswa berhasil diperbarui!';
    header('Location: ../pages/anggota-list.php?id=' . $id_mhs);
    exit();

} catch (PDOException $e) {
    error_log("Error Edit Mahasiswa: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem!';
    header("Location: ../pages/mhs-edit.php?id=$id_mhs");
    exit();
}
?>
