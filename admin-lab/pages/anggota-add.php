<?php
/**
 * Tambah Anggota Dosen
 * File: pages/anggota-add.php
 */

session_start();
require_once '../config/database.php';

// Ambil pesan error jika ada
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota Dosen - Admin Lab IVSS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div id="wrapper">
        
        <?php include '../components/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../components/header.php'; ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-user-plus me-2"></i>Tambah Anggota Dosen Baru
                        </h1>
                        <a href="anggota-list.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user-circle me-2"></i>Form Anggota Dosen
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/dosen_add_process.php" method="POST" enctype="multipart/form-data" id="formDosen">
                                        
                                        <div class="mb-3">
                                            <label for="nama_lengkap" class="form-label">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                                   placeholder="Masukkan nama lengkap dengan gelar" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="Masukkan alamat email" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="lokasi_dosen" class="form-label">
                                                Lokasi Dosen
                                            </label>
                                            <input type="text" class="form-control" id="lokasi_dosen" name="lokasi_dosen" 
                                                   placeholder="Contoh: Gedung A, Lantai 3, Ruang 305">
                                        </div>

                                        <div class="mb-3">
                                            <label for="link_sinta" class="form-label">
                                                Link SINTA Dosen
                                            </label>
                                            <input type="url" class="form-control" id="link_sinta" name="link_sinta" 
                                                   placeholder="Masukkan URL SINTA (Opsional)">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="biografi" class="form-label">
                                                Biografi Dosen
                                            </label>
                                            <textarea class="form-control" id="biografi" name="biografi" rows="4" 
                                                      placeholder="Masukkan deskripsi singkat atau biografi dosen (Opsional)"></textarea>
                                        </div>

                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-graduation-cap me-2"></i>Pendidikan Dosen
                                        </h5>

                                        <div id="pendidikanContainer">
                                            <div class="row g-2 mb-1 p-2 border rounded education-item">
                                                
                                                <div class="col-md-2">
                                                    <label class="form-label">Jenjang <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="jenjang[]" required>
                                                        <option value="">Pilih Jenjang</option>
                                                        <option value="S1">S1/D4</option>
                                                        <option value="S2">S2</option>
                                                        <option value="S3">S3</option>
                                                        <option value="Profesi">Profesi</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="jurusan[]" required placeholder="Contoh: Teknik Informatika">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Universitas <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="universitas[]" required placeholder="Contoh: Universitas Indonesia">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Tahun Lulus <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="tahun_lulus[]" required placeholder="Tahun">
                                                </div>

                                                <div class="col-md-1 d-flex align-items-end justify-content-end">
                                                    <!-- Tombol hapus akan muncul dinamis via JS -->
                                                </div>

                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success btn-sm mb-4" id="addPendidikan">
                                            <i class="fas fa-plus me-2"></i>Tambah Pendidikan
                                        </button>

                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-certificate me-2"></i>Sertifikasi Dosen
                                        </h5>
                                        <div id="sertifikasiContainer">
                                            <div class="row mb-2 border p-2 rounded certification-item">
                                                <div class="col-md-5">
                                                    <label for="nama_sertifikasi_0" class="form-label">Nama Sertifikasi</label>
                                                    <input type="text" class="form-control" id="nama_sertifikasi_0" name="nama_sertifikasi[]" placeholder="Contoh: Sertifikasi Dosen Nasional">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="tahun_sertifikasi_0" class="form-label">Tahun Perolehan</label>
                                                    <input type="number" class="form-control" id="tahun_sertifikasi_0" name="tahun_sertifikasi[]" placeholder="Contoh: 2020">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="penyelenggara_0" class="form-label">Penerbit</label>
                                                    <input type="text" class="form-control" id="penyelenggara_0" name="penerbit[]" placeholder="Contoh: Kemenristekdikti">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm mb-4" id="addSertifikasi">
                                            <i class="fas fa-plus me-2"></i>Tambah Sertifikasi
                                        </button>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="password" class="form-label">
                                                    Password <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password" name="password" 
                                                           placeholder="Minimal 6 karakter" required>
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="confirm_password" class="form-label">
                                                    Konfirmasi Password <span class="text-danger">*</span>
                                                </label>
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                                       placeholder="Ulangi password" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="foto" class="form-label">
                                                Foto Profil
                                            </label>
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
                                                <i class="fas fa-save me-2"></i>Simpan Dosen
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-redo me-2"></i>Reset Form
                                            </button>
                                            <a href="anggota-list.php" class="btn btn-light">
                                                <i class="fas fa-times me-2"></i>Batal
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <?php include '../components/footer.php';?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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
    document.getElementById('formDosen').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;

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

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });

    // Tambah pendidikan
    document.getElementById("addPendidikan").addEventListener("click", function () {
        const container = document.getElementById("pendidikanContainer");
        const item = container.querySelector(".education-item").cloneNode(true);

        // Reset value
        item.querySelectorAll("input, select").forEach(el => el.value = "");

        // Tambah tombol hapus
        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className = "btn btn-danger btn-sm remove-education";
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        deleteBtn.onclick = () => item.remove();

        item.querySelector(".col-md-1").appendChild(deleteBtn);

        container.appendChild(item);
    });

    // Tambah sertifikasi
    document.getElementById("addSertifikasi").addEventListener("click", function () {
        const container = document.getElementById("sertifikasiContainer");
        const item = container.querySelector(".certification-item").cloneNode(true);

        // Reset value
        item.querySelectorAll("input").forEach(el => el.value = "");

        // Tambah tombol hapus
        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className = "btn btn-danger btn-sm remove-certification";
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        deleteBtn.onclick = () => item.remove();

        item.querySelector(".col-md-1").appendChild(deleteBtn);

        container.appendChild(item);
    });
    </script>
</body>
</html>