<?php
/**
 * Edit Admin (Khusus Super Admin)
 * File: pages/admin-edit.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login dan role super admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'superadmin') {
    $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini!';
    header('Location: dashboard.php');
    exit();
}

// Ambil ID admin
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID admin tidak valid!';
    header('Location: admin-list.php');
    exit();
}

// Ambil data admin
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM admin_lab WHERE id = ?");
$stmt->execute([$id]);
$admin = $stmt->fetch();

if (!$admin) {
    $_SESSION['error'] = 'Admin tidak ditemukan!';
    header('Location: admin-list.php');
    exit();
}

// Ambil pesan error jika ada
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin - Admin Lab IVSS</title>
    
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
                            <i class="fas fa-user-edit me-2"></i>Edit Admin
                        </h1>
                        <a href="admin-list.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Form Edit Admin -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user-circle me-2"></i>Form Edit Admin
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/admin_edit_process.php" method="POST" enctype="multipart/form-data" id="formAdmin">
                                        
                                        <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                        <input type="hidden" name="old_foto" value="<?php echo $admin['foto']; ?>">

                                        <div class="mb-3">
                                            <label for="nama_lengkap" class="form-label">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                                   value="<?php echo htmlspecialchars($admin['nama_lengkap']); ?>"
                                                   placeholder="Masukkan nama lengkap" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">
                                                Username <span class="text-muted">(Tidak dapat diubah)</span>
                                            </label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($admin['email']); ?>"
                                                   placeholder="admin@labkampus.ac.id" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ganti Password (Opsional)</label>
                                            <small class="text-muted d-block mb-2">Kosongkan jika tidak ingin mengubah password</small>
                                        </div>

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

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="role" class="form-label">
                                                    Role <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="role" name="role" required>
                                                    <option value="admin" <?php echo $admin['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                    <option value="superadmin" <?php echo $admin['role'] === 'superadmin' ? 'selected' : ''; ?>>Super Admin</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="status" class="form-label">
                                                    Status <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="aktif" <?php echo $admin['status'] === 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                                    <option value="nonaktif" <?php echo $admin['status'] === 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Foto Profil Saat Ini</label>
                                            <div class="mb-2">
                                                <img src="../assets/img/<?php echo htmlspecialchars($admin['foto']); ?>" 
                                                     class="rounded-circle" 
                                                     style="width: 150px; height: 150px; object-fit: cover;"
                                                     onerror="this.src='../assets/img/default-avatar.png'">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Ganti Foto Profil (Opsional)</label>
                                            <input type="file" class="form-control" id="foto" name="foto" 
                                                   accept="image/*" onchange="previewImage(event)">
                                            <small class="text-muted">Format: JPG, PNG. Maksimal 1MB. Kosongkan jika tidak ingin mengganti.</small>
                                            
                                            <div id="imagePreview" class="mt-3" style="display: none;">
                                                <label class="form-label">Preview Foto Baru:</label><br>
                                                <img id="preview" src="#" alt="Preview" class="rounded-circle" 
                                                     style="width: 150px; height: 150px; object-fit: cover;">
                                            </div>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Admin
                                            </button>
                                            <a href="admin-list.php" class="btn btn-light">
                                                <i class="fas fa-times me-2"></i>Batal
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Admin
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Terdaftar:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($admin['created_at'])); ?></p>
                                    
                                    <p class="mb-1"><strong>Terakhir Update:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($admin['updated_at'])); ?></p>
                                </div>
                            </div>

                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Perhatian
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small text-muted mb-0">
                                        <li>Username tidak dapat diubah setelah dibuat</li>
                                        <li>Kosongkan field password jika tidak ingin mengubahnya</li>
                                        <li>Mengubah role ke "Admin" akan mengurangi hak akses</li>
                                        <li>Status "Nonaktif" akan menonaktifkan akun</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include '../components/footer.php';?>

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
    document.getElementById('formAdmin').addEventListener('submit', function(e) {
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
