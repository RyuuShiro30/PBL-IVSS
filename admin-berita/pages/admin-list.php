<?php


session_start();
require_once '../config/database.php';

// Cek apakah sudah login dan role super admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'superadmin') {
    $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini!';
    header('Location: dashboard.php');
    exit();
}

// Ambil pesan jika ada
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Ambil daftar admin
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM admin_berita ORDER BY created_at DESC");
$admin_list = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - Admin Berita Lab Kampus</title>
    
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
                            <i class="fas fa-users-cog me-2"></i>Kelola Admin
                        </h1>
                        <a href="admin-add.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Tambah Admin
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

                    <!-- Admin Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Data Admin (Total: <?php echo count($admin_list); ?>)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Foto</th>
                                            <th width="20%">Nama Lengkap</th>
                                            <th width="15%">Username</th>
                                            <th width="20%">Email</th>
                                            <th width="10%">Role</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($admin_list as $index => $admin): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td>
                                                <img src="../assets/img/<?php echo htmlspecialchars($admin['foto']); ?>" 
                                                     class="rounded-circle" 
                                                     style="width: 40px; height: 40px; object-fit: cover;"
                                                     onerror="this.src='../assets/img/default-avatar.png'">
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($admin['nama_lengkap']); ?></strong>
                                                <?php if ($admin['id'] === $_SESSION['admin_id']): ?>
                                                <span class="badge bg-info ms-2">Anda</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                            <td>
                                                <?php if ($admin['role'] === 'superadmin'): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-crown me-1"></i>Super Admin
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-user me-1"></i>Admin
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($admin['status'] === 'aktif'): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="admin-edit.php?id=<?php echo $admin['id']; ?>" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?php echo $admin['id']; ?>, '<?php echo htmlspecialchars(addslashes($admin['nama_lengkap'])); ?>')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
        if (confirm('Apakah Anda yakin ingin menghapus admin "' + nama + '"?\n\nPeringatan: Semua data terkait admin ini akan terhapus!')) {
            window.location.href = '../actions/admin_delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>
