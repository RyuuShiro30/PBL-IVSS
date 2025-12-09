<?php
$host = 'localhost';
$port = '5432';
$db   = 'IVSS';
$user = 'postgres';
$pass = '113005';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass);

    // Set agar error muncul sebagai exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
