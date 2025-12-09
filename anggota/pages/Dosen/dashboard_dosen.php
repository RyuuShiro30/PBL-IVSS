<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: LoginDosen.php");
    exit;
}

require '../../koneksi.php';

$id = $_SESSION['id'];
$nama = $_SESSION['nama'];

$query = $conn->prepare("
    SELECT pd.*, d.nama 
    FROM publikasi_dosen pd
    JOIN dosen d ON pd.id_dosen = d.id
    WHERE pd.id_dosen = :id
    ORDER BY pd.created_at_publikasi DESC
");

$query->bindParam(":id", $id);
$query->execute();
$publikasi = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../../css/Dosen/dashboard_dosen.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <title>Dashboard Dosen</title>

    <style>
        .pub-list {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 5px;
        }
    </style>
</head>
<body>

<?php include 'sidebar_dosen.php'; ?>

<div class="content">

    <div class="header">
        <h1 class="title">Dashboard Dosen</h1>
        <p class="subtitle">Selamat Datang Dosen<br>Laboratorium IVSS</p>
    </div>

    <div class="card">
        <h2 class="section-title">Daftar Publikasi</h2>

        <div class="pub-list">
            <?php 
            $no = 1;
            foreach ($publikasi as $p): 
            ?>
                <div class="pub-item">
                    <div class="pub-number"><?= str_pad($p['id_publikasi'], 3, "0", STR_PAD_LEFT) ?></div>
                    <div class="pub-info">
                        <div class="pub-title"><?= htmlspecialchars($p['nama_publikasi']) ?></div>
                        <div class="pub-date">
                            <i data-feather="calendar"></i>
                            <?= date("d F Y", strtotime($p['created_at_publikasi'])) ?>
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
