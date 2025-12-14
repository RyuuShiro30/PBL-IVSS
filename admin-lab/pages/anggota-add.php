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
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle me-2"></i>
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="card shadow mb-4">
<div class="card-body">

<form action="../actions/dosen_add_process.php" method="POST" enctype="multipart/form-data" id="formDosen">

<div class="mb-3">
    <label class="form-label">Nama Lengkap *</label>
    <input type="text" class="form-control" name="nama_lengkap" required>
</div>

<div class="mb-3">
    <label class="form-label">Email *</label>
    <input type="email" class="form-control" name="email" required>
</div>
<div class="mb-3">
    <label class="form-label">NIP</label>
    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP">
</div>

<div class="mb-3">
    <label class="form-label">NIDN</label>
    <input type="text" class="form-control" name="nidn" placeholder="Masukkan NIDN">
</div>

<div class="mb-3">
    <label class="form-label">Role Dosen <span class="text-danger">*</span></label>
    <select class="form-select" name="role_lab" required>
        <option value="">Pilih Role</option>
        <option value="Kepala Lab">Kepala Lab</option>
        <option value="Peneliti">Peneliti</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Program Studi</label>
    <input type="text" class="form-control" name="prodi_dosen"
           placeholder="Contoh: Teknik Informatika">
</div>

<div class="mb-3">
    <label class="form-label">Lokasi Dosen</label>
    <input type="text" class="form-control" name="lokasi_dosen">
</div>

<div class="mb-3">
    <label class="form-label">Link SINTA</label>
    <input type="url" class="form-control" name="link_sinta">
</div>

<div class="mb-3">
    <label class="form-label">Link LinkedIn</label>
    <input type="url" class="form-control" name="link_linkedin">
</div>

<div class="mb-3">
    <label class="form-label">Link Google Scholar</label>
    <input type="url" class="form-control" name="link_google_scholar">
</div>

<!-- ================= PENDIDIKAN ================= -->
<h5 class="text-primary mt-4">Pendidikan Dosen</h5>

<div id="pendidikanContainer">
    <div class="row g-2 mb-2 education-item">
        <div class="col-md-2">
            <select class="form-select" name="jenjang[]">
                <option value="">Jenjang</option>
                <option value="S1">S1/D4</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="jurusan[]" placeholder="Jurusan">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="universitas[]" placeholder="Universitas">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="tahun_lulus[]" placeholder="Tahun">
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

<button type="button" class="btn btn-success btn-sm mb-4" id="addPendidikan">
    <i class="fas fa-plus me-2"></i>Tambah Pendidikan
</button>

<!-- ================= SERTIFIKASI ================= -->
<h5 class="text-primary">Sertifikasi Dosen</h5>

<div id="sertifikasiContainer">
    <div class="row g-2 mb-2 certification-item">
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_sertifikasi[]" placeholder="Nama Sertifikasi">
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="tahun_sertifikasi[]" placeholder="Tahun">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="penerbit[]" placeholder="Penerbit">
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

<button type="button" class="btn btn-success btn-sm mb-4" id="addSertifikasi">
    <i class="fas fa-plus me-2"></i>Tambah Sertifikasi
</button>

<div class="mb-3">
    <label class="form-label">Foto Profil</label>
    <input type="file" class="form-control" name="foto" accept="image/*">
</div>

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
</div>

<?php include '../components/footer.php'; ?>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>
<script>
document.getElementById('addPendidikan').addEventListener('click', function () {
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 education-item';

    row.innerHTML = `
        <div class="col-md-2">
            <select class="form-select" name="jenjang[]">
                <option value="">Jenjang</option>
                <option value="S1">S1/D4</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="text" class="form-control" name="jurusan[]" placeholder="Jurusan">
        </div>

        <div class="col-md-4">
            <input type="text" class="form-control" name="universitas[]" placeholder="Universitas">
        </div>

        <div class="col-md-2">
            <input type="number" class="form-control" name="tahun_lulus[]" placeholder="Tahun">
        </div>

        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-danger remove-pendidikan">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    document.getElementById('pendidikanContainer').appendChild(row);
});

    // event remove
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-pendidikan')) {
            e.target.closest('.education-item').remove();
        }
    });

document.getElementById('addSertifikasi').addEventListener('click', function () {
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 certification-item';

    row.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_sertifikasi[]" placeholder="Nama Sertifikasi">
        </div>

        <div class="col-md-4">
            <input type="number" class="form-control" name="tahun_sertifikasi[]" placeholder="Tahun">
        </div>

        <div class="col-md-2">
            <input type="text" class="form-control" name="penerbit[]" placeholder="Penerbit">
        </div>

        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-danger remove-sertifikat">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    document.getElementById('sertifikasiContainer').appendChild(row);
});

// hapus sertifikasi
document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-sertifikat')) {
        e.target.closest('.certification-item').remove();
    }
});
</script>

</body>
</html>
