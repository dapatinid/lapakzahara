<?php
include '../../config.php';

$idproduk = $_POST['id_product'];
$page_product = $_POST['page_product'];
$warna_value = $_POST['warna_value'];
$ukuran_value = $_POST['ukuran_value'];

// Menyesuaikan query penghapusan dengan variabel warna dan ukuran
$delete_cart = $server->query("DELETE FROM `keranjang` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser' AND `warna_k`='$warna_value' AND `ukuran_k`='$ukuran_value'");

if ($delete_cart) { // Periksa apakah penghapusan berhasil
    ?>
    <script>
        window.location.reload(); // Reload halaman saat proses selesai
    </script>
    <?php
} else {
    // Jika penghapusan gagal, Anda dapat menangani atau memberikan pesan kesalahan
    echo "Gagal menghapus item dari keranjang.";
}
?>
