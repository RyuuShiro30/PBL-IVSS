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

    <!-- FOTO + NAMA (TENGAH) -->
    <div class="center-header">
        <div class="card header-card">
            <img src="../img-dosen/Ulla-Delfana-Rosiani.jpg" class="photo" alt="Foto Dosen">
            <h1 class="nama">Ulla Delfana Rosiani ST., MT</h1>
            <p class="instansi">Sistem Informasi Bisnis • Politeknik Negeri Malang</p>
        </div>
    </div>

    <!-- IDENTITAS & PROFIL AKADEMIK -->
    <div class="grid-2">

        <div class="card identitas-card">
            <h2 class="judul-card">Identitas Dosen</h2>
            <div class="data-grid">
                <p><strong>NIP:</strong> 19780327200312200</p>
                <p><strong>NIDN:</strong> 4314058001</p>
                <p><strong>Email:</strong> rosiani@polinema.ac.id</p>
                <p><strong>Program Studi:</strong> Rekayasa Teknologi Informasi</p>
            </div>
        </div>

        <div class="card">
            <h2 class="judul-card">Profil Akademik & Profesional</h2>
            <div class="link-list">
                <a href="#">Google Scholar</a>
                <a href="#">Sinta</a>
                <a href="#">LinkedIn</a>
                <a href="#">Email</a>
            </div>
        </div>
    </div>

    <!-- TAB -->
    <div class="tabs-container">
        <div class="tabs">
            <button class="tab-button active" onclick="openTab('publikasi')">Publikasi</button>
            <button class="tab-button" onclick="openTab('bimbingan')">Mahasiswa Bimbingan</button>
            <button class="tab-button" onclick="openTab('pendidikan')">Pendidikan</button>
            <button class="tab-button" onclick="openTab('sertifikasi')">Sertifikasi</button>
        </div>

        <div class="tab-content active" id="publikasi">
            <div class="list-card">Judul Publikasi 1 — 2023</div>
            <div class="list-card">Judul Publikasi 2 — 2022</div>
            <div class="list-card">Judul Publikasi 3 — 2021</div>
        </div>

        <div class="tab-content" id="bimbingan">
            <div class="list-card">Mahasiswa 1 — Judul Skripsi</div>
            <div class="list-card">Mahasiswa 2 — Judul Skripsi</div>
            <div class="list-card">Mahasiswa 3 — Judul Skripsi</div>
        </div>

        <div class="tab-content" id="pendidikan">
            <div class="list-card">S3 - Doktor</div>
            <div class="list-card">S2 - Magister Teknik</div>
            <div class="list-card">S1 - Sarjana Teknik</div>
        </div>

        <div class="tab-content" id="sertifikasi">
            <div class="list-card">-</div>
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
                <a href="#" class="social-icon"><img src="../icon/tiktok.svg"></a>
                <a href="#" class="social-icon"><img src="../icon/instagram.svg"></a>
                <a href="#" class="social-icon"><img src="../icon/youtube.svg"></a>
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