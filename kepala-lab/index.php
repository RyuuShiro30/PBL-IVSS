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

// Ambil pesan sukses/error jika ada
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
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

    <!-- Custom CSS -->
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .register-container {
            width: 100%;
            max-width: 850px; 
        }

        .register-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* LOGO SECTION - LEFT SIDE */
        .logo-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            padding: 40px 30px; /* Diperkecil dari 60px 40px */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            min-height: 100%;
        }

        .logo-container {
            width: 90px; /* Diperkecil dari 120px */
            height: 90px;
            margin-bottom: 20px; /* Diperkecil dari 25px */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
        }

        .logo-section h3 {
            color: white;
            font-weight: 600;
            font-size: 1.4rem; /* Diperkecil dari 1.6rem */
            margin-bottom: 0.4rem;
            text-align: center;
        }

        .logo-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem; /* Diperkecil dari 0.9rem */
            margin: 0;
            text-align: center;
            font-weight: 400;
        }

        /* FORM SECTION - RIGHT SIDE */
        .form-section {
            padding: 30px 35px; /* Diperkecil dari 40px 45px */
        }

        .form-section h4 {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.35rem; /* Diperkecil dari 1.5rem */
            margin-bottom: 6px;
        }

        .form-section .subtitle {
            color: var(--secondary-color);
            margin-bottom: 20px; /* Diperkecil dari 25px */
            font-size: 0.85rem; /* Diperkecil dari 0.9rem */
        }

        /* FORM STYLES */
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 5px; /* Diperkecil dari 6px */
            font-size: 0.85rem; /* Diperkecil dari 0.9rem */
        }

        .input-group-text {
            background-color: white;
            border: 1px solid var(--border-color);
            color: var(--secondary-color);
            border-right: none;
            padding: 8px 12px; /* Diperkecil */
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            padding: 8px 12px; /* Diperkecil dari 10px 14px */
            font-size: 0.9rem; /* Diperkecil dari 0.95rem */
        }

        .input-group .form-control {
            border-left: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: none;
            outline: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.85rem;
        }

        textarea.form-control {
            min-height: 80px; /* Diperkecil dari 90px */
            resize: vertical;
        }

        /* SPACING */
        .mb-3 {
            margin-bottom: 0.85rem !important; /* Diperkecil spacing antar field */
        }

        /* BUTTON */
        .btn-register {
            padding: 10px; /* Diperkecil dari 11px */
            font-weight: 500;
            font-size: 0.95rem; /* Diperkecil dari 1rem */
            background: var(--dark-color);
            border: none;
            color: white;
            transition: background 0.3s ease;
        }

        .btn-register:hover {
            background: #0e2676ff;
        }

        .btn-secondary-custom {
            padding: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            background: white;
            border: 1px solid var(--border-color);
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--light-color);
        }

        /* ALERT */
        .alert {
            font-size: 0.85rem; /* Diperkecil dari 0.9rem */
            border-radius: 8px;
            border: none;
            padding: 10px 14px; /* Diperkecil dari 12px 16px */
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* FOOTER TEXT */
        .footer-text {
            text-align: center;
            margin-top: 15px; /* Diperkecil dari 20px */
        }

        .footer-text small {
            font-size: 0.8rem; /* Diperkecil dari 0.85rem */
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-text a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-text a:hover {
            color: white;
            text-decoration: underline;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .logo-section {
                min-height: auto;
                padding: 30px 25px;
            }

            .logo-container {
                width: 80px;
                height: 80px;
                margin-bottom: 15px;
            }

            .logo-section h3 {
                font-size: 1.3rem;
            }

            .logo-section p {
                font-size: 0.8rem;
            }

            .form-section {
                padding: 25px 20px;
            }

            .form-section h4 {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 15px 10px;
            }

            .logo-section {
                padding: 25px 15px;
            }

            .logo-container {
                width: 70px;
                height: 70px;
            }

            .logo-section h3 {
                font-size: 1.2rem;
            }

            .form-section {
                padding: 20px 15px;
            }
        }

        /* Optimasi untuk skala 100% */
        @media (min-width: 1920px) {
            .register-container {
                max-width: 850px; /* Lebih kecil untuk layar besar */
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="register-box">
                        <div class="row g-0">
                            <!-- Logo Section (Left Side) -->
                            <div class="col-md-4">
                                <div class="logo-section">
                                    <div class="logo-container">
                                        <img src="assets/img/Logo-lab.png" alt="Lab Logo">
                                    </div>
                                    <h3><strong>IVSS</strong></h3>
                                    <p>Intelligence Vision and Smart System</p>
                                </div>
                            </div>

                            <!-- Form Section (Right Side) -->
                            <div class="col-md-8">
                                <div class="form-section">
                                    <h4>Daftar Member Lab</h4>
                                    <p class="subtitle">Lengkapi formulir pendaftaran di bawah ini</p>

                                    <!-- Alert Messages -->
                                    <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?php echo htmlspecialchars($error); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?php echo htmlspecialchars($success); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Registration Form -->
                                    <form action="register.php" method="POST" id="registerForm">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nama" class="form-label">Nama Lengkap</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="nama" name="nama"
                                                        placeholder="Nama lengkap" required autofocus>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="nim" class="form-label">NIM</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-id-card"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="nim" name="nim"
                                                        placeholder="NIM" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="jurusan" class="form-label">Jurusan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-graduation-cap"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="jurusan" name="jurusan"
                                                        placeholder="Jurusan" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="prodi" class="form-label">Program Studi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-book"></i>
                                                    </span>
                                                    <input type="text" class="form-control" id="prodi" name="prodi"
                                                        placeholder="Program Studi" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Aktif</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="email@example.com" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dosen" class="form-label">Dosen Pengampu</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                </span>
                                                <select class="form-select" id="dosen" name="dosen" required">

                                                    <?php foreach ($dosenList as $row): ?>
                                                    <option value="<?= $row['id'] ?>">
                                                        <?= htmlspecialchars($row['nama']) ?>
                                                    </option>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="alasan" class="form-label">Alasan Bergabung</label>
                                            <textarea class="form-control" id="alasan" name="alasan"
                                                placeholder="Mengapa Anda ingin bergabung dengan lab ini?"
                                                required></textarea>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-register">
                                                <i class="fas fa-user-plus me-2"></i>
                                                Daftar Sekarang
                                                </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="footer-text">
                        <small>
                            &copy; 2025 Lab Kampus - IVSS. All rights reserved.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const nim = document.getElementById('nim').value.trim();
            const email = document.getElementById('email').value.trim();
            const dosen = document.getElementById('dosen').value;
            const alasan = document.getElementById('alasan').value.trim();
            if (nama === '' || nim === '' || email === '' || dosen === '' || alasan === '') {
                e.preventDefault();
                alert('Semua field harus diisi!');
                return false;
            }
            // Validasi email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return false;
            }
        });
    </script>
</body>

</html>