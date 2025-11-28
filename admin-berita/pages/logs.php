<?php


session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Ambil logs
$pdo = getDBConnection();
$admin_id = $_SESSION['admin_id'];
$role = $_SESSION['role'];

// Super admin lihat semua log, admin biasa hanya lihat log sendiri
if ($role === 'superadmin') {
    $count_stmt = $pdo->query("SELECT COUNT(*) as total FROM logs");
    $total_logs = $count_stmt->fetch()['total'];
    
    $stmt = $pdo->prepare("
        SELECT l.*, a.nama_lengkap as admin_name, a.username
        FROM logs l 
        LEFT JOIN admin_berita a ON l.admin_id = a.id 
        ORDER BY l.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$limit, $offset]);
} else {
    $count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM logs WHERE admin_id = ?");
    $count_stmt->execute([$admin_id]);
    $total_logs = $count_stmt->fetch()['total'];
    
    $stmt = $pdo->prepare("
        SELECT l.*, a.nama_lengkap as admin_name, a.username
        FROM logs l 
        LEFT JOIN admin_berita a ON l.admin_id = a.id 
        WHERE l.admin_id = ?
        ORDER BY l.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$admin_id, $limit, $offset]);
}

$logs = $stmt->fetchAll();
$total_pages = ceil($total_logs / $limit);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Admin Berita Lab Kampus</title>
    
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
                            <i class="fas fa-history me-2"></i>Log Aktivitas
                        </h1>
                        <?php if ($role === 'superadmin'): ?>
                        <span class="badge bg-info">Menampilkan Semua Log</span>
                        <?php else: ?>
                        <span class="badge bg-secondary">Menampilkan Log Pribadi</span>
                        <?php endif; ?>
                    </div>

                    <!-- Logs Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Riwayat Aktivitas (Total: <?php echo $total_logs; ?>)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <?php if ($role === 'superadmin'): ?>
                                            <th width="15%">Admin</th>
                                            <?php endif; ?>
                                            <th width="15%">Aksi</th>
                                            <th width="35%">Detail</th>
                                            <th width="15%">IP Address</th>
                                            <th width="15%">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($logs) > 0): ?>
                                            <?php foreach ($logs as $index => $log): ?>
                                            <tr>
                                                <td><?php echo $offset + $index + 1; ?></td>
                                                <?php if ($role === 'superadmin'): ?>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($log['admin_name'] ?? 'System'); ?></strong><br>
                                                    <small class="text-muted">@<?php echo htmlspecialchars($log['username'] ?? '-'); ?></small>
                                                </td>
                                                <?php endif; ?>
                                                <td>
                                                    <?php
                                                    $badge_class = 'bg-secondary';
                                                    $icon = 'fa-circle';
                                                    
                                                    if (strpos($log['aksi'], 'Login') !== false) {
                                                        $badge_class = 'bg-success';
                                                        $icon = 'fa-sign-in-alt';
                                                    } elseif (strpos($log['aksi'], 'Logout') !== false) {
                                                        $badge_class = 'bg-info';
                                                        $icon = 'fa-sign-out-alt';
                                                    } elseif (strpos($log['aksi'], 'Tambah') !== false) {
                                                        $badge_class = 'bg-primary';
                                                        $icon = 'fa-plus';
                                                    } elseif (strpos($log['aksi'], 'Edit') !== false) {
                                                        $badge_class = 'bg-warning';
                                                        $icon = 'fa-edit';
                                                    } elseif (strpos($log['aksi'], 'Hapus') !== false) {
                                                        $badge_class = 'bg-danger';
                                                        $icon = 'fa-trash';
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?>">
                                                        <i class="fas <?php echo $icon; ?> me-1"></i>
                                                        <?php echo htmlspecialchars($log['aksi']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($log['detail'] ?? '-'); ?></small>
                                                </td>
                                                <td>
                                                    <code><?php echo htmlspecialchars($log['ip_address'] ?? '-'); ?></code>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo date('d/m/Y', strtotime($log['created_at'])); ?><br>
                                                        <?php echo date('H:i:s', strtotime($log['created_at'])); ?>
                                                    </small>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="<?php echo $role === 'superadmin' ? '6' : '5'; ?>" class="text-center py-5">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                                    <p class="text-muted">Belum ada aktivitas</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <?php 
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($total_pages, $page + 2);
                                    
                                    for ($i = $start_page; $i <= $end_page; $i++): 
                                    ?>
                                    <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <?php endif; ?>
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
