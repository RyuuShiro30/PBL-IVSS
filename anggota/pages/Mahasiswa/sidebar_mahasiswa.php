<div class="sidebar">
    <div class="top-section">

    <div class="logo-area">
        <div class="logo-box">
            <img src="../../../user/img/IVSS.png" alt="Logo">
        </div>

        <div class="logo-text">
            <span class="titleSidebar">Lab IVSS</span>
            <span class="subtitleSidebar">Politeknik Negeri Malang</span>
        </div>
    </div>

        <div class="welcome-box">
            <p class="welcome">Welcome,</p>
            <p class="name"><?= $_SESSION['name'] ?></p>
            <p class="role">Mahasiswa</p>
        </div>
    </div>

    <ul class="menu">
        <li><a href="dashboard_mhs.php"><i data-feather="home"></i>Dashboard</a></li>
        <li><a href="input_publikasi_mhs.php"><i data-feather="file-plus"></i>Tambah Riset</a></li>
        <li><a href="profile_mhs.php"><i data-feather="user"></i>Profil Anggota</a></li>
    </ul>

    <div class="logout">
        <a href="LogoutMhs.php"><i data-feather="log-out"></i>Log Out</a>
    </div>
</div>
<style>
    .logo-box {
    background: #ffffff;
    padding: 6px;
    border-radius: 10px;
    width: 55px;
    height: 55px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
