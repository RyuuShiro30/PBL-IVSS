<?php
require 'config.php'; // menghasilkan $pdo
session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE members 
                SET status = :status,
                    tanggal_update = NOW()
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':id'     => $id
        ]);

        echo json_encode(["success" => true]);

    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "error"   => $e->getMessage()
        ]);
    }
}
?>
