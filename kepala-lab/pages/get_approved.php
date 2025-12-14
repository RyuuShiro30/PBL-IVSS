<?php
require __DIR__ . '/../config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

try {
    $sql = "
        SELECT 
            nm.id_new_member,
            nm.nama_new_member,
            nm.nim_new_member,
            nm.jurusan_new_member,
            nm.prodi_new_member,
            nm.email_new_member,
            nm.judul_riset_new_member,
            nm.tanggal_daftar_new_member,
            nm.tanggal_update_member,
            nm.alasan_new_member,
            d.nama AS nama_dosen
        FROM new_member nm
        LEFT JOIN dosen d ON nm.dosen_id = d.id
        WHERE nm.status_new_member = 'diterima'
        ORDER BY nm.tanggal_update_member DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
