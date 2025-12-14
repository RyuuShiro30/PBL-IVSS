<?php
/**
 * Dashboard Admin
 * File: pages/dashboard.php
 */

session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

date_default_timezone_set('Asia/Jakarta');

// ==================== FUNGSI ====================
function formatTanggalIndo($tanggal) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $split = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// ==================== DATABASE ====================
$pdo = getDBConnection();

// Statistik
$total_fasilitas  = $pdo->query("SELECT COUNT(*) FROM fasilitas")->fetchColumn();
$total_galeri     = $pdo->query("SELECT COUNT(*) FROM galeri")->fetchColumn();
$total_riset      = $pdo->query("SELECT COUNT(*) FROM riset")->fetchColumn();
$total_publikasi  = $pdo->query("SELECT COUNT(*) FROM publikasi_dosen")->fetchColumn();

// Riset terbaru
$riset_terbaru = $pdo->query("
    SELECT judul, created_at 
    FROM riset 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();

// Log aktivitas
$admin_id = $_SESSION['admin_id'];
$role     = $_SESSION['role'];

if ($role === 'superadmin') {
    $stmt = $pdo->query("
        SELECT l.*, a.nama_lengkap AS admin_name
        FROM logs_lab l
        LEFT JOIN admin_lab a ON l.admin_id = a.id
        ORDER BY l.created_at DESC
        LIMIT 5
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT l.*, a.nama_lengkap AS admin_name
        FROM logs_lab l
        LEFT JOIN admin_lab a ON l.admin_id = a.id
        WHERE l.admin_id = ?
        ORDER BY l.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$admin_id]);
}
$logs_terbaru = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin Lab IVSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

                <!-- Heading -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h1>
                    <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= formatTanggalIndo(date('Y-m-d')); ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <?= date('H:i:s'); ?> WIB
                        </small>
                    </div>
                </div>

                <h5 class="mb-4">
                    Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>
                </h5>

                <!-- ================= STATISTIK ================= -->
                <div class="row">

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card shadow h-100 border-left-primary">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-primary text-uppercase">Fasilitas</div>
                                    <div class="h5 fw-bold"><?= $total_fasilitas ?></div>
                                </div>
                                <i class="fas fa-building fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card shadow h-100 border-left-success">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-success text-uppercase">Riset</div>
                                    <div class="h5 fw-bold"><?= $total_riset ?></div>
                                </div>
                                <i class="fas fa-newspaper fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card shadow h-100 border-left-warning">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-warning text-uppercase">Galeri</div>
                                    <div class="h5 fw-bold"><?= $total_galeri ?></div>
                                </div>
                                <i class="fas fa-images fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card shadow h-100 border-left-info">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-info text-uppercase">Publikasi</div>
                                    <div class="h5 fw-bold"><?= $total_publikasi ?></div>
                                </div>
                                <i class="fas fa-book-open fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================= KONTEN ================= -->
                <div class="row">

                    <!-- Riset Terbaru -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="fw-bold text-primary mb-0">
                                    <i class="fas fa-newspaper me-2"></i>Riset Terbaru
                                </h6>
                                <a href="riset-list.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($riset_terbaru): ?>
                                            <?php foreach ($riset_terbaru as $r): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($r['judul']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">
                                                    Belum ada data
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Log Aktivitas -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header d-flex justify-content-between">
                                <h6 class="fw-bold text-primary mb-0">
                                    <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                                </h6>
                                <a href="logs.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                            </div>
                            <div class="card-body">
                                <?php if ($logs_terbaru): ?>
                                    <?php foreach ($logs_terbaru as $log): ?>
                                    <div class="mb-3 border-bottom pb-2">
                                        <small class="text-muted">
                                            <?= htmlspecialchars($log['admin_name'] ?? 'System') ?>
                                        </small>
                                        <div class="fw-semibold"><?= htmlspecialchars($log['aksi']) ?></div>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                        </small>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">Belum ada aktivitas</p>
                                <?php endif; ?>
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
<script src="../assets/js/admin.js"></script>
</body>
</html>
