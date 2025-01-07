<?php
include '../../config.php';

$identitas = mysqli_real_escape_string($server, $_POST['identitas']);  // Mengambil nilai identitas (bisa berupa email atau nama pengguna)
$password = $_POST['password'];

$cek_akun = $server->query("SELECT * FROM `akun` WHERE `email`='$identitas' OR `nama_pengguna`='$identitas'");  // Menyesuaikan dengan identitas
$cek_akun_data = mysqli_fetch_assoc($cek_akun);

if ($cek_akun_data) {
    $pass_akun = $cek_akun_data['password'];
    if (password_verify($password, $pass_akun)) {
        // ENSKRIPSI ID
        $idakun = $cek_akun_data['id'] . "hcCTZvFLD7XIchiaMqEka0TLzGgdpsXB";
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "ecommerce";
        $encryption = openssl_encrypt($idakun, $ciphering, $encryption_key, $options, $encryption_iv);
        $buat_cookie = setcookie("login", $encryption, time() + (86400 * 30), "/");
        if ($buat_cookie) {
            // Update waktu terakhir aktif saat login berhasil
            $iduser = $cek_akun_data['id'];
            $query = "UPDATE akun SET waktu_terakhir_aktif = CURRENT_TIMESTAMP, status_user = 'online' WHERE id = $iduser";
            $result = $server->query($query);

            if ($result) {
                echo "Waktu terakhir aktif berhasil diperbarui.";
            } else {
                echo "Gagal memperbarui waktu terakhir aktif.";
            }
?>
            <a href="<?php echo $url; ?>" id="sukses_login"></a>
            <script>
                sukses_login.click();
            </script>
        <?php
        }
    ?>
        <script>
            password.style.borderColor = '#e2e2e2';
            p_password.style.display = 'none';
            p_password.innerHTML = '';
        </script>
    <?php
    } else {
    ?>
        <script>
            password.style.borderColor = '#EA2027';
            p_password.style.display = 'block';
            p_password.innerHTML = 'Password Salah';
        </script>
    <?php
    }
?>
    <script>
        email.style.borderColor = '#e2e2e2';
        p_email.style.display = 'none';
        p_email.innerHTML = '';
    </script>
<?php
} else {
?>
    <script>
        email.style.borderColor = '#EA2027';
        p_email.style.display = 'block';
        p_email.innerHTML = 'Email atau Username Salah';
    </script>
<?php
}

?>
