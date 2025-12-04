<?php
require('../config.php'); // menghasilkan $pdo
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_login.php');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

try {
    // Ambil user berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM kepalalab WHERE username = :username");
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        // Password cocok → set session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];

        header('Location: ../pages/dashboard.php');
        exit;
    } else {
        header('Location: ../actions/admin_login.php?error=1');
        exit;
    }

} catch (PDOException $e) {
    header('Location: ../actions/admin_login.php?error=db');
    exit;
}
