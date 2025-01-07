<?php
include '../../config.php';

$id_inv_mpcod = $_POST['id_inv_mpcod'];
$time = date("Y-m-d H:i:s");

$update_pesanan_cod = $server->query("UPDATE `invoice` SET `tipe_progress`='Dikemas',`waktu_transaksi`='$time' WHERE `idinvoice`='$id_inv_mpcod' ");

if ($update_pesanan_cod) {
?>
    <script>
        window.location.href = '';
    </script>
<?php
}
