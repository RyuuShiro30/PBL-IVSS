<?php
/**
 * Daftar Publikasi Dosen
 * File: pages/publikasi-list.php
 */

session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Flash message
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$pdo = getDBConnection();

/* Pagination */
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit  = 10;
$offset = ($page - 1) * $limit;

/* Count total publikasi */
$total_publikasi = $pdo->query("SELECT COUNT(*) FROM publikasi_dosen")->fetchColumn();
$total_pages     = ceil($total_publikasi / $limit);

/* Data publikasi + dosen */
$stmt = $pdo->prepare("
    SELECT 
        p.id_publikasi,
        p.nama_publikasi,
        p.link_publikasi,
        p.tahun_publikasi,
        p.created_at_publikasi,
        d.nama AS nama_dosen
    FROM publikasi_dosen p
    LEFT JOIN dosen d ON p.id_dosen = d.id
    ORDER BY p.created_at_publikasi DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$publikasi_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Publikasi - Admin Lab IVSS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
<div id="wrapper">

    <?php include '../components/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php include '../components/header.php'; ?>

            <div class="container-fluid">

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-uppercase mb-1">
                                            Total Publikasi
                                        </div>
                                        <div class="h5 mb-0 fw-bold">
                                            <?= $total_publikasi ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book-open fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-book-open me-2"></i>Daftar Publikasi Dosen
                    </h1>
                    <a href="publikasi-add.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Publikasi
                    </a>
                </div>

                <!-- Alert -->
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Table -->
                <div class="card shadow mb-4">
                    <div class="card-body table-responsive">

                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Publikasi</th>
                                    <th>Link Publikasi</th>
                                    <th>Tahun</th>
                                    <th>Penulis</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if ($publikasi_list): ?>
                                <?php foreach ($publikasi_list as $i => $p): ?>
                                    <tr>
                                        <td><?= $offset + $i + 1 ?></td>
                                        <td><?= htmlspecialchars($p['nama_publikasi']) ?></td>
                                        <td>
                                            <a href="<?= htmlspecialchars($p['link_publikasi']) ?>" target="_blank">
                                                <?= htmlspecialchars($p['link_publikasi']) ?>
                                            </a>
                                        </td>
                                        <td><?= $p['tahun_publikasi'] ?></td>
                                        <td><?= htmlspecialchars($p['nama_dosen'] ?? '-') ?></td>
                                        <td>
                                            <a href="publikasi-edit.php?id=<?= $p['id_publikasi'] ?>"
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete(
                                                        <?= $p['id_publikasi'] ?>,
                                                        '<?= addslashes($p['nama_publikasi']) ?>'
                                                    )">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block empty-icon"></i>
                                        <p class="text-muted mb-3">Belum ada publikasi</p>
                                        <a href="publikasi-add.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Tambah Publikasi Pertama
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            </tbody>
                        </table>

                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>

        <?php include '../components/footer.php'; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(id, nama) {
    if (confirm('Hapus publikasi "' + nama + '" ?')) {
        window.location.href = '../actions/publikasi_delete.php?id=' + id;
    }
}
</script>
</body>
</html>
