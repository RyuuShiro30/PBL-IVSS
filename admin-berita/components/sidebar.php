<?php
// Ambil nama halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Cek role admin
$is_superadmin = ($_SESSION['role'] ?? '') === 'superadmin';
?>


<style>
/* Sidebar tetap fixed saat scroll */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 14rem;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
    background: linear-gradient(180deg, #5a67d8 0%, #4c51bf 100%) !important;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

/* Content wrapper adjustment untuk fixed sidebar */
#content-wrapper {
    margin-left: 14rem;
    width: calc(100% - 14rem);
    transition: all 0.3s ease;
}

/* Ketika sidebar collapsed */
.sidebar.toggled {
    width: 6.5rem;
}

.sidebar.toggled ~ #content-wrapper {
    margin-left: 6.5rem;
    width: calc(100% - 6.5rem);
}

/* ===== SIDEBAR INTERNAL LAYOUT ===== */

/* Sidebar brand */
.sidebar-brand {
    height: 70px;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.sidebar-brand:hover {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar-brand-logo img {
    transition: all 0.3s ease;
    filter: brightness(1.2);
}

.sidebar-brand:hover .sidebar-brand-logo img {
    transform: scale(1.05);
}

.sidebar-brand-text {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 0.5px;
}

/* Sidebar divider */
.sidebar-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin: 0.5rem 1rem;
    flex-shrink: 0;
}

/* Sidebar heading */
.sidebar-heading {
    font-size: 0.65rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 1);
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 1.5rem 1rem 0.5rem;
    flex-shrink: 0;
}

/* Nav items container - scrollable area */
.sidebar .nav-item {
    margin: 0.25rem 0.75rem;
}

.sidebar .nav-item .nav-link {
    padding: 0.65rem 1rem;
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 500;
    transition: all 0.25s ease;
    border: none !important;
}

.sidebar .nav-item .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #0f0f0fff;
    transform: translateX(3px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.sidebar .nav-item.active .nav-link {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}

.sidebar .nav-item .nav-link i {
    margin-right: 0.75rem;
    font-size: 0.95rem;
    width: 20px;
    text-align: center;
}

/* Sidebar footer - tetap di bawah */
.sidebar-footer {
    margin-top: auto;
    padding: 1rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.sidebar-footer .nav-link {
    padding: 0.65rem 1rem;
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 500;
    transition: all 0.25s ease;
    margin: 0 0.75rem;
    border: none !important;
}

.sidebar-footer .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    transform: translateX(3px);
    box-shadow:0 2px 6px rgba(0, 0, 0, 0.1);
}


.sidebar-footer i {
    margin-right: 0.65rem;
}

/* ===== COLLAPSE MENU STYLING ===== */

.collapse-inner {
    background: rgba(255, 255, 255, 0.95) !important;
    border-radius: 8px;
    margin: 0.25rem 0.75rem;
    padding: 0.5rem 0 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: none !important;
}

.collapse-header {
    font-size: 0.65rem;
    font-weight: 700;
    color: #5a67d8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.5rem 1rem 0.25rem;
    margin-bottom: 0;
    border: none !important;
}

.collapse-item {
    padding: 0.65rem 1rem;
    margin: 0.2rem 0.5rem;
    border-radius: 6px;
    color: #4a5568;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    border: none !important;
}

.collapse-item:hover {
    background: #e8eaf6;
    color: #5a67d8;
    transform: translateX(3px);
    box-shadow: 0 2px 4px rgba(90, 103, 216, 0.1);
}

.collapse-item.active {
    background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(90, 103, 216, 0.3);
}

.collapse-item i {
    margin-right: 0.5rem;
    font-size: 0.8rem;
}

/* ===== SCROLLBAR STYLING ===== */

.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* ===== SIDEBAR TOGGLE BUTTON ===== */

#sidebarToggle {
    width: 2.5rem;
    height: 2.5rem;
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

#sidebarToggle:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

#sidebarToggle::after {
    content: '\f104';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: rgba(255, 255, 255, 0.8);
}

#sidebarToggle:hover::after {
    color: white;
}

/* ===== ANIMATION ===== */

.collapse {
    transition: all 0.3s ease;
}

/* ===== RESPONSIVE ===== */

