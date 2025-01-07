<?php
include '../../config.php';
include '../../system/email/class.phpmailer.php';
include '../../system/email/send-email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    // Perform server-side validation (e.g., check if email is valid)
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generate a 64-character (32 bytes * 2) hexadecimal token
        
        // Update the token in the database for the corresponding email
        $updateTokenQuery = $server->prepare("UPDATE `akun` SET `token`=? WHERE `email`=?");
        $updateTokenQuery->bind_param("ss", $token, $email);
        $updateTokenQuery->execute();

        // Check if the email exists in the database
        $selectEmailQuery = $server->prepare("SELECT * FROM `akun` WHERE `email`=?");
        $selectEmailQuery->bind_param("s", $email);
        $selectEmailQuery->execute();
        $result = $selectEmailQuery->get_result();
        $userData = $result->fetch_assoc();

        if ($userData) {
            // Email found, send password reset link using custom EmailSend function
            EmailSend(
                $email,
                '',
                "Pengguna yang terhormat,
<br><br>
Anda telah meminta untuk menyetel ulang sandi Anda. Ikuti tautan di bawah untuk menyetel ulang sandi Anda:
<br><br>
<a href='$url/reset?token=$token'>$url/reset?token=$token</a>
<br><br >
Jika Anda tidak memintanya, Anda dapat mengabaikan email ini.
<br><br>
Salam Hangat,
<br>
$title_name - $slogan",
                'Permintaan Reset Password',
                $server
            );
            // Handle response
            echo "<script>
                var successBox = document.getElementById('topsdjkf');
                var sentBox = document.getElementById('topsdjkf2');
                if (successBox && sentBox) {
                    successBox.style.display = 'none';
                    sentBox.style.display = 'block';
                }
            </script>";
        } else {
            // Email not found
            echo "<script>
                email.style.borderColor = '#EA2027';
                p_email.style.color = '#EA2027';
                p_email.style.display = 'block';
                p_email.innerHTML = 'Alamat Email tidak terdaftar!';
            </script>";
        }
    } else {
        // Invalid email
        echo "<script>
            email.style.borderColor = '#EA2027';
            p_email.style.color = '#EA2027';
            p_email.style.display = 'block';
            p_email.innerHTML = 'Masukan alamat Email yang valid!';
        </script>";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}
?>
