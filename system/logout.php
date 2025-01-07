<?php
session_start();

if (isset($_COOKIE['login'])) {
    // Memperbarui waktu terakhir aktivitas dan status pengguna di database
    include '../config.php';
    
    // Peroleh ID pengguna dari cookie yang dienkripsi
    $encrypted_id = $_COOKIE['login'];
    $decrypted_id = openssl_decrypt($encrypted_id, "AES-128-CTR", "ecommerce", 0, "1234567891011121");
    $iduser_key_login = explode("hcCTZvFLD7XIchiaMqEka0TLzGgdpsXB", $decrypted_id);
    $id_user = $iduser_key_login[0];
    
    // Perbarui waktu terakhir aktivitas dan status
    $current_time = date('Y-m-d H:i:s');
    $update_query = "UPDATE akun SET waktu_terakhir_aktif = '$current_time', status_user = 'offline' WHERE id = '$id_user'";
    $update_result = $server->query($update_query);

    if ($update_result) {
        // Hapus cookie login
        unset($_COOKIE['login']);
        setcookie('login', '', strtotime('-1 month'), '/');
        
        // Jika pembaruan berhasil
        header("location: ../index.php");
        exit(); // Pastikan untuk menghentikan eksekusi setelah melakukan redirect
    } else {
        // Jika gagal memperbarui waktu aktivitas
        echo "Gagal memperbarui waktu aktivitas.";
    }
}
?>
