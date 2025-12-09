<?php
session_start();
require '../../koneksi.php';

if (!isset($_SESSION['id_mhs'])) exit("Akses ditolak");

$id = $_SESSION['id_mhs'];

$nama  = $_POST['nama'];
$email = $_POST['email'];
$prodi = $_POST['prodi'];
$tahun = $_POST['tahun'];
$dosen = $_POST['dosen'];

$fotoBaru = null;

if (!empty($_FILES['foto']['name'])) {
    $fotoBaru = time() . "_" . $_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "../../../user/uploads/" . $fotoBaru);
}

// update ke DB
$sql = "
UPDATE profile_mahasiswa SET
    nama = :nama,
    email = :email,
    prodi = :prodi,
    tahun_lulus = :tahun,
    id_dosen = :dosen
";

if ($fotoBaru) {
    $sql .= ", mahasiswa_profile = :foto";
}

$sql .= " WHERE id_mhs = :id";

$stmt = $conn->prepare($sql);

$params = [
    ':nama' => $nama,
    ':email' => $email,
    ':prodi' => $prodi,
    ':tahun' => $tahun ?: null,
    ':dosen' => $dosen ?: null,
    ':id' => $id
];

if ($fotoBaru) $params[':foto'] = $fotoBaru;

$stmt->execute($params);

header("Location: profile_mhs.php?update=success");
exit;
