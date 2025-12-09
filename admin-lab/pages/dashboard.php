<?php
/**
 * Dashboard Admin
 * File: pages/dashboard.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk format tanggal Indonesia
function formatTanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
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

// Ambil data statistik
$pdo = getDBConnection();

// Total fasilitas
$stmt = $pdo->query("SELECT COUNT(*) as total FROM fasilitas");
$total_fasilitas = $stmt->fetch()['total'];

// Total Galeri
$stmt = $pdo->query("SELECT COUNT(*) as total FROM galeri");
$total_galeri = $stmt->fetch()['total'];

// Total Riset
$stmt = $pdo->query("SELECT COUNT(*) as total FROM riset");
$total_riset = $stmt->fetch()['total'];

// Riset terbaru
$stmt = $pdo->query("
    SELECT * from riset
    ORDER BY created_at DESC 
    LIMIT 5
");
$riset_terbaru = $stmt->fetchAll();

// Log aktivitas terbaru (10)
$admin_id = $_SESSION['admin_id'];
$role = $_SESSION['role'];

if ($role === 'superadmin') {
    // Super admin lihat semua log
    $stmt = $pdo->query("
        SELECT l.*, a.nama_lengkap as admin_name 
        FROM logs_lab l 
        LEFT JOIN admin_lab a ON l.admin_id = a.id 
        ORDER BY l.created_at DESC 
        LIMIT 3
    ");
} else {
    // Admin biasa hanya lihat log sendiri
    $stmt = $pdo->prepare("
        SELECT l.*, a.nama_lengkap as admin_name 
        FROM logs_lab l 
        LEFT JOIN admin_lab a ON l.admin_id = a.id 
        WHERE l.admin_id = ?
        ORDER BY l.created_at DESC 
        LIMIT 10
    ");
    $stmt->execute([$admin_id]);
}
$logs_terbaru = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Lab IVSS</title>
    
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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../components/header.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </h1>
                        <div class="text-end">
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?php echo formatTanggalIndo(date('Y-m-d')); ?>
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <span id="jam-realtime"><?php echo date('H:i:s'); ?> WIB</span>
                            </small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h3>
                    </div>

                    <!-- Content Row - Stats -->
                    <div class="row">

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Fasilitas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_fasilitas; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-building fa-2x text-gray-300"></i>                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Riset
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_riset; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Galeri
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_galeri; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-portrait fa-2x text-gray-300"></i>                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
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
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($riset_terbaru) > 0): ?>
                                                    <?php foreach ($riset_terbaru as $riset): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($riset['judul']); ?></strong>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                <?php echo date('d/m/Y H:i', strtotime($riset['created_at'])); ?>
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            Belum ada riset
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Log Aktivitas Terbaru -->
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
                                                        <small class="text-muted d-block">
                                                            <?php echo htmlspecialchars($log['admin_name'] ?? 'System'); ?>
                                                        </small>
                                                        <strong class="d-block"><?php echo htmlspecialchars($log['aksi']); ?></strong>
                                                        <small class="text-muted">
                                                            <?php echo htmlspecialchars($log['detail'] ?? ''); ?>
                                                        </small>
                                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-center text-muted">Belum ada aktivitas</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include '../components/footer.php';?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/admin.js"></script>
</body>
</html>
