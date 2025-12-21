<?php
/**
 * Proses Edit Galeri
 * File: actions/galeri_edit_process.php
 */

session_start();
require_once '../config/database.php';

// ===============================
// CEK LOGIN
// ===============================
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// ===============================
// CEK METHOD
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/galeri-list.php');
    exit();
}

// ===============================
// AMBIL DATA FORM (WAJIB DI ATAS)
// ===============================
$id = $_POST['id'] ?? 0;
$admin_id = $_SESSION['admin_id'];
$deskripsi_galeri = trim($_POST['deskripsi_galeri'] ?? '');

// ===============================
// VALIDASI INPUT
// ===============================
if (!$id || empty($deskripsi_galeri)) {
    $_SESSION['error'] = 'Harap isi semua field yang wajib diisi!';
    header('Location: ../pages/galeri-edit.php?id=' . $id);
    exit();
}

// ===============================
// AMBIL GAMBAR LAMA
// ===============================
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT gambar_galeri FROM galeri WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    $_SESSION['error'] = 'Data galeri tidak ditemukan!';
    header('Location: ../pages/galeri-list.php');
    exit();
}

$old_gambar_galeri = $data['gambar_galeri'];
$gambar_galeri = $old_gambar_galeri;

// ===============================
// HANDLE UPLOAD GAMBAR BARU
// ===============================
if (isset($_FILES['gambar_galeri']) && $_FILES['gambar_galeri']['error'] === UPLOAD_ERR_OK) {

    $upload_dir = '../assets/img/galeri/';

    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_tmp  = $_FILES['gambar_galeri']['tmp_name'];
    $file_name = $_FILES['gambar_galeri']['name'];
    $file_size = $_FILES['gambar_galeri']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png'];

    // Validasi ekstensi
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format file tidak diizinkan! (JPG, JPEG, PNG)';
        header('Location: ../pages/galeri-edit.php?id=' . $id);
        exit();
    }

    // Validasi ukuran (2MB)
    if ($file_size > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran file terlalu besar! Maksimal 2MB.';
        header('Location: ../pages/galeri-edit.php?id=' . $id);
        exit();
    }

    // Nama file unik
    $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;

    // Upload
    if (move_uploaded_file($file_tmp, $upload_path)) {

        // Hapus gambar lama
        if ($old_gambar_galeri && file_exists($upload_dir . $old_gambar_galeri)) {
            unlink($upload_dir . $old_gambar_galeri);
        }

        $gambar_galeri = $new_file_name;

    } else {
        $_SESSION['error'] = 'Gagal mengupload gambar!';
        header('Location: ../pages/galeri-edit.php?id=' . $id);
        exit();
    }
}

// ===============================
// UPDATE DATABASE
// ===============================
try {
    $stmt = $pdo->prepare("
        UPDATE galeri 
        SET deskripsi_galeri = ?, 
            gambar_galeri = ?, 
            updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $deskripsi_galeri,
        $gambar_galeri,
        $id
    ]);

    // ===============================
    // LOG AKTIVITAS
    // ===============================
    $log_stmt = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address) 
        VALUES (?, ?, ?, ?)
    ");
    $log_stmt->execute([
        $admin_id,
        'Edit Galeri',
        'Mengedit galeri: ' . $deskripsi_galeri,
        $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'
    ]);

    $_SESSION['success'] = 'Galeri berhasil diperbarui!';
    header('Location: ../pages/galeri-list.php');
    exit();

} catch (PDOException $e) {
    error_log('Edit Galeri Error: ' . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem.';
    header('Location: ../pages/galeri-edit.php?id=' . $id);
    exit();
}
