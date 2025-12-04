<?php
require __DIR__ . '/../config.php'; 

try {
    $sql = "SELECT COUNT(*) AS total FROM members WHERE LOWER(status) = 'pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = (int)($row['total'] ?? 0);

    header('Content-Type: application/json');
    echo json_encode(["count" => $count]);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
