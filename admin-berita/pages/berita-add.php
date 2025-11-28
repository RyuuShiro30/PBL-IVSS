<?php

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
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
    <title>Tambah Berita - Admin Berita Lab Kampus</title>
    
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
                            <i class="fas fa-plus me-2"></i>Tambah Berita Baru
                        </h1>
                        <a href="berita-list.php" class="btn btn-secondary">
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

                    <!-- Form Tambah Berita -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-newspaper me-2"></i>Form Berita
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/berita_add_process.php" method="POST" enctype="multipart/form-data" id="formBerita">
                                        
                                        <div class="mb-3">
                                            <label for="judul" class="form-label">
                                                Judul Berita <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="judul" name="judul" 
                                                   placeholder="Masukkan judul berita" required>
                                            <small class="text-muted">Slug akan dibuat otomatis dari judul</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="link_berita" class="form-label">
                                                Link Berita <span class="text-danger">*</span>
                                            </label>
                                            <input type="url" class="form-control" id="link_berita" name="link_berita" 
                                                   placeholder="https://example.com/berita/judul-berita" required>
                                            <small class="text-muted">URL lengkap menuju halaman berita</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="thumbnail" class="form-label">
                                                Thumbnail Berita
                                            </label>
                                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" 
                                                   accept="image/*" onchange="previewImage(event)">
                                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                            
                                            <div id="imagePreview" class="mt-3" style="display: none;">
                                                <label class="form-label">Preview:</label><br>
                                                <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">
                                                Status Berita <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="">Pilih Status</option>
                                                <option value="draft">Draft</option>
                                                <option value="published">Published</option>
                                            </select>
                                            <small class="text-muted">Draft = belum tampil di website, Published = tampil di website</small>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan Berita
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-redo me-2"></i>Reset Form
                                            </button>
                                            <a href="berita-list.php" class="btn btn-light">
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
                                        <i class="fas fa-info-circle me-2"></i>Informasi
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="font-weight-bold">Panduan Menambah Berita:</h6>
                                    <ol class="small">
                                        <li>Isi judul berita yang menarik dan deskriptif</li>
                                        <li>Masukkan link lengkap ke halaman berita</li>
                                        <li>Upload thumbnail berita (opsional)</li>
                                        <li>Pilih status berita (Draft/Published)</li>
                                        <li>Klik "Simpan Berita" untuk menyimpan</li>
                                    </ol>

                                    <hr>

                                    <h6 class="font-weight-bold">Catatan:</h6>
                                    <ul class="small text-muted">
                                        <li>Field bertanda <span class="text-danger">*</span> wajib diisi</li>
                                        <li>Slug akan dibuat otomatis dari judul</li>
                                        <li>Ukuran thumbnail maksimal 2MB</li>
                                        <li>Berita dengan status "Published" akan langsung tampil di website</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">
                                        <i class="fas fa-user-circle me-2"></i>Author
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Nama:</strong></p>
                                    <p class="text-muted"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
                                    
                                    <p class="mb-1"><strong>Username:</strong></p>
                                    <p class="text-muted"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                    
                                    <p class="mb-1"><strong>Role:</strong></p>
                                    <p class="text-muted">
                                        <span class="badge bg-primary">
                                            <?php echo $_SESSION['role'] === 'superadmin' ? 'Super Admin' : 'Admin'; ?>
                                        </span>
                                    </p>
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
    // Preview image before upload
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
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
    document.getElementById('formBerita').addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value.trim();
        const link = document.getElementById('link_berita').value.trim();
        const status = document.getElementById('status').value;

        if (!judul || !link || !status) {
            e.preventDefault();
            alert('Harap isi semua field yang wajib diisi!');
            return false;
        }

        // Disable button to prevent double submit
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });
    </script>
</body>
</html>
