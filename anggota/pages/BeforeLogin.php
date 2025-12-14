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

        <img class="IVSS-LOGO-DENGAN" src="../../admin-berita/assets/img/Logo-lab.png" />

        <p class="text-wrapper">Selamat datang di Laboratorium IVSS</p>
        <p class="div">Silahkan pilih tipe admin untuk masuk</p>
        

        <div class="btn-group">

            <a href="../../admin-lab/index.php" class="login">
                <div class="text-wrapper-2">Admin Lab</div>
            </a>

            <a href="../../admin-berita/index.php" class="login-2">
                <div class="text-wrapper-3">Admin Berita</div>
            </a>

        </div>

      </div>
    </div>
  </body>
</html>
<style>
  body,
html {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
}

.before-login {
    background-color: #062041;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.frame {
    width: 80vw;
    max-width: 1100px;
    padding: 60px 40px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0px 4px 10px #00000040;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.IVSS-LOGO-DENGAN {
    width: 180px;
    margin-bottom: 30px;
}

.text-wrapper {
    font-family: "Inter-ExtraBold", Arial, sans-serif;
    font-size: 40px;
    color: #062041;
    margin-bottom: 10px;
}

.div {
    font-family: Inter;
    font-size: 24px;
    color: #062041;
    margin-bottom: 40px;
}

.btn-group {
    display: flex;
    gap: 30px;
}

.login,
.login-2 {
    display: flex;
    width: 220px;
    height: 60px;
    background: #062041;
    border-radius: 8px;
    justify-content: center;
    align-items: center;
    text-decoration: none;
}

.login:hover,
.login-2:hover {
    background-color: #ff900d;
}

.text-wrapper-2,
.text-wrapper-3 {
    font-family: "Inter", Arial, sans-serif;
    color: #fff;
    font-size: 22px;
}

.before-login>.text-wrapper-2,
.before-login>.text-wrapper-3 {
    display: none;
}
</style>
