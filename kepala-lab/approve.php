<?php
require __DIR__ . '/config.php';
session_start();

// ===== AUTH =====
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success"=>false,"error"=>"unauthorized"]);
    exit;
}

// ===== METHOD =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success"=>false,"error"=>"Method not allowed"]);
    exit;
}

// ===== AMBIL INPUT =====
$id     = $_POST['id'] ?? null;
$status = $_POST['status_new_member'] ?? null;

if (!$id || !$status) {
    echo json_encode(["success"=>false,"error"=>"Parameter tidak lengkap"]);
    exit;
}

try {

    // Ambil data pendaftar
    $stmt = $pdo->prepare("SELECT * FROM new_member WHERE id_new_member = :id");
    $stmt->execute([':id' => $id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        echo json_encode(["success"=>false,"error"=>"Pendaftar tidak ditemukan"]);
        exit;
    }

    // ===== JIKA DITERIMA =====
    if ($status === 'diterima') {

        // Buat password dari NIM
        $pass = password_hash($member['nim_new_member'], PASSWORD_DEFAULT);

        // Insert ke tabel mahasiswa
        $pdo->prepare("
            INSERT INTO mahasiswa (nama, nim, prodi, email, password)
            VALUES (:nama, :nim, :prodi, :email, :pass)
        ")->execute([
            ':nama'  => $member['nama_new_member'],
            ':nim'   => $member['nim_new_member'],
            ':prodi' => $member['prodi_new_member'],
            ':email' => $member['email_new_member'],
            ':pass'  => $pass
        ]);

        $id_mhs = $pdo->lastInsertId();

        // Insert ke profile_mahasiswa
        $pdo->prepare("
            INSERT INTO profile_mahasiswa (id_mhs, id_dosen, nama, nim, email, prodi, password)
            VALUES (:id_mhs, :id_dosen, :nama, :nim, :email, :prodi, :pass)
        ")->execute([
            ':id_mhs'   => $id_mhs,
            ':nama'     => $member['nama_new_member'],
            ':nim'      => $member['nim_new_member'],
            ':email'    => $member['email_new_member'],
            ':prodi'    => $member['prodi_new_member'],
            ':id_dosen' => $member['dosen_id'],  // ← PERBAIKAN DI SINI
            ':pass'     => $pass
        ]);
    }

    // ===== UPDATE STATUS =====
    $pdo->prepare("
        UPDATE new_member
        SET status_new_member = :status,
            tanggal_update_member = NOW()
        WHERE id_new_member = :id
    ")->execute([
        ':status' => $status,
        ':id'     => $id
    ]);

    echo json_encode(["success"=>true,"message"=>"Status berhasil diupdate"]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
