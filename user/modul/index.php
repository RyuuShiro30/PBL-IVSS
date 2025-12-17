
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intelligent Vision and Smart System</title>
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

<section class="hero">
    <img src="../img/FOTO GEDUNG.png" alt="Gedung IVSS" class="hero-bg">
    <h1>INTELLIGENT VISION<br>AND SMART SYSTEM</h1>
    <p>Shaping the Future through Intelligent Vision and Adaptive Systems</p>
</section>

<div class="learn-more">
    <a href="about.php" class="learn-more-link">
        <h2>
            <img src="../img/right-arrow.png" alt="">
            Learn More 
        </h2>
    </a>
</div>
</body>
</html>
<style>
    .hero {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 80px 20px 0 20px;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
    background-color: #0A192F;
}

.hero-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(10, 35, 66, 0.85),
        rgba(0, 30, 60, 0.85),
        rgba(31, 66, 110, 0.85)
    );
    z-index: 1;
}

.hero h1,
.hero p {
    position: relative;
    z-index: 2;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.hero h1 {
    font-size: 60px;
    font-weight: 900;
    line-height: 1.2;
    color: #E7F4FF;
    margin: 0;
    text-shadow: 0 0 15px rgba(0, 198, 255, 0.2);
    animation: wave 4s ease-in-out infinite;
}

@keyframes wave {

    0%,
    100% {
        transform: translateY(0);
        letter-spacing: 1px;
    }

    50% {
        transform: translateY(-10px);
        letter-spacing: 2px;
    }
}

.hero p {
    margin-top: 25px;
    font-size: 20px;
    color: #A8B2D1;
    max-width: 650px;
    text-shadow: 0 0 6px rgba(0, 198, 255, 0.15);
}

.learn-more {
    display: flex;
    justify-content: center;
    position: absolute;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}

.learn-more a {
    text-decoration: none;
}

.learn-more h2 {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 600;
    cursor: pointer;
    padding: 12px 32px;
    border-radius: 40px;
    border: 2px solid #FF9D00;
    color: #FF9D00;
    background: rgba(10, 25, 47, 0.6);
    backdrop-filter: blur(8px);
    transition: all 0.3s ease;
}

.learn-more h2:hover {
    color: #FFFFFF;
    background-color: #FF9D00;
    border-color: #FF9D00;
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(255, 157, 0, 0.3);
}

.learn-more img {
    height: 22px;
    width: auto;
    filter: brightness(0) saturate(100%) invert(68%) sepia(64%) saturate(1488%) hue-rotate(359deg) brightness(102%) contrast(101%);
    transition: filter 0.3s ease, transform 1.5s ease-in-out;
}

.learn-more h2:hover img {
    filter: invert(100%);
}
</style>