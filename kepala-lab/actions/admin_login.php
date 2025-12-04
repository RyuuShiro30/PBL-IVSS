<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: ../pages/dashboard.php");
    exit();
}

// Ambil pesan error jika ada
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['error']);
unset($_SESSION['success']);

// Jika ada ?error di URL, tampilkan pesan error manual
if (isset($_GET['error'])) {
    $error = "Username atau password salah!";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kepala Lab Kampus</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1e3a8a;
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
        }

        .login-container {
            width: 100%;
            padding: 20px;
        }

        .login-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }

        /* LOGO SECTION - LEFT SIDE */
        .logo-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            min-height: 550px;
        }

        .logo-container {
            width: 140px;
            height: 140px;
            margin-bottom: 25px;
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
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .logo-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin: 0;
            text-align: center;
            font-weight: 400;
        }

        /* FORM SECTION - RIGHT SIDE */
        .form-section {
            padding: 50px 45px;
        }

        .form-section h4 {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .form-section .subtitle {
            color: var(--secondary-color);
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        /* FORM STYLES */
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .input-group-text {
            background-color: white;
            border: 1px solid var(--border-color);
            color: var(--secondary-color);
            border-right: none;
        }

        .form-control {
            border: 1px solid var(--border-color);
            padding: 10px 14px;
            font-size: 0.95rem;
        }

        .input-group .form-control {
            border-left: none;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
            outline: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* BUTTON */
        .btn-login {
            padding: 11px;
            font-weight: 500;
            font-size: 1rem;
            background: var(--primary-color);
            border: none;
            color: white;
            transition: background 0.3s ease;
        }

        .btn-login:hover {
            background: #1e40af;
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--secondary-color);
        }

        .btn-outline-secondary:hover {
            background-color: var(--light-color);
            border-color: var(--border-color);
            color: var(--secondary-color);
        }

        /* CHECKBOX */
        .form-check-input {
            cursor: pointer;
            border-color: var(--border-color);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .form-check-label {
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--dark-color);
        }

        /* ALERT */
        .alert {
            font-size: 0.9rem;
            border-radius: 8px;
            border: none;
            padding: 12px 16px;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* DEMO INFO */
        .demo-info {
            background-color: var(--light-color);
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }

        .demo-info i {
            color: var(--primary-color);
        }

        .demo-info small {
            color: var(--secondary-color);
            font-size: 0.85rem;
        }

        /* FOOTER TEXT */
        .footer-text {
            text-align: center;
            margin-top: 20px;
        }

        .footer-text small {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .logo-section {
                min-height: auto;
                padding: 40px 30px;
            }

            .logo-container {
                width: 120px;
                height: 120px;
                margin-bottom: 20px;
            }

            .logo-section h3 {
                font-size: 1.5rem;
            }

            .form-section {
                padding: 40px 30px;
            }

            .form-section h4 {
                font-size: 1.35rem;
            }
        }

        @media (max-width: 576px) {
            .logo-section {
                padding: 30px 20px;
            }

            .logo-container {
                width: 100px;
                height: 100px;
            }

            .logo-section h3 {
                font-size: 1.35rem;
            }

            .form-section {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="login-box">
                        <div class="row g-0">
                            <!-- Logo Section (Left Side) -->
                            <div class="col-md-5">
                                <div class="logo-section">
                                    <div class="logo-container">
                                        <img src="../assets/img/Logo-lab.png" alt="Lab Logo">
                                    </div>
                                    <h1><strong>IVSS</strong></h1>
                                    <p2>Intelligence Vision and Smart System</p2>
                                </div>
                            </div>

                            <!-- Form Section (Right Side) -->
                            <div class="col-md-7">
                                <div class="form-section">
                                    <h4>Kepala Lab</h4>
                                    <p class="subtitle">Silakan masuk ke akun Anda</p>

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

                                    <!-- Login Form -->
                                    <form action="admin_auth.php" method="POST" id="loginForm">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control" id="username" name="username" 
                                                       placeholder="Masukkan username" required autofocus>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input type="password" class="form-control" id="password" name="password" 
                                                       placeholder="Masukkan password" required>
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="remember">
                                            <label class="form-check-label" for="remember">
                                                Ingat Saya
                                            </label>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 btn-login">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            Masuk
                                        </button>
                                    </form>

                                    <!-- Demo Info -->
                                    <div class="demo-info">
                                        <small>
                                            <i class="fas fa-info-circle me-1"></i>
                                            Demo: kepalalab / kalab123
                                        </small>
                                    </div>
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
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (username === '' || password === '') {
                e.preventDefault();
                alert('Username dan password harus diisi!');
            }
        });
    </script>
</body>
</html>