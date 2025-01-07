<?php
// Lakukan pengecekan terhadap method yang digunakan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan terdapat data yang dikirim melalui POST
    if (isset($_POST['user_id']) && isset($_POST['status'])) {
        // Ambil data dari POST
        $user_id = $_POST['user_id'];
        $status = $_POST['status'];

        // Lakukan koneksi ke database Anda
        include '../../config.php'; // Sesuaikan dengan file koneksi database Anda

        // Perbarui nilai waktu terakhir aktif dan status user
        $current_time = date('Y-m-d H:i:s');
        $update_query = "UPDATE akun SET waktu_terakhir_aktif = '$current_time', status_user = '$status' WHERE id = $user_id";

        // Lakukan query update ke database
        if ($server->query($update_query)) {
            // Update berhasil
            echo "Status berhasil diperbarui";
        } else {
            // Update gagal
            echo "Gagal memperbarui status";
        }
    } else {
        // Data tidak lengkap
        echo "Data tidak lengkap";
    }
} else {
    // Metode request tidak valid
    echo "Metode request tidak valid";
}
?>
