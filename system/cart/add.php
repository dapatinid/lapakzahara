<?php
include '../../config.php';

$iduser = $profile['id'];
$idproduk = $_POST['idproduk'];
$jumlah_produk_sementara = $_POST['jumlah_produk'];
$jumlah_produk_tulis = $_POST['jumlah_produk'];
if ($jumlah_produk_sementara === 'null' || $jumlah_produk_sementara == 0 || $jumlah_produk_sementara === '') {
    $jumlah_produk = 1;
} else {
    $jumlah_produk = $jumlah_produk_tulis;
}
$warna_value = $_POST['warna_value'];
$ukuran_value = $_POST['ukuran_value'];
$ukuran_harga_satuan_value_send = $_POST['ukuran_harga_satuan_value_send'];
$id_lokasi = $_POST['id_lokasi'];
$lokasi = $_POST['lokasi'];
$lokasi_harga_satuan_value_send = $_POST['lokasi_harga_satuan_value_send']; 

$time = date("Y-m-d H:i:s");

$cart = $server->query("SELECT * FROM `keranjang` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser' AND `warna_k`='$warna_value' AND `ukuran_k`='$ukuran_value'");
$cart_data = mysqli_fetch_assoc($cart);

if ($cart_data) {
    // Jika varian warna dan ukuran sudah ada di keranjang, update jumlahnya
    // $new_quantity = $cart_data['jumlah'] + $jumlah_produk;
    $new_quantity = $jumlah_produk;

    $update_cart = $server->query("UPDATE `keranjang` SET `jumlah`='$new_quantity', `waktu`='$time' WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser' AND `warna_k`='$warna_value' AND `ukuran_k`='$ukuran_value'");

    if ($update_cart) {
        ?>
        <script>
            window.location.href = '<?php echo $url; ?>/cart';
        </script>
        <?php
    }
} else {
    // Jika varian warna dan ukuran belum ada di keranjang, tambahkan data baru
    $select_produk_cart = $server->query("SELECT * FROM `iklan` WHERE `id`='$idproduk'");
    $produk_data_cart = mysqli_fetch_assoc($select_produk_cart);
    $diskon_cart = $produk_data_cart['diskon'];

    $insert_cart = $server->query("INSERT INTO `keranjang`(`id_iklan`, `id_user`, `jumlah`, `harga_k`,`rega_k`, `diskon_k`, `warna_k`, `ukuran_k`, `id_lokasi_k`, `lokasi_k`, `waktu`) VALUES ('$idproduk', '$iduser', '$jumlah_produk', '$ukuran_harga_satuan_value_send', '$lokasi_harga_satuan_value_send', '$diskon_cart', '$warna_value', '$ukuran_value', '$id_lokasi', '$lokasi', '$time')");

    if ($insert_cart) {
        ?>
        <script>
            window.location.href = '<?php echo $url; ?>/cart';
        </script>
        <?php
    }
}
?>
