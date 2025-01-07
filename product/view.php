<?php
    session_start();
   include '../config.php';
   
   $slugpro = $_GET['idproduct'];
   
   $product_query = $server->query("SELECT *, kategori.slug AS kategori_slug, brand.icon AS brand_icon, brand.slug AS brand_slug FROM `akun`, `kategori`, `brand`, `iklan` WHERE iklan.slug='$slugpro' AND iklan.id_kategori=kategori.id AND iklan.id_brand=brand.id AND iklan.user_id=akun.id ");
   
   if ($product_query) {
    $product_data = mysqli_fetch_assoc($product_query);
    // Mendapatkan slug kategori
    $kategori_slug = $product_data['kategori_slug'];
    // Mendapatkan slug brand
    $brand_slug = $product_data['brand_slug'];
    $brand_icon = $product_data['brand_icon'];
    // ... Lanjutkan dengan pengolahan data lainnya ...
   } else {
    // Handle error jika query gagal
    echo "Error in retrieving product data.";
   }
   
   // Mengambil persentase diskon dari kolom diskon dalam tabel iklan
   $diskon_persen = $product_data['diskon'];
   $harga_maksimal = 0; // Definisikan nilai awal variabel $harga_maksimal
   
   if (!$product_data['ukuran'] == '') {
       $exp_ukuran_vp = explode(',', $product_data['ukuran']);
       $harga_maksimal = 0; // Inisialisasi harga maksimal
   
       foreach ($exp_ukuran_vp as $key_ukuran_vp => $value_ukuran_vp) {
           $exp_ukuran_saja_vp = explode('===', $value_ukuran_vp);
           $hitung_diskon_vp = ($product_data['diskon'] / 100) * $exp_ukuran_saja_vp[1];
           $harga_diskon_vp = $exp_ukuran_saja_vp[1] - $hitung_diskon_vp;
   
           // Membandingkan dengan harga maksimal sebelumnya
           if ($harga_diskon_vp > $harga_maksimal) {
               $harga_maksimal = $harga_diskon_vp;
           }
       }
       
       // Harga maksimal sekarang berisi harga maksimal dari varian ukuran
   }
   
   $idproduct = $product_data['id'];
   
   if ($product_data['stok'] == $product_data['terjual'] or $product_data['stok'] < $product_data['terjual']) {
       header("location: " . $url);
   }
   
   if ($product_data) {
       $hitung_diskon_fs = ($product_data['diskon'] / 100) * $product_data['harga'];
       $harga_diskon_fs = $product_data['harga'] - $hitung_diskon_fs;
   
       $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
       $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
   
       $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
       $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
       $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
       $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
       $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
   } else {
       header("location: " . $url);
   }
   
   $sf_view_produk = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='view produk' ");
   $data_sf_view_produk = mysqli_fetch_array($sf_view_produk);
   
   if ($product_data['user_id'] == '0') {
       $loc_dikirim_dari = $kota_toko . ', ' . $provinsi_toko;
   } else {
       $select_user_lokasi_vp = $server->query("SELECT * FROM `iklan`, `akun` WHERE iklan.id='$idproduct' AND iklan.user_id=akun.id ");
       $data_user_lokasi_vp = mysqli_fetch_assoc($select_user_lokasi_vp);
       $loc_dikirim_dari = $data_user_lokasi_vp['provinsi_user'] . ', ' . $data_user_lokasi_vp['kota_user'] . ', ' . $data_user_lokasi_vp['kecamatan_user'] . ', ' . $data_user_lokasi_vp['no_whatsapp'];
   }
   
   // TOTAL PRODUK TOKO
   $idusertoko = $product_data['user_id'];
   $toko_penjual = $server->query("SELECT * FROM `iklan` WHERE `user_id`='$idusertoko' AND `status`='' ");
   $jumlah_toko_penjual = mysqli_num_rows($toko_penjual);

   // Tentukan kondisi apakah pengguna yang terautentikasi adalah pemilik produk atau bukan
    $isOwner = (isset($_COOKIE['login']) && $idusertoko === $iduser);
   
   ?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
    <title>Jual <?php echo $product_data['judul']; ?> | <?php echo $title_name; ?></title>
    <!-- META SEO -->
    <?php include '../partials/seo.php'; ?>
    <!-- META SEO -->
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" integrity="sha512-OTcub78R3msOCtY3Tc6FzeDJ8N9qvQn1Ph49ou13xgA9VsH9+LRxoFU6EqLhW4+PKRfU+/HReXmSZXHEkpYoOA==" crossorigin="anonymous" />
    <!-- Include Magnific Popup CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/product/view.css">
    <link rel="stylesheet" href="../../assets/css/product/addon.css">
    </head>
<body>
<!-- LAPORKAN PRODUK -->
<div class="back_catatan" id="back_catatan">
    <div class="box_catatan">
        <h1>Laporkan Produk</h1>
        <ul class="options-list" id="alasanLaporkan" onclick="handleOptionClick(event)">
            <li value="Spam atau Pornografi">Spam/Pornografi</li>
            <li value="Pencemaran Nama Baik">Pencemaran Nama Baik</li>
            <li value="Penipuan">Penipuan</li>
            <li value="Produk Palsu atau Imitasi">Produk Palsu atau Imitasi</li>
            <li value="Kondisi Produk Tidak Sesuai dengan Deskripsi">Kondisi Produk Tidak Sesuai dengan Deskripsi</li>
            <li value="Pengiriman atau Penanganan yang Buruk">Pengiriman atau Penanganan yang Buruk</li>
            <li value="Produk Kadaluarsa atau Tidak Layak Jual">Produk Kadaluarsa atau Tidak Layak Jual</li>
            <li value="Ketidakpuasan dengan Kualitas Produk">Ketidakpuasan dengan Kualitas Produk</li>
            <li value="lainnya">Lainnya</li>
        </ul>
        <textarea id="deskripsiMasalah" rows="3" class="input" placeholder="Tambahkan Alasan Lainnya..." style="margin-top: 20px; display: none;"></textarea>

        <div class="button butacat" onclick="simpan_catatan('<?php echo $idproduct; ?>')">
            <p id="p_butacat">Kirim</p>
            <img src="../../assets/icons/loading-w.svg" id="load_butacat">
        </div>
        <div class="button batal_lokasi" id="batal_catatan" onclick="BatalLcatatan()">
            <p>Batalkan</p>
        </div>
    </div>
