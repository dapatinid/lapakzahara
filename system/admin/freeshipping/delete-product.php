<?php
include '../../../config.php';

$val_id_h_produk = $_POST['val_id_h_produk'];

$delete_pro_fs = $server->query("UPDATE `iklan` SET `gratis_ongkir`='0' WHERE `id`='$val_id_h_produk' ");

if ($delete_pro_fs) {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php
}
?>