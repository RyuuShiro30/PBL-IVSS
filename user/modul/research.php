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
        <button id="loadMoreBtn">Lihat Fasilitas Lainnya</button>
    </div>
</section>



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
</style>