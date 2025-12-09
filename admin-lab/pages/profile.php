<?php
/**
 * Halaman Profil Admin
 * File: pages/profile.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil data admin dari database
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM admin_lab WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

if (!$admin) {
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Ambil pesan jika ada
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

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
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div id="wrapper">
        
        <?php include '../components/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../components/header.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-user-circle me-2"></i>Profil Saya
                        </h1>
                    </div>

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
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-body text-center">
                                    <img src="../assets/img/<?php echo htmlspecialchars($admin['foto']); ?>" 
                                         class="rounded-circle mb-3" 
                                         style="width: 150px; height: 150px; object-fit: cover;"
                                         onerror="this.src='../assets/img/default-avatar.png'">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($admin['nama_lengkap']); ?></h5>
                                    <p class="text-muted mb-3">@<?php echo htmlspecialchars($admin['username']); ?></p>
                                    
                                    <div class="mb-3">
                                        <?php if ($admin['role'] === 'superadmin'): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-crown me-1"></i>Super Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user me-1"></i>Admin
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($admin['status'] === 'aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </div>

                                    <hr>

                                    <div class="text-start">
                                        <p class="mb-2">
                                            <i class="fas fa-envelope text-muted me-2"></i>
                                            <?php echo htmlspecialchars($admin['email']); ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            Bergabung: <?php echo date('d F Y', strtotime($admin['created_at'])); ?>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            Update: <?php echo date('d/m/Y H:i', strtotime($admin['updated_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Form -->
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-edit me-2"></i>Edit Profil
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/profile_update.php" method="POST" enctype="multipart/form-data" id="formProfile">
                                        
                                        <div class="mb-3">
                                            <label for="nama_lengkap" class="form-label">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                                   value="<?php echo htmlspecialchars($admin['nama_lengkap']); ?>"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">
                                                Username <span class="text-muted">(Tidak dapat diubah)</span>
                                            </label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo htmlspecialchars($admin['username']); ?>" 
                                                   disabled>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($admin['email']); ?>"
                                                   required>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="mb-3">Ganti Password (Opsional)</h6>
                                        <p class="text-muted small">Kosongkan jika tidak ingin mengubah password</p>

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

                                        <hr class="my-4">
                                        <h6 class="mb-3">Ganti Foto Profil (Opsional)</h6>

                                        <div class="mb-3">
                                            <input type="file" class="form-control" id="foto" name="foto" 
                                                   accept="image/*" onchange="previewImage(event)">
                                            <small class="text-muted">Format: JPG, PNG. Maksimal 1MB</small>
                                            
                                            <div id="imagePreview" class="mt-3" style="display: none;">
                                                <label class="form-label">Preview:</label><br>
                                                <img id="preview" src="#" alt="Preview" class="rounded-circle" 
                                                     style="width: 150px; height: 150px; object-fit: cover;">
                                            </div>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-redo me-2"></i>Reset
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; 2024 Admin Berita Lab Kampus - IVSS. All rights reserved.</span>
                    </div>
                </div>
            </footer>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/admin.js"></script>
    
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });
    </script>
</body>
</html>
