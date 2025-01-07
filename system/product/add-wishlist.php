<?php
include '../../config.php';

$id_product = $_POST['id_product'];
$time = date("Y-m-d H:i:s");

$select_wishlist = $server->query("SELECT * FROM `favorit` WHERE `produk_id`='$id_product' AND `user_id`='$iduser' ");
$data_wishlist = mysqli_fetch_assoc($select_wishlist);

if (!$data_wishlist) {
    $insert_wishlist = $server->query("INSERT INTO `favorit`(`user_id`, `produk_id`, `waktu`) VALUES ('$iduser', '$id_product', '$time')");
}

?>