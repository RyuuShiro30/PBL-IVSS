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

                <div class="news-card">
                    <div class="card-image">
                        <img src="../../admin-berita/assets/img/thumbnails/<?= htmlspecialchars($row['thumbnail']); ?>">alt="<?= htmlspecialchars($row['judul']); ?>">
                    </div>

                    <div class="card-content">

                        <p class="card-date">
                            <?= date("M d, Y", strtotime($row['created_at'])); ?>
                        </p>

                        <h3><?= htmlspecialchars($row['judul']); ?></h3>

                        <p class="card-description">
                            <a href="<?= htmlspecialchars($row['link_berita']); ?>" 
                            target="_blank"
                            style="color: inherit; text-decoration: underline;"><?= htmlspecialchars($row['link_berita']); ?>
                            </a>
                        </p>


                        <p class="card-meta-detail">
                            oleh <?= htmlspecialchars($row['author_name'] ?? 'Admin'); ?> 
                            | ARTIKEL, BERITA
                        </p>

                        <p class="card-meta">
                            oleh <?= htmlspecialchars($row['author_name'] ?? 'Admin'); ?> |
                            <?= date("M d, Y", strtotime($row['created_at'])); ?> 
                            | ARTIKEL, BERITA
                        </p>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>
            <p style="text-align:center; margin:20px 0; ">Belum ada berita untuk ditampilkan.</p>
        <?php endif; ?>

    </div>
<<<<<<< Updated upstream

    <div class="load-more-wrapper">
        <button id="loadMoreBtn">Lihat Fasilitas Lainnya</button>
    </div>
</div>
</body>
</html>
=======
</div>
>>>>>>> Stashed changes
