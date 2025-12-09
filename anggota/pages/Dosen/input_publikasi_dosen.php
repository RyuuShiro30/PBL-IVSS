<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: LoginDosen.php");
    exit;
}

require '../../koneksi.php';

$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_publikasi   = trim($_POST['nama_publikasi'] ?? '');
    $link_publikasi   = trim($_POST['link_publikasi'] ?? '');
    $tahun_publikasi  = trim($_POST['tahun_publikasi'] ?? '');
    $created_at       = trim($_POST['created_at'] ?? '');

    if ($nama_publikasi === '' || $link_publikasi === '' ||
        $tahun_publikasi === '' || $created_at === '') {

        $err = "Semua field wajib diisi.";

    } else {

        $id_dosen = $_SESSION['id'];

        $stmt = $conn->prepare("
            INSERT INTO publikasi_dosen 
            (nama_publikasi, link_publikasi, tahun_publikasi, created_at_publikasi, id_dosen)
            VALUES (:nama, :link, :tahun, :created, :id)
        ");

        $stmt->bindValue(':nama', $nama_publikasi);
        $stmt->bindValue(':link', $link_publikasi);
        $stmt->bindValue(':tahun', $tahun_publikasi);
        $stmt->bindValue(':created', $created_at);
        $stmt->bindValue(':id', $id_dosen);

        if ($stmt->execute()) {
            $success = "Publikasi dosen berhasil ditambahkan!";
        } else {
            $err = "Gagal menambah publikasi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Publikasi Dosen</title>
    <link rel="stylesheet" href="../../css/Dosen/publikasi_dosen.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

<?php include 'sidebar_dosen.php'; ?>

<div class="container">
    <div class="header">
        <h2 class="page-title">Tambah Publikasi Dosen</h2>
        <p class="subtitle">Tambahkan publikasi anda</p>
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
                <label>Nama Publikasi</label>
                <input type="text" name="nama_publikasi" placeholder="Masukkan nama publikasi">
            </div>

            <div class="form-group">
                <label>Link Publikasi</label>
                <input type="text" name="link_publikasi" placeholder="https://example.com/research">
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Tahun Publikasi</label>
                    <input type="number" name="tahun_publikasi" placeholder="Tahun">
                </div>

                <div class="form-group half">
                    <label>Created at</label>
                    <input type="date" name="created_at">
                </div>
            </div>

            <button class="btn-submit" type="submit">Simpan Publikasi</button>
        </form>
    </div>
</div>

<script>
    feather.replace();
</script>

</body>
</html>
