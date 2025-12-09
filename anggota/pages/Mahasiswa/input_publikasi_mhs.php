<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../Login.php");
    exit;
}

require '../../koneksi.php';

$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul_riset   = trim($_POST['judul'] ?? '');
    $link_riset   = trim($_POST['link_riset'] ?? '');
    $tahun  = trim($_POST['tahun'] ?? '');
    $created_at       = trim($_POST['created_at'] ?? '');

    if ($judul_riset === '' || $link_riset === '' ||
        $tahun === '' || $created_at === '') {
        $err = "Semua field wajib diisi.";

    } else {

        // Convert date DD/MM/YYYY → YYYY-MM-DD
        $dateObj = DateTime::createFromFormat('d/m/Y', $created_at);

        if (!$dateObj) {
            $err = "Format tanggal salah! Gunakan DD/MM/YYYY.";
        } else {

            $created_at_db = $dateObj->format('Y-m-d');
            $id_mahasiswa = $_SESSION['id_mhs'];

            $stmt = $conn->prepare("
                INSERT INTO riset 
                (judul, link_riset, tahun, created_at, id_mhs)
                VALUES (:nama, :link, :tahun, :created, :id)
            ");

            $stmt->bindValue(':nama', $judul_riset);
            $stmt->bindValue(':link', $link_riset);
            $stmt->bindValue(':tahun', $tahun);
            $stmt->bindValue(':created', $created_at_db);
            $stmt->bindValue(':id', $id_mahasiswa);

            if ($stmt->execute()) {
                $success = "Publikasi berhasil ditambahkan!";
            } else {
                $err = "Gagal menambah publikasi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Publikasi</title>
    <link rel="stylesheet" href="../../css/Mahasiswa/publikasi_mhs.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

<?php include 'sidebar_mahasiswa.php'; ?>

<div class="container">
    <div class="header">
        <h2 class="page-title">Tambah Riset Anggota</h2>
        <p class="subtitle">Tambahkan riset anda</p>
    </div>
    
    <div class="form-card">

        <?php if ($err): ?>
            <div class="alert alert-error"><?= $err ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama Riset</label>
                <input type="text" name="judul" placeholder="Masukkan nama riset">
            </div>

            <div class="form-group">
                <label>Link Riset</label>
                <input type="text" name="link_riset" placeholder="https://example.com/research">
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Tahun Riset</label>
                    <input type="number" name="tahun" placeholder="Tahun">
                </div>

                <div class="form-group half">
                    <label>Created at</label>
                    <input type="text" name="created_at" placeholder="DD/MM/YYYY">
                </div>
            </div>

            <button class="btn-submit" type="submit">Simpan Riset</button>
        </form>
    </div>
</div>

<script>
    feather.replace();
</script>

</body>
</html>
