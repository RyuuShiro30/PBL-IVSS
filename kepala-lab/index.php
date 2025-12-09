<?php
session_start();
require 'config.php';

// Ambil list dosen
try {
    $stmt = $pdo->prepare("SELECT id, nama FROM dosen ORDER BY nama ASC");
    $stmt->execute();
    $dosenList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal mengambil data dosen: " . $e->getMessage());
}

// Ambil pesan sukses/error
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member Lab - IVSS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #5d6be3ff;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --border-color: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }
        .register-container { width: 100%; max-width: 850px; height: 100%; }
        .register-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
        }
        .row.g-0 {
            flex: 1;
}

        /* LEFT SIDE */
        .logo-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            height: 100%;
        }
        .logo-container {
            width: 90px;
            height: 90px;
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 100%; height: 100%; object-fit: contain;
        }

        /* FORM SIDE */
        .form-section { padding: 30px 35px; }
        .form-section h4 { font-weight: 600; color: var(--dark-color); margin-bottom: 6px; }
        .subtitle { color: var(--secondary-color); margin-bottom: 20px; font-size: 0.85rem; }

        .form-label { font-weight: 500; font-size: 0.85rem; margin-bottom: 5px; }
        .input-group-text {
            background: white;
            border: 1px solid var(--border-color);
            border-right: none;
        }
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            padding: 8px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }
        textarea { min-height: 80px; resize: vertical; }

        .btn-register {
            padding: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            border: none;
            background: var(--dark-color);
            color: white;
        }
        .btn-register:hover { background: #ff900d; }

        .footer-text { text-align: center; margin-top: 15px; }
        .footer-text small { font-size: 0.8rem; color: rgba(255,255,255,0.8); }

        @media (max-width: 768px) {
            .form-section { padding: 25px 20px; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="row g-0">

                <!-- LEFT -->
                <div class="col-md-4">
                    <div class="logo-section">
                        <div class="logo-container">
                            <img src="assets/img/Logo-lab.png" alt="Lab Logo">
                        </div>
                        <h3><strong>IVSS</strong></h3>
                        <p>Intelligence Vision and Smart System</p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-md-8">
                    <div class="form-section">
                        <h4>Daftar Member Lab</h4>
                        <p class="subtitle">Lengkapi formulir pendaftaran di bawah ini</p>

                        <!-- ALERT -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- FORM -->
                        <form action="register.php" method="POST" id="registerForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="nama_new_member" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIM</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" name="nim_new_member" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jurusan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                        <input type="text" class="form-control" name="jurusan_new_member" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Program Studi</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <input type="text" class="form-control" name="prodi_new_member" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Aktif</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email_new_member" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dosen Pengampu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-chalkboard-teacher"></i></span>
                                    <select class="form-select" name="dosen_id" id="dosen_id" required>
                                        <?php foreach ($dosenList as $row): ?>
                                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Alasan Bergabung</label>
                                <textarea class="form-control" name="alasan_new_member" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-register d-grid w-100">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sekarang
                            </button>
                        </form>

                        <!-- Login Info -->
                        <div class="text-center mt-3">
                            <small style="font-size: .85rem; color: var(--secondary-color);">
                                Sudah Disetujui jadi anggota?
                                <a href="../anggota/index.php" style="color: var(--primary-color); font-weight: 600;">Login</a>
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="footer-text">
            <small>&copy; 2025 Lab Kampus - IVSS. All rights reserved.</small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value.trim();
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(email)) {
                e.preventDefault();
                alert("Format email tidak valid!");
            }
        });

    </script>
</body>
</html>
