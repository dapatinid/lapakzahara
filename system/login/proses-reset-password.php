<?php
include '../../config.php';
include '../../system/email/class.phpmailer.php';
include '../../system/email/send-email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
    $token = isset($_POST['token']) ? $_POST['token'] : '';

    $newPassword = mysqli_real_escape_string($server, $newPassword);
    $token = mysqli_real_escape_string($server, $token);

    $checkTokenQuery = "SELECT * FROM `akun` WHERE `token`='$token'";
    $result = $server->query($checkTokenQuery);

    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $email = $userData['email'];

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $updatePasswordQuery = "UPDATE `akun` SET `password`='$hashedPassword' WHERE `token`='$token' AND `email`='$email'";
        $updateResult = $server->query($updatePasswordQuery);

        if ($updateResult && $server->affected_rows > 0) {
            // Mengubah status token menjadi tidak valid setelah penggunaan pertama
            $invalidateTokenQuery = "UPDATE `akun` SET `token` = NULL WHERE `token`='$token'";
            $server->query($invalidateTokenQuery);

            // Menyiapkan pesan notifikasi untuk ditampilkan
            $notification = "Password berhasil diperbarui!";
            echo "<script>alert('$notification');</script>";
            
            // Mengirim notifikasi email
            $subject = "Password Berhasil Diperbarui!";
            $body = "Pengguna yang terhormat,
<br><br>
Terima kasih telah melakukan perubahan kata sandi.
<br>
Pembaruan telah berhasil dilakukan untuk keamanan akun Anda.
<br>
Jangan ragu untuk menghubungi kami jika ada pertanyaan lebih lanjut.
<br><br>
Salam Hangat,
<br>
$title_name - $slogan";
            EmailSend($email, $subject, $body, "", $server);

            exit();
        } else {
            echo "Gagal memperbarui password!";
        }
    } else {
        echo "Token tidak valid!";
    }
} else {
    echo "Metode permintaan tidak valid.";
}
?>