</div>
<!-- LAPORKAN PRODUK -->


      <!-- SHARE PRODUK -->
<div class="back_share_produk" id="back_share_produk">
    <div class="share_produk">
        <i class="ri-close-circle-fill close_sp" onclick="close_share_produk()"></i>
        <h1>Bagikan Produk</h1>
        <div class="box_link_produk">
        <div class="isi_link_produk bg_lp_link" onclick="copy_link_produk('/product/<?php echo $slugpro; ?>', <?php echo $idproduct; ?>)">
    <i class="ri-file-copy-fill" id="ico_copy_p_<?php echo $idproduct; ?>"></i>
    <i class="ri-checkbox-circle-fill" id="ico_selesai_copy_p_<?php echo $idproduct; ?>" style="display: none;"></i>
</div>


            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank">
                <div class="isi_link_produk bg_lp_wa">
                    <i class="ri-whatsapp-fill"></i>
                </div>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank">
                <div class="isi_link_produk bg_lp_fb">
                    <i class="ri-facebook-box-fill"></i>
                </div>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=Check%20this%20out" target="_blank">
                <div class="isi_link_produk bg_lp_twitter">
                    <i class="ri-twitter-fill"></i>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- SHARE PRODUK -->
      <!-- PILIH VARIAN PRODUK -->
      <div class="back_varian_produk" id="back_varian_produk">
         <div class="varian_produk">
            <i class="fas fa-window-close close_varian_produk" onclick="close_back_varian_produk()"></i>
            <?php
               if (!$product_data['warna'] == '') {
               ?>
            <div class="varian_ukuran">
               <h1>Pilih Varian</h1>
               <div class="box_select_varian">
                  <?php
                     $exp_warna_vp = explode(',', $product_data['warna']);
                     foreach ($exp_warna_vp as $key_warna_vp => $value_warna_vp) {
                     ?>
                  <div class="isi_box_select_varian c_id_varian_warna" id="id_varian_warna<?php echo $key_warna_vp; ?>" onclick="click_varian_warna('<?php echo $key_warna_vp; ?>', '<?php echo $value_warna_vp; ?>')"><?php echo $value_warna_vp; ?></div>
                  <?php
                     }
                     ?>
               </div>
            </div>
            <?php
               }
               ?>
            <?php
               if (!$product_data['ukuran'] == '') {
               ?>
            <div class="varian_ukuran">
               <h1>Pilih Jenis</h1>
               <div class="box_select_varian">
                  <?php
                     $exp_ukuran_vp = explode(',', $product_data['ukuran']);
                     foreach ($exp_ukuran_vp as $key_ukuran_vp => $value_ukuran_vp) {
                         $exp_ukuran_saja_vp = explode('===', $value_ukuran_vp);
                         $hitung_diskon_vp = ($product_data['diskon'] / 100) * $exp_ukuran_saja_vp[1];
                         $harga_diskon_vp = $exp_ukuran_saja_vp[1] - $hitung_diskon_vp;
                     ?>
                  <div class="isi_box_select_varian c_id_varian_ukuran" id="id_varian_ukuran<?php echo $key_ukuran_vp; ?>" onclick="click_varian_ukuran('<?php echo $key_ukuran_vp; ?>', '<?php echo $exp_ukuran_saja_vp[0]; ?>', '<?php echo $harga_diskon_vp; ?>', '<?php echo $exp_ukuran_saja_vp[1]; ?>')"><?php echo $exp_ukuran_saja_vp[0]; ?></div>
                  <?php
                     }
                     ?>
               </div>
            </div>
            <?php
               }
               ?> 
            <?php
               if (!$product_data['lokasi'] == '') {
               ?>
            
            <?php
               }
               ?>
            <div class="harga_varian_produk">
               <p>Jumlah</p> <span>(stok <?php echo $product_data['stok'] - $product_data['terjual']; ?>)</span> 
               <div class="box_pm_jumlah">
                  <div class="box_button_jumlah" onclick="kurang()">
                     <img src="../../assets/icons/kurang.svg">
                  </div>
                  <div class="box_hasil_jumlah">
                     <input inputmode="numeric" oninput="this.value = this.value.replace(/\D+/g, '')" value="1" id="jumlah_produk" required>
                  </div>
                  <div class="box_button_jumlah" onclick="tambah(<?php echo $product_data['stok'] - $product_data['terjual']; ?>);">
                     <img src="../../assets/icons/tambah.svg">
                  </div>
               </div>
            </div>
            <div class="harga_varian_produk">
               <p>Harga</p>     
               <h1>Rp<span id="harga_varian_produk"><?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></span></h1>
            </div>
            <input type="hidden" id="warna_value">
            <input type="hidden" id="ukuran_value">
            <input type="hidden" id="ukuran_harga_value" value="<?php echo $harga_diskon_fs; ?>">
            <input type="hidden" id="ukuran_harga_satuan_value" value="<?php echo $harga_diskon_fs; ?>">
            <input type="hidden" id="ukuran_harga_satuan_value_send" value="<?php echo $product_data['harga']; ?>">
            <input type="hidden" id="id_lokasi_value" value="<?php echo $product_data['id_lokasi']; ?>">
            <input type="hidden" id="lokasi_value" value="<?php echo $product_data['lokasi']; ?>">
            <div class="box_button_varian" id="buvar_masukkan_keranjang">
               <div class="button" id="masukan_keranjang" onclick="addcart('<?php echo $idproduct; ?>')">
                  <p>Masukkan Keranjang</p>
               </div>
               <div class="button" id="loading_masukan_keranjang">
                  <img src="../../assets/icons/loading-w.svg" alt="">
               </div>
            </div>
            <div class="box_button_varian" id="buvar_beli_sekarang">
               <div class="button" id="beli_sekarang" onclick="checkout('<?php echo $idproduct; ?>',)">
                  <p>Beli Sekarang</p>
               </div>
               <div class="button" id="loading_beli_sekarang">
                  <img src="../../assets/icons/loading-w.svg" alt="">
               </div>
            </div>
            <script>
                $('#jumlah_produk').keyup(function() {
                    var sisastok = <?php echo $product_data['stok'] - $product_data['terjual']; ?>;
                    if (jumlah_produk.value > sisastok ) {
                        var angkanyaa = sisastok;
                        var ukuran_harga_value_id = ukuran_harga_satuan_value.value * angkanyaa;
                        ukuran_harga_value.value = ukuran_harga_value_id;
                        harga_varian_produk.innerHTML = rubah(ukuran_harga_value_id);
                        jumlah_produk.value = angkanyaa;
                        // alert(ukuran_harga_value_id);
                    } else if (jumlah_produk.value === '') {
                        var angkanyaa = 0;
                        var ukuran_harga_value_id = ukuran_harga_satuan_value.value * angkanyaa;
                        ukuran_harga_value.value = ukuran_harga_value_id;
                        harga_varian_produk.innerHTML = rubah(ukuran_harga_value_id);
                        jumlah_produk.value = angkanyaa;
                        // alert(ukuran_harga_value_id);
                    } else {
                        var angkanyaa = jumlah_produk.value;
                        var ukuran_harga_value_id = ukuran_harga_satuan_value.value * angkanyaa;
                        ukuran_harga_value.value = ukuran_harga_value_id;
                        harga_varian_produk.innerHTML = rubah(ukuran_harga_value_id);                    
                        // alert(ukuran_harga_value_id);
                    }
                  }
                );
                
            </script>
            
         </div>
      </div>
      <!-- PILIH VARIAN PRODUK -->
      <!-- HEADER -->
      <?php include '../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <?php
            if ($data_sf_view_produk['opsi_fitur'] == 'Tidak Harus Login' || isset($_COOKIE['login'])) {
            ?>
         <!-- PRODUK -->
         <div class="product" itemscope itemtype="http://schema.org/Product">
            <div class="detail_product">
               <div class="foto_product" itemprop="image">
                  <div class="owl-carousel owl-theme">
                     <?php
                        $exp_gambar_vi = explode(',', $product_data['gambar']);
                        foreach ($exp_gambar_vi as $key_exp_gambar_vi => $value_exp_gambar_vi) {
                        ?>
                     <a href="../../assets/image/product/compressed/<?php echo $value_exp_gambar_vi; ?>" target="_blank"><img src="../../assets/image/product/compressed/<?php echo $value_exp_gambar_vi; ?>" style="width: 100%; height: 400px;"></a>
                     <?php
                        }
                        ?>
                  </div>
                  <?php
                     if (isset($_COOKIE['login'])) {
                         // WISHLIST
                         $select_wishlist = $server->query("SELECT * FROM `favorit` WHERE `produk_id`='$idproduct' AND `user_id`='$iduser' ");
                         $data_wishlist = mysqli_fetch_assoc($select_wishlist);
                     ?>
                  <div class="box_ov_gambar_p">
                     <div class="box_wishlist" onclick="show_share_produk()">
                        <i class="ri-share-fill wishlist_nonactive"></i>
                     </div>
                     <div class="box_wishlist">
                        <i class="ri-heart-3-fill wishlist_active <?php if (!$data_wishlist) {
                           echo 'wishlist_hidden';
                           } ?>" id="wishlist_active" onclick="remove_wishlist('<?php echo $idproduct; ?>')"></i>
                        <i class="ri-heart-3-fill wishlist_nonactive <?php if ($data_wishlist) {
                           echo 'wishlist_hidden';
                           } ?>" id="wishlist_nonactive" onclick="add_wishlist('<?php echo $idproduct; ?>')"></i>
                     </div>
                  </div>
                  <?php
                     }
                     ?>
               </div>
               <div class="deskripsi_product">
                  <h1 itemprop="name"><?php echo $product_data['judul']; ?></h1>
                  
                  <div class="harga_box1" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <div class="isi_harga_box1">
        <?php if (!empty($product_data['diskon'])) { ?>
            
            <h2>
                <span itemprop="priceCurrency" content="IDR">Rp</span><span itemprop="price" content="<?php echo number_format($harga_diskon_fs, 0, ".", ""); ?>"><?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></span>
                <?php if ($harga_maksimal > $product_data['harga']) { ?>- <?php echo number_format($harga_maksimal, 0, ".", "."); ?> <?php } ?>
                
                
            </h2>
            <h3><span style="font-size: 12px;/*! border: 1px solid #f00; */color: #f00;padding: 0 5px;text-align: center;vertical-align: middle;font-weight: 600;background: #ff00002b;border-radius: 2px;margin-right: 5px;" itemprop="priceSpecification" itemscope itemtype="http://schema.org/PriceSpecification">
                    <meta itemprop="discountPercentage" content="<?php echo $product_data['diskon']; ?>"/>
                    <meta itemprop="price" content="<?php echo number_format($harga_diskon_fs, 0, ".", ""); ?>"/>
                    <?php echo $product_data['diskon']; ?>%
                </span>
                <del>Rp<?php echo number_format($product_data['harga'], 0, ".", "."); ?></del>
            </h3>
        <?php } else { ?>
            <h2>
                <span itemprop="priceCurrency" content="IDR">Rp</span><span itemprop="price" content="<?php echo number_format($product_data['harga'], 0, ".", ""); ?>"><?php echo number_format($product_data['harga'], 0, ".", "."); ?></span>
                <?php if ($harga_maksimal > $product_data['harga']) { ?>- <?php echo number_format($harga_maksimal, 0, ".", "."); ?> <?php } ?>
            </h2>
        <?php } ?>
    </div>
        <div class="isi_harga_flashsale">
            <?php if ($product_data['gratis_ongkir'] == "ya") { ?>
                <img class="dcss-1isemmb" src="<?php echo $url; ?>/assets/icons/free-ongkir.png">
                <?php } ?>
                <?php if ($product_data['tipe_iklan'] == "flash sale") { ?>
                <img class="dcss-1isemmb" src="<?php echo $url; ?>/assets/icons/flash-sale.png">
                <?php } ?>
        </div>
