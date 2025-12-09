<?php
session_start();
require_once '../config/database.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = "ID mahasiswa tidak valid!";
    header("Location: anggota-list.php");
    exit();
}

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$mhs = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mhs) {
    $_SESSION['error'] = "Mahasiswa tidak ditemukan!";
    header("Location: anggota-list.php");
    exit();
}

$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Anggota Mahasiswa - Admin Lab IVSS</title>

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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">
                        <i class="fas fa-user-edit me-2"></i>Edit Anggota Mahasiswa
                    </h1>
                    <a href="anggota-list.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-id-card me-2"></i>Form Edit Mahasiswa
                                </h6>
                            </div>

                            <div class="card-body">
                                <form action="../actions/mhs_edit_process.php" method="POST" enctype="multipart/form-data" id="formMhs">
                                    <input type="hidden" name="id" value="<?= $mhs['id'] ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap *</label>
                                        <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($mhs['nama']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">NIM *</label>
                                        <input type="text" name="nim" class="form-control" required value="<?= htmlspecialchars($mhs['nim']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Program Studi *</label>
                                        <input type="text" name="prodi" class="form-control" required value="<?= htmlspecialchars($mhs['prodi']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email *</label>
                                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($mhs['email']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input type="number" name="tahun_lulus" class="form-control" value="<?= htmlspecialchars($mhs['tahun_lulus']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Foto Profil Saat Ini</label>
                                        <div class="mb-2">
                                            <img src="../assets/img/<?php echo htmlspecialchars($mahasiswa['mahasiswa_profile'] ?? ''); ?>" 
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


                                    <h6 class="text-primary mt-4">Ganti Password (Opsional)</h6>
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>

                                    <div class="row mt-2">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <input type="password" id="password" name="password" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Konfirmasi Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                        </div>
                                    </div>

                                    <div class="border-top pt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
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
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-info">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Mahasiswa
                                </h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Terdaftar:</strong><br>
                                    <?= date('d F Y, H:i', strtotime($mhs['created_at'])) ?>
                                </p>

                                <p><strong>Terakhir Update:</strong><br>
                                    <?= date('d F Y, H:i', strtotime($mhs['updated_at'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div><!-- row -->
            </div>
        </div>
