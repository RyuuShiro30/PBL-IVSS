<?php
require '../../admin-berita/config/database.php';

// Ambil berita yang sudah publish
$sql = "SELECT b.*,
        a.nama_lengkap AS author_name
        FROM berita b
        LEFT JOIN admin_berita a ON a.id = b.author_id
        WHERE b.status = 'published'
        ORDER BY b.created_at DESC";

// Jalankan query menggunakan executeQuery() bawaan PDO
$result = executeQuery($sql);

// Ambil semua data
$rows = $result ? $result->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News - IVSS</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/news.css">
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
        <h1>News</h1>
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

<div class="news-section">
    <div class="news-container">
    
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>

                <div class="news-card hidden-card">
                    <div class="card-image">
                        <img src="../../admin-berita/assets/img/thumbnails/<?= htmlspecialchars($row['thumbnail']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
                    </div>

                    <div class="card-content">

                        <p class="card-date">
                            <?= date("M d, Y", strtotime($row['created_at'])); ?>
                        </p>

                        <h3 class="card-title"><?= htmlspecialchars($row['judul']); ?></h3>

                        <div class="card-description">
                            <a href="<?= htmlspecialchars($row['link_berita']); ?>" 
                            target="_blank"
                            class="card-link">
                                Baca Selengkapnya →
                            </a>
                        </div>

                        <p class="card-meta">
                            <span class="author">oleh <?= htmlspecialchars($row['author_name'] ?? 'Admin'); ?></span>
                            <span class="separator">•</span>
                            <span class="category">ARTIKEL, BERITA</span>
                        </p>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>
            <div class="empty-state">
                <p>Belum ada berita untuk ditampilkan.</p>
            </div>
        <?php endif; ?>

    </div>
    
    <?php if (!empty($rows)): ?>
    <div class="load-more-wrapper">
        <button id="loadMoreBtn">Lihat Berita Lainnya</button>
    </div>
    <?php endif; ?>
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

<script src="../JS/news.js"></script>
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
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* ================= HERO ================= */
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
    left: 100px;
    z-index: 10;
}

.header-text h1 {
    font-size: 4em;
    color: #FF9D00;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    margin: 0;
}

/* ================= WAVE ================= */
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
    display: block;
    position: relative;
    width: 250%;
    height: 400px;
    animation: waveMove 5s linear infinite;
    transform: translate3d(0, 0, 0);
    will-change: transform;
}

.custom-shape-divider-bottom-1764076735 .shape-fill {
    fill: #FFFFFF;
}

@keyframes waveMove {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* =============== NEWS SECTION =============== */
.news-section {
    max-width: 1400px;
    margin: 0 auto;
    padding: 80px 40px 50px;
}

.news-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 40px;
    margin-bottom: 60px;
}

/* =============== NEWS CARD =============== */
.news-card {
    background-color: #FFFFFF;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

/* Card image */
.card-image {
    width: 100%;
    height: 240px;
    overflow: hidden;
    background-color: #f5f5f5;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.news-card:hover .card-image img {
    transform: scale(1.05);
}

/* Card content */
.card-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.card-date {
    margin: 0 0 12px 0;
    padding-bottom: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #FF9D00;
    border-bottom: 2px solid #FFF3E0;
    letter-spacing: 0.5px;
}

.card-title {
    margin: 0 0 16px 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #0A192F;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.8em;
}

.card-description {
    margin: auto 0 16px 0;
    padding-top: 8px;
}

.card-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #FF9D00;
    text-decoration: none;
    transition: all 0.2s ease;
}

.card-link:hover {
    color: #0A192F;
    gap: 10px;
}

.card-meta {
    margin: 0;
    padding-top: 16px;
    border-top: 1px solid #f0f0f0;
    font-size: 0.85rem;
    color: #666;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.card-meta .author {
    font-weight: 500;
}

.card-meta .separator {
    color: #ddd;
}

.card-meta .category {
    color: #999;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Empty state */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.empty-state p {
    font-size: 1.1rem;
    color: #666;
}
.hidden-card {
    display: none;
}

/* Load more button */
.load-more-wrapper {
    text-align: center;
    margin: 40px 0 80px;
}

#loadMoreBtn {
    padding: 14px 40px;
    background-color: #FF9D00;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 157, 0, 0.3);
}

#loadMoreBtn:hover {
    background-color: #0A192F;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(10, 25, 47, 0.4);
}

#loadMoreBtn:active {
    transform: translateY(0);
}

/* =============== RESPONSIVE =============== */

@media (max-width: 1200px) {
    .news-container {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .hero {
        height: 50vh;
    }

    .header-text {
        bottom: 120px;
        left: 30px;
    }

    .header-text h1 {
        font-size: 2.5em;
    }

    .news-section {
        padding: 60px 20px 40px;
    }

    .news-container {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .card-image {
        height: 200px;
    }

    .card-content {
        padding: 20px;
    }

    .card-title {
        font-size: 1.15rem;
    }

    .load-more-wrapper {
        margin: 30px 0 60px;
    }
}

@media (max-width: 480px) {
    .header-text {
        left: 20px;
        bottom: 100px;
    }

    .header-text h1 {
        font-size: 2em;
    }

    .news-section {
        padding: 40px 16px 30px;
    }

    .card-content {
        padding: 16px;
    }

    #loadMoreBtn {
        padding: 12px 32px;
        font-size: 0.95rem;
    }
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