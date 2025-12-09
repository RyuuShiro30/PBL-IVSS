<?php
session_start();

// Ambil notifikasi
$success = $_SESSION['setpass_success'] ?? '';
$error   = $_SESSION['setpass_error'] ?? '';

unset($_SESSION['setpass_success'], $_SESSION['setpass_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Anggota Lab</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0A192f;
            font-family: Arial, sans-serif;
        }
        .card {
            border-radius: 12px;
            padding: 25px;
            margin-top: 200px;
        }
        .btn-submit {
            background: #1e293b;
            color: white;
            width: 100%;
        }
        .btn-submit:hover {
            background: #ff900D;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            
            <div class="card shadow">

                <h4 class="text-center mb-3">Buat Akun Anggota</h4>
                <p class="text-center text-muted mb-4" style="font-size: .9rem;">
                    Gunakan email yang Anda daftarkan saat registrasi anggota
                </p>

                <!-- NOTIFIKASI -->
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <!-- FORM -->
                <form action="set_password_process.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Email Terdaftar</label>
                        <input type="email" name="email" class="form-control" 
                               placeholder="Masukkan email yang di-approve" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Buat password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control" 
                               placeholder="Ulangi password" required>
                    </div>

                    <button type="submit" class="btn btn-submit mt-2">
                        Buat Akun
                    </button>

                </form>

                <div class="text-center mt-3">
                    <a href="../anggota/index.php" class="small text-primary">
                        Sudah punya akun? Login di sini
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
