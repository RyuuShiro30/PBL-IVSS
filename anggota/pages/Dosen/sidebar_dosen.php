<div class="sidebar">
    <div class="top-section">

    <div class="logo-area">
        <div class="logo-box">
            <img src="../../assets/logo.png" alt="Logo">
        </div>

        <div class="logo-text">
            <span class="titleSidebar">Lab IVSS</span>
            <span class="subtitleSidebar">Politeknik Negeri Malang</span>
        </div>
    </div>

        <div class="welcome-box">
            <p class="welcome">Welcome,</p>
            <p class="name"><?= $_SESSION['nama'] ?></p>
            <p class="role">Dosen</p>
        </div>
    </div>

    <ul class="menu">
        <li><a href="dashboard_dosen.php"><i data-feather="home"></i>Dashboard</a></li>
        <li><a href="input_publikasi_dosen.php"><i data-feather="file-plus"></i>Tambah Publikasi</a></li>
        <li><a href="profile_dosen.php"><i data-feather="user"></i>Profil Anggota</a></li>
    </ul>

    <div class="logout">
        <a href="LogoutDosen.php"><i data-feather="log-out"></i>Log Out</a>
    </div>
</div>
