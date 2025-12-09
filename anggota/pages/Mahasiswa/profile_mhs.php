<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['id_mhs'])) {
    die("Anda harus login terlebih dahulu.");
}

$id = $_SESSION['id_mhs'];

$stmt = $conn->prepare("
    SELECT 
        pm.id_profile,
        pm.id_mhs,
        pm.nama,
        pm.email,
        pm.prodi,
        pm.tahun_lulus,
        pm.id_dosen,
        pm.mahasiswa_profile AS foto_profile,
        d.nama AS nama_dosen
    FROM profile_mahasiswa pm
    LEFT JOIN dosen d ON pm.id_dosen = d.id
    WHERE pm.id_mhs = :id
");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Profil tidak ditemukan.");
}

$pub = $conn->prepare("SELECT COUNT(*) AS total FROM riset WHERE id_mhs = :id");
$pub->bindValue(":id", $id);
$pub->execute();
$total_publikasi = $pub->fetch(PDO::FETCH_ASSOC)['total'];

$foto = (!empty($data['foto_profile'])) 
        ? "../../../user/uploads/" . $data['foto_profile'] 
        : "../../img/default-user.png";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Mahasiswa</title>
    <link rel="stylesheet" href="../../css/Mahasiswa/profile_mhs.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar_mahasiswa.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="content">

        <h1 class="page-title">Profil Mahasiswa</h1>
        <p class="page-subtitle">Informasi lengkap akun Anda</p>

        <div class="profile-card">

            <!-- LEFT -->
            <div class="profile-left">
                <div class="avatar">
                    <img src="<?= htmlspecialchars($foto) ?>" alt="Foto Profil">
                </div>

                <div class="name"><?= htmlspecialchars($data['nama']) ?></div>
                <div class="role">Mahasiswa</div>

                <div class="id-anggota">ID Anggota: <b><?= htmlspecialchars($data['id_mhs']) ?></b></div>
            </div>

            <!-- RIGHT -->
            <div class="profile-right">

                <div class="item">
                    <span class="label">Email</span>
                    <span class="value"><?= htmlspecialchars($data['email']) ?></span>
                </div>

                <div class="item">
                    <span class="label">Program Studi</span>
                    <span class="value"><?= htmlspecialchars($data['prodi']) ?></span>
                </div>

                <div class="item">
                    <span class="label">Tahun Lulus</span>
                    <span class="value"><?= $data['tahun_lulus'] ?: '-' ?></span>
                </div>

                <div class="item">
                    <span class="label">Jumlah Riset</span>
                    <span class="value"><?= $total_publikasi ?></span>
                </div>

                <div class="item">
                    <span class="label">Dosen Pengampu</span>
                    <span class="value"><?= $data['nama_dosen'] ?: '-' ?></span>
                </div>
                <div class="action">
    <a href="edit_profile.php" class="btn-edit">Edit Profil</a>
</div>
            </div>
        </div>

    </div>

<script>
    feather.replace();
</script>
</body>
</html>
<style>
    .btn-edit {
    display: inline-block;
    background: #0A4D68;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
}
.btn-edit:hover {
    background: #086084;
}
</style>
