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
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Query Anggota
$pdo = getDBConnection();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Query Dosen
$stmtDosen = $pdo->prepare("SELECT * FROM view_dosen_full ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmtDosen->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtDosen->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtDosen->execute();
$dosen = $stmtDosen->fetchAll();

// Query Mahasiswa
$stmtMHS = $pdo->prepare("SELECT * FROM mahasiswa ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmtMHS->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtMHS->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtMHS->execute();
$mahasiswa = $stmtMHS->fetchAll();

// Count Dosen
$count_stmt_dosen = $pdo->prepare("SELECT COUNT(*) as total FROM dosen");
$count_stmt_dosen->execute();
$total_dosen = $count_stmt_dosen->fetch()['total'];
$total_pages_dosen = ceil($total_dosen / $limit);

// Count Mahasiswa
$count_stmt_mhs = $pdo->prepare("SELECT COUNT(*) as total FROM mahasiswa");
$count_stmt_mhs->execute();
$total_mhs = $count_stmt_mhs->fetch()['total'];
$total_pages_mhs = ceil($total_mhs / $limit);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - Admin Lab IVSS</title>
    
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
                            <i class="fas fa-person me-2"></i>Daftar Anggota Dosen
                        </h1>
                        <a href="anggota-add.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Anggota
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

                    <!-- Anggota Table -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="18%">Nama</th>
                                            <th width="22%">Email</th>
                                            <th width="10%">Foto</th>
                                            <th width="20%">Biografi</th>
                                            <th width="20%">Pendidikan</th>
                                            <th width="20%">Sertifikasi</th>
                                            <th width="10%">Link Sinta</th>
                                            <th width="10%">Lokasi</th>
                                            <th width="10%">Tanggal</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($dosen) > 0): ?>
                                            <?php foreach ($dosen as $index => $dosen): ?>
                                            <tr>
                                                <td><?php echo $offset + $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($dosen['nama']); ?></strong><br>
                                                </td>
                                                <td><?php echo htmlspecialchars($dosen['email']);?></td>
                                                <td>
                                                    <?php if ($dosen['dosen_profile']): ?>
                                                    <img src="../assets/img/logo/<?php echo htmlspecialchars($dosen['dosen_profile']); ?>" 
                                                         class="img-dosen" 
                                                         style="width: 60px; height: 40px; object-fit: cover;"
                                                         onerror="this.src='../assets/img/default-avatar.png'">
                                                    <?php else: ?>
                                                    <img src="../assets/img/default-avatar.png" 
                                                         class="img-dosen" 
                                                         style="width: 60px; height: 40px; object-fit: cover;">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($dosen['biografi_dosen'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($dosen['pendidikan'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($dosen['sertifikat'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($dosen['link_sinta_dosen'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($dosen['lokasi_dosen'] ?? ''); ?></td>
                                                <td>
                                                    <small>
                                                        <?php echo date('d/m/Y', strtotime($dosen['created_at'])); ?><br>
                                                        <?php echo date('H:i', strtotime($dosen['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="dosen-edit.php?id=<?php echo $dosen['id']; ?>" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDeleteDosen(<?php echo $dosen['id']; ?>, '<?php echo htmlspecialchars(addslashes($dosen['nama'])); ?>')"
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
                                                    <p class="text-muted">Belum ada anggota dosen</p>
                                                    <a href="anggota-add.php" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Tambah Anggota Dosen Pertama
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages_dosen > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">

                                    <!-- Prev -->
                                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <!-- Number -->
                                    <?php for ($i = 1; $i <= $total_pages_dosen; $i++): ?>
                                    <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>

                                    <!-- Next -->
                                    <li class="page-item <?php echo $page >= $total_pages_dosen ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>

                                </ul>
                            </nav>
                            <?php endif; ?>

                        </div>
                    </div>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-person me-2"></i>Daftar Anggota Mahasiswa
                        </h1>
                    </div>
                    <!-- Anggota Table -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="18%">Nama</th>
                                            <th width="22%">NIM</th>
                                            <th width="20%">Prodi</th>
                                            <th width="10%">Email</th>
                                            <th width="10%">Tahun Lulus</th>
                                            <th width="10%">Foto</th>
                                            <th width="10%">Tanggal</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($mahasiswa) > 0): ?>
                                            <?php foreach ($mahasiswa as $index => $mahasiswa): ?>
                                            <tr>
                                                <td><?php echo $offset + $index + 1; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($mahasiswa['nama'] ?? ''); ?></strong><br>
                                                </td>
                                                <td><?php echo htmlspecialchars($mahasiswa['nim'] ?? '');?></td>
                                                <td><?php echo htmlspecialchars($mahasiswa['prodi'] ?? '');?></td>
                                                <td><?php echo htmlspecialchars($mahasiswa['email'] ?? '');?></td>
                                                <td><?php echo htmlspecialchars($mahasiswa['tahun_lulus'] ?? '');?></td>
                                                <td>
                                                    <?php if ($mahasiswa['mahasiswa_profile']): ?>
                                                    <img src="../assets/img/mahasiswa/<?php echo htmlspecialchars($mahasiswa['mahasiswa_profile']); ?>" 
                                                         class="img-mahasiswa" 
                                                         style="width: 60px; height: 40px; object-fit: cover;"
                                                         onerror="this.src='../assets/img/default-avatar.png'">
                                                    <?php else: ?>
                                                    <img src="../assets/img/default-avatar.png" 
                                                         class="img-dosen" 
                                                         style="width: 60px; height: 40px; object-fit: cover;">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo date('d/m/Y', strtotime($mahasiswa['created_at'])); ?><br>
                                                        <?php echo date('H:i', strtotime($mahasiswa['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="mhs-edit.php?id=<?php echo $mahasiswa['id']; ?>" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDeleteMhs(<?php echo $mahasiswa['id']; ?>, '<?php echo htmlspecialchars(addslashes($mahasiswa['nama'])); ?>')"
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
                                                    <p class="text-muted">Belum ada anggota mahasiswa</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages_mhs > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo $filter_status; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <?php for ($i = 1; $i <= $total_pages_mhs; $i++): ?>
                                    <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $filter_status; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?php echo $page >= $total_pages_mhs ? 'disabled' : ''; ?>">
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
    function confirmDeleteDosen(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus anggota dosen "' + nama + '"?')) {
            window.location.href = '../actions/dosen_delete.php?id=' + id;
        }
    }
    function confirmDeleteMhs(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus anggota mahasiswa "' + nama + '"?')) {
            window.location.href = '../actions/mhs_delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>

