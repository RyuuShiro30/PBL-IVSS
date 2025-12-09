<?php
/**
 * Edit Riset
 * File: pages/riset-edit.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil ID Riset
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'ID Riset tidak valid!';
    header('Location: riset-list.php');
    exit();
}

// Ambil data Riset
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM riset WHERE id = ?");
$stmt->execute([$id]);
$riset = $stmt->fetch();

// Ambil daftar dosen
$stmtDosen = $pdo->query("SELECT id, nama FROM dosen ORDER BY nama ASC");
$daftarDosen = $stmtDosen->fetchAll();

// Ambil daftar mahasiswa
$stmtMhs = $pdo->query("SELECT id, nama FROM mahasiswa ORDER BY nama ASC");
$daftarMahasiswa = $stmtMhs->fetchAll();

// Ambil dosen yang terlibat
$stmt = $pdo->prepare("SELECT id_dosen FROM riset_dosen WHERE id_riset = ?");
$stmt->execute([$id]);
$selectedDosen = array_column($stmt->fetchAll(), 'id_dosen');

// Ambil mahasiswa yang terlibat
$stmt = $pdo->prepare("SELECT id_mahasiswa FROM riset_mahasiswa WHERE id_riset = ?");
$stmt->execute([$id]);
$selectedMahasiswa = array_column($stmt->fetchAll(), 'id_mahasiswa');


if (!$riset) {
    $_SESSION['error'] = 'Riset tidak ditemukan!';
    header('Location: riset-list.php');
    exit();
}

// Ambil pesan error jika ada
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Riset - Admin Lab IVSS</title>
    
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
                            <i class="fas fa-edit me-2"></i>Edit Riset
                        </h1>
                        <a href="riset-list.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Form Edit Riset -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-newspaper me-2"></i>Form Edit Riset
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/riset_edit_process.php" method="POST" enctype="multipart/form-data" id="formRiset">
                                        
                                        <input type="hidden" name="id" value="<?php echo $riset['id']; ?>">

                                        <div class="mb-3">
                                            <label for="judul" class="form-label">
                                                Judul Riset <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($riset['judul']); ?>" placeholder="Masukkan judul riset" required>
                                            
                                        </div>

                                         <div class="mb-3">
                                            <label for="link_riset" class="form-label">
                                                Link Riset <span class="text-danger">*</span>
                                            </label>
                                            <input type="url" class="form-control" id="link_riset" name="link_riset" value="<?php echo htmlspecialchars($riset['link_riset']); ?>" placeholder="https://example.com" required>
                                            <small class="text-muted">URL lengkap menuju halaman SINTA / Scopus</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="tahun" class="form-label">
                                                Tahun Riset <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="tahun" name="tahun" value="<?php echo htmlspecialchars($riset['tahun']); ?>"  placeholder="Masukkan tahun riset" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Dosen Terlibat:</label>

                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                                <?php foreach ($daftarDosen as $d): ?>
                                                    <div class="form-check">
                                                        <input 
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="dosen[]"
                                                            value="<?= $d['id'] ?>"
                                                            <?= in_array($d['id'], $selectedDosen) ? 'checked' : '' ?>
                                                        >
                                                        <label class="form-check-label">
                                                            <?= htmlspecialchars($d['nama']) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <small class="text-muted">Centang dosen yang terlibat dalam riset ini.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Mahasiswa Terlibat:</label>

                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                                <?php foreach ($daftarMahasiswa as $m): ?>
                                                    <div class="form-check">
                                                        <input 
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="mahasiswa[]"
                                                            value="<?= $m['id'] ?>"
                                                            <?= in_array($m['id'], $selectedMahasiswa) ? 'checked' : '' ?>
                                                        >
                                                        <label class="form-check-label">
                                                            <?= htmlspecialchars($m['nama']) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <small class="text-muted">Centang mahasiswa yang terlibat dalam riset ini.</small>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Riset
                                            </button>
                                            <a href="riset-list.php" class="btn btn-light">
                                                <i class="fas fa-times me-2"></i>Batal
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Riset
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Dibuat:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($riset['created_at'])); ?></p>
                                    
                                    <p class="mb-1"><strong>Terakhir Update:</strong></p>
                                    <p class="text-muted"><?php echo date('d F Y, H:i', strtotime($riset['updated_at'])); ?></p>
                                    
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/admin.js"></script>
    
    <script>

    // Form validation
    document.getElementById('formRiset').addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value.trim();
        const link = document.getElementById('link_riset').value.trim();
        const tahun = document.getElementById('tahun').value.trim();

        if (!judul || !link || !tahun) {
            e.preventDefault();
            alert('Harap isi semua field yang wajib diisi!');
            return false;
        }

        // Disable button to prevent double submit
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });
    </script>
</body>
</html>
