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

$pdo = getDBConnection();

// Ambil data dosen
$stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = ?");
$stmt->execute([$id]);
$dosen = $stmt->fetch();

if (!$dosen) {
    $_SESSION['error'] = 'Dosen tidak ditemukan!';
    header('Location: anggota-list.php');
    exit();
}

// Ambil pendidikan
$stmtEdu = $pdo->prepare("SELECT * FROM pendidikan WHERE dosen_id = ?");
$stmtEdu->execute([$id]);
$pendidikan_list = $stmtEdu->fetchAll(PDO::FETCH_ASSOC);

// Ambil sertifikat
$stmtCert = $pdo->prepare("SELECT * FROM sertifikat WHERE dosen_id = ?");
$stmtCert->execute([$id]);
$sertifikat_list = $stmtCert->fetchAll(PDO::FETCH_ASSOC);

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota Dosen - Admin Lab IVSS</title>

    <!-- Bootstrap -->
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
                        <?= htmlspecialchars($error) ?>
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

                                    <!-- IDENTITAS -->
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap *</label>
                                        <input type="text" class="form-control" name="nama_lengkap" required
                                               value="<?= htmlspecialchars($dosen['nama']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="email" required
                                               value="<?= htmlspecialchars($dosen['email']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Lokasi Dosen</label>
                                        <input type="text" class="form-control" name="lokasi_dosen"
                                               value="<?= htmlspecialchars($dosen['lokasi_dosen'] ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Link SINTA</label>
                                        <input type="url" class="form-control" name="link_sinta"
                                               value="<?= htmlspecialchars($dosen['link_sinta_dosen'] ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">LinkedIn</label>
                                        <input type="url" class="form-control" name="link_linkedin"
                                               value="<?= htmlspecialchars($dosen['link_linkedin'] ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Google Scholar</label>
                                        <input type="url" class="form-control" name="link_google_scholar"
                                               value="<?= htmlspecialchars($dosen['link_google_scholar'] ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Biografi</label>
                                        <textarea class="form-control" name="biografi_dosen" rows="4"><?= htmlspecialchars($dosen['biografi_dosen'] ?? '') ?></textarea>
                                    </div>

                                    <!-- FOTO -->
                                    <div class="mb-3">
                                        <label class="form-label">Foto Profil Saat Ini</label><br>
                                        <img src="../assets/img/logo/<?= htmlspecialchars($dosen['dosen_profile'] ?? '') ?>"
                                             class="rounded-circle" style="width:150px;height:150px;object-fit:cover"
                                             onerror="this.src='../assets/img/default-avatar.png'">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Foto Dosen</label>
                                        <input type="file" class="form-control" name="foto" accept="image/*"
                                               onchange="previewImage(event)">
                                    </div>

                                    <div class="mb-3" id="imagePreview" style="display:none;">
                                        <label class="form-label">Preview Foto</label><br>
                                        <img id="preview" class="rounded-circle border"
                                             style="width:150px;height:150px;object-fit:cover;">
                                    </div>

                                    <!-- SUBMIT -->
                                    <div class="border-top pt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan
                                        </button>
                                        <a href="anggota-list.php" class="btn btn-secondary">Batal</a>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-info">Informasi</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Terdaftar:</strong><br><?= date('d F Y, H:i', strtotime($dosen['created_at'])) ?></p>
                                <p><strong>Update:</strong><br><?= date('d F Y, H:i', strtotime($dosen['updated_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../components/footer.php'; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 1024 * 1024) {
        alert('Ukuran maksimal 1MB');
        event.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview').src = e.target.result;
        document.getElementById('imagePreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
