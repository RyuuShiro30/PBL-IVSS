<?php
session_start();
require __DIR__ . '/../config/database.php';

// Ambil parameter id atau nama
$id   = $_GET['id']   ?? null;
$nama = $_GET['nama'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->execute([(int)$id]);
} elseif ($nama) {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE nama = ?");
    $stmt->execute([$nama]);
} else {
    die('Parameter mahasiswa tidak valid');
}

$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$mahasiswa) die('Mahasiswa tidak ditemukan');

// ================= FOTO MAHASISWA =================
$foto_folder = '../../admin-lab/assets/img/mahasiswa/'; 
$foto_path = (!empty($mahasiswa['mahasiswa_profile']) && file_exists($foto_folder . $mahasiswa['mahasiswa_profile']))
    ? $foto_folder . $mahasiswa['mahasiswa_profile']
    : $foto_folder . 'default.png'; // default

// ================= RISET MAHASISWA =================
$stmt = $pdo->prepare("
    SELECT r.judul, r.link_riset, r.tahun 
    FROM riset r 
    JOIN riset_mahasiswa rm ON r.id = rm.id_riset 
    WHERE rm.id_mahasiswa = ? 
    ORDER BY r.tahun DESC
");
$stmt->execute([$id]);
$riset = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ================= PAGINATION =================
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

// Total riset
$stmtCount = $pdo->prepare("
    SELECT COUNT(*) as total 
    FROM riset r
    JOIN riset_mahasiswa rm ON r.id = rm.id_riset
    WHERE rm.id_mahasiswa = :id_mahasiswa
");
$stmtCount->execute([':id_mahasiswa' => $mahasiswa['id']]);
$totalRiset = $stmtCount->fetchColumn();
$totalPages = ceil($totalRiset / $limit);

// Ambil riset untuk page saat ini
$stmt = $pdo->prepare("
    SELECT r.judul, r.link_riset, r.tahun 
    FROM riset r
    JOIN riset_mahasiswa rm ON r.id = rm.id_riset
    WHERE rm.id_mahasiswa = :id_mahasiswa
    ORDER BY r.tahun DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':id_mahasiswa', $mahasiswa['id'], PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$riset = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ================= DOSEN PEMBIMBING =================
$stmt = $pdo->prepare("
    SELECT nama, prodi_dosen, email 
    FROM dosen 
    WHERE id = ?
");
$stmt->execute([$mahasiswa['dosen_id']]);
$dosen = $stmt->fetch(PDO::FETCH_ASSOC);
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
    background: #0A192F;
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

/* FOOTER */
.footer {
    width: 100%;
    background: #0A192F;
    color: white;
    padding: 60px 80px 30px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 60px;
    align-items: start;
    margin-bottom: 35px;
}

.footer-col:first-child {
    max-width: 300px;
}

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

.footer-col p {
    font-size: 15px;
    line-height: 1.7;
    opacity: 0.9;
    margin-top: 20px;
}

.footer-col h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #FF9D00;
}

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
    transition: 0.2s ease;
}

.social-icon img:hover {
    transform: scale(1.15);
    filter: brightness(0) invert(1) drop-shadow(0 0 4px #FF9D00);
}

.operating-hours-title {
    margin-top: 25px;
}
/* ================= RESPONSIVE ================= */

/* TABLET (≤ 992px) */
@media (max-width: 1024px) {

    .container {
        width: 94%;
        padding-top: 90px;
    }

    .grid-2 {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .header-card {
        padding: 32px 26px;
    }

    .nama {
        font-size: 26px;
    }

    .photo {
        width: 130px;
        height: 130px;
    }

    .footer-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }
}

/* MOBILE (≤ 768px) */
@media (max-width: 768px) {

    /* NAVBAR */
    .navbar {
        flex-direction: column;
        padding: 14px 20px;
        gap: 12px;
    }

    .menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
    }

    /* CONTAINER */
    .container {
        width: 95%;
        padding-top: 120px;
        gap: 28px;
    }

    /* HEADER CARD */
    .header-card {
        padding: 28px 22px;
    }

    .photo {
        width: 115px;
        height: 115px;
    }

    .nama {
        font-size: 22px;
    }

    .instansi {
        font-size: 14px;
    }

    /* CARD */
    .card {
        padding: 22px 20px;
    }

    .judul-card {
        font-size: 18px;
    }

    .data-grid p {
        font-size: 14px;
    }

    /* TAB */
    .tab-button {
        font-size: 15px;
        padding: 12px;
    }

    .tab-content {
        padding: 20px;
    }

    .list-card {
        font-size: 14px;
    }

    /* PAGINATION */
    .page-link {
        padding: 6px 10px;
        font-size: 14px;
    }

    /* FOOTER */
    .footer {
        padding: 50px 30px 25px;
    }

    .footer-content {
        grid-template-columns: 1fr;
        gap: 35px;
        text-align: center;
    }

    .footer-logos {
        justify-content: center;
    }

    .social-icons {
        justify-content: center;
    }
}

/* SMALL MOBILE (≤ 480px) */
@media (max-width: 480px) {

    .photo {
        width: 100px;
        height: 100px;
    }

    .nama {
        font-size: 20px;
    }

    .instansi {
        font-size: 13px;
    }

    .judul-card {
        font-size: 17px;
    }

    .page-link {
        margin: 3px 2px;
        padding: 5px 9px;
        font-size: 13px;
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

<div class="container">
    <!-- FOTO + NAMA -->
    <div class="center-header">
        <div class="card header-card">
            <img src="<?= $foto_path ?>" class="photo" alt="Foto Mahasiswa">
            <div class="nama"><?= htmlspecialchars($mahasiswa['nama']) ?></div>
            <div class="instansi"><?= htmlspecialchars($mahasiswa['prodi']) ?> • Politeknik Negeri Malang</div>
        </div>
    </div>

    <!-- IDENTITAS + DOSEN PEMBIMBING -->
    <div class="grid-2">
        <div class="card identitas-card">
            <h2 class="judul-card">Identitas Mahasiswa</h2>
            <div class="data-grid">
                <p><strong>NIM:</strong> <?= htmlspecialchars($mahasiswa['nim']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($mahasiswa['email']) ?></p>
                <p><strong>Program Studi:</strong> <?= htmlspecialchars($mahasiswa['prodi']) ?></p>
                <p><strong>Jurusan:</strong> <?= htmlspecialchars($mahasiswa['jurusan_mahasiswa']) ?></p>
            </div>
        </div>

        <div class="card">
            <h2 class="judul-card">Dosen Pembimbing</h2>
            <?php if($dosen): ?>
                <div class="data-grid">
                    <p><strong>Nama:</strong> <?= htmlspecialchars($dosen['nama']) ?></p>
                    <p><strong>Program Studi:</strong> <?= htmlspecialchars($dosen['prodi_dosen']) ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($dosen['email']) ?>"><?= htmlspecialchars($dosen['email']) ?></a></p>
                </div>
            <?php else: ?>
                <p>Belum ada dosen pembimbing</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAB RISET -->
<div class="tabs-container">
    <div class="tabs">
        <button class="tab-button active">Riset</button>
    </div>

    <div class="tab-content active" id="riset">
        <?php if($riset): foreach($riset as $r): ?>
            <div class="list-card">
                <?= htmlspecialchars($r['judul']) ?> —
                <a href="<?= $r['link_riset'] ?>" target="_blank">Link Riset</a>
                (<?= $r['tahun'] ?>)
            </div>
        <?php endforeach; else: ?>
            <div class="list-card">Belum ada riset</div>
        <?php endif; ?>

        <!-- ✅ PAGINATION MASUK KE SINI -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a
                        href="?id=<?= $mahasiswa['id'] ?>&page=<?= $i ?>"
                        class="page-link <?= ($i == $page) ? 'active' : '' ?>"
                    >
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-content">
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

        <div class="footer-col">
            <h3>Quick Links</h3>
            <a href="about.php">About</a>
            <a href="research.php">Research</a>
            <a href="members.php">Members</a>
            <a href="news.php">News</a>
        </div>

        <div class="footer-col">
            <h3>Contact</h3>
            <p>Email: ivss@polinema.ac.id</p>
            <p>Telp: (0341) 404424</p>
            <p>Jl. Soekarno-Hatta No. 9, Malang</p>
        </div>

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