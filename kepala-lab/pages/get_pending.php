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
    $query = "
        SELECT 
            m.*, 
            d.nama AS dosen_pengampu
        FROM members m
        LEFT JOIN dosen d ON m.dosen_id = d.id
        WHERE LOWER(m.status) = 'pending'
        ORDER BY m.tanggal_daftar DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data ?: []);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
