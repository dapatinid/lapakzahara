<?php
include '../../config.php';

// Pastikan hanya menerima POST request dan terdapat data yang diperlukan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_inv_hapus'])) {
    // Ambil nilai id invoice dari POST request
    $id_inv_hapus = $_POST['id_inv_hapus'];

    // Buat query untuk menghapus invoice dari tabel
    $query = "DELETE FROM invoice WHERE idinvoice = '$id_inv_hapus'";

    // Eksekusi query
    if (mysqli_query($server, $query)) {
        // Jika penghapusan berhasil, kirim respons yang sesuai
        echo "Invoice berhasil dihapus.";
    } else {
        // Jika terjadi kesalahan dalam eksekusi query, kirim pesan kesalahan
        echo "Error: Gagal menghapus invoice";
    }
}
?>
