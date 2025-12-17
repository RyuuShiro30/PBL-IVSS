<?php
session_start();
require __DIR__ . '/../config/database.php';

$id   = $_GET['id']   ?? null;
$nama = $_GET['nama'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = ?");
    $stmt->execute([(int)$id]);
} elseif ($nama) {
    $stmt = $pdo->prepare("SELECT * FROM dosen WHERE nama = ?");
    $stmt->execute([$nama]);
} else {
    die('Parameter dosen tidak valid');
}

$dosen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dosen) {
    die('Dosen tidak ditemukan');
}

// ================= PAGINATION PUBLIKASI =================
$limitPublikasi = 3;
$pagePublikasi  = isset($_GET['page_publikasi']) ? max(1, (int)$_GET['page_publikasi']) : 1;
$offsetPublikasi = ($pagePublikasi - 1) * $limitPublikasi;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM publikasi_dosen WHERE id_dosen = ?");
$stmt->execute([$id]);
$totalPublikasi = (int)$stmt->fetchColumn();

$totalPagePublikasi = ceil($totalPublikasi / $limitPublikasi);

// ================= PUBLIKASI =================
$stmt = $pdo->prepare("
    SELECT * FROM publikasi_dosen
    WHERE id_dosen = ?
    ORDER BY tahun_publikasi DESC
    LIMIT $limitPublikasi OFFSET $offsetPublikasi
");
$stmt->execute([$id]);
$publikasi = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ================= MAHASISWA BIMBINGAN =================
$stmt = $pdo->prepare("SELECT nama, nim, prodi FROM mahasiswa WHERE dosen_id = ?");
$stmt->execute([$id]);
$bimbingan = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ================= PENDIDIKAN =================
$stmt = $pdo->prepare("SELECT * FROM pendidikan WHERE dosen_id = ? ORDER BY tahun_lulus DESC");
$stmt->execute([$id]);
$pendidikan = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ================= SERTIFIKAT =================
$stmt = $pdo->prepare("SELECT * FROM sertifikat WHERE dosen_id = ? ORDER BY tahun DESC");
$stmt->execute([$id]);
$sertifikat = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ================= FOTO DOSEN =================
$foto_folder = '../../admin-lab/assets/img/logo/'; // folder tempat foto dosen
$foto_path = (!empty($dosen['dosen_profile']) && file_exists($foto_folder . $dosen['dosen_profile']))
    ? $foto_folder . $dosen['dosen_profile']
    : $foto_folder . 'default.png'; // default jika belum ada foto
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Dosen</title>
    <link rel="stylesheet" href="../style/navbar.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body {
    background: #eef3f9;
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

.container {
    width: 92%;
    max-width: 1100px;
    margin: 40px auto;
    display: flex;
    flex-direction: column;
    padding-top: 100px;
    margin-bottom: 100px;
    gap: 35px;
}

/* HEADER TENGAH */
.center-header {
    width: 100%;
    display: flex;
    justify-content: center;
}

.header-card {
    border-left: none !important;
    width: 100%;
    max-width: 600px;
    text-align: center;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    padding: 40px 32px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #0A192F;
}

.nama {
    font-size: 30px;
    font-weight: 700;
    margin: 15px 0 4px;
}

.instansi {
    font-size: 15px;
    color: #595959;
}

/* ================= PAGINATION ================= */
.pagination {
    margin-top: 20px;
    text-align: center;
}

.page-link {
    display: inline-block;
    padding: 6px 12px;
    margin: 2px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    background: #FF9D00;
    color: #ffffff;
    transition: 0.2s ease;
}

.page-link:hover {
    background: #0A192F;
}

/* GRID IDENTITAS + PROFIL */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 35px;
}

/* CARD */
.card {
    background: #fff;
    border-radius: 18px;
    padding: 28px 32px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    border-left: 6px solid #0A192F;
}

/* IDENTITAS */
.identitas-card {
    margin-top: 0 !important;
}

.judul-card {
    font-size: 20px;
    font-weight: 700;
    color: #0d6efd;
    margin-bottom: 16px;
}

.data-grid p {
    margin: 6px 0;
    font-size: 15px;
}

