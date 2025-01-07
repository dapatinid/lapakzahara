<?php
   include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">
 
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
      <title>Login | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
      <link rel="stylesheet" href="../assets/css/login-register/index.css">
   </head>

<body>
    <!-- CONTENT -->
    <div class="log_reg">
         <center><a href="<?php echo $url; ?>"><img src="../assets/icons/<?php echo $logo; ?>" alt=""></a></center>
        <div class="box_log_reg" style="display: block;" id="topsdjkf">
            <h1>Lupa Password</h1>
            <div class="box_form_log_reg" style="margin-bottom: 15px;">
                <div class="form_log_reg">
                    <p id="p_email"></p>
                    <input type="text" placeholder="Masukan alamat Email" class="input" id="email">
                </div>
            </div>
            <div id="lupa_masuk_button">
                <div class="button" id="masuk">
                    <p>Kirim</p>
                </div>
            </div>
            <div id="masuk_loading">
                <div class="button">
                    <img src="../assets/icons/loading-w.svg" id="loading_masuk">
                </div>
            </div>
        </div>

        <div class="box_log_reg" style="display: none;" id="topsdjkf2">
            <h1>Terkirim!</h1>
            <center>
                <p style="margin-top: 15px;">
                    Kami telah mengirimkan email yang berisi tautan untuk mereset kata sandi Anda. Cek kotak masuk atau folder spam Anda.
                </p>
            </center>
            <div>
                <a href="/login">
                    <div class="button" id="masuk">
                        <p>Kembali</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="res" id="res"></div>
    <!-- CONTENT -->

    <!-- JS -->
    <script src="../assets/js/login/lost-password.js"></script>
    <!-- JS -->

    <style>
        header {
            display: none;
        }

        .back_header {
            display: none;
        }

        @media only screen and (max-width: 600px) {
            header {
                display: block;
                background-color: var(--white);
                box-shadow: none;
            }

            .back_header {
                display: block;
            }

            .box_search_header {
                display: none;
            }

            .box_back_button {
                color: var(--orange);
                font-size: 30px;
            }
        }
    </style>
</body>

</html>