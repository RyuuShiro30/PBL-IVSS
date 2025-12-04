<?php
include "../modul/datafasilitas.php";
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style/navbar.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
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

<!-- ================= HERO SECTION ================= -->
<div class="hero">
    <img src="../img/about.jpg" alt="gambar gedung lab">
    <div class="header-text">
        <h1>About IVSS</h1>
    </div>

    <div class="custom-shape-divider-bottom-1764076735">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,100 C150,150 450,50 600,100 
                     C750,150 1050,50 1200,100 V120 H0 Z"
                  class="shape-fill"></path>
        </svg>
    </div>
</div>

<!-- ================= PROFIL LAB ================= -->
<div class="profil-lab">
    <div class="profil">
        <img src="../img/profil-foto.png" alt="foto profil lab" class="profil-img">

        <div class="profil-text">
            <h1>Profil Laboratorium</h1>
            <p>
                Laboratorium Visi Cerdas dan Sistem Cerdas merupakan pusat riset dan pengembangan
                di bawah Jurusan Teknologi Informasi Politeknik Negeri Malang yang berfokus pada
                bidang intelligent vision dan smart system. Laboratorium ini menjadi wadah bagi
                dosen dan mahasiswa untuk melakukan penelitian, pembelajaran, serta pelatihan
                dalam pengembangan sistem cerdas berbasis pengolahan citra dan kecerdasan buatan.
                <br><br>
                Penelitian di laboratorium ini mengintegrasikan computer vision, AI, dan IoT
                untuk menciptakan solusi inovatif yang mampu mengenali, menganalisis, serta
                merespon lingkungan secara mandiri.
            </p>
        </div>
    </div>
</div>

<!-- ================= VISI ================= -->
<section class="visi-banner">
    <div class="visi-container">
        <h1>VISI</h1>
        <p>
            Menjadi laboratorium unggulan dalam pengembangan teknologi penglihatan cerdas (Intelligent Vision)
            dan sistem cerdas terintegrasi (Smart Systems) yang inovatif, aplikatif, serta berdaya saing nasional
            dan internasional untuk mendukung kemajuan bidang teknologi informasi dan industri berbasis kecerdasan
            buatan.
        </p>
    </div>
</section>

<!-- ================= MISI ================= -->
<section class="misi-section">
    <h1 class="misi-title">MISI</h1>

    <div class="misi-grid">
        <div class="misi-card">
            <ul>
                <li>Melaksanakan penelitian dan inovasi di bidang computer vision, artificial intelligence, dan smart systems.</li>
                <li>Menyediakan fasilitas riset dan pelatihan bagi dosen dan mahasiswa Polinema.</li>
                <li>Mendorong kolaborasi akademik dan industri untuk menghasilkan solusi nyata dan berkelanjutan.</li>
                <li>Menghasilkan publikasi ilmiah, prototipe, dan produk inovatif.</li>
                <li>Mengembangkan ekosistem pembelajaran adaptif berbasis riset.</li>
            </ul>
        </div>
    </div>
</section>

<!-- ================= FOKUS RISET ================= -->
<section class="fokus-riset">
    <h1>FOKUS RISET</h1>

    <div class="riset-container">
        <div class="riset-card">
            <div class="icon">👁️</div>
            <h3>Intelligent Vision</h3>
            <p>Penelitian terkait computer vision, image processing, dan visual AI.</p>
        </div>

        <div class="riset-card">
            <div class="icon">⚙️</div>
            <h3>Smart System</h3>
            <p>Pengembangan sistem cerdas, IoT, dan otomatisasi berbasis AI.</p>
        </div>
    </div>
</section>

<!-- ================= GALERI ================= -->
<section class="galeri">
    <h1>GALERI</h1>

    <div class="gallery-wrapper">
        <div class="gallery-container" id="galleryContainer">
            <div class="gallery-item"><img src="../img/keg1.jpg"></div>
            <div class="gallery-item"><img src="../img/keg2.jpg"></div>
            <div class="gallery-item"><img src="../img/keg3.jpg"></div>
            <div class="gallery-item"><img src="../img/keg4.jpg"></div>
            <div class="gallery-item"><img src="../img/keg5.jpg"></div>
            <div class="gallery-item"><img src="../img/keg6.jpg"></div>
        </div>
    </div>

    <div class="gallery-controls">
        <button onclick="slideGallery(-1)" class="gallery-btn">❮</button>
        <button onclick="slideGallery(1)" class="gallery-btn">❯</button>
    </div>
</section>