</div>
                  
                  <div class="box_rate_product" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                     <?php
                        if ($jumlah_rating_vp) {
                        ?>
                     <div class="isi_box_rate_product" >
                        <h5 itemprop="ratingCount"><?php echo $hasil_rata_rat; ?></h5>
                        <?php
                           for ($j_icon_star = 1; $j_icon_star <= $for_star_loop; $j_icon_star++) {
                           ?>
                        <i class="ri-star-fill"></i>
                        <?php
                           }
                           if ($after_dot_rat > 0) {
                           ?>
                        <i class="ri-star-half-fill"></i>
                        <?php
                           }
                           ?>
                     </div>
                     <span></span>
                     <?php
                        }
                        ?> 
                     <div class="isi_box_rate_product">
                        <h4 itemprop="ratingValue"><?php echo $jumlah_rating_vp; ?></h4>
                        <p>Penilaian</p>
                     </div>
                     <span></span>
                     <div class="isi_box_rate_product">
                        <h4><?php echo $product_data['terjual']; ?></h4>
                        <p>Terjual</p>
                     </div>
                  </div>
                  <div class="harga_box" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <div class="isi_harga_box">
        <?php if (!empty($product_data['diskon'])) { ?>
            <h3><span>Rp</span><del><?php echo number_format($product_data['harga'], 0, ".", "."); ?></del></h3>
            <h2>
                <span itemprop="priceCurrency" content="IDR">Rp</span>
                <span itemprop="price" content="<?php echo number_format($harga_diskon_fs, 0, ".", ""); ?>"><?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></span>
                <?php if ($harga_maksimal > $product_data['harga']) { ?>- <?php echo number_format($harga_maksimal, 0, ".", "."); ?> <?php } ?>
                <span style="font-size: 12px;border: 1px solid var(--orange);color: var(--orange);padding: 0 5px;text-align: center;vertical-align: middle;font-weight: 600;" itemprop="priceSpecification" itemscope itemtype="http://schema.org/PriceSpecification">
                    <meta itemprop="discountPercentage" content="<?php echo $product_data['diskon']; ?>"/>
                    <meta itemprop="price" content="<?php echo number_format($harga_diskon_fs, 0, ".", ""); ?>"/>
                    Hemat <?php echo $product_data['diskon']; ?>%
                </span>
            </h2>
        <?php } else { ?>
            <h2>
                <span itemprop="priceCurrency" content="IDR">Rp</span>
                <span itemprop="price" content="<?php echo number_format($product_data['harga'], 0, ".", ""); ?>"><?php echo number_format($product_data['harga'], 0, ".", "."); ?></span>
                <?php if ($harga_maksimal > $product_data['harga']) { ?>- <?php echo number_format($harga_maksimal, 0, ".", "."); ?> <?php } ?>
            </h2>
        <?php } ?>
    </div>
    <div class="isi_harga_flashsale">
            <?php if ($product_data['gratis_ongkir'] == "ya") { ?>
                <img class="dcss-1isemmb" src="<?php echo $url; ?>/assets/icons/free-ongkir.png">
                <?php } ?>
                <?php if ($product_data['tipe_iklan'] == "flash sale") { ?>
                <img class="dcss-1isemmb" src="<?php echo $url; ?>/assets/icons/flash-sale.png">
                <?php } ?>
        </div>
