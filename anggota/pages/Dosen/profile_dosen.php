<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['id'])) {
    die("Anda harus login terlebih dahulu.");
}

$id = $_SESSION['id'];

$stmt = $conn->prepare("
    SELECT 
        id,
        nama,
        email,
        dosen_profile,
        biografi_dosen,
        lokasi_dosen,
        link_sinta_dosen
    FROM dosen
    WHERE id = :id
");
$stmt->bindValue(":id", $id);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Profil tidak ditemukan.");
}

$foto = (!empty($data['dosen_profile']))
        ? "../../../admin-lab/assets/img/logo/" . $data['dosen_profile']
        : "../../img/default-user.png";

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Dosen</title>
    <link rel="stylesheet" href="../../css/Dosen/profile_dosen.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <?php include 'sidebar_dosen.php'; ?>
    <div class="content">

        <h1 class="page-title">Profil Dosen</h1>
        <p class="page-subtitle">Informasi lengkap dosen laboratorium</p>

        <div class="profile-card">
            <div class="profile-left">
                  <div class="avatar">
                    <img src="<?= $foto ?>" alt="Foto Profil">
                  </div>

                <div class="dosen-name"><?= htmlspecialchars($data['nama']) ?></div>  
                <div class="dosen-role">Dosen</div>
                <div class="kode-dosen">ID Dosen: <?= htmlspecialchars($data['id']) ?></div>
            </div>

            <div class="profile-right">
                <div class="item">
                    <span class="label">Email</span>
                    <span class="value"><?= htmlspecialchars($data['email']) ?></span>
                </div>
                <div class="item">
                    <span class="label">Lokasi Dosen</span>
                    <span class="value"><?= htmlspecialchars($data['lokasi_dosen'] ?: '-') ?></span>
                </div>
                <div class="item">
                    <span class="label">Link Sinta</span>
                    <span class="value">
                        <?php if ($data['link_sinta_dosen']): ?>
                            <a href="<?= htmlspecialchars($data['link_sinta_dosen']) ?>" target="_blank">
                                <?= htmlspecialchars($data['link_sinta_dosen']) ?>
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </span>
                </div>

                <div class="item" style="grid-column: span 2;">
                    <span class="label">Biografi</span>
                    <span class="value"><?= nl2br(htmlspecialchars($data['biografi_dosen'] ?: '-')) ?></span>
                </div>

            </div>
        </div>

    </div>

<script>
    feather.replace();
</script>
</body>
</html>
