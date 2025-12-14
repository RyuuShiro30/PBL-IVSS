<?php
/**
 * Daftar Anggota
 * File: pages/Anggota-list.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil pesan jika ada
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$pdo = getDBConnection();

// Pagination
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

/* =========================
   QUERY DOSEN
========================= */
$stmtDosen = $pdo->prepare("
    SELECT * FROM view_dosen_full
    ORDER BY created_at ASC
    LIMIT :limit OFFSET :offset
");
$stmtDosen->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtDosen->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtDosen->execute();
$dosen = $stmtDosen->fetchAll();

// Count dosen
$count_stmt_dosen = $pdo->query("SELECT COUNT(*) FROM dosen");
$total_dosen = $count_stmt_dosen->fetchColumn();
$total_pages_dosen = ceil($total_dosen / $limit);

/* =========================
   QUERY MAHASISWA
========================= */
$stmtMHS = $pdo->prepare("
    SELECT * FROM mahasiswa
    ORDER BY created_at DESC
    LIMIT :limit OFFSET :offset
");
$stmtMHS->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtMHS->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtMHS->execute();
$mahasiswa = $stmtMHS->fetchAll();

// Count mahasiswa
$count_stmt_mhs = $pdo->query("SELECT COUNT(*) FROM mahasiswa");
$total_mhs = $count_stmt_mhs->fetchColumn();
$total_pages_mhs = ceil($total_mhs / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Anggota - Admin Lab IVSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

<!-- ================= DOSEN ================= -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">
        <i class="fas fa-user-tie me-2"></i>Daftar Anggota Dosen
    </h1>
    <a href="anggota-add.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Anggota
    </a>
</div>

<?php if ($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card shadow mb-5">
<div class="card-body table-responsive">

<table class="table table-hover align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>NIP</th>
    <th>NIDN</th>
    <th>Role</th>
    <th>Program Studi</th>
    <th>Email</th>
    <th>Foto</th>
    <th>Pendidikan</th>
    <th>Sertifikasi</th>
    <th>Lokasi</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php if ($dosen): ?>
<?php foreach ($dosen as $i => $d): ?>
<tr>
    <td><?= $offset + $i + 1 ?></td>

    <td><strong><?= htmlspecialchars($d['nama']) ?></strong></td>

    <td><?= htmlspecialchars($d['nip'] ?? '-') ?></td>

    <td><?= htmlspecialchars($d['nidn'] ?? '-') ?></td>

    <td>
        <span class="badge bg-info">
            <?= htmlspecialchars($d['role_lab'] ?? '-') ?>
        </span>
    </td>

    <td><?= htmlspecialchars($d['prodi_dosen'] ?? '-') ?></td>

    <td><?= htmlspecialchars($d['email']) ?></td>

    <td>
        <img src="../assets/img/logo/<?= htmlspecialchars($d['dosen_profile'] ?: 'default-avatar.png') ?>"
             width="50" height="50"
             class="rounded-circle"
             style="object-fit:cover">
    </td>

    <td><?= htmlspecialchars($d['pendidikan'] ?? '-') ?></td>

    <td><?= htmlspecialchars($d['sertifikat'] ?? '-') ?></td>

    <td><?= htmlspecialchars($d['lokasi_dosen'] ?? '-') ?></td>

    <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>

    <td>
        <a href="dosen-edit.php?id=<?= $d['id'] ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <button onclick="confirmDeleteDosen(<?= $d['id'] ?>,'<?= addslashes($d['nama']) ?>')"
                class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="13" class="text-center text-muted py-4">
        Belum ada anggota dosen
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

<!-- ================= MAHASISWA ================= -->
<h1 class="h3 text-gray-800 mb-3">
    <i class="fas fa-user-graduate me-2"></i>Daftar Anggota Mahasiswa
</h1>

<div class="card shadow mb-4">
<div class="card-body table-responsive">

<table class="table table-hover align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>NIM</th>
    <th>Prodi</th>
    <th>Email</th>
    <th>Tahun Lulus</th>
    <th>Foto</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php if ($mahasiswa): ?>
<?php foreach ($mahasiswa as $i => $m): ?>
<tr>
    <td><?= $offset + $i + 1 ?></td>
    <td><?= htmlspecialchars($m['nama']) ?></td>
    <td><?= htmlspecialchars($m['nim']) ?></td>
    <td><?= htmlspecialchars($m['prodi']) ?></td>
    <td><?= htmlspecialchars($m['email']) ?></td>
    <td><?= htmlspecialchars($m['tahun_lulus'] ?? '-' ) ?></td>
    <td>
        <img src="../assets/img/mahasiswa/<?= htmlspecialchars($m['mahasiswa_profile'] ?: 'default-avatar.png') ?>"
             width="60" height="40" style="object-fit:cover">
    </td>
    <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
    <td>
        <a href="mhs-edit.php?id=<?= $m['id'] ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <button onclick="confirmDeleteMhs(<?= $m['id'] ?>,'<?= addslashes($m['nama']) ?>')"
                class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="9" class="text-center text-muted py-4">
        Belum ada anggota mahasiswa
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

</div>
</div>

<?php include '../components/footer.php'; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDeleteDosen(id, nama) {
    if (confirm('Hapus dosen "' + nama + '" ?')) {
        location.href = '../actions/dosen_delete.php?id=' + id;
    }
}
function confirmDeleteMhs(id, nama) {
    if (confirm('Hapus mahasiswa "' + nama + '" ?')) {
        location.href = '../actions/mhs_delete.php?id=' + id;
    }
}
</script>

</body>
</html>