</div>


<div class="rincian_dekstop">
<div class="cv_title1">
               <p>Detail Produk</p>
            </div>
                  <div class="rincian_product" itemprop="description">
                  <div class="isi_rincian_product" itemprop="itemCondition">
            <h5>Kondisi Produk</h5>
             <?php
    // Memeriksa apakah kondisi produk terisi
    if (!empty($product_data['kondisi'])) {
        echo '<p>' . $product_data['kondisi'] . '</p>';
    } else {
        echo '<p>-</p>'; // Tambahkan simbol "-" jika kosong
    }
    ?>
</div>
                  
                  <div class="isi_rincian_product" itemprop="category">
                        <h5>Kategori</h5>
                        <p itemprop="name"><a href="<?php echo $url; ?>/category/<?php echo $kategori_slug; ?>"><?php echo $product_data['nama']; ?></a></p>
                     </div>
                     <div class="isi_rincian_product" itemprop="brand">
                        <h5>Merek</h5>
                        <p itemprop="name"><a href="<?php echo $url; ?>/brand/<?php echo $brand_slug; ?>"><?php echo $product_data['namab']; ?></a></p>
                     </div>
                     <div class="isi_rincian_product" itemprop="inventoryLevel">
                        <h5>Stok Tersisa</h5>
                        <p><?php echo $product_data['stok'] - $product_data['terjual']; ?></p>
                     </div>
                     <div class="isi_rincian_product" itemprop="weight">
                        <h5>Berat Produk</h5>
                        <p><?php echo $product_data['berat']; ?> Gram</p>
                     </div>
                     <div class="isi_rincian_product">
                        <h5>Dikirim dari</h5>
                        <p><?php echo $product_data['lokasi']; ?> - Indonesia</p>
                     </div>
        </div>
        </div>
        
        
        <!-- PRODUK -->
                  <?php
