<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['id_mhs'])) {
    exit("Anda harus login.");
}

$id = $_SESSION['id_mhs'];

$stmt = $conn->prepare("SELECT * FROM profile_mahasiswa WHERE id_mhs = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil daftar dosen
$stmtDosen = $conn->prepare("SELECT id, nama FROM dosen ORDER BY nama ASC");
$stmtDosen->execute();
$dosenList = $stmtDosen->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil Mahasiswa</title>
    <link rel="stylesheet" href="../../css/Mahasiswa/profile_mhs.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F4F6FA;
        }

        .content {
            margin-left: 260px;
            padding: 40px;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #0A4D68;
        }

        .form-update {
            background: white;
            padding: 35px;
            border-radius: 14px;
            max-width: 600px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .form-update label {
            display: block;
            margin-top: 15px;
            font-size: 15px;
            font-weight: 600;
            color: #333;
        }

        .form-update input {
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #d3d3d3;
            margin-top: 5px;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        .form-update input:focus {
            border-color: #0A4D68;
        }

        .btn-edit {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background: #0A4D68;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-edit:hover {
            background: #086084;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 18px;
            background: #6c757d;
            text-decoration: none;
            color: white;
            font-size: 14px;
            border-radius: 6px;
        }

        .btn-back:hover {
            background: #5a6268;
        }
        .form-update select {
    width: 100%;
    padding: 11px;
    border-radius: 8px;
    border: 1px solid #d3d3d3;
    margin-top: 5px;
    font-size: 15px;
    outline: none;
    transition: 0.2s;
}
.form-update select:focus {
    border-color: #0A4D68;
}
    </style>
</head>

<body>

<?php include 'sidebar_mahasiswa.php'; ?>

<div class="content">

    <a href="profile_mhs.php" class="btn-back">⬅ Kembali ke Profil</a>

    <h1>Edit Profil Mahasiswa</h1>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="form-update">

        <label>Nama</label>
        <input name="nama" type="text" value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label>Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($data['email']) ?>" required>

        <label>Program Studi</label>
        <input name="prodi" type="text" value="<?= htmlspecialchars($data['prodi']) ?>">

        <label>Tahun Lulus</label>
        <input name="tahun" type="number" value="<?= htmlspecialchars($data['tahun_lulus']) ?>">

<label>Dosen Pengampu</label>
<select name="dosen" required>
    <option value="">-- Pilih Dosen Pengampu --</option>
    <?php foreach ($dosenList as $dosen): ?>
        <option value="<?= $dosen['id'] ?>"
            <?= ($data['id_dosen'] == $dosen['id']) ? 'selected' : '' ?>>
            <?= $dosen['nama'] ?>
        </option>
    <?php endforeach; ?>
</select>


        <label>Ganti Foto Profil</label>
        <input type="file" name="foto">

        <button type="submit" class="btn-edit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
