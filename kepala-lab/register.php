<?php
require 'config.php'; // ini membuat $pdo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid. Silakan daftar melalui form.");
}

$nama    = $_POST['nama'] ?? null;
$nim     = $_POST['nim'] ?? null;
$jurusan = $_POST['jurusan'] ?? null;
$prodi   = $_POST['prodi'] ?? null;
$email   = $_POST['email'] ?? null;
$dosen   = $_POST['dosen'] ?? null;
$alasan  = $_POST['alasan'] ?? null;

if (!$nama || !$nim || !$jurusan || !$prodi || !$email || !$dosen || !$alasan) {
    die("Semua data harus diisi dengan benar.");
}

try {
    $query = "INSERT INTO members 
        (nama, nim, jurusan, prodi, email, dosen_id, alasan, status, tanggal_daftar)
        VALUES (:nama, :nim, :jurusan, :prodi, :email, :dosen, :alasan, 'pending', NOW())";

    $stmt = $pdo->prepare($query);   // gunakan $pdo, bukan $conn

    $stmt->execute([
        ':nama'    => $nama,
        ':nim'     => $nim,
        ':jurusan' => $jurusan,
        ':prodi'   => $prodi,
        ':email'   => $email,
        ':dosen'   => $dosen,
        ':alasan'  => $alasan
    ]);

} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - IVSS</title>
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

        .success-box {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 50px 40px;
            text-align: center;
        }

        .icon-container {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .icon-container i {
            font-size: 3.5rem;
            color: #10b981;
        }

        .success-header h2 {
            color: white;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .success-header p {
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
        }

        .success-content {
            padding: 40px 45px;
        }

        .logo-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .logo-badge img {
            width: 100px;
            height: 100px;
        }

        .logo-badge span {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 4px solid #6b86d3ff;
        }

        .btn-primary-custom {
            padding: 14px 30px;
            font-weight: 600;
            background: #6b86d3ff;
            border: none;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background: #0e2676ff;
            color: white;
            transform: translateY(-2px);
        }

        .btn-primary-custom i {
            margin-right: 10px;
        }

        .btn-secondary-custom {
            padding: 14px 30px;
            font-weight: 600;
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-secondary-custom:hover {
            background: #f8fafc;
            border-color: #6b86d3ff;
            color: #6b86d3ff;
            transform: translateY(-2px);
        }

        .btn-secondary-custom i {
            margin-right: 10px;
        }

        .success-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            margin-top: 30px;
        }

        .success-footer p {
            color: #64748b;
            font-size: 0.85rem;
            margin: 0;
        }

        .success-footer a {
            color: #6b86d3ff;
            text-decoration: none;
            font-weight: 500;
        }

        .success-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            body { padding: 20px 15px; }
            .success-header { padding: 40px 30px; }
            .icon-container { width: 85px; height: 85px; }
            .icon-container i { font-size: 3rem; }
            .success-header h2 { font-size: 1.6rem; }
            .success-content { padding: 30px 25px; }
            .info-card { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="success-box">
        <!-- Header dengan Icon -->
        <div class="success-header">
            <div class="icon-container">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Pendaftaran Berhasil!</h2>
        </div>

        <!-- Konten -->
        <div class="success-content">
            <!-- Logo Besar -->
            <div class="logo-badge">
                <img src="assets/img/Logo-lab.png" alt="Lab Logo">
            </div>

            <p style="text-align: center; color: #64748b; margin-bottom: 30px;">
                Pendaftaran Anda sedang dalam proses verifikasi. Cek status secara berkala!
            </p>

            <!-- Tombol -->
            <a href="cek_status.php" class="btn-primary-custom">
                <i class="fas fa-search"></i> Cek Status Pendaftaran
            </a>
            <a href="index.php" class="btn-secondary-custom">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>