$isOwner = (isset($_COOKIE['login']) && $idusertoko == $iduser);
if (!$isOwner && !$isStoreOwner) {
    if (isset($_COOKIE['login'])) {
        ?>
        <div class="add_cart">
            <div class="button_box_cart">
                
                <div class="laporkan_produk" onclick="ubahcatatan()">
                 <i class="fa fa-flag"></i>
                </div>
                <div class="masukan_keranjang" id="masukan_keranjang2" onclick="view_addcart()">
                    <i class="fa fa-shopping-cart"></i>
                    <p>Tambah Keranjang</p>
                </div>
                <div class="masukan_keranjang" id="loading_keranjang">
                    <img src="../../assets/icons/loading-o.svg" alt="">
                </div>
                <div class="masukan_keranjang" id="hapus_keranjang" onclick="removecart('<?php echo $idproduct; ?>')">
                    <i class="ri-delete-bin-7-line"></i>
                    <p>Hapus Keranjang</p>
                </div>
                <?php
                $cart = $server->query("SELECT * FROM `keranjang` WHERE `id_iklan`='$idproduct' AND `id_user`='$iduser'");
                $cart_data = mysqli_fetch_assoc($cart);
                if ($cart_data) {
                    ?>
                    <script>
                        document.getElementById('masukan_keranjang2').style.display = 'flex';
                        document.getElementById('hapus_keranjang').style.display = 'none';
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        document.getElementById('masukan_keranjang2').style.display = 'flex';
                        document.getElementById('hapus_keranjang').style.display = 'none';
                    </script>
                    <?php
                }
                ?>
                <?php if (!isset($hide_checkout_button)) { ?>
                    <div class="beli_sekarang" onclick="view_checkout()">
                        <p>Beli Sekarang</p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="add_cart">
            <div class="button_box_cart">
                <div class="masukan_keranjang" onclick="belum_login_klik_beli()">
                    <i class="fa fa-shopping-cart"></i>
                    <p>Tambah Keranjang</p>
                </div>
                <div class="beli_sekarang" onclick="belum_login_klik_beli()">
                    <p>Beli Sekarang</p>
                </div>
            </div>
        </div>
        <?php
    }
} elseif ($isOwner || $isStoreOwner) {
    // Tampilkan tombol edit produk di sini dengan ikon pengaturan
    ?>
    <div class="add_cart">
            <div class="button_box_cart">
                <div class="masukan_keranjang" onclick="belum_login_klik_beli()">
                    <i class="ri-settings-3-line"></i>
                    <p>Sunting Produk</p>
                </div>
                <div class="beli_sekarang" onclick="show_share_produk()">
                    <p>Bagikan Produk</p>
                </div>
            </div>
        </div>
    <?php
}
?>

               </div>
            </div>
         </div>
         
         
         
         <div class="rincian_mobile">
         <div class="content_view">
             <div class="cv_title1">
               <p>Detail Produk</p>
            </div>
             <div class="deskripsi_product">

                  <div class="rincian_product" itemprop="description">
                  <div class="isi_rincian_product" itemprop="itemCondition">
            <h5>Kondisi Produk</h5>
             <?php
    // Memeriksa apakah kondisi produk terisi
    if (!empty($product_data['kondisi'])) {
        echo '<p>' . $product_data['kondisi'] . '</p>';
    } else {
        echo '<p>-</p>'; // Tambahkan simbol "-" jika kosong
    }
    ?>
</div>
                  
                  <div class="isi_rincian_product" itemprop="category">
                        <h5>Kategori</h5>
                        <p itemprop="name"><a href="<?php echo $url; ?>/category/<?php echo $kategori_slug; ?>"><?php echo $product_data['nama']; ?></a></p>
                     </div>
                     <div class="isi_rincian_product" itemprop="brand">
                        <h5>Merek</h5>
                        <p itemprop="name"><a href="<?php echo $url; ?>/brand/<?php echo $brand_slug; ?>"><?php echo $product_data['namab']; ?></a></p>
                     </div>
                     <div class="isi_rincian_product" itemprop="inventoryLevel">
                        <h5>Stok Tersisa</h5>
                        <p><?php echo $product_data['stok'] - $product_data['terjual']; ?></p>
                     </div>
                     <div class="isi_rincian_product" itemprop="weight">
                        <h5>Berat Produk</h5>
                        <p><?php echo $product_data['berat']; ?> Gram</p>
                     </div>
                     
        </div>

               </div>
               </div></div>
         
         <!-- PROFIL TOKO -->
         <div class="content_view">
            <div class="box_profile_penjual">
               <div class="profile_penjual">
                  <img src="../../assets/image/profil-toko/<?php echo $product_data['logo_toko']; ?>">
                  

                  <div class="name_profile_penjual">
                     <h1><?php echo $product_data['nama_toko']; ?> 
                     <?php 
               if ($product_data['verifikasi_toko'] == 'Ya') {
                  echo '<img id="img-verif" src="../../assets/icons/verifikasi-toko.png">';
              }
               ?> 
                     </h1>
                     
                     <div class="<?php echo ($status_user === 'offline') ? 'offline' : 'online'; ?>">
    <?php
    // Check status online atau offline
    $status_user = $product_data['status_user'];

    
    // Tampilkan ikon online jika status online
    if ($status_user === 'online') {
        echo '<span class="online-icon"></span>';
    }
    
    // Tampilkan ikon offline jika status offline
    if ($status_user === 'offline') {
        echo '<span class="offline-icon"></span>';
    }
    ?> <span class="status-user">
        <?php
        $status_user = $product_data['status_user'];
$last_active = $product_data['waktu_terakhir_aktif'];

if ($status_user === 'offline') {
    $last_active_time = strtotime($last_active);
    $current_time = time();
    $time_diff = $current_time - $last_active_time;

    if ($time_diff < 60) {
        echo 'Aktif beberapa detik yang lalu';
    } elseif ($time_diff >= 60 && $time_diff < 3600) {
        $minutes = floor($time_diff / 60);
        echo 'Terakhir Aktif ' . $minutes . ' menit yang lalu';
    } elseif ($time_diff >= 3600 && $time_diff < 86400) {
        $hours = floor($time_diff / 3600);
        echo 'Terakhir Aktif ' . $hours . ' jam yang lalu';
    } elseif ($time_diff >= 86400 && $time_diff < 604800) {
        $days = floor($time_diff / 86400);
        echo 'Terakhir Aktif ' . $days . ' hari yang lalu';
    } else {
        // Jika lebih dari 7 hari, tampilkan dengan format date
        echo date('d M Y H:i:s', $last_active_time);
    }
} else {
    echo 'Sedang Aktif';
}

        ?>
    </span>
</div>

<p><i class="fas fa-map-marker-alt"></i> <span><?php echo $product_data['kecamatan_user']; ?>, <?php echo $product_data['kota_user']; ?></span></p>
                     
                     
                  </div>
               </div>
               <div class="bio_penjual">
                   
                   
                   <?php
// Asumsikan $idtoko sudah didefinisikan sebelumnya


// Kueri SQL
$query = "SELECT AVG(star_rat) AS rata_rata_star
          FROM rating
          WHERE id_invoice_rat IN (SELECT idinvoice FROM invoice WHERE id_iklan IN (SELECT id FROM iklan WHERE user_id = $idusertoko))";

$result = $conn->query($query);

// Periksa hasil query
if ($result) {
    $row = $result->fetch_assoc();
    $rata_rata_star = $row['rata_rata_star'];

    // Format rata-rata bintang
    $total_ulasan = number_format($rata_rata_star, 1);

}
?>
<?php
// Query untuk menghitung rata-rata waktu proses
$sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, waktu_transaksi, waktu_dikirim)) AS rata_rata_waktu
        FROM invoice
        WHERE id_iklan IN (SELECT id FROM iklan WHERE user_id = $idusertoko)";