@media (max-width: 768px) {
    .sidebar {
        width: 0;
        margin-left: -14rem;
    }
    
    .sidebar.toggled {
        width: 14rem;
        margin-left: 0;
    }
    
    #content-wrapper {
        margin-left: 0;
        width: 100%;
    }
    
    .sidebar.toggled ~ #content-wrapper {
        margin-left: 0;
        width: 100%;
    }
}

/* ===== PAGE WRAPPER ===== */

#wrapper {
    display: flex;
    min-height: 100vh;
}

/* Ensure body doesn't have overflow issues */
body {
    overflow-x: hidden;
}
</style>

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-logo">
            <img src="../assets/img/Logo-lab.png" alt="Logo Lab Kampus" width="40" style="object-fit: contain;">
        </div>
        <div class="sidebar-brand-text mx-3">
            LAB IVSS
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link mt-3" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Konten
    </div>

    <!-- Nav Item - Berita -->
    <li class="nav-item <?php echo in_array($current_page, ['berita-list.php', 'berita-add.php', 'berita-edit.php']) ? 'active' : ''; ?>">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBerita"
            aria-expanded="true" aria-controls="collapseBerita">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Kelola Berita</span>
        </a>
        <div id="collapseBerita" class="collapse <?php echo in_array($current_page, ['berita-list.php', 'berita-add.php', 'berita-edit.php']) ? 'show' : ''; ?>" 
             aria-labelledby="headingBerita" data-bs-parent="#accordionSidebar">
            <div class="collapse-inner">
                <h6 class="collapse-header">Menu Berita</h6>
                <a class="collapse-item <?php echo $current_page === 'berita-list.php' ? 'active' : ''; ?>" 
                   href="berita-list.php">
                    <i class="fas fa-list"></i> Daftar Berita
                </a>
                <a class="collapse-item <?php echo $current_page === 'berita-add.php' ? 'active' : ''; ?>" 
                   href="berita-add.php">
                    <i class="fas fa-plus"></i> Tambah Berita
                </a>
            </div>
        </div>
    </li>

    <?php if ($is_superadmin): ?>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Admin
    </div>

    <!-- Nav Item - Admin (Khusus Super Admin) -->
    <li class="nav-item <?php echo in_array($current_page, ['admin-list.php', 'admin-add.php', 'admin-edit.php']) ? 'active' : ''; ?>">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin"
            aria-expanded="true" aria-controls="collapseAdmin">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Kelola Admin</span>
        </a>
        <div id="collapseAdmin" class="collapse <?php echo in_array($current_page, ['admin-list.php', 'admin-add.php', 'admin-edit.php']) ? 'show' : ''; ?>" 
             aria-labelledby="headingAdmin" data-bs-parent="#accordionSidebar">
            <div class="collapse-inner">
                <h6 class="collapse-header">Menu Admin</h6>
                <a class="collapse-item <?php echo $current_page === 'admin-list.php' ? 'active' : ''; ?>" 
                   href="admin-list.php">
                    <i class="fas fa-list"></i> Daftar Admin
                </a>
                <a class="collapse-item <?php echo $current_page === 'admin-add.php' ? 'active' : ''; ?>" 
                   href="admin-add.php">
                    <i class="fas fa-user-plus"></i> Tambah Admin
                </a>
            </div>
        </div>
    </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Lainnya
    </div>

    <!-- Nav Item - Log Aktivitas -->
    <li class="nav-item <?php echo $current_page === 'logs.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="logs.php">
            <i class="fas fa-fw fa-history"></i>
            <span>Log Aktivitas</span>
        </a>
    </li>

    <!-- Nav Item - Profil -->
    <li class="nav-item <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="profile.php">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil Saya</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    
    
    <!-- Sidebar Toggler (Sidebar) -->
    
        <!-- Logout in sidebar footer -->
        <div class="sidebar-footer px-3 pb-3">
    <a class="nav-link d-flex align-items-center justify-content-start" 
        href="#" 
        data-bs-toggle="modal" 
        data-bs-target="#logoutModal" 
        title="Logout">
        <i class="fas fa-fw fa-sign-out-alt me-2" style="width:20px;text-align:center;"></i>
        <span>Logout</span>
    </a>
</div>
</ul>
<!-- End of Sidebar -->