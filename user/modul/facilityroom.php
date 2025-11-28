<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Room - IVSS</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/facilityroom.css">
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
        <h1>Facilityroom</h1>
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
</div>

<section class="facilities-section">
    <div class="facilities-header-wrapper">
    </div>

    <div class="facilities-container">

        <div class="facility-card">
            <div class="facility-icon">
                <img src="../icon/ac-furniture-home-svgrepo-com.svg" alt="AC Icon">
            </div>
            <div class="facility-box">
                <span>AC</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card">
            <div class="facility-icon">
                <img src="../icon/computer-and-monitor-svgrepo-com.svg" alt="Computer Icon">
            </div>
            <div class="facility-box">
                <span>Computer & Monitor</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card">
            <div class="facility-icon">
                <img src="../icon/mosque-svgrepo-com.svg" alt="Mushola Icon">
            </div>
            <div class="facility-box">
                <span>Mushola</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/projector-svgrepo-com.svg" alt="lcd Icon">
            </div>
            <div class="facility-box">
                <span>LCD</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/lockers-storage-svgrepo-com.svg" alt="loker Icon">
            </div>
            <div class="facility-box">
                <span>Loker</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/cctv-svgrepo-com.svg" alt="CCTV Icon">
            </div>
            <div class="facility-box">
                <span>CCTV</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>
        
        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/camera-svgrepo-com.svg" alt="Sony Alpha A600 Icon">
            </div>
            <div class="facility-box">
                <span>Sony Alpha A600</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/cupboard-two-doors-svgrepo-com.svg" alt="Lemari Icon">
            </div>
            <div class="facility-box">
                <span>Lemari</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/usb-cable-usb-svgrepo-com.svg" alt="USB cable Icon">
            </div>
            <div class="facility-box">
                <span>USB Cable</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/video-camera-video-cameras-svgrepo-com.svg" alt="Camera Machine vision Icon">
            </div>
            <div class="facility-box">
                <span>Machine Vision Camera</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/lens-camera-lens-svgrepo-com.svg" alt="Lensa Kamera Icon">
            </div>
            <div class="facility-box">
                <span>Lens Camera</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>

        <div class="facility-card hidden-card">
            <div class="facility-icon">
                <img src="../icon/cameradepth-svgrepo-com.svg" alt="Depth Camera Icon">
            </div>
            <div class="facility-box">
                <span>Depth Camera</span>
                <a href="../modul/facilitydetail.php" class="facility-arrow">→</a>
            </div>
        </div>
    </div> 
</div> 

    <div class="load-more-wrapper">
        <button id="loadMoreBtn">Lihat Fasilitas Lainnya</button>
    </div>

</section> 

<script src="../JS/loadmore.js"></script>
</body>
</html>