$result = $conn->query($sql);

if ($result) {
    // Ambil data hasil query
    $row = $result->fetch_assoc();
    $rataRataWaktuDetik = $row['rata_rata_waktu'];

    // Konversi rata-rata waktu dalam detik ke format yang mencakup hari, jam, menit, dan detik
    $hari = floor($rataRataWaktuDetik / (60 * 60 * 24));
    $sisaWaktu = $rataRataWaktuDetik % (60 * 60 * 24);
    $jam = floor($sisaWaktu / (60 * 60));
    $sisaWaktu = $sisaWaktu % (60 * 60);
    $menit = floor($sisaWaktu / 60);
    $detik = $sisaWaktu % 60;

    // Format waktu dalam rentang waktu
    if ($hari >= 1 && $hari <= 2) {
        $waktu_proses = "1-2 hari";
    } elseif ($hari > 2) {
        $waktu_proses = "$hari hari";
    } elseif ($jam >= 1 && $jam <= 2) {
        $waktu_proses = "1-2 jam";
    } elseif ($jam > 2) {
        $waktu_proses = "$jam jam";
    } elseif ($menit >= 1 && $menit <= 2) {
        $waktu_proses = "1-2 menit";
    } elseif ($menit > 2) {
        $waktu_proses = "$menit menit";
    } elseif ($detik == 1) {
        $waktu_proses = '1 detik';
    } else {
        $waktu_proses = "$detik detik";
    }
} else {
    $waktu_proses = "Belum ada data.";
}
?>
<?php
// Query untuk menghitung total penjualan dari tabel iklan untuk user tertentu
$query = "SELECT SUM(terjual) AS jumlah_transaksi FROM iklan WHERE user_id = $idusertoko";

// Eksekusi query ke database menggunakan koneksi yang relevan
$result = $server->query($query);

// Periksa apakah query berhasil dieksekusi
if ($result) {
    // Ambil nilai total penjualan dari hasil query
    $row = $result->fetch_assoc();

    // Simpan nilai total penjualan dalam variabel
    $jumlah_transaksi = $row['jumlah_transaksi'];

} else {
    echo "Gagal menghitung total penjualan.";
}

?>
               <div class="keterangan"><?php echo $jumlah_transaksi !== null ? $jumlah_transaksi : 0; ?>
               <p>Penjualan</p>
               </div>
               <div class="separator"></div>
               <div class="keterangan">± <?php echo $waktu_proses; ?>
               <p>Proses pesanan</p>
               </div>
               <div class="separator"></div>
               <div class="keterangan"><?php echo $total_ulasan; ?>/5
               <p>Rata-rata ulasan</p>
               </div>
               </div>
               <div class="box_chat_visit">
               <?php
$isOwner = (isset($_COOKIE['login']) && $idusertoko == $iduser);
if (!$isOwner) {
?>
    <!-- Tombol Chat Sekarang -->
<a href="../../chat/?mulai=<?php echo $product_data['user_id']; ?>">
    <div class="isi_chat_visit">
        <i class="ri-chat-1-line"></i>
        <p>Chat Sekarang</p>
    </div>
</a>

<?php
}
?>

                  <a href="../../@<?php echo $product_data['nama_pengguna']; ?>">
                     <div class="isi_chat_visit">
                        <i class="ri-store-2-line"></i>
                        <p>Kunjungi Toko</p>
                     </div>
                  </a>
               </div>
            </div>
         </div>
         <!-- DESKRIPSI -->
         <div class="content_view">
            <div class="cv_title">
               <p>Deskripsi Produk</p>
            </div>
            <div class="isi_cv">
    <p>
        <?php
        // Mendapatkan deskripsi dari $product_data
        $deskripsi = $product_data['deskripsi'];

        // Membuat pola ekspresi reguler untuk menemukan hashtag
        $pattern = '/#(\w+)/i';

        // Mengganti setiap hashtag dengan tautan
        $deskripsi_dengan_link = preg_replace($pattern, "<a href='$url/search.php?keyword=$1'>#$1</a>", $deskripsi);

        // Menampilkan deskripsi dengan hashtag sebagai tautan
        echo nl2br($deskripsi_dengan_link);
        ?>
    </p>