<!-- ================= FACILITIES ================= -->
<section class="facilities-section">
    <div class="facilities-header-wrapper">
        <h2 class="facilities-title">FACILITES</h2>

        <a href="facilityroom.php" class="find-more-btn">
            <span class="circle"><span class="arrow">→</span></span>
            <span class="text">FIND OUT MORE</span>
        </a>
    </div>

    <div class="facilities-container">

        <div class="facility-card">
            <div class="facility-icon">
                <img src="../icon/ac-furniture-home-svgrepo-com.svg" alt="AC Icon">
            </div>
            <div class="facility-box">
                <span>AC</span>
                <a href="../modul/facilitydetail.php?id=ac" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card">
            <div class="facility-icon">
                <img src="../icon/computer-and-monitor-svgrepo-com.svg" alt="Computer Icon">
            </div>
            <div class="facility-box">
                <span>Computer & Monitor</span>
                <a href="../modul/facilitydetail.php?id=computer" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card">
            <div class="facility-icon">
                <img src="../icon/mosque-svgrepo-com.svg" alt="Mushola Icon">
            </div>
            <div class="facility-box">
                <span>Mushola</span>
                <a href="../modul/facilitydetail.php?id=mushola" class="facility-arrow">→</a>
            </div>
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
<script src="../JS/galeri.js"></script>
</body>
</html>
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

    .profil-lab {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 100px 20px;
        background: #FFFFFF;
    }

    .profil {
        display: flex;
        align-items: center;
        gap: 50px;
        max-width: 1100px;
        width: 100%;
    }

    .profil-img {
        width: 500px;
        height: auto;
        object-fit: contain;
    }

    .profil-text {
        flex: 1;
    }

    .profil-text h1 {
        font-size: 36px;
        margin-bottom: 20px;
        color: #0A192F;
        font-weight: 700;
    }

    .profil-text p {
        font-size: 17px;
        line-height: 1.8;
        color: #444;
    }

    /* ======== VISI BANNER ======== */
    .visi-banner {
        width: 100%;
        padding: 80px 20px;
        background: linear-gradient(135deg, #0A0F2D, #25406F, #4C75C3);
        color: white;
        display: flex;
        justify-content: center;
        text-align: center;
    }

    .visi-container {
        max-width: 900px;
    }

    .visi-banner h1 {
        font-size: 48px;
        margin-bottom: 25px;
        font-weight: 700;
    }

    .visi-banner p {
        font-size: 19px;
        line-height: 1.8;
        opacity: 0.95;
    }

    /* ======== MISI SECTION ======== */
    .misi-section {
        padding: 80px 20px;
        background: #F6F8FA;
    }

    .misi-title {
        text-align: center;
        font-size: 38px;
        margin-bottom: 40px;
        color: #0A2D4A;
    }

    .misi-grid {
        display: flex;
        justify-content: center;
    }

    .misi-card {
        max-width: 1000px;
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .misi-card ul {
        padding-left: 20px;
    }

    .misi-card li {
        margin-bottom: 15px;
        line-height: 1.7;
        color: #333;
        font-size: 17px;
    }

    /**FOKUS RISET**/
    .fokus-riset {
        padding: 60px 20px;
        text-align: center;
    }

    .fokus-riset h1 {
        font-size: 28px;
        color: #11226A;
        font-weight: 800;
        margin-bottom: 35px;
    }

    .riset-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .riset-card {
        width: 260px;
        padding: 25px;
        border-radius: 14px;
        border: 2px solid #008CFF;
        background: white;
        transition: 0.3s;
    }

    .riset-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .icon {
        font-size: 38px;
        margin-bottom: 12px;
    }

    .riset-card h3 {
        font-size: 18px;
        color: #0D2B7E;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .riset-card p {
        font-size: 14px;
        color: #555;
        line-height: 1.6;
    }

    /* ===== GALERI ===== */
    .galeri {
        padding: 40px 40px 70px;
        text-align: center;
    }

    .galeri h1 {
        font-size: 32px;
        text-align: center;
        margin-bottom: 25px;
        width: 100%;
        color: #0a2b6b;
    }

    .gallery-wrapper {
        width: 100%;
        overflow-x: hidden;
        padding: 0;
    }

    .gallery-container {
        display: flex;
        gap: 0;
        transition: transform 0.4s ease;
    }

    .gallery-item {
        flex: 0 0 33.333333%;
        padding: 0 10px;
    }

    .gallery-item img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .gallery-controls {
        width: 100%;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .gallery-btn {
        background: #0A192F;
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 20px;
        border-radius: 50px;
        cursor: pointer;
        transition: 0.2s;
    }

    .gallery-btn:hover {
        background: #FF9D00;
    }

    /* ===== FASILITIES SECTION ===== */
    .facilities-section {
        width: 100%;
        padding: 40px 60px;
    }

    .facilities-header-wrapper {
        display: flex;
        position: relative;
        align-items: center;
        margin-bottom: 35px;
        justify-content: center;

    }

    .facilities-title {
        font-size: 32px;
        color: #003366;
        font-weight: 700;

    }

    .find-more-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        text-decoration: none;
        color: #0A192F;
        font-weight: 700;
        font-size: 18px;
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        margin-right: 60px;
        
    }

    .find-more-btn .circle {
        width: 42px;
        height: 42px;
        border: 2px solid #0A192F;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .find-more-btn .arrow {
        font-size: 20px;
    }

    .find-more-btn .text {
        letter-spacing: 0.5px;
    }


    /* ===== FACILITIES CARDS ===== */

    .facilities-container {
        display: grid;
        justify-content: center;
        grid-template-columns: repeat(3, 300px);
        gap: 110px;
        align-items: flex-start;
    }

    .facility-card {
        width: 300px;
        display: flex;
        flex-direction: column;
        margin-bottom: 100px;

    }

    .facility-icon {
        width: 300px;
        height: 180px;
        background: #0A192F;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .facility-icon img {
        width: 90px;
        height: 90px;
        filter: brightness(0) invert(1);
        object-fit: contain;
        display: block;
        transition: 0.3s ease;
    }

    .facility-card:hover .facility-icon img {
        filter: invert(57%) sepia(93%) saturate(457%) hue-rotate(-1deg) brightness(103%) contrast(104%);
    }

    .facility-box {
        width: 100%;
        margin-top: 20px;
        padding: 18px 25px;
        border: 2px solid #0A192F;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .facility-box span {
        font-size: 18px;
        font-weight: 600;
        color: #0A192F;
    }

    .facility-arrow {
        font-size: 20px;
        color: #0A0F2D;
        text-decoration: none;
        font-weight: bold;
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

