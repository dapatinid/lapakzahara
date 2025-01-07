<?php
include '../../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idvohapus = $_POST['idvohapus'];

    // Lakukan validasi data di sini sesuai kebutuhan

    // Hapus voucher dari database
    $query = "DELETE FROM vouchers WHERE id='$idvohapus'";
    if (mysqli_query($server, $query)) {
        // Redirect ke halaman voucher setelah berhasil dihapus
        header("location: " . $url . "/admin/voucher/");
        exit();
    } else {
        echo "Error: " . mysqli_error($server);
    }
}
?>
