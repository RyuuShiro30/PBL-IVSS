<?php
session_start();
require __DIR__ . '/../config/database.php';

$search = trim($_GET['search'] ?? '');
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$limit  = 6;
$offset = ($page - 1) * $limit;

/* ================= MAIN QUERY ================= */
$sql = "
SELECT 
    r.id,
    r.judul,
    r.link_riset,
    r.tahun,
    string_agg(DISTINCT m.nama, ', ') AS mahasiswa,
    string_agg(DISTINCT d.nama, ', ') AS dosen
FROM riset r
LEFT JOIN riset_mahasiswa rm ON rm.id_riset = r.id
LEFT JOIN mahasiswa m ON m.id = rm.id_mahasiswa
LEFT JOIN riset_dosen rd ON rd.id_riset = r.id
LEFT JOIN dosen d ON d.id = rd.id_dosen
WHERE 1=1
";

if ($search !== '') {
    $sql .= " AND r.judul ILIKE :search";
}

$sql .= "
GROUP BY r.id, r.judul, r.link_riset, r.tahun
ORDER BY r.tahun DESC
LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);

if ($search !== '') {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$risets = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ================= COUNT QUERY ================= */
$countSql = "
SELECT COUNT(DISTINCT r.id)
FROM riset r
WHERE 1=1
";

if ($search !== '') {
    $countSql .= " AND r.judul ILIKE :search";
}

$countStmt = $pdo->prepare($countSql);

if ($search !== '') {
    $countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}

$countStmt->execute();
$totalData  = $countStmt->fetchColumn();
$totalPages = ceil($totalData / $limit);
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
        <h1>Researchroom</h1>
    </div>
    <div class="custom-shape-divider-bottom-1764076735">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,100 C150,150 450,50 600,100 C750,150 1050,50 1200,100 V120 H0 Z" class="shape-fill"></path>
        </svg>
    </div>
</div>
<div class="breadcrumb">
    <a href="index.php">Home</a>
    <span class="dot"></span>
    <a class="active" href="news.php">Research</a>
    <span class="dot"></span>
    <a href="#">Researchroom</a>
</div>


<!-- ===== SEARCH ===== -->
<div class="search-wrapper">
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Cari judul riset..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<!-- ===== RESEARCH LIST ===== -->
<section class="research-results">
    <div class="results-grid">
        <?php if (!empty($risets)): ?>
            <?php foreach ($risets as $r): ?>
                <div class="result-card">
                    <h3><?= htmlspecialchars($r['judul']) ?></h3>
                    <span class="result-year"><?= $r['tahun'] ?></span>

                    <?php if (!empty($r['mahasiswa'])): ?>
                        <p><strong>Mahasiswa:</strong> <?= htmlspecialchars($r['mahasiswa']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($r['dosen'])): ?>
                        <p><strong>Dosen Peneliti:</strong> <?= htmlspecialchars($r['dosen']) ?></p>
                    <?php endif; ?>

                    <a href="<?= htmlspecialchars($r['link_riset']) ?>" target="_blank" class="link-selengkapnya">
                        Baca Selengkapnya
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Riset tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ===== PAGINATION ===== -->
<?php if ($totalPages > 1): ?>
<div class="pagination-wrapper">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
           class="pagination-link <?= ($i == $page) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
<?php endif; ?>

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

/* ===== SEARCH STYLE ===== */
.search-wrapper {
    margin: 40px 140px 0;
    margin-left: 50px;
}

.search-form {
    display: flex;
    max-width: 1500px;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

.search-form input {
    flex: 1;
    padding: 14px 16px;
    border: none;
    outline: none;
    font-size: 15px;
}

.search-form button {
    padding: 0 26px;
    background: #FF9D00;
    color: white;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.search-form button:hover {
    background: #0A192F;
}

@media (max-width: 768px) {
    .search-wrapper {
        margin: 30px 20px 0;
    }

    .search-form {
        max-width: 100%;
    }
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    margin-top: 100px;
    margin-left: 60px;
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

/* ===== RESEARCH RESULTS SECTION ===== */
/* ===== SECTION TITLE ===== */
.section-title {
    font-size: 32px;
    font-weight: 700;
    color: #0A2D4A;
    margin: 60px 0 30px;
    position: relative;
    padding-left: 18px;
}

/* garis aksen di kiri judul */
.section-title::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 70%;
    background: linear-gradient(#FF9D00, #d27300);
    border-radius: 4px;
}

/* responsive */
@media (max-width: 600px) {
    .section-title {
        font-size: 26px;
        margin: 40px 0 20px;
    }
}
.research-results {
    padding: 80px 60px;
}


.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(3, 1fr));
    gap: 50px;
    max-width: 1150px;
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
    margin-top: 20px;
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
@media (max-width: 1024px) {
    .results-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }
}

@media (max-width: 600px) {
    .results-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
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
    gap: 50px;
    margin-top: 30px;
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

/* Load more button */
.load-more-wrapper {
    text-align: center;
    margin: 40px 0 80px;
}

.load-more-btn {
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
    text-decoration: none;
    margin-top: 100px;
}

.load-more-btn:hover {
    background-color: #0A192F;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(10, 25, 47, 0.4);
}

.load-more-btn:active {
    transform: translateY(0);
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin: 40px 0 60px;
}

.pagination-link {
    padding: 10px 16px;
    border-radius: 6px;
    background: #f2f2f2;
    color: #0A192F;
    text-decoration: none;
    font-weight: 600;
    transition: 0.25s;
}

.pagination-link:hover {
    background: #FF9D00;
    color: #fff;
}

.pagination-link.active {
    background: #0A192F;
    color: #fff;
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