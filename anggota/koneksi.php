<?php
$host     = "localhost";
$port     = "5432";
$dbname   = "IVSS";
$user     = "postgres";    
$password = "113005";  

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
