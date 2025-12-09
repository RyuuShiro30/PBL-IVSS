<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Cek POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/riset-list.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Ambil data form
$id = $_POST['id'] ?? 0;
$judul = trim($_POST['judul'] ?? '');
$link_riset = trim($_POST['link_riset'] ?? '');
$tahun = trim($_POST['tahun'] ?? '');

$dosenList = $_POST['dosen'] ?? [];        // array ID dosen
$mhsList = $_POST['mahasiswa'] ?? [];      // array ID mahasiswa

// Validasi
if (empty($id) || empty($judul) || empty($link_riset) || empty($tahun)) {
    $_SESSION['error'] = 'Harap isi semua field wajib!';
    header('Location: ../pages/riset-edit.php?id=' . $id);
    exit();
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    // ====== UPDATE DATA RISET ======
    $stmt = $pdo->prepare("
        UPDATE riset 
        SET judul = ?, link_riset = ?, tahun = ?, updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $judul,
        $link_riset,
        $tahun,
        $id
    ]);

    // ====== UPDATE DOSEN ======
    // Hapus data lama
    $pdo->prepare("DELETE FROM riset_dosen WHERE id_riset = ?")->execute([$id]);

    // Insert dosen baru (jika ada)
    if (!empty($dosenList)) {
        $stmtDosen = $pdo->prepare("INSERT INTO riset_dosen (id_riset, id_dosen) VALUES (?, ?)");
        foreach ($dosenList as $dosenID) {
            if (!empty($dosenID)) {
                $stmtDosen->execute([$id, $dosenID]);
            }
        }
    }

    // ====== UPDATE MAHASISWA ======
    // Hapus data lama
    $pdo->prepare("DELETE FROM riset_mahasiswa WHERE id_riset = ?")->execute([$id]);

    // Insert mahasiswa baru (jika ada)
    if (!empty($mhsList)) {
        $stmtMhs = $pdo->prepare("INSERT INTO riset_mahasiswa (id_riset, id_mahasiswa) VALUES (?, ?)");
        foreach ($mhsList as $mhsID) {
            if (!empty($mhsID)) {
                $stmtMhs->execute([$id, $mhsID]);
            }
        }
    }

    // ====== LOG AKTIVITAS ======
    $log = $pdo->prepare("
        INSERT INTO logs_lab (admin_id, aksi, detail, ip_address)
        VALUES (?, 'Edit Riset', ?, ?)
    ");
    $log->execute([
        $admin_id,
        "Mengedit riset: " . $judul,
        $_SERVER['REMOTE_ADDR']
    ]);

    $pdo->commit();

    $_SESSION['success'] = 'Riset berhasil diperbarui!';
    header('Location: ../pages/riset-list.php');
    exit();

} catch (PDOException $e) {

    // Rollback kalau error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("Error Edit Riset: " . $e->getMessage());
    $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    header('Location: ../pages/riset-edit.php?id=' . $id);
    exit();
}
?>
