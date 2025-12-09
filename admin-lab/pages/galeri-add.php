<?php
/**
 * Tambah Galeri
 * File: pages/galeri-add.php
 */

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
    <title>Tambah Galeri - Admin Lab IVSS</title>
    
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
                            <i class="fas fa-plus me-2"></i>Tambah Galeri Baru
                        </h1>
                        <a href="galeri-list.php" class="btn btn-secondary">
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

                    <!-- Form Tambah Galeri -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-newspaper me-2"></i>Form Galeri
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/galeri_add_process.php" method="POST" enctype="multipart/form-data" id="formGaleri">

                                        <div class="mb-3">
                                            <label for="gambar_galeri" class="form-label">
                                                Gambar Galeri <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control" id="gambar_galeri" name="gambar_galeri" 
                                                   accept="image/*" onchange="previewImage(event)">
                                            <small class="text-muted">Format: JPG, PNG. Maksimal 10MB</small>
                                            
                                            <div id="imagePreview" class="mt-3" style="display: none;">
                                                <label class="form-label">Preview:</label><br>
                                                <img id="preview" src="#" alt="Preview" class="img-galeri" style="max-width: 300px;">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="deskripsi_galeri" class="form-label">
                                                Deskrispi Galeri <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="deskripsi_galeri" name="deskripsi_galeri" placeholder="Masukkan deskripsi galeri" required>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan Galeri
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-redo me-2"></i>Reset Form
                                            </button>
                                            <a href="galeri-list.php" class="btn btn-light">
                                                <i class="fas fa-times me-2"></i>Batal
                                            </a>
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
    // Preview Image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 10MB');
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
    document.getElementById('formGaleri').addEventListener('submit', function(e) {
        const gambar_galeri = document.getElementById('gambar_galeri').value.trim();
        const deskripsi_galeri = document.getElementById('deskripsi_galeri').value.trim();

        if (!gambar_galeri || !deskripsi_galeri) {
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
