<?php
$host = '127.0.0.1';
$port = '5432';
$db   = 'labdb';
$user = 'postgres';
$pass = '12345';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass);

    // Set agar error muncul sebagai exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
