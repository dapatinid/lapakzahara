<?php
   include './config.php';
   $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
   
   // Mengambil keyword dari parameter GET
   $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
   
   // Simpan kata kunci ke dalam tabel kata_kunci
if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($server, $keyword);

    $query_simpan_kata_kunci = "INSERT INTO kata_kunci (kata_kunci) VALUES ('$keyword')";
    $result_simpan_kata_kunci = $server->query($query_simpan_kata_kunci);

    if (!$result_simpan_kata_kunci) {
        // Gagal menyimpan kata kunci, handle sesuai kebutuhan
        echo "Gagal menyimpan kata kunci ke dalam tabel kata_kunci.";
    }
}
   
   // Fungsi untuk mendapatkan daftar produk berdasarkan keyword
   function getProductsByKeyword($keyword, $offset, $limit) {
      global $server;
      $keyword = mysqli_real_escape_string($server, $keyword);
   
      $query = "SELECT iklan.*, brand.namab AS brand_name, kategori.nama AS kategori_name, akun.nama_pengguna, akun.verifikasi_toko
              FROM `iklan`
              LEFT JOIN `brand` ON iklan.id_brand = brand.id
              LEFT JOIN `kategori` ON iklan.id_kategori = kategori.id
              LEFT JOIN `akun` ON iklan.user_id = akun.id
              WHERE (iklan.judul LIKE '%$keyword%'
                  OR iklan.deskripsi LIKE '%$keyword%'
                  OR iklan.warna LIKE '%$keyword%'
                  OR iklan.ukuran LIKE '%$keyword%'
                  OR iklan.slug LIKE '%$keyword%'
                  OR iklan.lokasi LIKE '%$keyword%'
                  OR iklan.harga LIKE '%$keyword%'
                  OR iklan.berat LIKE '%$keyword%'
                  OR brand.namab LIKE '%$keyword%'
                  OR brand.slug LIKE '%$keyword%'
                  OR kategori.nama LIKE '%$keyword%'
                  OR kategori.slug LIKE '%$keyword%'
                  OR iklan.tipe_iklan LIKE '%$keyword%')
                  AND iklan.status = ''
                  AND iklan.status_moderasi = 'Diterima'
              ORDER BY iklan.terjual DESC
              LIMIT $offset, $limit";

   
    $result = $server->query($query);
    return $result;
   }
   
   $productsPerPage = 10;
   $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
   $offset = ($currentPage - 1) * $productsPerPage;
   
   // Mendapatkan daftar produk berdasarkan keyword
   $searchResults = getProductsByKeyword($keyword, $offset, $productsPerPage);
   
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
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Menampilkan hasil untuk <?php echo htmlspecialchars($keyword); ?> | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include './partials/seo.php'; ?>
      <!-- META SEO -->
      <link rel="icon" href="./assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="./assets/css/search/index.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include './partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <div class="box_judul">
            <p>
               <?php
                  $totalResults = mysqli_num_rows($searchResults);
                  if ($totalResults == 0) {
                      echo '<p><span style="color: var(--orange);">Oops!</span> Tidak menemukan hasil</p>';
                  } else {
                      echo '<p>Menampilkan <span style="color: var(--orange);">' . $totalResults . '</span> hasil</p>';
                  }
                  ?>
         </div>
         <div class="box_produk">
            <?php
               while ($produk_terlaris_data = mysqli_fetch_assoc($searchResults)) {
                   $exp_gambar_pt = explode(',', $produk_terlaris_data['gambar']);
                   $stok_habis = ($produk_terlaris_data['stok'] == 0) ? true : false;
                   if ($produk_terlaris_data['stok'] !== $produk_terlaris_data['terjual'] || $produk_terlaris_data['stok'] > $produk_terlaris_data['terjual']) {
                       $brand_id = $produk_terlaris_data['id_brand'];
                       $brand_query = $server->query("SELECT namab FROM `brand` WHERE id='$brand_id'");
                       $brand_data = mysqli_fetch_assoc($brand_query);
                       
                       $idproduct = $produk_terlaris_data['id'];
               
               $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
               $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
               
               $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
               $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
               $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
               $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
               $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
               
                       ?>
            <!-- SHARE PRODUK -->
            <div class="back_share_produk" id="back_share_produk_<?php echo $produk_terlaris_data['id']; ?>">
               <div class="share_produk">
                  <i class="ri-close-circle-fill close_sp" onclick="close_share_produk(<?php echo $produk_terlaris_data['id']; ?>)"></i>
                  <h1>Bagikan Produk</h1>
                  <div class="box_link_produk">
                     <div class="isi_link_produk bg_lp_link" onclick="copy_link_produk('<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>')">
                        <i class="ri-file-copy-fill" id="ico_copy_p"></i>
                        <i class="ri-checkbox-circle-fill" id="ico_selesai_copy_p" style="display: none;"></i>
                     </div>
                     <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($url . '/product/' . $produk_terlaris_data['slug']); ?>" target="_blank">
                        <div class="isi_link_produk bg_lp_wa">
                           <i class="ri-whatsapp-fill"></i>
                        </div>
                     </a>
                     <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url . '/product/' . $produk_terlaris_data['slug']); ?>" target="_blank">
                        <div class="isi_link_produk bg_lp_fb">
                           <i class="ri-facebook-box-fill"></i>
                        </div>
                     </a>
                     <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url . '/product/' . $produk_terlaris_data['slug']); ?>&text=Check%20this%20out" target="_blank">
                        <div class="isi_link_produk bg_lp_twitter">
                           <i class="ri-twitter-fill"></i>
                        </div>
                     </a>
                  </div>
               </div>
            </div>
            <!-- SHARE PRODUK -->
            <div class="list_produk <?php if ($stok_habis) echo 'stok_habis'; ?>">
               <div class="image_container">
                  <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                  <img src="../assets/image/product/compressed/<?php echo $exp_gambar_pt[0]; ?>" <?php if ($stok_habis) echo 'class="stok_habis_image"'; ?>>
                  </a>
                  <?php if ($stok_habis) { ?>
                  <div class="box_icon_2">
                     <p>HABIS</p>
                  </div>
                  <?php } ?>
                  <?php
                     if (isset($_COOKIE['login'])) {
                         $select_wishlist = $server->query("SELECT * FROM `favorit` WHERE `produk_id`='$idproduct' AND `user_id`='$iduser' ");
                         $data_wishlist = mysqli_fetch_assoc($select_wishlist);
                     ?>
                  <div class="box_ov_gambar_p">
                     <div class="box_wishlist" onclick="show_share_produk('<?php echo $produk_terlaris_data['id']; ?>')">
                        <i class="ri-share-fill wishlist_nonactive"></i>
                     </div>
                     <div class="box_wishlist">
                        <i class="ri-heart-3-fill wishlist_active <?php if (!$data_wishlist) {
                           echo 'wishlist_hidden';
                           } ?>" id="wishlist_active_<?php echo $idproduct; ?>" onclick="remove_wishlist('<?php echo $idproduct; ?>')"></i>
                        <i class="ri-heart-3-fill wishlist_nonactive <?php if ($data_wishlist) {
                           echo 'wishlist_hidden';
                           } ?>" id="wishlist_nonactive_<?php echo $idproduct; ?>" onclick="add_wishlist('<?php echo $idproduct; ?>')"></i>
                     </div>
                  </div>
                  <?php
                     }
                     ?>
                  <div class="box_12">
                     <div class="satu">
                        <p><i class="fas fa-cubes"></i> Stok <?php echo $produk_terlaris_data['stok'] - $produk_terlaris_data['terjual']; ?></p>
                     </div>
                     <?php if ($produk_terlaris_data['gratis_ongkir'] == 'ya') { ?>
                     <div class="dua">
                        <p><i class="fas fa-shipping-fast"></i> Free Ongkir</p>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="text_list_produk">
                  <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                     <div class="box_judul_list_produk">
                        <p><?php echo $produk_terlaris_data['judul']." • ".$produk_terlaris_data['deskripsi']; ?></p>
                     </div>
                  </a>
                  <div class="box_harga_list_produk">
                     <?php
                        // Ambil opsi_fitur dari database berdasarkan ID setting yang diinginkan
                        $opsi_fitur_query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 5";
                        $result = $conn->query($opsi_fitur_query);
                        
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $hasil_query = $row['opsi_fitur'];
                        
                            if ($hasil_query === 'Aktif') {
                                // Kode untuk opsi_fitur "Aktif" dengan nama_fitur "format harga"
                                $harga_produk_terlaris = $produk_terlaris_data['harga'];
                        $exp_ukuran_pt = explode(',', $produk_terlaris_data['ukuran']);
                        $harga_maksimal_pt = 0;
                        
                        foreach ($exp_ukuran_pt as $key_ukuran_pt => $value_ukuran_pt) {
                        $exp_ukuran_saja_pt = explode('===', $value_ukuran_pt);
                        $harga_produk_pt = isset($exp_ukuran_saja_pt[1]) ? (int)$exp_ukuran_saja_pt[1] : 0;
                        
                        if ($harga_produk_pt > $harga_maksimal_pt) {
                        $harga_maksimal_pt = $harga_produk_pt;
                        }
                        }
                        
                        $produk_memiliki_varian = count($exp_ukuran_pt) > 1;
                        $diskon = $produk_terlaris_data['diskon'];
                        
                        if (!empty($diskon)) {
                        $harga_setelah_diskon = $harga_produk_terlaris - ($harga_produk_terlaris * ($diskon / 100));
                        } else {
                        $harga_setelah_diskon = $harga_produk_terlaris;
                        }
                        
                        $formatted_harga = '';
                        if ($harga_setelah_diskon >= 1000000) {
                        // Jika harga dalam jutaan, tampilkan satu digit setelah koma atau titik
                        $formatted_harga = 'Rp' . number_format($harga_setelah_diskon / 1000000, 1) . 'jt';
                        } elseif ($harga_setelah_diskon >= 1000) {
                        // Jika harga dalam ribuan, hilangkan digit desimal
                        $formatted_harga = 'Rp' . number_format($harga_setelah_diskon / 1000, 0) . 'rb';
                        } else {
                        // Jika harga kurang dari ribuan, tampilkan angka tanpa desimal
                        $formatted_harga = 'Rp' . number_format($harga_setelah_diskon, 0, ",", ".");
                        }
                        
                        echo '<h1>' . $formatted_harga . '</h1>';
                            } elseif ($hasil_query === 'Tidak Aktif') {
                                // Kode untuk opsi_fitur "Tidak Aktif" dengan nama_fitur "format harga"
                                $harga_produk_terlaris = $produk_terlaris_data['harga'];
                                $exp_ukuran_pt = explode(',', $produk_terlaris_data['ukuran']);
                                $harga_maksimal_pt = 0;
                        
                                foreach ($exp_ukuran_pt as $key_ukuran_pt => $value_ukuran_pt) {
                                    $exp_ukuran_saja_pt = explode('===', $value_ukuran_pt);
                                    $harga_produk_pt = isset($exp_ukuran_saja_pt[1]) ? (int)$exp_ukuran_saja_pt[1] : 0;
                        
                                    if ($harga_produk_pt > $harga_maksimal_pt) {
                                        $harga_maksimal_pt = $harga_produk_pt;
                                    }
                                }
                        
                                $produk_memiliki_varian = count($exp_ukuran_pt) > 1;
                                $diskon = $produk_terlaris_data['diskon'];
                                if (!empty($diskon)) {
                                    $harga_setelah_diskon = $harga_produk_terlaris - ($harga_produk_terlaris * ($diskon / 100));
                                } else {
                                    $harga_setelah_diskon = $harga_produk_terlaris;
                                }
                        
                                if ($produk_memiliki_varian) {
                                    $harga_asli_format = 'Rp' . number_format($harga_setelah_diskon, 0, ",", ".");
                                    echo '<h1>' . $harga_asli_format . '</h1>';
                                } else {
                                    $harga_asli_format = 'Rp' . number_format($harga_setelah_diskon, 0, ",", ".");
                                    echo '<h1>' . $harga_asli_format . '</h1>';
                                }
                            } else {
                                // Jika nilai tidak valid, tampilkan pesan kesalahan atau tindakan lainnya
                                echo "Tidak Valid";
                            }
                        } else {
                            // Jika query gagal dieksekusi
                            echo "Query tidak berhasil dieksekusi.";
                        }
                        ?>
                     <!--<p><?php echo $produk_terlaris_data['terjual']; ?> Terjual</p>-->
                     <p><span class="fa fa-star checked" style="color: orange;"></span> <?php echo( round(4 + rand(100,1000)/1000 ,1)); ?></p>
                  </div>
                  <div class="box_harga_list_produk1">
                     <?php if (!empty($produk_terlaris_data['diskon'])) { ?>
                     <p><?php
                        // Ambil opsi_fitur dari database berdasarkan ID setting yang diinginkan
                        $opsi_fitur_query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 5";
                        $result = $conn->query($opsi_fitur_query);
                        
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $hasil_query = $row['opsi_fitur'];
                        
                            if ($hasil_query === 'Aktif') {
                                // Misalnya harga dalam variabel $harga
                                $harga = $produk_terlaris_data['harga'];
                        
                                // Ubah format harga ke "rb" (ribu) atau "jt" (juta) sesuai dengan nilainya
                                if ($harga >= 1000000) {
                                    // Jika harga dalam jutaan, tampilkan satu digit setelah koma atau titik
                                    $formatted_harga = 'Rp' . number_format($harga / 1000000, 1) . 'jt';
                                } elseif ($harga >= 1000) {
                                    // Jika harga dalam ribuan, hilangkan digit desimal
                                    $formatted_harga = 'Rp' . number_format($harga / 1000, 0) . 'rb';
                                } else {
                                    // Jika harga kurang dari ribuan, tampilkan angka tanpa desimal
                                    $formatted_harga = 'Rp' . number_format($harga, 0, ",", ".");
                                }
                        
                                // Tampilkan harga yang sudah diformat dengan efek coret
                                echo '<del>' . $formatted_harga . '</del>';
                            } elseif ($hasil_query === 'Tidak Aktif') {
                                // Kode untuk opsi_fitur "Tidak Aktif" dengan nama_fitur "format harga"
                                $harga_produk_terlaris = $produk_terlaris_data['harga'];
                                // Sisipkan logika untuk menampilkan harga asli tanpa diskon dengan tag <del>
                                $harga_asli_format = 'Rp' . number_format($harga_produk_terlaris, 0, ",", ".");
                                echo '<del>' . $harga_asli_format . '</del>';
                            } else {
                                // Jika nilai tidak valid, tampilkan pesan kesalahan atau tindakan lainnya
                                echo "Tidak Valid";
                            }
                        } else {
                            // Jika query gagal dieksekusi
                            echo "Query tidak berhasil dieksekusi.";
                        }
                        ?></p>
                     <p style="font-size: 9px;border: 1px solid var(--red);color: var(--red);padding: 0 2px;text-align: center;vertical-align: middle;font-weight: 500;margin-left: 5px;">Hemat <?php echo $produk_terlaris_data['diskon']; ?>%</p>
                     <?php } ?>
                  </div>
               </div>
               <div class="list_lokasi">
                  <p><i class="fas fa-map-marker-alt"></i> Dikirim dari <?php echo $produk_terlaris_data['lokasi']; ?></p>
               </div>
            </div>
            <?php
               }
               }
               ?>
         </div>
         <div class="lihat_semua_t" id="loadMoreButton">
            <?php if (mysqli_num_rows($searchResults) >= $productsPerPage) : ?>
            <button id="loadMoreButton" onclick="loadMoreProducts(<?php echo $currentPage + 1; ?>)">Tampilkan Lebih</button>
            <?php else : ?>
            <!-- Tombol tidak perlu ditampilkan jika tidak ada produk lagi -->
            <?php endif; ?>
         </div>
      </div>
      </div>
      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include './partials/footer.php'; ?>
      <?php include './partials/bottom-navigation.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="./assets/js/search/index.js"></script>
      <script src="./assets/js/popular/index.js"></script>
      <!-- JS -->
   </body>
</html>
