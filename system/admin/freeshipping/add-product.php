<?php
include '../../../config.php';

$id_produk_new_fs = $_POST['id_produk_new_fs'];

$update_fs_adm = $server->query("UPDATE `iklan` SET `gratis_ongkir`='ya' WHERE `id`='$id_produk_new_fs' ");

if ($update_fs_adm) {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php
}
?>