/* LINK PROFIL AKADEMIK */
.link-list a {
    display: block;
    margin: 4px 0;
    font-weight: 600;
    color: #0d6efd;
    text-decoration: none;
}

.link-list a:hover {
    text-decoration: underline;
}

/* TAB */
.tabs-container {
    background: #fff;
    padding: 0;
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.tabs {
    display: flex;
    border-bottom: 1px solid #e0e0e0;
}

.tab-button {
    flex: 1;
    padding: 14px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
}

.tab-button.active {
    background: #FF9D00;
    color: white;
}

.tab-content {
    display: none;
    padding: 25px 30px;
}

.tab-content.active {
    display: block;
}

.list-card {
    background: #f7f9ff;
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 12px;
    border-left: 5px solid #0d6efd;
}

/* === FOOTER === */
.footer {
        width: 100%;
        background: #0A192F;
        color: white;
        padding: 60px 80px 30px;
    }

    /* GRID 3 KOLOM SIMETRIS */
    .footer-content {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 60px;
        align-items: start;
        margin-bottom: 35px;
    }

    /* KOLOM KIRI AGAR TIDAK MELEBAR */
    .footer-col:first-child {
        max-width: 300px;
    }

    /* LOGO-LOGO LAB */
    .footer-logos {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 15px;
    }

    .footer-logos img {
        max-height: 65px;
        width: auto;
        object-fit: contain;
    }

    /* TEKS DI KOLOM KIRI */
    .footer-col p {
        font-size: 15px;
        line-height: 1.7;
        opacity: 0.9;
        margin-top: 20px;
    }

    /* JUDUL KOLOM */
    .footer-col h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #FF9D00;
    }

    /* LINK */
    .footer-col a {
        display: block;
        color: #dcdcdc;
        margin-bottom: 8px;
        text-decoration: none;
        font-size: 15px;
        transition: 0.2s;
    }

    .footer-col a:hover {
        color: #FF9D00;
    }

    /* FOOTER BOTTOM */
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.25);
        text-align: center;
        padding: 12px 0;
        font-size: 14px;
        opacity: 0.8;
    }

    .social-icons {
        display: flex;
        gap: 12px;
        margin-top: 10px;
    }

    .social-icon img {
        width: 28px;
        height: 28px;
        filter: brightness(0) invert(1);
        /* jadi putih */
        transition: 0.2s ease;
    }

    .social-icon img:hover {
        transform: scale(1.15);
        filter: brightness(0) invert(1) drop-shadow(0 0 4px #FF9D00);
    }

    .footer-col .operating-hours-title {
        margin-top: 25px;
    }

/* ================= GLOBAL SAFETY ================= */
@media (max-width: 768px) {
    body {
        overflow-x: hidden;
    }
}

/* ================= CONTAINER ================= */
@media (max-width: 992px) {
    .container {
        width: 94%;
        padding-top: 90px;
        gap: 28px;
    }
}

@media (max-width: 768px) {
    .container {
        width: 95%;
        padding-top: 120px;
        gap: 25px;
    }
}

/* ================= HEADER CARD ================= */
@media (max-width: 768px) {
    .header-card {
        padding: 28px 22px;
    }

    .photo {
        width: 120px;
        height: 120px;
    }

    .nama {
        font-size: 22px;
    }

    .role_lab {
        font-size: 14px;
        color: #666;
    }
}

@media (max-width: 480px) {
    .photo {
        width: 100px;
        height: 100px;
    }

    .nama {
        font-size: 20px;
    }
}

/* ================= GRID IDENTITAS ================= */
@media (max-width: 992px) {
    .grid-2 {
        grid-template-columns: 1fr;
        gap: 25px;
    }
}

/* ================= CARD ================= */
@media (max-width: 768px) {
    .card {
        padding: 22px 20px;
    }

    .judul-card {
        font-size: 18px;
    }

    .data-grid p {
        font-size: 14px;
    }
}

/* ================= TAB ================= */
@media (max-width: 768px) {
    .tabs {
        flex-wrap: wrap;
    }

    .tab-button {
        flex: 1 1 50%;
        font-size: 15px;
        padding: 12px;
    }

    .tab-content {
        padding: 20px;
    }

    .list-card {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .tab-button {
        flex: 1 1 100%;
    }
}

/* ================= PAGINATION ================= */
@media (max-width: 768px) {
    .pagination {
        margin-top: 16px;
    }

    .page-link {
        padding: 6px 10px;
        font-size: 14px;
        margin: 3px 2px;
    }
}


/* ================= FOOTER ================= */
@media (max-width: 992px) {
    .footer-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }
}

@media (max-width: 576px) {
    .footer {
        padding: 45px 24px 25px;
    }

    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .footer-logos {
        justify-content: center;
    }

    .social-icons {
        justify-content: center;
    }

    .footer-col:first-child {
        max-width: 100%;
    }
}

</style>

</head>
<body>

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo-area">
        <img src="../img/IVSS.png" alt="Logo" class="logo">
        <span class="site-title">IVSS</span>
    </div>
    <nav class="menu">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="news.php">News</a>
        <a href="research.php">Research</a>
        <a href="members.php">Members</a>
    </nav>
</header>

<!-- ================= CONTAINER ================= -->
<div class="container">

    <!-- ===== HEADER TENGAH ===== -->
    <div class="center-header">
        <div class="card header-card">
            <img src="<?= $foto_path ?>" class="photo" alt="Foto Dosen">
            <div class="nama"><?= htmlspecialchars($dosen['nama']) ?></div>
            <div class="role_lab"><?= htmlspecialchars($dosen['role_lab']) ?></div>
        </div>
    </div>

    <!-- ===== GRID IDENTITAS + PROFIL ===== -->
    <div class="grid-2">

        <!-- IDENTITAS -->
        <div class="card identitas-card">
            <h2 class="judul-card">Identitas Dosen</h2>
            <div class="data-grid">
                <p><strong>NIP:</strong> <?= $dosen['nip'] ?: '-' ?></p>
                <p><strong>NIDN:</strong> <?= $dosen['nidn'] ?: '-' ?></p>
                <p><strong>Email:</strong> <?= $dosen['email'] ?></p>
                <p><strong>Program Studi:</strong> <?= $dosen['prodi_dosen'] ?></p>
            </div>
        </div>

        <!-- PROFIL AKADEMIK -->
        <div class="card">
            <h2 class="judul-card">Profil Akademik & Profesional</h2>
            <div class="link-list">
                <?php if ($dosen['google_scholar_dosen']) : ?>
                    <a href="<?= $dosen['google_scholar_dosen'] ?>" target="_blank">Google Scholar</a>
                <?php endif; ?>
                <?php if ($dosen['link_sinta_dosen']) : ?>
                    <a href="<?= $dosen['link_sinta_dosen'] ?>" target="_blank">Sinta</a>
                <?php endif; ?>
                <?php if ($dosen['linkedin_dosen']) : ?>
                    <a href="<?= $dosen['linkedin_dosen'] ?>" target="_blank">LinkedIn</a>
                <?php endif; ?>
                <a href="mailto:<?= $dosen['email'] ?>">Email</a>
            </div>
        </div>

    </div>

    <!-- ===== TABS ===== -->
    <div class="tabs-container">

        <div class="tabs">
            <button class="tab-button active" onclick="openTab('publikasi')">Publikasi</button>
            <button class="tab-button" onclick="openTab('bimbingan')">Mahasiswa Bimbingan</button>
            <button class="tab-button" onclick="openTab('pendidikan')">Pendidikan</button>
            <button class="tab-button" onclick="openTab('sertifikasi')">Sertifikasi</button>
        </div>

        <!-- PUBLIKASI -->
        <div class="tab-content active" id="publikasi">
            <?php if ($publikasi): foreach ($publikasi as $p): ?>
                <div class="list-card">
                    <?= htmlspecialchars($p['nama_publikasi']) ?> —
                    <a href="<?= $p['link_publikasi'] ?>" target="_blank">Link Publikasi</a>
                    (<?= $p['tahun_publikasi'] ?>)
                </div>
            <?php endforeach; else: ?>
                <div class="list-card">Belum ada publikasi</div>
            <?php endif; ?>
                <!-- PAGINATION -->    
            <?php if ($totalPagePublikasi > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPagePublikasi; $i++): ?>
                        <a
                            href="?id=<?= $id ?>&page_publikasi=<?= $i ?>"
                            class="page-link <?= ($i == $pagePublikasi) ? 'active' : '' ?>"
                        >
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>


        <!-- BIMBINGAN -->
        <div class="tab-content" id="bimbingan">
            <?php if ($bimbingan): foreach ($bimbingan as $m): ?>
                <div class="list-card">
                    <?= htmlspecialchars($m['nama']) ?> (<?= $m['nim'] ?>) - <?= $m['prodi'] ?>
                </div>
            <?php endforeach; else: ?>
                <div class="list-card">Belum ada mahasiswa bimbingan</div>
            <?php endif; ?>
        </div>

        <!-- PENDIDIKAN -->
        <div class="tab-content" id="pendidikan">
            <?php if ($pendidikan): foreach ($pendidikan as $pd): ?>
                <div class="list-card">
                    <?= $pd['jenjang'] ?> - <?= $pd['jurusan'] ?> - <?= $pd['universitas'] ?>
                    (<?= $pd['tahun_lulus'] ?>)
                </div>
            <?php endforeach; else: ?>
                <div class="list-card">Data pendidikan belum tersedia</div>
            <?php endif; ?>
        </div>

        <!-- SERTIFIKAT -->
        <div class="tab-content" id="sertifikasi">
            <?php if ($sertifikat): foreach ($sertifikat as $s): ?>
                <div class="list-card">
                    <?= $s['nama_sertifikat'] ?> - <?= $s['penyelenggara'] ?>
                    (<?= $s['tahun'] ?>)
                </div>
            <?php endforeach; else: ?>
                <div class="list-card">Belum ada sertifikasi</div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ================= FOOTER SECTION ================= -->
<footer class="footer">

    <div class="footer-content">

        <!-- Logo + Deskripsi -->
        <div class="footer-col">
            <div class="footer-logos">
                <img src="../img/IVSS.png" class="footer-logo">
                <img src="../img/polinema.png" class="footer-logo">
                <img src="../img/jti.webp" class="footer-logo">
            </div>

            <p>
                Intelligent Vision & Smart System Laboratory<br>
                Politeknik Negeri Malang<br>
                Jurusan Teknologi Informasi
            </p>
        </div>

        <!-- Quick Links -->
        <div class="footer-col">
            <h3>Quick Links</h3>
            <a href="about.php">About</a>
            <a href="research.php">Research</a>
            <a href="members.php">Members</a>
            <a href="news.php">News</a>
        </div>

        <!-- Contact -->
        <div class="footer-col">
            <h3>Contact</h3>
            <p>Email: ivss@polinema.ac.id</p>
            <p>Telp: (0341) 404424</p>
            <p>Jl. Soekarno-Hatta No. 9, Malang</p>
        </div>

        <!-- Follow Us -->
        <div class="footer-col">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="https://www.tiktok.com/@polinema_campus?_r=1&_t=ZS-91qpSTjlNpJ" target="_blank" class="social-icon"><img src="../icon/tiktok.svg" alt="TikTok"></a>
                <a href="https://www.instagram.com/jtipolinema?igsh=YTFpdGtrdXdqeTZ4" target="_blank" class="social-icon"><img src="../icon/instagram.svg" alt="Instagram"></a>
                <a href="https://youtube.com/@politekniknegerimalangofficial?si=SyxJ1hhDib9aLjzx" target="_blank" class="social-icon"><img src="../icon/youtube.svg" alt="YouTube"></a>
            </div>
            <h3 class="operating-hours-title">Jam Operasional</h3>
            <p>07.00 - 15.00</p>
        </div>
    </div>

    <div class="footer-bottom">
        © 2025 IVSS Laboratory - All Rights Reserved.
    </div>
</footer>

<script src="../JS/profil_dosen.js"></script>
</body>
</html>