<?php
include '../../../config.php';

$val_id_h_produk = $_POST['val_id_h_produk'];

$delete_produk_adm = $server->query("DELETE FROM `iklan` WHERE `id`='$val_id_h_produk'");
$delete_produk_adm_keranjang = $server->query("DELETE FROM `keranjang` WHERE `id_iklan`='$val_id_h_produk'");

if ($delete_produk_adm && $delete_produk_adm_keranjang) {
?>
    <script>
        location.reload(); // Reload halaman saat proses selesai
    </script>
<?php
}
?>
