<?php
/**
 * Tambah Riset
 * File: pages/riset-add.php
 */

session_start();
require_once '../config/database.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil pesan error jika ada
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$pdo = getDBConnection();

// Ambil data dosen
$queryDosen = $pdo->query("SELECT id, nama FROM dosen ORDER BY nama ASC");
$dosenList = $queryDosen->fetchAll(PDO::FETCH_ASSOC);

// Ambil data mahasiswa
$queryMhs = $pdo->query("SELECT id, nama FROM mahasiswa ORDER BY nama ASC");
$mhsList = $queryMhs->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Riset - Admin Lab IVSS</title>
    
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
                            <i class="fas fa-plus me-2"></i>Tambah Riset Baru
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

                    <!-- Form Tambah Riset -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-newspaper me-2"></i>Form Riset
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="../actions/riset_add_process.php" method="POST" enctype="multipart/form-data" id="formRiset">
                                        
                                        <div class="mb-3">
                                            <label for="judul" class="form-label">
                                                Judul Riset <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul riset" required>
                                            
                                        </div>

                                        <div class="mb-3">
                                            <label for="link_riset" class="form-label">
                                                Link Riset <span class="text-danger">*</span>
                                            </label>
                                            <input type="url" class="form-control" id="link_riset" name="link_riset" 
                                                   placeholder="https://example.com" required>
                                            <small class="text-muted">URL lengkap menuju halaman SINTA / Scopus</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="tahun" class="form-label">
                                                Tahun Riset <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukkan tahun riset" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Dosen Peneliti <span class="text-danger">*</span></label>
                                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                                <?php foreach ($dosenList as $d): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                            name="id_dosen[]" value="<?= $d['id']; ?>" 
                                                            id="dosen_<?= $d['id']; ?>">
                                                        <label class="form-check-label" for="dosen_<?= $d['id']; ?>">
                                                            <?= htmlspecialchars($d['nama']); ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Mahasiswa (Opsional)</label>
                                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                                <?php if (!empty($mahasiswaList)): ?>
                                                    <?php foreach ($mahasiswaList as $m): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                name="id_mahasiswa[]" value="<?= $m['id']; ?>"
                                                                id="mhs_<?= $m['id']; ?>">
                                                            <label class="form-check-label" for="mhs_<?= $m['id']; ?>">
                                                                <?= htmlspecialchars($m['nama']); ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <small class="text-muted">Tidak ada data mahasiswa.</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="border-top pt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan Riset
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-redo me-2"></i>Reset Form
                                            </button>
                                            <a href="riset-list.php" class="btn btn-light">
                                                <i class="fas fa-times me-2"></i>Batal
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

        const id_dosen = document.querySelectorAll('input[name="id_dosen[]"]:checked');

        if (!judul || !link || !tahun || id_dosen.length === 0) {
            e.preventDefault();
            alert('Harap isi semua field yang wajib diisi termasuk memilih dosen!');
            return false;
        }

        // Prevent double submit
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });

    </script>

</body>
</html>
