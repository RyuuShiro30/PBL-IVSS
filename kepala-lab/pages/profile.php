<?php
/**
 * Halaman Profil Admin
 * File: pages/profile.php
 */

require __DIR__ . '/../config.php';
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil data admin dari database
$sql = "SELECT * FROM kepalalab WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['admin_id']]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Ambil pesan jika ada
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$root = dirname(__DIR__);
$page_title = "Profil Saya";
$active_page = "profile";

include $root . "/components/sidebar.php";
include $root . "/components/header.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Admin Lab IVSS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fc;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 90px 30px 80px 30px;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        .card-header {
            background: #4e73df;
            color: white;
            font-weight: 600;
            border-radius: 8px 8px 0 0 !important;
        }

        .profile-card {
            border-left: 6px solid #4e73df;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid #4e73df;
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #5a5c69;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .btn {
            font-size: 14px;
            padding: 8px 20px;
            font-weight: 500;
        }

        .info-item {
            padding: 10px 0;
            border-bottom: 1px solid #e3e6f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            color: #858796;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            color: #5a5c69;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="main-content">
        <!-- Alert Messages -->
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Profile Content -->
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow profile-card">
                    <div class="card-body text-center py-4">
                        <img src="../assets/img/<?php echo htmlspecialchars($data['foto']); ?>" 
                             class="rounded-circle profile-img mb-3" 
                             onerror="this.src='../assets/img/default-avatar.png'">
                        <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($data['nama_lengkap']); ?></h5>
                        <p class="text-muted mb-0">@<?php echo htmlspecialchars($data['username']); ?></p>
                        <span class="badge bg-success mt-2">Kepala Lab</span>
                    </div>
                    <div class="card-body pt-0">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope me-1"></i> Email
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($data['email']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user-tag me-1"></i> Role
                            </div>
                            <div class="info-value"><?php echo ucfirst($data['role']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar me-1"></i> Bergabung
                            </div>
                            <div class="info-value"><?php echo date('d M Y', strtotime($data['created_at'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-clock me-1"></i> Update Terakhir
                            </div>
                            <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($data['updated_at'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="../actions/profile_update.php" method="POST" enctype="multipart/form-data" id="formProfile">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                           value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">
                                        Username <span class="text-muted small">(Tidak dapat diubah)</span>
                                    </label>
                                    <input type="text" class="form-control" 
                                           value="<?php echo htmlspecialchars($data['username']); ?>" 
                                           disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($data['email']); ?>"
                                       required>
                            </div>

                            <hr class="my-3">
                            <h6 class="mb-2 fw-semibold text-primary">Ganti Password (Opsional)</h6>
                            <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengubah password</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Minimal 6 karakter">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Ulangi password">
                                </div>
                            </div>

                            <hr class="my-3">
                            <h6 class="mb-2 fw-semibold text-primary">Ganti Foto Profil (Opsional)</h6>

                            <div class="mb-3">
                                <input type="file" class="form-control" id="foto" name="foto" 
                                       accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted">Format: JPG, PNG. Maksimal 1MB</small>
                                
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <label class="form-label">Preview:</label><br>
                                    <img id="preview" src="#" alt="Preview" class="rounded-circle" 
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #4e73df;">
                                </div>
                            </div>

                            <div class="border-top pt-3 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i>Reset
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include $root . "/components/footer.php"; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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

    // Preview image before upload
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 1 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 1MB');
                event.target.value = '';
                document.getElementById('imagePreview').style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').style.display = 'none';
        }
    }

    // Form validation
    document.getElementById('formProfile').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;

        // Jika password diisi, validasi
        if (password !== '') {
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }

            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return false;
            }
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
    });

    // Reset image preview on form reset
    document.getElementById('formProfile').addEventListener('reset', function() {
        document.getElementById('imagePreview').style.display = 'none';
    });
    </script>
</body>
</html>