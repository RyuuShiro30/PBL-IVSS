<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    $_SESSION['error'] = 'ID tidak valid';
    header('Location: publikasi-list.php');
    exit();
}

$pdo = getDBConnection();

/* publikasi */
$stmt = $pdo->prepare("SELECT * FROM publikasi_dosen WHERE id_publikasi = ?");
$stmt->execute([$id]);
$publikasi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$publikasi) {
    $_SESSION['error'] = 'Data tidak ditemukan';
    header('Location: publikasi-list.php');
    exit();
}

/* dosen */
$dosen = $pdo->query("SELECT id, nama FROM dosen ORDER BY nama")->fetchAll();

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Publikasi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div id="wrapper">
<?php include '../components/sidebar.php'; ?>
<div id="content-wrapper">
<div id="content">
<?php include '../components/header.php'; ?>

<div class="container-fluid">

<h1 class="h3 mb-4">Edit Publikasi</h1>

<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow">
<div class="card-body">

<form action="../actions/publikasi_edit_process.php" method="POST">
<input type="hidden" name="id_publikasi" value="<?= $publikasi['id_publikasi'] ?>">

<div class="mb-3">
<label class="form-label">Nama Publikasi</label>
<input type="text" name="nama_publikasi" class="form-control"
value="<?= htmlspecialchars($publikasi['nama_publikasi']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Link Publikasi</label>
<input type="url" name="link_publikasi" class="form-control"
value="<?= htmlspecialchars($publikasi['link_publikasi']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Tahun</label>
<input type="number" name="tahun_publikasi" class="form-control"
value="<?= $publikasi['tahun_publikasi'] ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Dosen</label>
<select name="id_dosen" class="form-select" required>
<?php foreach ($dosen as $d): ?>
<option value="<?= $d['id'] ?>"
<?= $d['id'] == $publikasi['id_dosen'] ? 'selected' : '' ?>>
<?= htmlspecialchars($d['nama']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<button class="btn btn-primary">Update</button>
<a href="publikasi-list.php" class="btn btn-secondary">Batal</a>

</form>

</div>
</div>

</div>
</div>
</div>
</div>

</body>
</html>
