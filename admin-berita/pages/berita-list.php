<?php


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

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Filter status
$filter_status = $_GET['status'] ?? '';

// Query berita
$pdo = getDBConnection();

// Count total
$where_clause = '';
$params = [];
if ($filter_status) {
    $where_clause = "WHERE b.status = ?";
    $params[] = $filter_status;
}

$count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM berita b $where_clause");
$count_stmt->execute($params);
$total_berita = $count_stmt->fetch()['total'];
$total_pages = ceil($total_berita / $limit);

// Get berita dengan pagination
$params[] = $limit;
$params[] = $offset;
$stmt = $pdo->prepare("
    SELECT b.*, a.nama_lengkap as author_name 
    FROM berita b 
    LEFT JOIN admin_berita a ON b.author_id = a.id 
    $where_clause
    ORDER BY b.created_at DESC 
    LIMIT ? OFFSET ?
");
$stmt->execute($params);
$berita_list = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Berita - Admin Berita Lab Kampus</title>
    
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
                            <i class="fas fa-newspaper me-2"></i>Daftar Berita
                        </h1>
                        <a href="berita-add.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Berita
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

                    <!-- Filter & Search -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Status Berita</label>
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="published" <?php echo $filter_status === 'published' ? 'selected' : ''; ?>>Published</option>
                                        <option value="draft" <?php echo $filter_status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i>Filter
                                    </button>
                                    <a href="berita-list.php" class="btn btn-secondary">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Berita Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Data Berita (Total: <?php echo $total_berita; ?>)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Thumbnail</th>
                                            <th width="30%">Judul</th>
                                            <th width="15%">Author</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($berita_list) > 0): ?>
                                            <?php foreach ($berita_list as $index => $berita): ?>
                                            <tr>
                                                <td><?php echo $offset + $index + 1; ?></td>
                                                <td>
                                                    <?php if ($berita['thumbnail']): ?>
                                                    <img src="../assets/img/thumbnails/<?php echo htmlspecialchars($berita['thumbnail']); ?>" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 40px; object-fit: cover;"
                                                         onerror="this.src='../assets/img/no-image.png'">
                                                    <?php else: ?>
                                                    <img src="../assets/img/no-image.png" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 40px; object-fit: cover;">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($berita['judul']); ?></strong><br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-link me-1"></i>
                                                        <a href="<?php echo htmlspecialchars($berita['link_berita']); ?>" target="_blank">
                                                            Lihat Berita
                                                        </a>
                                                    </small>
                                                </td>
                                                <td><?php echo htmlspecialchars($berita['author_name'] ?? 'Unknown'); ?></td>
                                                <td>
                                                    <?php if ($berita['status'] === 'published'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Published
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-file-alt me-1"></i>Draft
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo date('d/m/Y', strtotime($berita['created_at'])); ?><br>
                                                        <?php echo date('H:i', strtotime($berita['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="berita-edit.php?id=<?php echo $berita['id']; ?>" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?php echo $berita['id']; ?>, '<?php echo htmlspecialchars(addslashes($berita['judul'])); ?>')"
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
                                                    <p class="text-muted">Belum ada berita</p>
                                                    <a href="berita-add.php" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Tambah Berita Pertama
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
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo $filter_status; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $filter_status; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&status=<?php echo $filter_status; ?>">
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
    
    <script>
    function confirmDelete(id, judul) {
        if (confirm('Apakah Anda yakin ingin menghapus berita "' + judul + '"?')) {
            window.location.href = '../actions/berita_delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>
