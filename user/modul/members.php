<?php
require '../config/database.php'; // file ini HARUS menghasilkan $pdo

/* ================= DOSEN ================= */
$stmt = $pdo->prepare("
    SELECT id, nama, dosen_profile, role_lab
    FROM dosen
    ORDER BY id ASC
");
$stmt->execute();
$dosenList = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ================= ACTIVE MEMBERS ================= */
$stmt = $pdo->prepare("
    SELECT 
        id,
        nama,
        prodi,
        mahasiswa_profile
    FROM mahasiswa
    WHERE tahun_lulus IS NULL
    ORDER BY nama ASC
");
$stmt->execute();
$activeMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ================= ALUMNI MEMBERS ================= */
$stmt = $pdo->prepare("
    SELECT 
        id,
        nama,
        prodi,
        mahasiswa_profile,
        tahun_lulus
    FROM mahasiswa
    WHERE tahun_lulus IS NOT NULL
    ORDER BY tahun_lulus DESC, nama ASC
");
$stmt->execute();
$alumniMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../style/navbar.css">
    </head>
<body>
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
<div class="hero">
    <img src="../img/about.jpg" alt="gambar gedung lab">
    <div class="header-text">
        <h1>Members</h1>
    </div>
<div class="custom-shape-divider-bottom-1764076735">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,100 C150,150 450,50 600,100 C750,150 1050,50 1200,100 V120 H0 Z" class="shape-fill"></path>
    </svg>
</div>
</div>

<!-- DOSEN -->
<section class="members-section">
    <h2 class="members-title">Laboratorium Members</h2>    
    <div class="members-container">
        <?php if ($dosenList): ?>
            <?php foreach ($dosenList as $dosen): ?>
                <a class="member-card" href="../modul/profil_dosen.php?id=<?= urlencode($dosen['id']) ?>">
                    <img src="../../admin-lab/assets/img/logo/<?= htmlspecialchars($dosen['dosen_profile'] ?? 'default.jpg'); ?>" alt="Foto <?= htmlspecialchars($dosen['id']) ?>">
                    <h3><?= htmlspecialchars($dosen['nama']) ?></h3>
                    <h4><?= htmlspecialchars($dosen['role_lab'] ?? '') ?></h4>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Belum ada data dosen</p>
        <?php endif; ?>
    </div>
</section>

<!-- ACTIVE MEMBERS -->
<section class="active-members-section members-section">
    <h2 class="members-title">Active Members</h2>
    <div class="members-container active-members-container">
        <?php if ($activeMembers): ?>
            <?php foreach ($activeMembers as $member): ?>
                <a class="member-card active-member-card" href="../modul/mhs-profile.php?id=<?= urlencode($member['id']) ?>">
                    <img src="../../admin-lab/assets/img/mahasiswa/<?= htmlspecialchars($member['mahasiswa_profile'] ?? 'default.png') ?>" alt="Foto <?= htmlspecialchars($member['nama']) ?>">
                    <h3><?= htmlspecialchars($member['nama']) ?></h3>
                    <p><?= htmlspecialchars($member['prodi']) ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-state">Belum ada active member.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ALUMNI -->
<section class="alumni-section members-section">
    <h2 class="members-title">Alumni Laboratorium</h2>
    <div class="members-container alumni-container">
        <?php if ($alumniMembers): ?>
            <?php foreach ($alumniMembers as $alumni): ?>
                <div class="member-card alumni-card">
                    <img src="../../admin-lab/assets/img/mahasiswa/<?= htmlspecialchars($alumni['mahasiswa_profile'] ?? 'default.png') ?>" alt="Foto <?= htmlspecialchars($alumni['nama']) ?>">
                    <h3><?= htmlspecialchars($alumni['nama']) ?></h3>
                    <p><?= htmlspecialchars($alumni['prodi']) ?><br><small>Lulus <?= htmlspecialchars($alumni['tahun_lulus']) ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-state">Belum ada data alumni.</p>
        <?php endif; ?>
    </div>

    <div class="load-more-wrapper">
        <button id="loadMoreBtn">Load More</button>
        <h1 class="shiny-title">Daftar Jadi Anggota?</h1>
        <a href="../../kepala-lab/" class="join-link" target="_blank">Daftar Sekarang</a>
    </div>
</section>


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

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    margin: 0;
    background-color: #FFFFFF;
}

/* ===== HERO ===== */
.hero {
    position: relative;
    width: 100%;
    height: 70vh;
    overflow: hidden;
    border-bottom: none;
}

.hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.header-text {
    position: absolute;
    bottom: 170px;
    left: 50px;
    z-index: 10;
    margin-left: 50px;
}

.header-text h1 {
    font-size: 4em;
    color: #FF9D00;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    margin: 0;
}

/* ===== WAVE ===== */
.custom-shape-divider-bottom-1764076735 {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    margin-bottom: -2px;
}

.custom-shape-divider-bottom-1764076735 svg {
    position: relative;
    display: block;
    width: 250%;
    height: 400px;
    will-change: transform;
    transform: translate3d(0, 0, 0);
    animation: waveMove 5s linear infinite;
}

.custom-shape-divider-bottom-1764076735 .shape-fill {
    fill: #FFFFFF;
}

@keyframes waveMove {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-50%);
    }
}

