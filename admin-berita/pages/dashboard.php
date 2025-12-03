<?php


session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil data statistik
$pdo = getDBConnection();

// --- OPTIMASI DENGAN MATERIALIZED VIEW ---
// Mengambil semua data statistik dalam 1 kali query
try {
    $stmt = $pdo->query("SELECT * FROM mv_dashboard_berita");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika MV belum ada isinya (baru dibuat), set default 0
    $total_berita     = $stats['total_berita'] ?? 0;
    $berita_published = $stats['total_published'] ?? 0;
    $berita_draft     = $stats['total_draft'] ?? 0;
} catch (PDOException $e) {
    $total_berita = 0; $berita_published = 0; $berita_draft = 0;
    error_log("Error MV: " . $e->getMessage());
}
// Berita terbaru (5)
$stmt = $pdo->query("
    SELECT b.*, a.nama_lengkap as author_name 
    FROM berita b 
    LEFT JOIN admin_berita a ON b.author_id = a.id 
    ORDER BY b.created_at DESC 
    LIMIT 10
");
$berita_terbaru = $stmt->fetchAll();

// Log aktivitas terbaru (10)
$admin_id = $_SESSION['admin_id'];
$role = $_SESSION['role'];

if ($role === 'superadmin') {
    // Super admin lihat semua log
    $stmt = $pdo->query("
        SELECT l.*, a.nama_lengkap as admin_name 
        FROM logs l 
        LEFT JOIN admin_berita a ON l.admin_id = a.id 
        ORDER BY l.created_at DESC 
        LIMIT 5
    ");
} else {
    // Admin biasa hanya lihat log sendiri
    $stmt = $pdo->prepare("
        SELECT l.*, a.nama_lengkap as admin_name 
        FROM logs l 
        LEFT JOIN admin_berita a ON l.admin_id = a.id 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Berita Lab Kampus</title>
    
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
                            <i class="fas fa-tachometer-alt me-2 mt-1 "></i>Dashboard
                        </h1>
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('d F Y, H:i'); ?>
                            </small>
                        </div>
                    </div>

                    <!-- Alert Welcome -->
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</strong> 
                        Anda login sebagai <strong><?php echo $_SESSION['role'] === 'superadmin' ? 'Super Admin' : 'Admin'; ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Content Row - Stats -->
                    <div class="row">

                        <!-- Total Berita Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Berita
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $total_berita; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Berita Published Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Berita Published
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $berita_published; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Berita Draft Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Berita Draft
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $berita_draft; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Access Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Quick Access
                                            </div>
                                            <div class="mt-2">
                                                <a href="berita-add.php" class="btn btn-sm btn-info">
                                                    <i class="fas fa-plus"></i> Tambah Berita
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-bolt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Berita Terbaru -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                                    </h6>
                                    <a href="berita-list.php" class="btn btn-sm btn-primary">
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
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($berita_terbaru) > 0): ?>
                                                    <?php foreach ($berita_terbaru as $berita): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($berita['judul']); ?></strong>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($berita['author_name'] ?? 'Unknown'); ?></td>
                                                        <td>
                                                            <?php if ($berita['status'] === 'published'): ?>
                                                                <span class="badge bg-success">Published</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning">Draft</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                <?php echo date('d/m/Y H:i', strtotime($berita['created_at'])); ?>
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            Belum ada berita
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
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; 2024 Admin Berita Lab Kampus - IVSS. All rights reserved.</span>
                    </div>
                </div>
            </footer>
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
