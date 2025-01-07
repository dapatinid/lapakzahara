<?php
include '../../config.php';
include '../../system/email/class.phpmailer.php';
include '../../system/email/send-email.php';

// Fungsi untuk membersihkan dan memvalidasi input
function clean_and_validate_input($input) {
    global $server;
    $cleaned_input = mysqli_real_escape_string($server, $input);
    $cleaned_input = htmlspecialchars($cleaned_input);
    return $cleaned_input;
}

$nama_lengkap = clean_and_validate_input($_POST['nama_lengkap']);
$email = clean_and_validate_input($_POST['email']);
$no_whatsapp = clean_and_validate_input($_POST['no_whatsapp']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$time = date("Y-m-d H:i:s");

// CEK EMAIL
$cek_email = $server->query("SELECT * FROM `akun` WHERE `email`='$email' ");
$cek_email_data = mysqli_fetch_assoc($cek_email);

// CEK no_whatsapp
$cek_no_whatsapp = $server->query("SELECT * FROM `akun` WHERE `no_whatsapp`='$no_whatsapp' ");
$cek_no_whatsapp_data = mysqli_fetch_assoc($cek_no_whatsapp);

if ($cek_email_data) {
    ?>
    <script>
        email.style.borderColor = '#EA2027';
        p_email.style.display = 'block';
        p_email.innerHTML = 'Email Sudah Terdaftar';
    </script>
    <?php
} elseif ($cek_no_whatsapp_data) {
    // Tambahkan validasi untuk no_whatsapp
    ?>
    <script>
        no_whatsapp.style.borderColor = '#EA2027';
        p_no_whatsapp.style.display = 'block';
        p_no_whatsapp.innerHTML = 'Nomor WhatsApp Sudah Terdaftar';
    </script>
    <?php
} else {
    $insert_akun = $server->query("INSERT INTO `akun`(`foto`, `nama_lengkap`, `email`, `no_whatsapp`, `password`, `waktu`, `tipe_daftar`, `tipe_akun`, `logo_toko`) VALUES ('user.png', '$nama_lengkap', '$email', '$no_whatsapp', '$password', '$time', '', '','logo-toko.png')");

    if ($insert_akun) {
        // Mengambil data akun yang baru dibuat
        $select_akun = $server->query("SELECT * FROM `akun` WHERE `email`='$email' AND `password`='$password' ");
        $select_akun_data = mysqli_fetch_assoc($select_akun);

        // ENSKRIPSI ID
        $idakun = $select_akun_data['id'] . "hcCTZvFLD7XIchiaMqEka0TLzGgdpsXB";
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "ecommerce";
        $encryption = openssl_encrypt($idakun, $ciphering, $encryption_key, $options, $encryption_iv);
        $buat_cookie = setcookie("login", $encryption, time() + (86400 * 30), "/");

        if ($buat_cookie) {
            // Notifikasi berhasil registrasi
            $id_user = $select_akun_data['id'];
            $deskripsi_notif = "Selamat! Akun Anda telah berhasil didaftarkan. Silahkan lengkapi data profil dan mulailah berbelanja!";

            $insert_notif_registration = $server->query("INSERT INTO `notification`(`id_user`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) 
                VALUES ('$id_user', 'Pendaftaran Berhasil', '$deskripsi_notif', '$time', '')");

            if ($insert_notif_registration) {
                // Notifikasi email
                $judul_progress_produk = "Selamat! Akun Berhasil Didaftarkan";
                $nama_user_mp = $select_akun_data['nama_lengkap'];

                // Menggunakan variabel yang sudah didefinisikan di config.php
                $deskripsi_email = "Hi $nama_user_mp
                    <br><br>
                    Selamat! Akun Anda telah berhasil didaftarkan. Silahkan lengkapi data profil dan mulailah berbelanja!
                    <br><br>
                    Salam Hangat,
                    <br>
                    $title_name - $slogan";

                $email_user_mp = $select_akun_data['email'];

                EmailSend("$email_user_mp", "$judul_progress_produk", "$deskripsi_email", "", $server);
                ?>
                <a href="<?php echo $url; ?>" id="sukses_register"></a>
                <script>
                    sukses_register.click();
                </script>
                <?php
            }
        }
    }
    ?>
    <script>
        email.style.borderColor = '#e2e2e2';
        p_email.style.display = 'none';
        p_email.innerHTML = '';

        // Tambahkan validasi untuk no_whatsapp
        no_whatsapp.style.borderColor = '#e2e2e2';
        p_no_whatsapp.style.display = 'none';
        p_no_whatsapp.innerHTML = '';
    </script>
<?php
}
?>
