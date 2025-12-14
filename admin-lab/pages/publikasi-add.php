<?php
/**
 * Tambah Publikasi
 * File: pages/publikasi-add.php
 */

session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

$pdo = getDBConnection();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

// ambil dosen
$dosen = $pdo->query("SELECT id, nama FROM dosen ORDER BY nama")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Publikasi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div id="wrapper">
<?php include '../components/sidebar.php'; ?>

<div id="content-wrapper">
<div id="content">
<?php include '../components/header.php'; ?>

<div class="container-fluid">

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3"><i class="fas fa-plus me-2"></i>Tambah Publikasi</h1>
    <a href="publikasi-list.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow">
<div class="card-body">

<form action="../actions/publikasi_add_process.php" method="POST">

    <div class="mb-3">
        <label class="form-label">Nama Publikasi</label>
        <input type="text" name="nama_publikasi" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Link Publikasi</label>
        <input type="url" name="link_publikasi" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Tahun Publikasi</label>
        <input type="number" name="tahun_publikasi" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Dosen</label>
        <select name="id_dosen" class="form-select" required>
            <option value="">-- Pilih Dosen --</option>
            <?php foreach ($dosen as $d): ?>
                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary">
        <i class="fas fa-save me-2"></i>Simpan
    </button>

</form>

</div>
</div>

</div>
</div>
</div>
</div>

</body>
</html>
