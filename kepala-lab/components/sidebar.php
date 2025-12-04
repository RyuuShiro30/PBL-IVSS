<style>
.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    color: white;
    z-index: 30;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.sidebar-title {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 0 20px 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 15px;
}

.sidebar-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.sidebar-menu {
    flex: 1;
    overflow-y: auto;
}

.sidebar a {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    font-size: 15px;
    margin: 5px 10px;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.sidebar a i {
    width: 24px;
    margin-right: 12px;
    font-size: 16px;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    transform: translateX(5px);
}

.active-menu {
    background: rgba(255,255,255,0.25) !important;
    color: white !important;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.sidebar-divider {
    height: 1px;
    background: rgba(255,255,255,0.2);
    margin: 15px 20px;
}

.sidebar-section-title {
    color: rgba(255,255,255,0.6);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px 20px 8px 20px;
    margin-top: 10px;
}

.sidebar-footer {
    margin-top: auto;
    padding-bottom: 20px;
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 15px;
}

.logout-link {
    background: rgba(255,255,255,0.1);
    margin: 5px 10px !important;
}

.logout-link:hover {
    background: rgba(220, 53, 69, 0.8) !important;
    color: white !important;
}
</style>

<div class="sidebar">
    <div class="sidebar-title">
        <img src="../assets/img/Logo-lab.png" alt="Lab Logo" class="sidebar-logo">
        <span>LAB IVSS</span>
    </div>

    <div class="sidebar-menu">
        <a href="../pages/dashboard.php" class="<?= $active_page == 'dashboard' ? 'active-menu' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>


        <a href="../pages/get_history.php" class="<?= $active_page == 'history' ? 'active-menu' : '' ?>">
            <i class="fas fa-history"></i>
            <span>Log Aktivitas</span>
        </a>

        <a href="../pages/profile.php" class="<?= $active_page == 'profil' ? 'active-menu' : '' ?>">
            <i class="fas fa-user"></i>
            <span>Profil Saya</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="#" class="logout-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin keluar dari sistem?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="../actions/admin_logout.php" class="btn btn-danger">Ya, Logout</a>
            </div>
        </div>
    </div>
</div>