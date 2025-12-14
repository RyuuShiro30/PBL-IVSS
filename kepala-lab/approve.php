<?php
require __DIR__ . '/config.php';
session_start();

/* ===== AUTH ===== */
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(["success"=>false,"error"=>"Unauthorized"]);
    exit;
}

/* ===== METHOD ===== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success"=>false,"error"=>"Method not allowed"]);
    exit;
}

/* ===== INPUT ===== */
$id     = $_POST['id'] ?? null;
$status = $_POST['status_new_member'] ?? null;

if (!$id || !$status) {
    echo json_encode(["success"=>false,"error"=>"Parameter tidak lengkap"]);
    exit;
}

try {
    $pdo->beginTransaction();

    /* ===== AMBIL DATA NEW MEMBER ===== */
    $stmt = $pdo->prepare("SELECT * FROM new_member WHERE id_new_member = :id");
    $stmt->execute([':id' => $id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        throw new Exception("Data pendaftar tidak ditemukan");
    }

    /* ===== JIKA DITERIMA ===== */
    if ($status === 'diterima') {

        /* ===== INSERT MAHASISWA ===== */
        $stmtMhs = $pdo->prepare("
            INSERT INTO mahasiswa (
                nama,
                nim,
                prodi,
                email,
                jurusan_mahasiswa,
                mahasiswa_profile,
                tahun_lulus,
                created_at,
                updated_at
            ) VALUES (
                :nama,
                :nim,
                :prodi,
                :email,
                :jurusan,
                :profile,
                NULL,
                NOW(),
                NOW()
            )
            RETURNING id
        ");

        $stmtMhs->execute([
            ':nama'    => $member['nama_new_member'],
            ':nim'     => $member['nim_new_member'],
            ':prodi'   => $member['prodi_new_member'],
            ':email'   => $member['email_new_member'],
            ':jurusan' => $member['jurusan_new_member'],
            ':profile' => $member['new_member_profile']
        ]);

        $id_mahasiswa = $stmtMhs->fetchColumn();

        /* ===== INSERT RISET ===== */
        $stmtRiset = $pdo->prepare("
            INSERT INTO riset (
                judul,
                link_riset,
                tahun,
                created_at,
                updated_at
            ) VALUES (
                :judul,
                NULL,
                EXTRACT(YEAR FROM NOW()),
                NOW(),
                NOW()
            )
            RETURNING id
        ");

        $stmtRiset->execute([
            ':judul' => $member['judul_riset_new_member']
        ]);

        $id_riset = $stmtRiset->fetchColumn();

        /* ===== RELASI RISET_MAHASISWA ===== */
        $pdo->prepare("
            INSERT INTO riset_mahasiswa (id_riset, id_mahasiswa)
            VALUES (:id_riset, :id_mahasiswa)
        ")->execute([
            ':id_riset'     => $id_riset,
            ':id_mahasiswa' => $id_mahasiswa
        ]);
    }

    /* ===== UPDATE STATUS ===== */
    $pdo->prepare("
        UPDATE new_member
        SET status_new_member = :status,
            tanggal_update_member = NOW()
        WHERE id_new_member = :id
    ")->execute([
        ':status' => $status,
        ':id'     => $id
    ]);

    $pdo->commit();

    echo json_encode(["success"=>true,"message"=>"Approve berhasil"]);

} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "success"=>false,
        "error"=>$e->getMessage()
    ]);
}
