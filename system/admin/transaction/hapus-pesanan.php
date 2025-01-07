<?php
include '../../../config.php';

$idinvoice_hpp = $_POST['idinvoice_hpp'];

$hapus_pesanan_hpp = $server->query("DELETE FROM `invoice` WHERE `idinvoice`='$idinvoice_hpp' ");
?>