<?php
   include '../../config.php';
   $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
   
   function generateStarRating($rating) {
    $fullStar = floor($rating);
    $halfStar = ceil($rating - $fullStar);
   
    $stars = '';
   
    for ($i = 1; $i <= $fullStar; $i++) {
        $stars .= '<i class="ri-star-fill"></i>';
    }
   
    if ($halfStar > 0) {
        $stars .= '<i class="ri-star-half-fill"></i>';
    }
   
    return $stars;
   }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
      <title>Jual Produk Terlaris Terbaru & Terlengkap | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../../partials/seo.php'; ?>
      <!-- META SEO -->
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/flashsale/index.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include '../../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <!-- FLASH SALE -->
         <?php
            $productsPerPage = 10;
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $offset = ($currentPage - 1) * $productsPerPage;
            
            $select_fs = $server->query("SELECT * FROM `flashsale` WHERE `id_fs`='1' ");
            $data_fs = mysqli_fetch_assoc($select_fs);
            $wb_fs = $data_fs['waktu_berakhir'];
            $datenow_fs = time();
            if ($wb_fs > $datenow_fs) {
            ?>
         <div class="box_judul">
            <div class="box_fs_res">
               <i class="ri-flashlight-fill"></i>
               <p>Flash Sale</p>
            </div>
            <div class="countdown_flash_sale">
               <div id="days"></div>
               <span id="td_days">:</span>
               <div id="hours"></div>
               <span>:</span>
               <div id="minutes"></div>
               <span>:</span>
               <div id="seconds"></div>
            </div>
         </div>
         <div class="box_produk">
            <?php
               $flash_sale = $server->query("
  SELECT iklan.*, akun.nama_pengguna, akun.verifikasi_toko
  FROM `iklan`
  LEFT JOIN `akun` ON iklan.user_id = akun.id
  WHERE iklan.tipe_iklan = 'flash sale' AND iklan.status_moderasi = 'Diterima'
  LIMIT 20");
               while ($flash_sale_data = mysqli_fetch_assoc($flash_sale)) {
               $hitung_diskon_fs = ($flash_sale_data['diskon'] / 100) * $flash_sale_data['harga'];
               $harga_diskon_fs = $flash_sale_data['harga'] - $hitung_diskon_fs;
               $terjual_fs = ($flash_sale_data['terjual'] / $flash_sale_data['stok']) * 100;
               $exp_gambar_fs = explode(',', $flash_sale_data['gambar']);
               
               $idproduct = $flash_sale_data['id'];
               
               $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
               $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
               
               $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
               $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
               $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
               $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
               $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
               
               if ($flash_sale_data['stok'] !== $flash_sale_data['terjual'] or $flash_sale_data['stok'] > $flash_sale_data['terjual']){
               ?>
            <div class="iklan_flash_sale">
               <a href="<?php echo $url; ?>/product/<?php echo $flash_sale_data['slug']; ?>">
                  <?php
                     if ($flash_sale_data['tipe_iklan'] == "flash sale") {
                     ?>
                  <div class="box_persen_flashsale_1">
                     <p>Stok <?php echo $flash_sale_data['stok'] - $flash_sale_data['terjual']; ?></p>
                  </div>
                  <?php 
                     if ($flash_sale_data['gratis_ongkir'] == 'ya') {
                        echo '<div class="box_icon_1_2_3">';
                        echo '<p>Gratis Ongkir</p>';
                        echo '</div>';
                     }
                     ?> 
                  <?php
                     }
                     ?>
                  <div class="box_persen_flashsale" style="padding: 0 5px;background: #f2300c;">
                     <p>-<?php echo $flash_sale_data['diskon']; ?>%</p>
                  </div>
                  <img src="../../assets/image/product/compressed/<?php echo $exp_gambar_fs[0]; ?>">
                  <div class="text_iklan_flash_sale">
                     <div class="box_judul_list_produk">
                        <p style="text-align: center;"><?php echo $flash_sale_data['judul']; ?></p>
                     </div>
                     
                     <?php 
                        // Tampilkan hanya jika ada rating
                        if ($jumlah_rating_vp > 0) {
                        echo '<div class="box_icon_4_2">';
                        echo '<i class="ri-star-fill"></i>'.'<h5 style="font-size: 11px;margin-left: 2px;margin-top: 1px;color: var(--orange);">' .  $hasil_rata_rat . '</h5>';
                        echo '</div>';
                        }
                        ?>
                     <h3><span>Rp</span><del><?php echo number_format($flash_sale_data['harga'], 0, ".", "."); ?></del></h3>
                     <h1><span>Rp</span><?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></h1>
                     <div class="total_barang_flash_sale">
                        <div class="persen_barang_flash_sale" style="width: <?php echo $terjual_fs; ?>%;"></div>
                        <div class="text_barang_flash_sale">
                           <p><?php echo $flash_sale_data['terjual']; ?> Terjual</p>
                        </div>
                     </div>
                  </div>
               </a>
            </div>
            <?php
               }
               }
               ?>
         </div>
         <div class="lihat_semua_t" id="loadMoreButton">
            <?php if (mysqli_num_rows($flash_sale) >= $productsPerPage) : ?>
            <button onclick="loadMoreProducts(<?php echo $currentPage + 1; ?>)">Tampilkan Lebih</button>
            <?php endif; ?>
         </div>
      </div>
      <?php
         }
         ?>
      </div>
      <input type="hidden" id="time_count_flash_sale" value="<?php echo date("d M Y H:i:s", $wb_fs); ?>">
      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include '../../partials/footer.php'; ?>
      <?php include '../../partials/bottom-navigation.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="../../assets/js/flashsale/index.js"></script>
      <!-- JS -->
   </body>
</html>
