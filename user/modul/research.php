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
        <h1>Research</h1>
    </div>
    <div class="custom-shape-divider-bottom-1764076735">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,100 C150,150 450,50 600,100 C750,150 1050,50 1200,100 V120 H0 Z" class="shape-fill"></path>
        </svg>
    </div>
</div>

<!-- ===== RESEARCH result ===== -->
<section class="research-results">
    <div class="results-grid">
        <div class="result-card">
            <h3>Real-Time Helmet Detection System</h3>
            <span class="result-year">2024</span>
            <p>Successfully deployed AI-based helmet detection for traffic monitoring with 94.5% accuracy.</p>
            <a href="#" class="link-selengkapnya">Baca Selengkapnya</a>
        </div>

        <div class="result-card">
            <h3>AI Navigation for Autonomous Robots</h3>
            <span class="result-year">2023</span>
            <p>Developed reinforcement learning navigation improving robotic path efficiency by 36%.</p>
            <a href="#" class="link-selengkapnya">Baca Selengkapnya</a>
        </div>

        <div class="result-card">
            <h3>Deep Learning for Medical Image Segmentation</h3>
            <span class="result-year">2022</span>
            <p>Introduced 3D-CNN segmentation for tumor region detection with IoU score 91.2%.</p>
            <a href="#" class="link-selengkapnya">Baca Selengkapnya</a>
        </div>
    </div>

    <div class="load-more-wrapper">
        <button id="loadMoreBtn">Lihat Riset Lainnya</button>
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
        © 2025 IVSS Laboratory – All Rights Reserved.
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

/* ===== RESEARCH RESULTS SECTION ===== */
.research-results {
    padding: 80px 60px;
}


.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
    gap: 60px;
    max-width: 1150px;
    margin: auto;
}

.result-card {
    background: #ffffff;
    padding: 28px 30px;
    border-radius: 16px;
    border: 1px solid #dfe7f0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.06);
    transition: 0.35s ease;
    position: relative;
    overflow: hidden;
}

.result-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.10);
}

.result-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 6px;
    height: 100%;
    background: linear-gradient(#FF9D00, #d27300);
    border-radius: 4px;
}

.result-card h3 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #0A2D4A;
}

.result-year {
    display: inline-block;
    font-size: 14px;
    font-weight: 600;
    color: #FF9D00;
    background: rgba(255, 157, 0, 0.12);
    padding: 6px 12px;
    border-radius: 20px;
    margin-bottom: 14px;
}

.result-card p {
    font-size: 16px;
    color: #53606f;
    line-height: 1.7;
    margin-bottom: 20px;
}

/* Download Button */
.link-selengkapnya {
    font-size: 16px;
    font-weight: 600;
    color: #0A2D4A;
    text-decoration: none;
    border: 2px solid #0A2D4A;
    padding: 10px 18px;
    border-radius: 40px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: 0.35s ease;
}

.link-selengkapnya:hover {
    background: #0A2D4A;
    color: white;
}

/* Optional icon on hover */
.link-selengkapnya::after {
    content: "→";
    font-size: 18px;
    transition: 0.3s;
}

.link-selengkapnya:hover::after {
    margin-left: 6px;
}

.load-more-wrapper {
    text-align: center;
    margin: 60px 0;
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
}

#loadMoreBtn:hover {
    background-color: #0A192F;
}

/* === FOOTER === */
.footer {
    width: 100%;
    background: #0A192F;
    color: white;
    padding: 60px 80px 30px;
    margin-top: 50px;
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