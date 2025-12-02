<?php
// Import data fasilitas
include '../modul/datafasilitas.php';

// Ambil ID dari URL ?id=computer
$id = $_GET['id'] ?? null;

// Validasi
if (!$id || !isset($facilities[$id])) {
    die("<h2 style='text-align:center;margin-top:40px;'>Fasilitas tidak ditemukan.</h2>");
}

// Ambil data
$data = $facilities[$id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Room - IVSS</title>
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
        <h1>Facility Details</h1>
    </div>

    <div class="custom-shape-divider-bottom-1764076735">
        <svg data-name="Layer 1"
             xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 1200 120"
             preserveAspectRatio="none">
            <path d="M0,100 C150,150 450,50 600,100 C750,150 1050,50 1200,100 V120 H0 Z"
                  class="shape-fill"></path>
        </svg>
    </div>
</div>

<div class="breadcrumb">
    <a href="index.php">Home</a>
    <span class="dot"></span>

    <a href="about.php">About</a>
    <span class="dot"></span>

    <a class="active" href="facilityroom.php">Facilityroom</a>
    <span class="dot"></span>

    <a class="active" href="facilitydetail.php">Facility Details</a>
</div>

<!-- ================= FACILITY DETAIL ================= -->

<section class="facility-detail">
    <div class="facility-content">

        <div class="facility-image">
            <img src="<?= $data['images'][0] ?>" alt="<?= $data['name']; ?>">
        </div>

        <div class="facility-info">
            <h3><?= $data['name']; ?></h3>

            <p>
                <?= $data['description']; ?>
            </p>
        </div>
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

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    margin-top: 100px;
    margin-left: 240px;
}

.breadcrumb a {
    text-decoration: none;
    color: #1d4c8b;
    font-weight: 500;
    font-size: 15px;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb .active {
    font-weight: 600;
    color: #022e6e;
}

.breadcrumb .dot {
    width: 7px;
    height: 7px;
    background: #c4c4c4;
    border-radius: 50%;
    display: inline-block;
}

/* ===== Facility Detail Section ===== */

.facility-detail {
    padding: 40px;
    max-width: 1100px;
    margin: auto;
    margin-bottom: 110px;
}

.facility-header {
    text-align: center;
    margin-bottom: 30px;
}

.facility-header h2 {
    font-size: 28px;
    color: #222;
    font-weight: 700;
}

.facility-header p {
    color: #555;
    margin-top: 5px;
}

.facility-content {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: center;
}

/* Image */
.facility-image img {
    width: 360px;
    height: 240px;
    object-fit: cover;
    border-radius: 12px;
    margin-left: -190px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Info Section */
.facility-info {
    max-width: 600px;
}

.facility-info h3 {
    font-size: 22px;
    font-weight: 700;
    color: #0A192F;
    margin-bottom: 10px;
    margin-left: 110px
}

.facility-info p {
    color: #444;
    line-height: 1.6;
    margin-left: 110px;
    
}

/* List */
.facility-list {
    margin-top: 15px;
    padding-left: 20px;
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
</style>