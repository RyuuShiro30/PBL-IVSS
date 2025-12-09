<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../css/BeforeLogin.css" />
  </head>
  <body>
    <div class="before-login">
      <div class="frame">
        
        <img class="IVSS-LOGO-DENGAN" src="../assets/logo.png" />
        
        <p class="text-wrapper">Selamat datang di Laboratorium IVSS</p>
        <p class="div">Silahkan pilih tipe akun untuk masuk</p>
        

        <div class="btn-group">

            <a href="Mahasiswa/LoginMhs.php" class="login">
                <div class="text-wrapper-2">Mahasiswa</div>
            </a>

            <a href="Dosen/LoginDosen.php" class="login-2">
                <div class="text-wrapper-3">Dosen</div>
            </a>

        </div>

      </div>
    </div>
  </body>
</html>
