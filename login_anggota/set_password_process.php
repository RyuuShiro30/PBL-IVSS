<?php
session_start();
include "../login_anggota/koneksi.php";  // pastikan path benar

$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    $_SESSION['setpass_error'] = "Password tidak sama!";
    header("Location: set_password.php");
    exit();
}

$query = $pdo->prepare("SELECT * FROM members WHERE email = :email AND status = 'approved'");
$query->bindParam(':email', $email);
$query->execute();

if ($query->rowCount() == 0) {
    $_SESSION['setpass_error'] = "Email tidak ditemukan atau belum di-approve!";
    header("Location: set_password.php");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$checkAkun = $pdo->prepare("SELECT * FROM anggota_mahasiswa WHERE email_mahasiswa = :email");
$checkAkun->bindParam(":email", $email);
$checkAkun->execute();

if ($checkAkun->rowCount() > 0) {
    $_SESSION['setpass_error'] = "Akun sudah pernah dibuat sebelumnya!";
    header("Location: set_password.php");
    exit();
}

$insert = $pdo->prepare("
    INSERT INTO anggota_mahasiswa (nama_mahasiswa, email_mahasiswa, password_mahasiswa, member_type)
    SELECT nama, email, :pass, 'mahasiswa'
    FROM members
    WHERE email = :email
");

$insert->bindParam(':pass', $hashed);
$insert->bindParam(':email', $email);

$insert->execute();

$_SESSION['setpass_success'] = "Akun berhasil dibuat! Silakan login.";
header("Location: login_anggota.php");
exit();
?>
