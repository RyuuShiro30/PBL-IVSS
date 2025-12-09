<?php
/**
 * Edit Dosen
 * File: pages/dosen-edit.php
 */

session_start();
require_once '../config/database.php';

// Ambil ID admin
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID admin tidak valid!';
    header('Location: anggota-list.php');
    exit();
}

// Ambil data admin
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = ?");
$stmt->execute([$id]);
$dosen = $stmt->fetch();

// Ambil pendidikan
$stmtEdu = $pdo->prepare("SELECT * FROM pendidikan WHERE dosen_id = ?");
$stmtEdu->execute([$id]);
$pendidikan_list = $stmtEdu->fetchAll(PDO::FETCH_ASSOC);

// Ambil sertifikat
$stmtCert = $pdo->prepare("SELECT * FROM sertifikat WHERE dosen_id = ?");
$stmtCert->execute([$id]);
$sertifikat_list = $stmtCert->fetchAll(PDO::FETCH_ASSOC);

if (!$dosen) {
    $_SESSION['error'] = 'Dosen tidak ditemukan!';
    header('Location: anggota-list.php');
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
    <title>Edit Anggota Dosen - Admin Lab IVSS</title>
    
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

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../components/header.php'; ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-user-plus me-2"></i>Edit Profile
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
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user-circle me-2"></i>Form Anggota Dosen
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/dosen_edit_process.php" method="POST" enctype="multipart/form-data" id="formDosen">
                                        <input type="hidden" name="id" value="<?= $dosen['id'] ?>">
                                        
                                        <div class="mb-3">
                                            <label for="nama_lengkap" class="form-label">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                                   placeholder="Masukkan nama lengkap dengan gelar" required value="<?php echo htmlspecialchars($dosen['nama']);?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="Masukkan alamat email" required value="<?php echo htmlspecialchars($dosen['email']);?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="lokasi_dosen" class="form-label">
                                                Lokasi Dosen
                                            </label>
                                            <input type="text" class="form-control" id="lokasi_dosen" name="lokasi_dosen" 
                                                   placeholder="Contoh: Gedung A, Lantai 3, Ruang 305" value="<?php echo htmlspecialchars($dosen['lokasi_dosen'] ?? '');?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="link_sinta" class="form-label">
                                                Link SINTA Dosen
                                            </label>
                                            <input type="url" class="form-control" id="link_sinta" name="link_sinta" 
                                                   placeholder="Masukkan URL SINTA (Opsional)" value="<?php echo htmlspecialchars($dosen['link_sinta_dosen'] ?? '');?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="biografi_dosen" class="form-label">
                                                Biografi Dosen
                                            </label>
                                            <textarea class="form-control" id="biografi_dosen" name="biografi_dosen" rows="4" 
                                                      placeholder="Masukkan deskripsi singkat atau biografi dosen (Opsional)">
                                                     <?= htmlspecialchars($dosen['biografi_dosen'] ?? '') ?></textarea>
                                        </div>

                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-graduation-cap me-2"></i>Pendidikan Dosen
                                        </h5>

                                        <div id="pendidikanContainer">
                                            <?php foreach ($pendidikan_list as $p): ?>
                                            <div class="row g-2 mb-1 p-2 border rounded education-item">

                                                <div class="col-md-2">
                                                    <label class="form-label">Jenjang *</label>
                                                    <select class="form-select" name="jenjang[]" required>
                                                        <option value="">Pilih Jenjang</option>
                                                        <option value="S1" <?= $p['jenjang']=='S1'?'selected':'' ?>>S1/D4</option>
                                                        <option value="S2" <?= $p['jenjang']=='S2'?'selected':'' ?>>S2</option>
                                                        <option value="S3" <?= $p['jenjang']=='S3'?'selected':'' ?>>S3</option>
                                                        <option value="Profesi" <?= $p['jenjang']=='Profesi'?'selected':'' ?>>Profesi</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Jurusan *</label>
                                                    <input type="text" class="form-control" name="jurusan[]" required
                                                        value="<?= htmlspecialchars($p['jurusan']) ?>">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Universitas *</label>
                                                    <input type="text" class="form-control" name="universitas[]" required
                                                        value="<?= htmlspecialchars($p['universitas']) ?>">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Tahun Lulus *</label>
                                                    <input type="number" class="form-control" name="tahun_lulus[]" required
                                                        value="<?= htmlspecialchars($p['tahun_lulus']) ?>">
                                                </div>

                                            </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <button type="button" class="btn btn-success btn-sm mb-4" id="addPendidikan">
                                            <i class="fas fa-plus me-2"></i>Tambah Pendidikan
                                        </button>
                                        
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-certificate me-2"></i>Sertifikasi Dosen
                                        </h5>

                                        <div id="sertifikasiContainer">
                                            <?php foreach ($sertifikat_list as $s): ?>
                                            <div class="row g-2 mb-2 border p-2 rounded certification-item">

                                                <div class="col-md-5">
                                                    <label class="form-label">Nama Sertifikasi</label>
                                                    <input type="text" class="form-control" name="nama_sertifikasi[]" 
                                                        value="<?= htmlspecialchars($s['nama_sertifikat']) ?>">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Tahun Perolehan</label>
                                                    <input type="number" class="form-control" name="tahun[]" 
                                                        value="<?= htmlspecialchars($s['tahun']) ?>">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Penerbit</label>
                                                    <input type="text" class="form-control" name="penerbit[]" 
                                                        value="<?= htmlspecialchars($s['penyelenggara']) ?>">
                                                </div>

                                            </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <button type="button" class="btn btn-success btn-sm mb-4" id="addSertifikasi">
                                            <i class="fas fa-plus me-2"></i>Tambah Sertifikasi
                                        </button>

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

                                        <div class="mb-3">
                                            <label class="form-label">Foto Profil Saat Ini</label>
                                            <div class="mb-2">
                                                <img src="../assets/img/logo/<?php echo htmlspecialchars($dosen['dosen_profile'] ?? ''); ?>" 
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
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Anggota Dosen
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Terdaftar:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($dosen['created_at'])); ?></p>
                                    
                                    <p class="mb-1"><strong>Terakhir Update:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($dosen['updated_at'])); ?></p>
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

    document.getElementById('addPendidikan').addEventListener('click', function () {
        const wrapper = document.createElement('div');
        wrapper.classList.add('row', 'pendidikan-item', 'mb-2');
        wrapper.innerHTML = `
            <div class="class-body">
                <div class="row g-2 mb-1 p-2 border rounded education-item">
                    <div class="col-md-2">
                        <label class="form-label">Jenjang *</label>
                        <select class="form-select" name="jenjang[]" required>
                            <option value="">Pilih Jenjang</option>
                            <option value="S1">S1/D4</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                            <option value="Profesi">Profesi</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Jurusan *</label>
                        <input type="text" class="form-control" name="jurusan[]" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Universitas *</label>
                        <input type="text" class="form-control" name="universitas[]" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tahun Lulus *</label>
                        <input type="number" class="form-control" name="tahun_lulus[]" required>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-pendidikan w-100">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('pendidikanContainer').appendChild(wrapper);
    });

    // event remove
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-pendidikan')) {
            e.target.closest('.pendidikan-item').remove();
        }
    });

    document.getElementById('addSertifikasi').addEventListener('click', function () {
        const wrapper = document.createElement('div');
        wrapper.classList.add('row', 'sertifikat-item', 'mb-2');

        wrapper.innerHTML = `
            <div class="class-body">
                <div class="row g-2 mb-1 p-2 border rounded sertifikat-entry">

                    <div class="col-md-4">
                        <label class="form-label">Nama Sertifikat *</label>
                        <input type="text" class="form-control" name="nama_sertifikat[]" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Penyelenggara *</label>
                        <input type="text" class="form-control" name="penyelenggara[]" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tahun *</label>
                        <input type="number" class="form-control" name="tahun[]" required>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-sertifikat w-100">
                            Hapus
                        </button>
                    </div>

                </div>
            </div>
        `;

        document.getElementById('sertifikasiContainer').appendChild(wrapper);
    });

    // event remove
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-sertifikat')) {
            e.target.closest('.sertifikat-item').remove();
        }
    });


    </script>
</body>
</html>