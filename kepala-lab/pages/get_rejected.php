<?php
require __DIR__ . '/../config.php'; // menghasilkan $pdo
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT m.*, d.nama AS dosen_pengampu
            FROM members m
            LEFT JOIN dosen d ON m.dosen_id = d.id
            WHERE m.status = 'rejected'
            ORDER BY m.tanggal_update DESC";

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
?>
