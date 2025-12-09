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
            nm.*, 
            d.nama AS dosen_pengampu
        FROM new_member nm
        LEFT JOIN dosen d ON nm.dosen_id = d.id
        WHERE LOWER(nm.status_new_member) = 'pending'
        ORDER BY nm.tanggal_daftar_new_member DESC
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