.members-section {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    padding: 50px 20px;
    justify-content: space-between;
}

.members-title {
    text-align: center;
    font-size: 2.2rem;
    font-weight: 800;
    color: #0A192F;
    margin-top: 20px;
}
.members-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}

.member-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(10, 25, 47, 0.15);
    transition: 0.3s;
    margin-bottom: 20px;
    margin-top: 20px;
    text-decoration: none;
}



.member-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.25);
}

.member-card img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    place-content: center;
}

.member-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #0A192F;
}

.member-card p {
    font-size: 0.95rem;
    color: #666;
}

/* ===== ACTIVE MEMBERS & ALUMNI ===== */

/* Gunakan grid 3 kolom */
.active-members-container{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    margin-top: 100px;
    margin-bottom: 20px;
}

.alumni-container{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    margin-top: 30px;
    margin-bottom: 100px;
}

/* Card style tetap mengikuti styling member-card */
.active-member-card,
.alumni-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(10, 25, 47, 0.15);
    transition: 0.3s;
}

.active-member-card:hover,
.alumni-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.25);
}

/* Foto card */
.active-member-card img,
.alumni-card img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
}

.load-more-wrapper {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px; /* jarak antar tombol */
}

#loadMoreBtn {
    padding: 10px 30px;
    background-color: #FF9D00;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s;
    margin-top: 0px;
}

#loadMoreBtn:hover {
    background-color: #0A192F;
}

/* ===== SHINY TEXT EFFECT ===== */
.shiny-title {
    font-size: 2.3rem;
    font-weight: 800;
    background: linear-gradient(
        90deg,
        #ff9d00 0%,
        #0A192F 25%,
        #0A192F 50%,
        #ffd68f 75%,
        #ff9d00 100%
    );
    background-size: 300% auto;
    -webkit-background-clip: text;
    color: transparent;
    animation: shineMove 3s linear infinite;
    margin-top: 10px;
}
.empty-state {
    grid-column: 1 / -1; 
    text-align: center;
    font-size: 1.1rem;
    color: #666;
    margin-top: 40px;
}

@keyframes shineMove {
    0% {
        background-position: 0% center;
    }
    100% {
        background-position: -300% center;
    }
}
/* SHINY BUTTON */
.join-link {
    display: inline-block;
    margin-top: 25px;
    background: linear-gradient(90deg, #ff9d00, #ffd68f, #ff9d00);
    background-size: 300% 300%;
    animation: shinyButtonMove 3s linear infinite;
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;
    transition: 0.3s ease;
    box-shadow: 0 6px 14px rgba(255, 157, 0, 0.35);
}

.join-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 157, 0, 0.5);
}

/* Animasi gradient */
@keyframes shinyButtonMove {
    0% { background-position: 0% 50%; }
    100% { background-position: -300% 50%; }
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
/* ================= RESPONSIVE GLOBAL ================= */

/* ===== TABLET ===== */
@media (max-width: 1024px) {

    /* FOOTER */
    .footer {
        padding: 50px 40px 25px;
    }

    .footer-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }

    .footer-col:first-child {
        max-width: 100%;
    }

    /* GRID CONTENT (FACILITY / MEMBERS) */
    .facilities-container,
    .members-container,
    .active-members-container,
    .alumni-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }
}

/* ===== MOBILE ===== */
@media (max-width: 768px) {

    /* FOOTER */
    .footer {
        padding: 40px 25px 20px;
        text-align: center;
    }

    .footer-content {
        grid-template-columns: 1fr;
        gap: 35px;
    }

    .footer-logos {
        justify-content: center;
        flex-wrap: wrap;
    }

    .social-icons {
        justify-content: center;
    }

    .footer-col p,
    .footer-col a {
        font-size: 14px;
    }

    /* GRID CONTENT */
    .facilities-container,
    .members-container,
    .active-members-container,
    .alumni-container {
        grid-template-columns: 1fr;
        gap: 35px;
    }

    /* MEMBER / FACILITY CARD */
    .member-card,
    .facility-card {
        margin: auto;
    }
}

/* ===== SMALL MOBILE ===== */
@media (max-width: 480px) {

    .footer-col h3 {
        font-size: 16px;
    }

    .footer-bottom {
        font-size: 13px;
    }

    .members-title {
        font-size: 1.8rem;
    }
}
</style>