</div>

         </div>
         <!-- PENILAIAN PRODUK -->
         <div class="content_view">
            <div class="cv_title" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
               <p>Penilaian Produk</p>
               <p itemprop="reviewCount"><?php echo $jumlah_rating_vp; ?></p>
            </div>
            <div class="isi_cv">
               <?php
                  if ($jumlah_rating_vp) {
                  ?>
               <div class="box_isi_rating">
                  <?php
                     while ($data_rating_vp = mysqli_fetch_assoc($select_rating_vp)) {
                         $jstar_user_vp = $data_rating_vp['star_rat'];
                     ?>
                  <div class="isi_user_rating">
                     <img src="../../assets/image/profile/<?php echo $data_rating_vp['foto']; ?>" alt="">
                     <div class="text_isi_user_rating">
                        <div class="name_text_isi_user_rating">
                            <?php
$nama_pengguna = $data_rating_vp['nama_pengguna'];
$panjang_nama = strlen($nama_pengguna);

if ($panjang_nama > 4) {
    $nama_tersembunyi = substr_replace($nama_pengguna, str_repeat('*', $panjang_nama - 3), 2, -2);
} else {
    $nama_tersembunyi = $nama_pengguna;
}
?>
                           <h5><?php echo $nama_tersembunyi; ?></h5>
                           <p><?php echo $data_rating_vp['waktu_rat']; ?></p>
                        </div>
                        <div class="star_user_rating">
                           <?php
                              for ($lstarurat = 1; $lstarurat <= $jstar_user_vp; $lstarurat++) {
                              ?>
                           <i class="ri-star-fill"></i>
                           <?php
                              }
                              ?>
                        </div>
                        <h3 class="desk_rat_usr"><?php echo $data_rating_vp['deskripsi_rat']; ?></h3>
                        <?php
                           if (!$data_rating_vp['img_rat'] == '') {
                           ?>
                        <a href="../../assets/image/penilaian/<?php echo $data_rating_vp['img_rat']; ?>" target="_blank">
                        <img src="../../assets/image/penilaian/<?php echo $data_rating_vp['img_rat']; ?>" id="img_rat_vp">
                        </a>
                        <?php
                           }
                           ?>
                     </div>
                  </div>
                  <?php
                     }
                     ?>
               </div>
               <?php
                  } else {
                  ?>
               <p>Belum ada penilaian</p>
               <?php
                  }
                  ?>
            </div>
         </div>
         <!-- PRODUK TERKAIT -->
<div class="content_view">
    <div class="cv_title">
        <p>Produk Serupa</p>
    </div>
    <div class="isi_cv_terkait" style="margin: 0 0 0 -5px;">
        <div class="owl-carousel owl-theme owl-loaded owl-drag" id="slider_produk_serupa">
    <?php
    $kategori_id = $product_data['id_kategori'];
    $related_products = $server->query("SELECT * FROM `iklan` WHERE id_kategori='$kategori_id' AND id != '$idproduct' AND `status_moderasi`='Diterima' LIMIT 5");

    $displayed_product_ids = array(); // Array to track displayed product IDs
    $has_related_products = false; // Flag to check if related products exist

    while ($related_product_data = mysqli_fetch_assoc($related_products)) {
        $related_product_id = $related_product_data['id'];
        $related_product_stok = ($related_product_data['stok'] == 0) ? true : false;

        // Check if the product ID is already displayed
        if (!in_array($related_product_id, $displayed_product_ids)) {
            $displayed_product_ids[] = $related_product_id;
            $related_product_url = $url . "/product/" . $related_product_data['slug'];
            $related_product_image = explode(',', $related_product_data['gambar']);
            $related_product_judul = $related_product_data['judul'];
            $related_product_desk = $related_product_data['deskripsi'];
            $related_product_harga = number_format($related_product_data['harga'], 0, ".", ".");
            $related_product_terjual = $related_product_data['terjual'];
            $has_related_products = true; // Set the flag to true
    ?>
            <div class="list_produk <?php if ($related_product_stok <= 0) echo 'stok_habis'; ?>" style="margin: 5px; scale:0.9;">
                <div class="image_container">
                    <a href="<?php echo $related_product_url; ?>">
                        <!-- Update the img tag to use $related_product_image -->
                        <img src="../../assets/image/product/compressed/<?php echo $related_product_image[0]; ?>" <?php if ($related_product_stok) echo 'class="stok_habis_image"'; ?>>
                    </a>
                    
                    <?php
    if (isset($_COOKIE['login'])) {
        $select_w4ishlist = $server->query("SELECT * FROM `favorit` WHERE `produk_id`='$related_product_id' AND `user_id`='$iduser' ");
        $data_w4ishlist = mysqli_fetch_assoc($select_w4ishlist);
?>
        <div class="box_ov_gambar_pd">
            <div class="box_w4ishlist" onclick="show_share_produk('<?php echo $related_product_id; ?>')">
                <i class="ri-share-fill w4ishlist_nonactive"></i>
            </div>
            <div class="box_w4ishlist">
                <i class="ri-heart-3-fill w4ishlist_active <?php if (!$data_w4ishlist) {
                    echo 'w4ishlist_hidden';
                } ?>" id="w4ishlist_active_<?php echo $related_product_id; ?>" onclick="remove_w4ishlist('<?php echo $related_product_id; ?>')"></i>
                <i class="ri-heart-3-fill w4ishlist_nonactive <?php if ($data_w4ishlist) {
                    echo 'w4ishlist_hidden';
                } ?>" id="w4ishlist_nonactive_<?php echo $related_product_id; ?>" onclick="add_w4ishlist('<?php echo $related_product_id; ?>')"></i>
            </div>
        </div>
<?php
    }
