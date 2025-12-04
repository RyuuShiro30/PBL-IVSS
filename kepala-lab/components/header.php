<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek session
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

// Refresh data dari database untuk memastikan data terbaru
try {
    require_once __DIR__ . '/../config.php';
    
    $stmt = $pdo->prepare("SELECT nama_lengkap, email, foto, role, username FROM kepalalab WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['admin_id']]);
    $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin_data) {
        // Update session dengan data terbaru dari database
        $_SESSION['nama_lengkap'] = $admin_data['nama_lengkap'];
        $_SESSION['admin_name'] = $admin_data['nama_lengkap'];
        $_SESSION['email'] = $admin_data['email'];
        $_SESSION['foto'] = $admin_data['foto'];
        $_SESSION['username'] = $admin_data['username'];
        $_SESSION['admin_username'] = $admin_data['username'];
        $_SESSION['role'] = $admin_data['role'];
    }
} catch (Exception $e) {
    error_log("Error loading admin data in header: " . $e->getMessage());
}

// Ambil data admin dari session
$admin_name = $_SESSION['nama_lengkap'] ?? $_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin';
$admin_role = $_SESSION['role'] ?? 'Kepala Lab';
$admin_foto = $_SESSION['foto'] ?? 'default-avatar.png';
?>

<style>
/* Header Styles */
.header-custom {
    height: 86px;
    background: #fff;
    border-bottom: 2px solid #e3e6f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 20;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-left: 250px; /* Sejajar dengan lebar sidebar */
}

/* Page Title */
.header-title {
    font-size: 22px;
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-title i {
    color: #4e73df;
}

/* User Info Box */
.header-user-box {
    display: flex;
    align-items: center;
    gap: 14px;
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    padding: 10px 18px;
    border-radius: 50px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
    cursor: pointer;
}

.header-user-box:hover {
    background: linear-gradient(135deg, #e2e6ea 0%, #f8f9fc 100%);
    border-color: #4e73df;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.15);
    transform: translateY(-2px);
}

/* User Avatar */
.user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid #4e73df;
    object-fit: cover;
    transition: all 0.3s ease;
}

.header-user-box:hover .user-avatar {
    border-color: #2e59d9;
    transform: scale(1.05);
}

/* User Info Text */
.user-info {
    display: flex;
    flex-direction: column;
    line-height: 1.3;
}

.user-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.user-role {
    font-size: 12px;
    color: #858796;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .header-custom {
        margin-left: 250px;
        height: 70px;
        display: flex;
        align-items: center;
    }
    
    .header-title {
        font-size: 18px;
    }
    
    .user-info {
        display: none;
    }
    
    .header-user-box {
        padding: 8px;
    }
}
</style>

<div class="header-custom">
    <!-- Page Title -->
    <div class="header-title">
        <i class="fas fa-tachometer-alt"></i>
        <?= htmlspecialchars($page_title ?? "Dashboard"); ?>
    </div>

    <!-- User Info -->
    <div class="header-user-box">
        <div class="user-info">
            <span class="user-name"><?= htmlspecialchars($admin_name); ?></span>
            <span class="user-role"><?= htmlspecialchars($admin_role); ?></span>
        </div>
        <img class="user-avatar" 
             src="../assets/img/<?= htmlspecialchars($admin_foto); ?>?t=<?= time(); ?>" 
             onerror="this.src='../assets/img/default-avatar.png'"
             alt="Profile">
    </div>
</div>