<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    $checkTokenQuery = "SELECT * FROM `akun` WHERE `token`='$token'";
    $result = $server->query($checkTokenQuery);

    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
            <title>Reset Password | <?php echo $title_name; ?></title>
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
                
                <div class="box_log_reg" style="display: block;" id="resetPasswordForm">
                    <h1>Reset Password</h1>
                    <div class="box_form_log_reg" style="margin-bottom: 15px;">
                        <div class="form_log_reg">
                            <p id="p_newPassword"></p>
                            <input type="password" placeholder="Masukkan password baru" class="input" id="newPassword">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                        </div>
                    </div>
                    <div id="resetButton">
                        <div class="button" id="reset">
                            <p>Reset</p>
                        </div>
                    </div>
                    <div id="resetLoading" style="display: none;">
                        <div class="button">
                            <img src="../assets/icons/loading-w.svg" id="loading_reset">
                        </div>
                    </div>
                </div>
                
                <div class="box_log_reg" style="display: none;" id="resetPasswordForm1">
            <h1>Selamat!</h1>
            <center>
                <p style="margin-top: 15px;">
                    Kata sandi Anda telah diperbarui. Silakan login kembali menggunakan kata sandi baru untuk melanjutkan.
                </p>
            </center>
            <div>
                <a href="/login">
                    <div class="button" id="masuk">
                        <p>Login</p>
                    </div>
                </a>
            </div>
        </div>
        
                <div class="res" id="res"></div>
            </div>
            <!-- CONTENT -->

            <!-- JS -->
            <script src="auth/reset-password.js"></script>
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
<?php
        exit;
    }
}
?>
