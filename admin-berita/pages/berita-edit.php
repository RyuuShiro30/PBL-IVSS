<?php


session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID berita
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID berita tidak valid!';
    header('Location: berita-list.php');
    exit();
}

// Ambil data berita
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch();

if (!$berita) {
    $_SESSION['error'] = 'Berita tidak ditemukan!';
    header('Location: berita-list.php');
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
    <title>Edit Berita - Admin Berita Lab Kampus</title>
    
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
                            <i class="fas fa-edit me-2"></i>Edit Berita
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

                    <!-- Form Edit Berita -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-newspaper me-2"></i>Form Edit Berita
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/berita_edit_process.php" method="POST" enctype="multipart/form-data" id="formBerita">
                                        
                                        <input type="hidden" name="id" value="<?php echo $berita['id']; ?>">
                                        <input type="hidden" name="old_thumbnail" value="<?php echo $berita['thumbnail']; ?>">

                                        <div class="mb-3">
                                            <label for="judul" class="form-label">
                                                Judul Berita <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="judul" name="judul" 
                                                   value="<?php echo htmlspecialchars($berita['judul']); ?>"
                                                   placeholder="Masukkan judul berita" required>
                                            <small class="text-muted">Slug saat ini: <?php echo htmlspecialchars($berita['slug']); ?></small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="link_berita" class="form-label">
                                                Link Berita <span class="text-danger">*</span>
                                            </label>
                                            <input type="url" class="form-control" id="link_berita" name="link_berita" 
                                                   value="<?php echo htmlspecialchars($berita['link_berita']); ?>"
                                                   placeholder="https://example.com/berita/judul-berita" required>
                                            <small class="text-muted">URL lengkap menuju halaman berita</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thumbnail Saat Ini</label>
                                            <?php if ($berita['thumbnail']): ?>
                                                <div class="mb-2">
                                                    <img src="../assets/img/thumbnails/<?php echo htmlspecialchars($berita['thumbnail']); ?>" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 300px;"
                                                         onerror="this.src='../assets/img/no-image.png'">
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted">Tidak ada thumbnail</p>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mb-3">
                                            <label for="thumbnail" class="form-label">
                                                Ganti Thumbnail (opsional)
                                            </label>
                                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" 
                                                   accept="image/*" onchange="previewImage(event)">
                                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</small>
                                            
                                            <div id="imagePreview" class="mt-3" style="display: none;">
                                                <label class="form-label">Preview Thumbnail Baru:</label><br>
                                                <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">
                                                Status Berita <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="">Pilih Status</option>
                                                <option value="draft" <?php echo $berita['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                                <option value="published" <?php echo $berita['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                            </select>
                                            <small class="text-muted">Draft = belum tampil di website, Published = tampil di website</small>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Berita
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
                                        <i class="fas fa-info-circle me-2"></i>Informasi Berita
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Dibuat:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($berita['created_at'])); ?></p>
                                    
                                    <p class="mb-1"><strong>Terakhir Update:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($berita['updated_at'])); ?></p>
                                    
                                    <p class="mb-1"><strong>Slug:</strong></p>
                                    <p class="text-muted"><code><?php echo htmlspecialchars($berita['slug']); ?></code></p>
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
                                        <li>Perubahan akan langsung tersimpan setelah klik "Update Berita"</li>
                                        <li>Jika mengganti thumbnail, thumbnail lama akan terhapus</li>
                                        <li>Pastikan link berita masih valid dan dapat diakses</li>
                                        <li>Status "Published" akan membuat berita tampil di website utama</li>
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
