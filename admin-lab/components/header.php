<?php
/**
 * Header Component
 * File: components/header.php
 */

// Cek session
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Ambil data admin dari session
$admin_name = $_SESSION['nama_lengkap'] ?? 'Admin';
$admin_role = $_SESSION['role'] ?? 'Administrator';
$admin_foto = $_SESSION['foto'] ?? 'default-avatar.png';
?>

<!-- Custom CSS untuk Header yang lebih modern -->
<style>
.topbar {
    border-bottom: 2px solid #e3e6f0;
    padding: 0.5rem 1.5rem;
}

.navbar-nav .nav-item .nav-link {
    padding: 0.5rem 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}
/* 
.navbar-nav .nav-item .nav-link:hover {
    background-color: #f8f9fc;
} */

.user-info-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.user-info-wrapper:hover {
    background-color: #f8f9fc;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.user-text-info {
    display: flex;
    flex-direction: column;
    text-align: right;
    line-height: 1.3;
}

.user-name {
    font-weight: 600;
    color: #3a3b45;
    font-size: 0.9rem;
}

.user-role {
    font-size: 0.75rem;
    color: #858796;
    font-weight: 500;
}

.img-profile {
    width: 45px;
    height: 45px;
    border: 2px solid #4e73df;
    padding: 2px;
    transition: all 0.3s ease;
}

.user-info-wrapper:hover .img-profile {
    transform: scale(1.05);
    border-color: #2e59d9;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    border-radius: 10px;
    margin-top: 10px;
}

.dropdown-item {
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    border-radius: 8px;
    margin: 0 8px;
}

.dropdown-item:hover {
    background-color: #f8f9fc;
    transform: translateX(5px);
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

.dropdown-divider {
    margin: 0.5rem 1rem;
}

#sidebarToggleTop {
    color: #4e73df;
    transition: all 0.3s ease;
}

#sidebarToggleTop:hover {
    color: #2e59d9;
    transform: rotate(90deg);
}

@media (max-width: 768px) {
    .user-text-info {
        display: none;
    }
    
    .user-info-wrapper {
        padding: 8px;
    }
}
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
    
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Spacer -->
    <div class="flex-grow-1"></div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle p-0" href="#" id="userDropdown" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="user-info-wrapper">
                    <div class="user-text-info d-none d-lg-flex">
                        <span class="user-name">
                            <?php echo htmlspecialchars($admin_name); ?>
                        </span>
                        <span class="user-role">
                            <?php echo htmlspecialchars($_SESSION['role']);?>
                        </span>
                    </div>
                    <img class="img-profile rounded-circle" 
                         src="../assets/img/<?php echo htmlspecialchars($admin_foto); ?>"
                         onerror="this.src='../assets/img/default-avatar.png'"
                         alt="Profile">
                </div>
            </a>
            
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow-lg animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                    Profil Saya
                </a>
                <a class="dropdown-item" href="logs.php">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-info"></i>
                    Aktivitas Saya
                </a>
                
                <div class="dropdown-divider"></div>
                
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<!-- Logout Modal dengan desain lebih modern -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="border-bottom: 2px solid #e3e6f0;">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="fas fa-sign-out-alt text-danger mr-2"></i>
                    Konfirmasi Logout
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-question-circle text-warning" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p class="mb-0" style="font-size: 1.1rem;">Apakah Anda yakin ingin keluar dari sistem?</p>
                <small class="text-muted">Anda harus login kembali untuk mengakses dashboard</small>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #e3e6f0;">
                <button class="btn btn-secondary px-4" type="button" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <a class="btn btn-danger px-4" href="../actions/logout.php">
                    <i class="fas fa-sign-out-alt mr-1"></i> Ya, Logout
                </a>
            </div>
        </div>
    </div>
</div>