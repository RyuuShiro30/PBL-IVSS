<?php
session_start();

$root = dirname(__DIR__);

$page_title = "History Pendaftaran";
$active_page = "history";

include $root . "../components/sidebar.php";
include $root . "../components/header.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pendaftaran</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f8f9fc;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 90px 30px 30px 30px;
            min-height: 100vh;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-left: 5px solid;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
        }

        .stats-card.total {
            border-left-color: #4e73df;
        }

        .stats-card.approved {
            border-left-color: #1cc88a;
        }

        .stats-card.rejected {
            border-left-color: #e74a3b;
        }

        .stats-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-text h6 {
            color: #858796;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .stats-text .stats-number {
            font-size: 32px;
            font-weight: 700;
            color: #5a5c69;
            line-height: 1;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stats-card.total .stats-icon {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }

        .stats-card.approved .stats-icon {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        }

        .stats-card.rejected .stats-icon {
            background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        }

        /* Card Tables */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 30px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .table thead th {
            font-weight: 600;
            font-size: 14px;
        }

        .table td {
            font-size: 14px;
            vertical-align: middle;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 90px 15px 30px 15px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .stats-text .stats-number {
                font-size: 24px;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="main-content">

        <!-- Stats Cards -->
        <div class="stats-container">
            <!-- Total Pendaftaran -->
            <div class="stats-card total">
                <div class="stats-content">
                    <div class="stats-text">
                        <h6>Total Pendaftaran</h6>
                        <div class="stats-number" id="totalCount">0</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="stats-card approved">
                <div class="stats-content">
                    <div class="stats-text">
                        <h6>Disetujui</h6>
                        <div class="stats-number" id="approvedCount">0</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="stats-card rejected">
                <div class="stats-content">
                    <div class="stats-text">
                        <h6>Ditolak</h6>
                        <div class="stats-number" id="rejectedCount">0</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE: APPROVED -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>History Disetujui
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelApproved">
                        <thead class="table-success">
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Prodi</th>
                                <th>Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Alasan</th>
                                <th>Dosen Pengampu</th>
                                <th>Tanggal Disetujui</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="spinner-border text-success" role="status"></div>
                                    <p class="mt-2 mb-0 text-muted">Memuat data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABLE: REJECTED -->
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-times-circle me-2"></i>History Ditolak
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelRejected">
                        <thead class="table-danger">
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Prodi</th>
                                <th>Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Alasan</th>
                                <th>Dosen Pengampu</th>
                                <th>Tanggal Ditolak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="spinner-border text-danger" role="status"></div>
                                    <p class="mt-2 mb-0 text-muted">Memuat data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <?php include $root . "/components/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* GLOBAL DATA */
        let approvedDataGlobal = [];
        let rejectedDataGlobal = [];

        /* UPDATE STATS COUNTS */
        function updateStats() {
            const approvedCount = approvedDataGlobal.length;
            const rejectedCount = rejectedDataGlobal.length;
            const totalCount = approvedCount + rejectedCount;

            console.log('Updating stats:', {
                approved: approvedCount,
                rejected: rejectedCount,
                total: totalCount
            });

            // Update langsung tanpa animasi
            document.getElementById('approvedCount').textContent = approvedCount;
            document.getElementById('rejectedCount').textContent = rejectedCount;
            document.getElementById('totalCount').textContent = totalCount;
        }

        /* TABLE APPROVED */
        async function loadApproved() {
            try {
                console.log('Fetching get_approved.php...');
                let res = await fetch("get_approved.php");
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                let data = await res.json();
                console.log('Approved data:', data);
                
                // Update global data
                approvedDataGlobal = Array.isArray(data) ? data : [];
                
                let tbody = document.querySelector("#tabelApproved tbody");

                if (approvedDataGlobal.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p class="mb-0">Belum ada pendaftaran yang disetujui</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '';
                    approvedDataGlobal.forEach(d => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${d.nama || '-'}</td>
                                <td>${d.nim || '-'}</td>
                                <td>${d.jurusan || '-'}</td>
                                <td>${d.prodi || '-'}</td>
                                <td>${d.email || '-'}</td>
                                <td>${d.tanggal_daftar || '-'}</td>
                                <td>${d.alasan || '-'}</td>
                                <td>${d.dosen_pengampu || '-'}</td>
                                <td>${d.tanggal_update || '-'}</td>
                            </tr>
                        `;
                    });
                }

                // Update stats after loading
                updateStats();
            } catch (error) {
                console.error('Error loading approved:', error);
                approvedDataGlobal = [];
                let tbody = document.querySelector("#tabelApproved tbody");
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            <p class="mb-0">Gagal memuat data: ${error.message}</p>
                        </td>
                    </tr>
                `;
                updateStats();
            }
        }

        /* TABLE REJECTED */
        async function loadRejected() {
            try {
                console.log('Fetching get_rejected.php...');
                let res = await fetch("get_rejected.php");
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                let data = await res.json();
                console.log('Rejected data:', data);
                
                // Update global data
                rejectedDataGlobal = Array.isArray(data) ? data : [];
                
                let tbody = document.querySelector("#tabelRejected tbody");

                if (rejectedDataGlobal.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p class="mb-0">Belum ada pendaftaran yang ditolak</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '';
                    rejectedDataGlobal.forEach(d => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${d.nama || '-'}</td>
                                <td>${d.nim || '-'}</td>
                                <td>${d.jurusan || '-'}</td>
                                <td>${d.prodi || '-'}</td>
                                <td>${d.email || '-'}</td>
                                <td>${d.tanggal_daftar || '-'}</td>
                                <td>${d.alasan || '-'}</td>
                                <td>${d.dosen_pengampu || '-'}</td>
                                <td>${d.tanggal_update || '-'}</td>
                            </tr>
                        `;
                    });
                }

                // Update stats after loading
                updateStats();
            } catch (error) {
                console.error('Error loading rejected:', error);
                rejectedDataGlobal = [];
                let tbody = document.querySelector("#tabelRejected tbody");
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            <p class="mb-0">Gagal memuat data: ${error.message}</p>
                        </td>
                    </tr>
                `;
                updateStats();
            }
        }

        /* LOAD ALL DATA */
        async function loadAllData() {
            console.log('Loading all data...');
            await Promise.all([loadApproved(), loadRejected()]);
        }

        /* LOAD AWAL */
        console.log('Initializing history page...');
        loadAllData();

        /* AUTO REFRESH TIAP 10 DETIK */
        setInterval(() => {
            console.log('Auto-refresh triggered');
            loadAllData();
        }, 10000);
    </script>

</body>

</html>