<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../LoginMhs.php");
    exit;
}

require '../../koneksi.php';

$id_mahasiswa = $_SESSION['id_mhs'];
$nama = $_SESSION['name'];

$query = $conn->prepare("
    SELECT * FROM riset
    WHERE id_mhs = :id
    ORDER BY created_at DESC
");
$query->bindParam(":id", $id_mahasiswa);
$query->execute();
$publikasi = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../../css/Mahasiswa/dashboard_mhs.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <title>Dashboard Mahasiswa</title>

    <style>
        .pub-list {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 5px;
        }
    </style>
</head>
<body>

<?php include 'sidebar_mahasiswa.php'; ?>

<div class="content">
    <div class="header">
        <h1 class="title">Dashboard Mahasiswa</h1>
        <p class="subtitle">Selamat Datang Mahasiswa<br>Laboratorium IVSS</p>
    </div>

    <div class="card">
        <h2 class="section-title">Daftar Riset</h2>

        <div class="pub-list">
            <?php 
            $no = 1;
            foreach ($publikasi as $p): 
            ?>
                <div class="pub-item">
                    <div class="pub-number"><?= str_pad($no++, 3, "0", STR_PAD_LEFT) ?></div>
                    <div class="pub-info">
                        <div class="pub-title"><?= htmlspecialchars($p['judul']) ?></div>
                        <div class="pub-date">
                            <i data-feather="calendar"></i>
                            <?= date("d F Y", strtotime($p['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($publikasi) === 0): ?>
                <p class="empty">Belum ada publikasi</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    feather.replace();
</script>

</body>
</html>
