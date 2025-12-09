<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require __DIR__ . '/../../koneksi.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $query = $conn->prepare("SELECT * FROM dosen WHERE email = ?");
    $query->execute([$email]);
    $dosen = $query->fetch(PDO::FETCH_ASSOC);

    if ($dosen) {
        if (password_verify($pass, $dosen['password'])) {
            $_SESSION['role']        = 'dosen';
            $_SESSION['id']    = $dosen['id'];
            $_SESSION['nama']  = $dosen['nama'];
            $_SESSION['email'] = $dosen['email'];
            $_SESSION['dosen_profile']  = $dosen['dosen_profile'];  

            header("Location: dashboard_dosen.php");
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
    <title>Login Dosen</title>
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>

<div class="login-container">
    
    <img src="../../assets/logo.png" class="logo" alt="logo">

    <h2>Login Dosen</h2>

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
