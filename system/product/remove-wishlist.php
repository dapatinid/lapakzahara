<?php
include '../../config.php';

$id_product = $_POST['id_product'];
$time = date("Y-m-d H:i:s");

$delete_wishlist = $server->query("DELETE FROM `favorit` WHERE `produk_id`='$id_product' AND `user_id`='$iduser' ");

?>