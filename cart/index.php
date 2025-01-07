<?php
   include '../config.php';
   $page = 'KERANJANG';
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
      <title>Keranjang Belanja | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
      <link rel="stylesheet" href="../assets/css/cart/index.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include '../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <?php
            if (isset($_COOKIE['login'])) {
                $select_cart = $server->query("SELECT * FROM `keranjang` WHERE `id_user`='$iduser'");
                $cek_cart = mysqli_num_rows($select_cart);
            ?>
         <div class="header_cart" id="header_cart">
            <p>Total Keranjang Belanja <span><?php echo $cek_cart; ?></span></p>
         </div>
         <div class="box_isi_cart">
            <?php
               if ($cek_cart) {
                   $select_cart_group = $server->query("SELECT * FROM `akun`, `keranjang`, `iklan` WHERE keranjang.id_user='$iduser' AND keranjang.id_iklan=iklan.id AND akun.id=iklan.user_id GROUP BY `iklan`.`user_id` ORDER BY `keranjang`.`id` DESC");
                   while ($cart_data_group = mysqli_fetch_array($select_cart_group)) {
                       $user_id_group = $cart_data_group['user_id'];
               ?>
            <div class="box_isi_cart_toko">
               <div class="iden_toko">
                  <i class="ri-store-2-line"></i>
                  <a href="<?php echo $url; ?>/@<?php echo $cart_data_group['nama_pengguna']; ?>">
                  <p><?php echo $cart_data_group['nama_lengkap']; ?> <?php if ($cart_data_group['centang_biru'] == 'verifed') { ?>
      <img src="<?php echo $url; ?>/assets/icons/verifikasi.webp" id="img-verif" data-original-title="null" class="has-tooltip" style="width: 20px; height: auto; vertical-align: middle;">
    <?php } ?></p>
                  </a>
               </div>
               <div class="box_isi_cart_toko_list">
               <?php
                     $iduser_mc = array();
                     $totalHargaToko = 0; // Inisialisasi total harga untuk toko ini
                     
                     $select_cart_list = $server->query("SELECT * FROM `kategori`, `keranjang`, `iklan` WHERE iklan.user_id='$user_id_group' AND keranjang.id_user='$iduser' AND keranjang.id_iklan=iklan.id AND iklan.id_kategori=kategori.id ORDER BY `keranjang`.`waktu` DESC");
                     while ($cart_data = mysqli_fetch_array($select_cart_list)) {
                         $hitung_diskon_fs = ($cart_data['diskon_k'] / 100) * $cart_data['harga_k'];
                         $harga_diskon_fs = ($cart_data['harga_k'] - $hitung_diskon_fs) * $cart_data['jumlah'];
                         $exp_gambar_cd = explode(',', $cart_data['gambar']);
                         $related_product_url = $url . "/product/" . $cart_data['slug'];
                         $iduser_mc[] = $cart_data['id'];
                         $v_valueiduser_mc = array();
                         foreach ($iduser_mc as $keyiduser_mc => $valueiduser_mc) {
                             $v_valueiduser_mc[] = $valueiduser_mc;
                         }
                         $id_all_produk = implode(',', $v_valueiduser_mc);
                     
                         // Tambahkan atribut data-harga-produk dengan harga produk ke elemen isi_cart
                         echo '<div class="isi_cart" id="isi_cart' . $cart_data['id'] . '" data-harga-produk="' . $harga_diskon_fs . '">';
                         echo '<div class="box_gambar_judul">';
                         echo '<a href="'.$related_product_url.'"><img src="../assets/image/product/compressed/' . $exp_gambar_cd[0] . '" alt=""></a>';
                         echo '<div class="box_judul_ic">';
                         echo '<h1>' . $cart_data['judul'] . '</h1>';
                         echo '<p>Varian : ';
if ($cart_data['warna_k'] && $cart_data['ukuran_k']) {
    echo '<span>' . $cart_data['warna_k'] . '</span>, <span>' . $cart_data['ukuran_k'] . '</span>';
} elseif ($cart_data['warna_k']) {
    echo '<span>' . $cart_data['warna_k'] . '</span>';
} elseif ($cart_data['ukuran_k']) {
    echo '<span>' . $cart_data['ukuran_k'] . '</span>';
}
echo '</p>';
                         echo '<p>Harga Satuan :   <span>' . number_format($harga_diskon_fs / $cart_data['jumlah'], 0, ".", ".") . '</span></p>';
                         echo '<p style="text-align: center; margin: -40px -150px 0 0;">Jumlah :   <span style="font-size:30px;">' . $cart_data['jumlah'] . '</span></p>';
                         echo '</div>';
                         echo '</div>';
                         echo '<div class="box_detail_isi_cart">';
                         echo '<div class="box_total_harga">';
                         echo '<p>Total Harga</p>';
                         echo '<h1><span>Rp</span>' . number_format($harga_diskon_fs, 0, ".", ".") . '</h1>';
                         echo '</div>';
                         
                         echo '<a class="box_remove_cart" href="'.$related_product_url.'">'; 
echo '<i class="ri-pencil-line"></i>';
echo '</a>';

                         echo '<div class="box_remove_cart" onclick="removecart(' . $cart_data['id'] . ', \'' . $cart_data['warna_k'] . '\', \'' . $cart_data['ukuran_k'] . '\')" id="remove_cart_' . $cart_data['id'] . '">'; 
echo '<i class="ri-delete-bin-line" id="icon_remove_cart' . $cart_data['id'] . '"></i>';
echo '<img src="../assets/icons/loading-o.svg" id="loading_remove_cart' . $cart_data['id'] . '">';
echo '</div>';

                         echo '</div>';
                         echo '</div>';
                     
                         // Tambahkan harga produk ke total harga toko ini
                         $totalHargaToko += $harga_diskon_fs;
                     }
                     ?>
                  <div class="box_checkout">
                     <!-- Tambahkan kode berikut untuk menampilkan total harga keseluruhan -->
                     <div class="box_judul_ic" style="margin-top: -8px;">
                        <p>Jumlah Total:</p>
                        <!-- ID elemen total-harga harus unik per toko -->
                        <h1 id="total-harga-<?php echo $user_id_group; ?>" style="font-size: 18px;font-weight: 600;color: var(--orange);margin-top: 3px;"><span>Rp</span><?php echo number_format($totalHargaToko, 0, ".", "."); ?></h1>
                     </div>
                     <div class="bayar" id="button_checkout<?php echo $user_id_group; ?>" onclick="checkout('<?php echo $user_id_group; ?>', '<?php echo $id_all_produk; ?>')">Checkout</div>
                     <div class="bayar loading_checkout" id="loading_checkout<?php echo $user_id_group; ?>"><img src="../assets/icons/loading-w.svg" alt=""></div>
                  </div>
               </div>
            </div>
            <?php
               }
               } else {
               ?>
            <div class="box_cart_0">
               <img src="../assets/icons/shopping-cart.svg" class="cart_0">
               <p class="p_cart_0">Belum Ada Produk Di Keranjang</p>
            </div>
            <?php
               }
               ?>
         </div>
         <?php
            } else {
                include '../partials/belum-login.php';
            }
            ?>
      </div>
      <div id="res" style="display: block;"></div>
      <!-- CONTENT -->
      <!-- BOTTOM NAVIGATION -->
      <?php include '../partials/bottom-navigation.php'; ?>
      <!-- BOTTOM NAVIGATION -->
      <!-- FOOTER -->
      <?php include '../partials/footer.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="../assets/js/cart/index.js"></script>
      <!-- JS -->
   </body>
</html>
