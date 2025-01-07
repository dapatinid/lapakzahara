<?php
include '../../../config.php';

$val_id_tl_produk = $_POST['val_id_tl_produk'];

// Perbarui status moderasi menjadi 'Diterima'
$update_status_query = "UPDATE `iklan` SET `status_moderasi` = 'Ditolak' WHERE `id` = '$val_id_tl_produk'";
$update_status_result = $server->query($update_status_query);

if ($update_status_result) {
    ?>
    <script>
        location.reload(); // Reload halaman saat proses selesai
    </script>
    <?php
}
?>
