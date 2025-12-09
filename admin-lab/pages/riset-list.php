<?php
/**
 * Daftar Riset
 * File: pages/Riset-list.php
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
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Query riset
$pdo = getDBConnection();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// count total
$count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM riset");
$count_stmt->execute();
$total_riset = $count_stmt->fetch()['total'];
$total_pages = ceil($total_riset / $limit);

$stmt = $pdo->prepare("
    SELECT *
    FROM v_riset_full
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->execute([$limit, $offset]);
$riset_list = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Riset - Admin Lab IVSS</title>
    
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

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
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
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-newspaper me-2"></i>Daftar Riset
                        </h1>
                        <a href="riset-add.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Riset
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Riset Table -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="30%">Judul</th>
                                            <th width=20%>Dosen Peneliti</th>
                                            <th width=20%>Mahasiswa Peneliti</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($riset_list) > 0): ?>
                                            <?php foreach ($riset_list as $index => $riset): ?>
                                            <tr>
                                                <td><?php echo $offset + $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($riset['judul']); ?></strong><br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-link me-1"></i>
                                                        <a href="<?php echo htmlspecialchars($riset['link_riset']); ?>" target="_blank">
                                                            Lihat Riset
                                                        </a>
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong><?php echo $riset['dosen_peneliti'] ?: '-'; ?></strong>
                                                </td>
                                                <td>
                                                    <strong><?php echo $riset['mahasiswa_peneliti'] ?: '-'; ?></strong>
                                                <td>
                                                    <small>
                                                        <?php echo date('d/m/Y', strtotime($riset['created_at'])); ?><br>
                                                        <?php echo date('H:i', strtotime($riset['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="riset-edit.php?id=<?php echo $riset['id_riset']; ?>" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?php echo $riset['id_riset']; ?>, '<?php echo htmlspecialchars(addslashes($riset['judul'])); ?>')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                                    <p class="text-muted">Belum ada riset</p>
                                                    <a href="riset-add.php" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Tambah Riset Pertama
                                                    </a>
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

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
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
            <?php include '../components/footer.php';?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/admin.js"></script>
    
    <script>
    function confirmDelete(id, judul) {
        if (confirm('Apakah Anda yakin ingin menghapus riset "' + judul + '"?')) {
            window.location.href = '../actions/riset_delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>