?>

                    <div class="box_12">
                        <div class="satu">
                            <p><i class="fas fa-cubes"></i> Stok <?php echo $related_product_stok; ?></p>
                        </div>
                        <?php if ($related_product_data['gratis_ongkir'] == 'ya') { ?>
                            <div class="dua">
                                <p><i class="fas fa-shipping-fast"></i> Free Ongkir</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="text_list_produk">
                    <a href="<?php echo $related_product_url; ?>">
                        <div class="box_judul_list_produk" style="margin: -10px 0 0 0;">
                            <p><?php echo $related_product_judul." • ".$related_product_desk; ?></p>
                        </div>
                    </a>
                    <div class="box_harga_list_produk" style="margin: 5px 0 -15px 0;">
                     <?php
                        // Ambil opsi_fitur dari database berdasarkan ID setting yang diinginkan
                        $opsi_fitur_query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 5";
                        $result = $conn->query($opsi_fitur_query);
                        
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $hasil_query = $row['opsi_fitur'];
                        
                            if ($hasil_query === 'Aktif') {
                                // Kode untuk opsi_fitur "Aktif" dengan nama_fitur "format harga"
                                $harga_produk_terlaris = $related_product_data['harga'];
                        $exp_ukuran_pt = explode(',', $related_product_data['ukuran']);
                        $harga_maksimal_pt = 0;
                        
                        foreach ($exp_ukuran_pt as $key_ukuran_pt => $value_ukuran_pt) {
                        $exp_ukuran_saja_pt = explode('===', $value_ukuran_pt);
                        $harga_produk_pt = isset($exp_ukuran_saja_pt[1]) ? (int)$exp_ukuran_saja_pt[1] : 0;
                        
                        if ($harga_produk_pt > $harga_maksimal_pt) {
                        $harga_maksimal_pt = $harga_produk_pt;
                        }
                        }
                        
                        $produk_memiliki_varian = count($exp_ukuran_pt) > 1;
                        $diskon = $related_product_data['diskon'];
                        
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
                                $harga_produk_terlaris = $related_product_data['harga'];
                                $exp_ukuran_pt = explode(',', $related_product_data['ukuran']);
                                $harga_maksimal_pt = 0;
                        
                                foreach ($exp_ukuran_pt as $key_ukuran_pt => $value_ukuran_pt) {
                                    $exp_ukuran_saja_pt = explode('===', $value_ukuran_pt);
                                    $harga_produk_pt = isset($exp_ukuran_saja_pt[1]) ? (int)$exp_ukuran_saja_pt[1] : 0;
                        
                                    if ($harga_produk_pt > $harga_maksimal_pt) {
                                        $harga_maksimal_pt = $harga_produk_pt;
                                    }
                                }
                        
                                $produk_memiliki_varian = count($exp_ukuran_pt) > 1;
                                $diskon = $related_product_data['diskon'];
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
                     <!--<p><?php echo $related_product_data['terjual']; ?> Terjual</p>-->
                     <p><span class="fa fa-star checked" style="color: orange;"></span> <?php echo( round(4 + rand(100,1000)/1000 ,1)); ?></p>
                  </div>
                    <div class="box_harga_list_produk1">
                     <?php if (!empty($related_product_data['diskon'])) { ?>
                     <p><?php
                        // Ambil opsi_fitur dari database berdasarkan ID setting yang diinginkan
                        $opsi_fitur_query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 5";
                        $result = $conn->query($opsi_fitur_query);
                        
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $hasil_query = $row['opsi_fitur'];
                        
                            if ($hasil_query === 'Aktif') {
                                // Misalnya harga dalam variabel $harga
                                $harga = $related_product_data['harga'];
                        
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
                                $harga_produk_terlaris = $related_product_data['harga'];
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
                     <p style="font-size: 9px;border: 1px solid var(--red);color: var(--red);padding: 0 2px;text-align: center;vertical-align: middle;font-weight: 500;margin-left: 5px;">Hemat <?php echo $related_product_data['diskon']; ?>%</p>
                     <?php } ?>
                  </div>
                </div>
                <div class="list_lokasi1" style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;width: 100%;padding: 2px 15px 2px 15px;box-sizing: border-box;background: #e8e8e8;color: #555;font-size: 8px;bottom: 0;border-radius: 0 0 10px 10px;">
                    <p><i class="fas fa-map-marker-alt"></i> Dikirim dari <?php echo $related_product_data['lokasi']; ?></p>
                </div>
            </div>
    <?php
        }
    }

    if (!$has_related_products) {
        echo '<p>Tidak ada produk terkait.</p>';
    }
    ?>
</div>

    </div>
</div>
        <!-- PRODUK TERKAIT -->

         <div class="back_add_card"></div>
         <div id="res" style="display: block;"></div>
         <?php
            } else {
                include '../partials/belum-login.php';
            }
            ?>
      </div>
      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include '../partials/footer.php'; ?>
      <!-- FOOTER -->
      <!-- JS --> 
      <!-- Include jQuery CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Include Owl Carousel CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- Include Magnific Popup CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
  <!-- Your custom JavaScript -->
      <script src="../../assets/js/product/view.js"></script>
      <?php
         if (!$product_data['warna'] == '') {
         ?>
      <script>
         id_varian_warna0.click();
      </script>
      <?php
         }
         if (!$product_data['ukuran'] == '') {
         ?>
      <script>
         id_varian_ukuran0.click();
      </script>
      <?php
         }
         if (!$product_data['lokasi'] == '') {
         ?>
      <script>
         id_varian_lokasi0.click();
      </script>
      <?php
         }
         ?>
      <!-- JS -->
      <style>
      </style>
      <script>
// Mengambil semua elemen gambar di halaman
const gambar = document.querySelectorAll('img');

// Iterasi melalui setiap elemen gambar dan menambahkan atribut alt dan title jika tidak ada
gambar.forEach(img => {
    if (!img.getAttribute('alt')) {
        img.setAttribute('alt', '<?php echo $product_data['judul']; ?>');
    }

    if (!img.getAttribute('title')) {
        img.setAttribute('title', '<?php echo $product_data['judul']; ?>');
    }
});
</script>
   </body>
</html>
