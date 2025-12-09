<?php
session_start();

$root = dirname(__DIR__);

$page_title = "Dashboard Kepala Lab";
$active_page = "dashboard";

include $root . "../components/sidebar.php";
include $root . "../components/header.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Lab</title>

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
            padding: 90px 30px 80px 30px;
        }

        .card-stat {
            border-left: 6px solid #4e73df;
            transition: transform 0.2s;
        }

        .card-stat:hover {
            transform: translateY(-5px);
        }

        .table thead th {
            background: #4e73df;
            color: white;
            font-weight: 600;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
    </style>
</head>

<body>

    <div class="main-content">
        <!-- CARD STAT -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow card-stat">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-primary text-uppercase small mb-1 fw-semibold">
                                    Pendaftar Baru
                                </p>
                                <h2 id="notif" class="fw-bold text-dark mb-0">
                                    <span class="spinner-border spinner-border-sm" role="status_new_member"></span>
                                </h2>
                            </div>
                            <div>
                                <i class="fas fa-users fa-3x text-primary opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE: PENDING -->
        <div class="card shadow">
            <div class="card-header" style="background: #4e73df;">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-clock me-2"></i>Pendaftaran Pending
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelPending">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Prodi</th>
                                <th>Email</th>
                                <th>Tanggal</th>
                                <th>Alasan</th>
                                <th>Dosen Pengampu</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status_new_member">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
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
        /* LOAD NOTIF */
        async function loadNotif() {
            try {
                console.log('Fetching count_pending.php...');
                let res = await fetch("count_pending.php");
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                let j = await res.json();
                console.log('Count data:', j);
                document.getElementById("notif").innerText = j.count;
            } catch (error) {
                console.error('Error loading notif:', error);
                document.getElementById("notif").innerHTML = '<small class="text-danger">Error</small>';
            }
        }

        /* TABLE PENDING */
        async function loadTable() {
            try {
                console.log('Fetching get_pending.php...');
                let res = await fetch("get_pending.php");
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                let data = await res.json();
                console.log('Pending data:', data);
                
                let tbody = document.querySelector("#tabelPending tbody");
                
                if (!data || data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p class="mb-0">Tidak ada pendaftaran pending</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = '';
                data.forEach(d => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${d.nama_new_member || '-'}</td>
                            <td>${d.nim_new_member || '-'}</td>
                            <td>${d.jurusan_new_member || '-'}</td>
                            <td>${d.prodi_new_member || '-'}</td>
                            <td>${d.email_new_member || '-'}</td>
                            <td>${d.tanggal_daftar_new_member || '-'}</td>
                            <td>${d.alasan_new_member || '-'}</td>
                            <td>${d.dosen_pengampu || '-'}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-success btn-sm" onclick="updateStatus(${d.id_new_member}, 'diterima')" style="min-width: 90px;">
                                        <i class="fas fa-check me-1"></i>Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="updateStatus(${d.id_new_member}, 'ditolak')" style="min-width: 90px;">
                                        <i class="fas fa-times me-1"></i>Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error loading table:', error);
                let tbody = document.querySelector("#tabelPending tbody");
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            <p class="mb-0">Gagal memuat data: ${error.message}</p>
                            <small>Periksa console untuk detail error</small>
                        </td>
                    </tr>
                `;
            }
        }

        /* UPDATE STATUS */
async function updateStatus(id, status) {
    try {
        console.log('Updating status:', { id, status });
        
        // Tampilkan loading/konfirmasi
        const action = status === 'diterima' ? 'menyetujui' : 'menolak';
        if (!confirm(`Apakah Anda yakin ingin ${action} pendaftaran ini?`)) {
            return;
        }
        
        const res = await fetch("../approve.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${id}&status_new_member=${status}`
        });
        
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        
        const result = await res.json();
        console.log('Update result:', result);
        
        if (result.success) {
            alert(`Pendaftaran berhasil ${status}!`);
            // Reload data
            loadNotif();
            loadTable();
        } else {
            alert(`Gagal: ${result.error || 'Unknown error'}`);
        }
        
    } catch (error) {
        console.error('Error updating status:', error);
        alert(`Terjadi kesalahan: ${error.message}`);
    }
}
       
        /* LOAD AWAL */
        console.log('Initializing dashboard...');
        loadNotif();
        loadTable();

        /* AUTO REFRESH TIAP 10 DETIK */
        setInterval(() => {
            console.log('Auto-refresh triggered');
            loadNotif();
            loadTable();
        }, 10000);
    </script>

</body>

</html>