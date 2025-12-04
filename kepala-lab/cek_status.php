<?php
session_start();
require 'config.php';

$status = "";
$data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    
    if (empty($nim)) {
        $status = "NIM tidak boleh kosong!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT m.*, d.nama as dosen_nama 
                                   FROM members m 
                                   LEFT JOIN dosen d ON m.dosen_id = d.id 
                                   WHERE m.nim = :nim");
            $stmt->execute(['nim' => $nim]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                $status = "NIM tidak ditemukan!";
            }
        } catch (PDOException $e) {
            $status = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran - IVSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6b86d3ff 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .check-box {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }

        .check-header {
            background: linear-gradient(135deg, #6b86d3ff 0%, #1e40af 100%);
            padding: 40px;
            text-align: center;
        }

        .icon-container {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .icon-container i {
            font-size: 2.5rem;
            color: #6b86d3ff;
        }

        .check-header h2 {
            color: white;
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }

        .check-content {
            padding: 40px 45px;
        }

        .logo-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .logo-badge img {
            width: 60px;
            height: 60px;
        }

        .logo-badge span {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .form-label {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: white;
            border: 1px solid #e2e8f0;
            color: #64748b;
            border-right: none;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            padding: 12px 14px;
            border-left: none;
        }

        .form-control:focus {
            border-color: #6b86d3ff;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #6b86d3ff;
        }

        .btn-primary {
            background: #6b86d3ff !important;
            border: none !important;
            font-weight: 600;
            padding: 12px !important;
            border-radius: 8px !important;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #0e2676ff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 134, 211, 0.4);
        }

        .btn-back {
            padding: 12px;
            font-weight: 600;
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
            border-radius: 8px;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #6b86d3ff;
            color: #6b86d3ff;
            transform: translateY(-2px);
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #6b86d3ff;
        }

        .result-card h5 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-item {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .result-value {
            color: #1e293b;
            font-weight: 600;
            margin-top: 3px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 3px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 768px) {
            body { padding: 20px 15px; }
            .check-header { padding: 35px 30px; }
            .icon-container { width: 70px; height: 70px; }
            .icon-container i { font-size: 2rem; }
            .check-header h2 { font-size: 1.5rem; }
            .check-content { padding: 30px 25px; }
        }
    </style>
</head>
<body>
    <div class="check-box">
        <!-- Header -->
        <div class="check-header">
            <div class="icon-container">
                <i class="fas fa-search"></i>
            </div>
            <h2>Cek Status Pendaftaran</h2>
        </div>

        <!-- Content -->
        <div class="check-content">
            <!-- Form -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Nomor Induk Mahasiswa (NIM)</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" class="form-control" name="nim" 
                               placeholder="Masukkan NIM Anda" required>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Cek Status Pendaftaran
                    </button>
                </div>
            </form>

            <!-- Alert Error -->
            <?php if ($status): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $status ?></span>
            </div>
            <?php endif; ?>

            <!-- Result Card -->
            <?php if ($data): ?>
            <div class="result-card">
                <h5>
                    <i class="fas fa-user-circle"></i>
                    Informasi Pendaftaran
                </h5>
                
                <div class="result-item">
                    <div class="result-label">Nama Lengkap</div>
                    <div class="result-value"><?= htmlspecialchars($data['nama']) ?></div>
                </div>

                <div class="result-item">
                    <div class="result-label">NIM</div>
                    <div class="result-value"><?= htmlspecialchars($data['nim']) ?></div>
                </div>

                <div class="result-item">
                    <div class="result-label">Status Pendaftaran</div>
                    <div>
                        <?php if ($data['status'] === 'pending'): ?>
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock me-1"></i>MENUNGGU PERSETUJUAN
                            </span>
                        <?php elseif ($data['status'] === 'approved'): ?>
                            <span class="status-badge status-approved">
                                <i class="fas fa-check-circle me-1"></i>DITERIMA
                            </span>
                        <?php else: ?>
                            <span class="status-badge status-rejected">
                                <i class="fas fa-times-circle me-1"></i>DITOLAK
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($data['status'] === 'pending'): ?>
                <p style="color: #92400e; margin-top: 15px; margin-bottom: 0; font-size: 0.9rem;">
                    <i class="fas fa-info-circle me-1"></i>
                    Pendaftaran Anda sedang dalam proses verifikasi. Harap bersabar.
                </p>
                <?php elseif ($data['status'] === 'approved'): ?>
                <p style="color: #065f46; margin-top: 15px; margin-bottom: 0; font-size: 0.9rem;">
                    <i class="fas fa-check-circle me-1"></i>
                    Selamat! Anda telah diterima sebagai member lab IVSS.
                </p>
                <?php else: ?>
                <p style="color: #991b1b; margin-top: 15px; margin-bottom: 0; font-size: 0.9rem;">
                    <i class="fas fa-info-circle me-1"></i>
                    Tetap semangat! Anda dapat mencoba mendaftar kembali di tahun berikutnya.
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Back Button -->
            <a href="index.php" class="btn-back">
                <i class="fas fa-home me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>