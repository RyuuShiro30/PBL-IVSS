<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require __DIR__ . '/../../koneksi.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $st = $conn->prepare("SELECT * FROM profile_mahasiswa WHERE email = ?");
    $st->execute([$email]);
    $m = $st->fetch(PDO::FETCH_ASSOC);

    if ($m) {
        if (password_verify($pass, $m['password'])) {

            $_SESSION['role']      = 'mahasiswa';
            $_SESSION['id_mhs'] = $m['id_mhs'];
            $_SESSION['name']      = $m['nama'];
            $_SESSION['email']     = $m['email'];

            header("Location: dashboard_mhs.php");
            exit;
        }
    }
    $err = "Email atau password salah.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Anggota</title>
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>

<div class="login-container">
    
    <img src="../../assets/logo.png" class="logo" alt="logo">

    <h2>Login</h2>

    <?php if ($err): ?>
        <div class="error"><?= $err ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required autocomplete="off">
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Masuk</button>
    </form>
</div>

</body>
</html>