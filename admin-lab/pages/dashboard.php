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

// Fungsi untuk format waktu relatif
function waktuRelative($tanggal) {
    $waktu = strtotime($tanggal);
    $sekarang = time();
    $selisih = $sekarang - $waktu;
    
    if ($selisih < 60) {
        return 'Baru saja';
    } elseif ($selisih < 3600) {
        $menit = floor($selisih / 60);
        return $menit . ' menit yang lalu';
    } elseif ($selisih < 86400) {
        $jam = floor($selisih / 3600);
        return $jam . ' jam yang lalu';
    } elseif ($selisih < 604800) {
        $hari = floor($selisih / 86400);
        return $hari . ' hari yang lalu';
    } else {
        return date('d/m/Y H:i', $waktu);
    }
}

// ==================== DATABASE ====================
$pdo = getDBConnection();

// Statistik
try {
    $stmt = $pdo->query("SELECT * FROM mv_admin_lab_statistik");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_fasilitas = $stats['total_fasilitas'] ?? 0;
    $total_galeri    = $stats['total_galeri'] ?? 0;
    $total_riset     = $stats['total_riset'] ?? 0;
    $total_publikasi = $stats['total_publikasi'] ?? 0;

} catch (PDOException $e) {
    $total_fasilitas = 0;
    $total_galeri    = 0;
    $total_riset     = 0;
    $total_publikasi = 0;
    error_log("MV LAB ERROR: " . $e->getMessage());
}


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
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h1>
                    <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= formatTanggalIndo(date('Y-m-d')); ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <span id="jam-realtime"><?= date('H:i:s'); ?> WIB</span>
                        </small>
                    </div>
                </div>

                <!-- Alert Welcome -->
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>!</strong> 
                    Anda login sebagai <strong><?= $_SESSION['role'] === 'superadmin' ? 'Super Admin' : 'Admin'; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <!-- ================= STATISTIK ================= -->
                <div class="row">

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Fasilitas
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $total_fasilitas ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-building fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Riset
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $total_riset ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Galeri
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $total_galeri ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-images fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Publikasi
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $total_publikasi ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book-open fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================= KONTEN ================= -->
                <div class="row">

                    <!-- Riset Terbaru -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-newspaper me-2"></i>Riset Terbaru
                                </h6>
                                <a href="riset-list.php" class="btn btn-sm btn-primary">
                                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Judul</th>
                                                <th>Author</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($riset_terbaru)): ?>
                                                <?php foreach ($riset_terbaru as $r): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= htmlspecialchars($r['judul'] ?? '') ?></strong>
                                                    </td>
                                                    <td>admin lab</td>
                                                    <td>
                                                        <small class="text-muted" title="<?= date('d F Y H:i:s', strtotime($r['created_at'])) ?>">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <?= waktuRelative($r['created_at']) ?>
                                                        </small>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">
                                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                        Belum ada data
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Aktivitas -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                                </h6>
                                <a href="logs.php" class="btn btn-sm btn-primary">
                                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="activity-list">
                                    <?php if (count($logs_terbaru) > 0): ?>
                                        <?php foreach ($logs_terbaru as $log): ?>
                                        <div class="activity-item mb-3 pb-3 border-bottom">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-circle text-primary" style="font-size: 0.5rem;"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <small class="text-primary fw-bold d-block">
                                                        <?= htmlspecialchars($log['admin_name'] ?? 'System') ?>
                                                    </small>
                                                    <strong class="d-block"><?= htmlspecialchars($log['aksi']) ?></strong>
                                                    <?php if (!empty($log['detail'])): ?>
                                                    <small class="text-muted d-block">
                                                        <?= htmlspecialchars($log['detail']) ?>
                                                    </small>
                                                    <?php endif; ?>
                                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?= waktuRelative($log['created_at']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-center text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Belum ada aktivitas
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white mt-auto">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>&copy; 2024 Admin Lab IVSS. All rights reserved.</span>
                </div>
            </div>
        </footer>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>

<!-- Real-time Clock Script -->
<script>
    // Update jam setiap detik
    function updateJam() {
        const now = new Date();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('jam-realtime').textContent = `${jam}:${menit}:${detik} WIB`;
    }
    
    // Update setiap detik
    setInterval(updateJam, 1000);
    
    // Update saat halaman load
    updateJam();
</script>
</body>
</html>
