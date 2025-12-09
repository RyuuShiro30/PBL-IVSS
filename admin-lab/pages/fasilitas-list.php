<?php
/**
 * Daftar Fasilitas
 * File: pages/Fasilitas-list.php
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

// Query Fasilitas
$pdo = getDBConnection();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// count total
$count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM fasilitas");
$count_stmt->execute();
$total_fasilitas = $count_stmt->fetch()['total'];
$total_pages = ceil($total_fasilitas / $limit);

$stmt = $pdo->prepare("
    SELECT *
    FROM fasilitas
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->execute([$limit, $offset]);
$fasilitas_list = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Fasilitas - Admin Lab IVSS</title>
    
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
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-building me-2"></i>Daftar Fasilitas
                        </h1>
                        <a href="fasilitas-add.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Fasilitas
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

                    <!-- Fasilitas Table -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">Nama</th>
                                            <th width="30%">Deskripsi</th>
                                            <th width="10%">Gambar</th>
                                            <th width=10%>Logo</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($fasilitas_list) > 0): ?>
                                            <?php foreach ($fasilitas_list as $index => $fasilitas): ?>
                                            <tr>
                                                <td><?php echo $offset + $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($fasilitas['nama']); ?></strong><br>
                                                </td>
                                                <td><?php echo htmlspecialchars($fasilitas['deskripsi_fasilitas']);?></td>
                                                <td>
                                                    <?php if ($fasilitas['gambar_fasilitas']): ?>
                                                    <img src="../assets/img/fasilitas/<?php echo htmlspecialchars($fasilitas['gambar_fasilitas']); ?>" 
                                                         class="img-fasilitas" 
                                                         style="width: 60px; height: 40px; object-fit: cover;"
                                                         onerror="this.src='../assets/img/no-image.png'">
                                                    <?php else: ?>
                                                    <img src="../assets/img/no-image.png" 
                                                         class="img-fasilitas" 
                                                         style="width: 60px; height: 40px; object-fit: cover;">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($fasilitas['logo']): ?>
                                                        <img src="../assets/img/logo/<?php echo htmlspecialchars($fasilitas['logo']); ?>"
                                                            class="img-logo"
                                                            style="width: 40px; height: 40px; object-fit: contain; background: #f8f8f8; padding: 3px; border-radius: 6px;"
                                                            onerror="this.src='../assets/img/no-image.png'">
                                                    <?php else: ?>
                                                        <img src="../assets/img/no-image.png"
                                                            class="img-logo"
                                                            style="width: 40px; height: 40px; object-fit: contain;">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo date('d/m/Y', strtotime($fasilitas['created_at'])); ?><br>
                                                        <?php echo date('H:i', strtotime($fasilitas['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="fasilitas-edit.php?id=<?php echo $fasilitas['id']; ?>" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?php echo $fasilitas['id']; ?>, '<?php echo htmlspecialchars(addslashes($fasilitas['nama'])); ?>')"
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
                                                    <p class="text-muted">Belum ada fasilitas</p>
                                                    <a href="fasilitas-add.php" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Tambah Fasilitas Pertama
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

                                    <!-- Prev -->
                                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>

                                    <!-- Number -->
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>

                                    <!-- Next -->
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
    function confirmDelete(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus fasilitas "' + nama + '"?')) {
            window.location.href = '../actions/fasilitas_delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>
