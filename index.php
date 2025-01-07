<?php
    include './config.php';
    $page = 'HOME';
    
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
        <title><?php echo strtoupper($title_name); ?> : <?php echo $slogan; ?></title>
        <!-- META SEO -->
        <?php include './partials/seo.php'; ?>
        <!-- META SEO -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" integrity="sha512-OTcub78R3msOCtY3Tc6FzeDJ8N9qvQn1Ph49ou13xgA9VsH9+LRxoFU6EqLhW4+PKRfU+/HReXmSZXHEkpYoOA==" crossorigin="anonymous" />
        <link rel="icon" href="./assets/icons/<?php echo $favicon; ?>" type="image/svg">
        <link rel="stylesheet" href="./assets/css/index.css">
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '750301989690741');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=750301989690741&ev=PageView&noscript=1"
            /></noscript>
        <!-- End Facebook Pixel Code -->
    </head>
    <body>
        <!-- HEADER -->
        <?php include './partials/header.php'; ?>
        <!-- HEADER -->
        <!-- CONTENT -->
        <div class="width">
            <!-- BANNER IKLAN -->
            <div class="banner_iklan">
                <div class="banner_big_iklan">
                    <div class="owl-carousel owl-theme">
                        <?php
                            $banner_promo = $server->query("SELECT * FROM `banner_promo` ORDER BY `banner_promo`.`idbanner` DESC");
                            while ($banner_promo_data = mysqli_fetch_assoc($banner_promo)) {
                            ?>
                        <img src="./assets/image/banner/compressed/<?php echo $banner_promo_data['image']; ?>" class="img_banner_big_iklan" Style="width:100%; height:auto;">
                        <?php
                            }
                            ?>
                    </div>
                </div>
            </div>
            <!-- BANNER IKLAN -->
            <!-- KATEGORI -->
            <div class="box_kategori">
                <div class="kategori">
                    <?php
                        $kategori = $server->query("SELECT * FROM `kategori` ORDER BY rand() ASC LIMIT 7");
                        while ($kategori_data = mysqli_fetch_assoc($kategori)) {
                        ?>
                    <a href="<?php echo $url; ?>/category/<?php echo $kategori_data['slug']; ?>">
                        <div class="isi_kategori">
                            <img src="./assets/icons/category/compressed/<?php echo $kategori_data['icon']; ?>">
                            <p><?php echo $kategori_data['nama']; ?></p>
                        </div>
                    </a>
                    <?php
                        }
                        ?>
                    <a href="<?php echo $url; ?>/kategori">
                        <div class="isi_kategori">
                            <img src="https://icons.veryicon.com/png/o/commerce-shopping/icon-of-lvshan-valley-mobile-terminal/home-category.png">
                            <p>Lainnya</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- KATEGORI -->
            <!-- FLASH SALE -->
            <?php
                $select_fs = $server->query("SELECT * FROM `flashsale` WHERE `id_fs`='1' ");
                $data_fs = mysqli_fetch_assoc($select_fs);
                $wb_fs = $data_fs['waktu_berakhir'];
                $datenow_fs = time();
                if ($wb_fs > $datenow_fs) {
                ?>
            <div class="flash_sale">
                <div class="title_flash_sale1">
                    <div class="box_title_flash_sale1">
                        <div class="box_fs_res">
                            <i class="ri-flashlight-fill"></i>
                            <p>Flash Sale</p>
                        </div>
                        <div class="countdown_flash_sale1">
                            <div id="days"></div>
                            <span id="td_days">:</span>
                            <div id="hours"></div>
                            <span>:</span>
                            <div id="minutes"></div>
                            <span>:</span>
                            <div id="seconds"></div>
                        </div>
                    </div>
                    <a href="<?php echo $url; ?>/flashsale">
                        <div class="box_lihat_semua_fs1">
                            Lihat Semua 
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                </div>
                <div class="box_iklan_flash_sale_1">
                    <div class="box_iklan_flash_sale">
                        <?php
                            $flash_sale = $server->query("
  SELECT iklan.*, akun.nama_pengguna, akun.verifikasi_toko
  FROM `iklan`
  LEFT JOIN `akun` ON iklan.user_id = akun.id
  WHERE iklan.tipe_iklan = 'flash sale' AND iklan.status_moderasi = 'Diterima'
  LIMIT 5");

while ($flash_sale_data = mysqli_fetch_assoc($flash_sale)) {
                            $hitung_diskon_fs = ($flash_sale_data['diskon'] / 100) * $flash_sale_data['harga'];
                            $harga_diskon_fs = $flash_sale_data['harga'] - $hitung_diskon_fs;
                            $terjual_fs = ($flash_sale_data['terjual'] / $flash_sale_data['stok']) * 100;
                            
                            $idproduct = $flash_sale_data['id'];
                            
                            $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
                            $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
                            
                            $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
                            $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
                            $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
                            $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
                            $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
                            
                            
                            $exp_gambar_fs = explode(',', $flash_sale_data['gambar']);
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
                                <div class="box_persen_flashsale">
                                    <p>-<?php echo $flash_sale_data['diskon']; ?>%</p>
                                </div>
                                <img src="./assets/image/product/compressed/<?php echo $exp_gambar_fs[0]; ?>">
                                <div class="text_iklan_flash_sale">
                                    <div class="box_judul_list_produk">
                                        <p style="text-align: center;"><?php echo $flash_sale_data['judul']; ?></p>
                                    </div>
                                    <?php 
                                        // Tampilkan hanya jika ada rating
                                        if ($jumlah_rating_vp > 0) {
                                        echo '<div class="box_icon_4_2">';
                                        echo '<i class="ri-star-fill"></i>'.'<h5 style="font-size: 11px;margin-left: 2px;margin-top: 1px;color: var(--orange);">' .  $hasil_rata_rat . '/5</h5>';
                                        echo '</div>';
                                        }
                                        ?>
                                    <h3><span>Rp</span><del><?php echo number_format($flash_sale_data['harga'], 0, ".", "."); ?></del></h3>
                                    <h1><span>Rp</span><?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></h1>
                                    <div class="total_barang_flash_sale">
                                        <!--<div class="persen_barang_flash_sale" style="width: <?php echo $terjual_fs; ?>%;"></div>-->
                                        <!--<div class="text_barang_flash_sale">-->
                                        <!--    <p><?php echo $flash_sale_data['terjual']; ?> Terjual</p>-->
                                        <!--</div>-->
                                        R 4.5
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                            }
                            }
                            ?>
                    </div>
                </div>
            </div>
            <?php
                }
                ?>
            <!-- FLASH SALE -->
            <!-- PRODUK TERLARIS -->
            <div class="flash_sale">
                <div class="title_flash_sale">
                    <div class="box_title_produk_terlaris">
                        <p>Produk Terlaris</p>
                    </div>
                    <a href="<?php echo $url; ?>/trends">
                        <div class="box_lihat_semua">
                            Lihat Semua
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                </div>
                <div class="box_iklan_flash_sale grid_terlaris" style="padding-bottom:20px;">
                    <?php
                        $produk_query = "SELECT iklan.*, brand.namab AS brand_namab, akun.nama_pengguna, akun.verifikasi_toko FROM `iklan` 
                LEFT JOIN `brand` ON iklan.id_brand=brand.id 
                LEFT JOIN `akun` ON iklan.user_id=akun.id 
                WHERE `tipe_iklan`='' AND `status_moderasi`='Diterima'
                ORDER BY `terjual` DESC LIMIT 10";

                        
                           $produk_terlaris = $server->query($produk_query);
                           while ($produk_terlaris_data = mysqli_fetch_assoc($produk_terlaris)) {
                          $exp_gambar_pt = explode(',', $produk_terlaris_data['gambar']);
                          $stok_habis = ($produk_terlaris_data['stok'] <= 0) || ($produk_terlaris_data['terjual'] >= $produk_terlaris_data['stok']);
                          
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
                                <!-- Di dalam loop produk -->
                                <div class="isi_link_produk bg_lp_link" onclick="copy_link_produk('/product/<?php echo $produk_terlaris_data['slug']; ?>', <?php echo $produk_terlaris_data['id']; ?>)">
    <i class="ri-file-copy-fill" id="ico_copy_p_<?php echo $produk_terlaris_data['id']; ?>"></i>
    <i class="ri-checkbox-circle-fill" id="ico_selesai_copy_p_<?php echo $produk_terlaris_data['id']; ?>" style="display: none;"></i>
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
                            <?php if ($stok_habis) { ?>
                            <a href="javascript:void(0);">
                            <?php } else { ?> 
                            <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                            <?php } ?> 
                            <img src="../assets/image/product/compressed/<?php echo $exp_gambar_pt[0]; ?>" <?php if ($stok_habis) echo 'class="stok_habis_image"'; ?>>
                            </a>
                            <?php if ($stok_habis) { ?>
                            <div class="box_icon_2">
                                <p>HABIS</p>
                            </div>
                            <?php } ?> 
                            <?php
                                if (isset($_COOKIE['login'])) {
                                    $select_w3ishlist = $server->query("SELECT * FROM `favorit` WHERE `produk_id`='$idproduct' AND `user_id`='$iduser' ");
                                    $data_w3ishlist = mysqli_fetch_assoc($select_w3ishlist);
                                ?>
                            <div class="box_ov_gambar_p">
                                <div class="box_w3ishlist" onclick="show_share_produk('<?php echo $produk_terlaris_data['id']; ?>')">
                                    <i class="ri-share-fill w3ishlist_nonactive"></i>
                                </div>
                                <div class="box_w3ishlist">
                                    <i class="ri-heart-3-fill w3ishlist_active <?php if (!$data_w3ishlist) {
                                        echo 'w3ishlist_hidden';
                                        } ?>" id="w3ishlist_active_<?php echo $idproduct; ?>" onclick="remove_w3ishlist('<?php echo $idproduct; ?>')"></i>
                                    <i class="ri-heart-3-fill w3ishlist_nonactive <?php if ($data_w3ishlist) {
                                        echo 'w3ishlist_hidden';
                                        } ?>" id="w3ishlist_nonactive_<?php echo $idproduct; ?>" onclick="add_w3ishlist('<?php echo $idproduct; ?>')"></i>
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
                            <?php if ($stok_habis) { ?>
                            <a href="javascript:void(0);">
                            <?php } else { ?> 
                            <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                            <?php } ?> 
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
                        ?>
                </div>
            </div>
            <!-- PRODUK TERLARIS -->
            <!-- PRODUK FAVORIT -->
            <div class="flash_sale">
                <div class="title_flash_sale">
                    <div class="box_title_produk_terlaris">
                        <p>Produk Favorit</p>
                    </div>
                    <a href="<?php echo $url; ?>/favorite">
                        <div class="box_lihat_semua">
                            Lihat Semua
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                </div>
                <div class="box_iklan_flash_sale grid_terlaris" style="padding-bottom:20px;">
                    <?php
                        $produk_query = "
  SELECT iklan.*, brand.namab AS brand_namab, akun.nama_pengguna, akun.verifikasi_toko, COUNT(favorit.id) AS jumlah_favorit
  FROM `iklan`
  LEFT JOIN `brand` ON iklan.id_brand=brand.id
  LEFT JOIN `akun` ON iklan.user_id=akun.id
  LEFT JOIN `favorit` ON iklan.id = favorit.produk_id
  WHERE `tipe_iklan` = '' AND `status_moderasi` = 'Diterima'
  GROUP BY iklan.id
  ORDER BY jumlah_favorit DESC, `waktu` DESC
  LIMIT 10
";

                        
                        // Eksekusi kueri
                        $produk_terlaris = $server->query($produk_query);
                           
                           $produk_terlaris = $server->query($produk_query);
                           while ($produk_terlaris_data = mysqli_fetch_assoc($produk_terlaris)) {
                               $exp_gambar_pt = explode(',', $produk_terlaris_data['gambar']);
                               $stok_habis = ($produk_terlaris_data['stok'] <= 0) || ($produk_terlaris_data['terjual'] >= $produk_terlaris_data['stok']);
                               
                               $idproduct = $produk_terlaris_data['id'];
                               
                               $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
                           $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
                           
                           $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
                           $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
                           $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
                           $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
                           $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
                               ?>
                    <div class="list_produk <?php if ($stok_habis) echo 'stok_habis'; ?>">
                        <div class="image_container">
                            <?php if ($stok_habis) { ?>
                            <a href="javascript:void(0);">
                            <?php } else { ?> 
                            <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                            <?php } ?> 
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
                            <?php if ($stok_habis) { ?>
                            <a href="javascript:void(0);">
                            <?php } else { ?> 
                            <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                            <?php } ?> 
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
                        ?>
                </div>
            </div>
            <!-- PRODUK FAVORIT -->
            <!-- PRODUK TERBARU -->
            <div class="flash_sale">
                <div class="title_flash_sale">
                    <div class="box_title_produk_terlaris">
                        <p>Produk Terbaru</p>
                    </div>
                    <a href="<?php echo $url; ?>/newests">
                        <div class="box_lihat_semua">
                            Lihat Semua
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                </div>
                <div class="box_iklan_flash_sale grid_terlaris" style="padding-bottom:20px;">
                    <?php
                        $produk_query = "
  SELECT iklan.*, brand.namab AS brand_namab, akun.nama_pengguna, akun.verifikasi_toko 
  FROM `iklan` 
  LEFT JOIN `brand` ON iklan.id_brand=brand.id 
  LEFT JOIN `akun` ON iklan.user_id=akun.id 
  WHERE `tipe_iklan`='' AND `status_moderasi`='Diterima'
  ORDER BY `waktu` DESC LIMIT 10
";

                        
                        $produk_terlaris = $server->query($produk_query);
                        while ($produk_terlaris_data = mysqli_fetch_assoc($produk_terlaris)) {
                            $exp_gambar_pt = explode(',', $produk_terlaris_data['gambar']);
                            $stok_habis = ($produk_terlaris_data['stok'] <= 0) || ($produk_terlaris_data['terjual'] >= $produk_terlaris_data['stok']);
                            
                            $idproduct = $produk_terlaris_data['id'];
                            
                            $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
                        $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
                        
                        $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
                        $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
                        $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
                        $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
                        $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
                            ?>
                    <div class="list_produk <?php if ($stok_habis) echo 'stok_habis'; ?>">
                        <div class="image_container">
                            <?php if ($stok_habis) { ?>
                            <a href="javascript:void(0);">
                            <?php } else { ?> 
                            <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                            <?php } ?> 
                            <img src="../assets/image/product/compressed/<?php echo $exp_gambar_pt[0]; ?>" <?php if ($stok_habis) echo 'class="stok_habis_image"'; ?>>
                            </a>
                            <?php if ($stok_habis) { ?>
                            <div class="box_icon_2">
                                <p>HABIS</p>
                            </div>
                            <?php } ?>
                            <?php
                                if (isset($_COOKIE['login'])) {
                                    $select_w4ishlist = $server->query("SELECT * FROM `favorit` WHERE `produk_id`='$idproduct' AND `user_id`='$iduser' ");
                                    $data_w4ishlist = mysqli_fetch_assoc($select_w4ishlist);
                                ?>
                            <div class="box_ov_gambar_p">
                                <div class="box_w4ishlist" onclick="show_share_produk('<?php echo $produk_terlaris_data['id']; ?>')">
                                    <i class="ri-share-fill w4ishlist_nonactive"></i>
                                </div>
                                <div class="box_w4ishlist">
                                    <i class="ri-heart-3-fill w4ishlist_active <?php if (!$data_w4ishlist) {
                                        echo 'w4ishlist_hidden';
                                        } ?>" id="w4ishlist_active_<?php echo $idproduct; ?>" onclick="remove_w4ishlist('<?php echo $idproduct; ?>')"></i>
                                    <i class="ri-heart-3-fill w4ishlist_nonactive <?php if ($data_w4ishlist) {
                                        echo 'w4ishlist_hidden';
                                        } ?>" id="w4ishlist_nonactive_<?php echo $idproduct; ?>" onclick="add_w4ishlist('<?php echo $idproduct; ?>')"></i>
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
                            <?php if ($stok_habis) { ?>
                            <a href="javascript:void(0);">
                            <?php } else { ?> 
                            <a href="<?php echo $url; ?>/product/<?php echo $produk_terlaris_data['slug']; ?>">
                            <?php } ?> 
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
                        ?>
                </div>
            </div>
            <!-- PRODUK TERBARU --> 
            <!-- BRAND -->
            <div class="box_brand">
                <div class="brand">
                    <?php
                        $brand_query = $server->query("
                            SELECT brand.*, COUNT(iklan.id) AS jumlah_produk
                            FROM brand
                            LEFT JOIN iklan ON brand.id = iklan.id_brand
                            GROUP BY brand.id
                            ORDER BY jumlah_produk DESC
                            LIMIT 7
                        ");
                        
                        while ($brand_data = mysqli_fetch_assoc($brand_query)) {
                        ?>
                    <a href="<?php echo $url; ?>/brand/<?php echo $brand_data['slug']; ?>" class="brand-item">
                        <div class="isi_brand">
                            <img src="./assets/icons/brand/compressed/<?php echo $brand_data['icon']; ?>">
                        </div>
                    </a>
                    <?php
                        }
                        ?>
                    <!-- Tombol "More" dengan gaya kelas "brand-item" -->
                    <a href="<?php echo $url; ?>/merek" class="brand-item">
                        <div class="isi_brand">
                            <p>Lainnya</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- BRAND -->
            <!--
                <div class="box_brand">
                   <div class="brand">
                   <?php
                    $akun_query = $server->query("SELECT * FROM akun LIMIT 7");
                    while ($akun_data = mysqli_fetch_assoc($akun_query)) {
                    ?>
                      <a href="<?php echo $url; ?>/brand/<?php echo $akun_data['nama_pengguna']; ?>" class="brand-item">
                         <div class="isi_brand">
                            <img src="./assets/image/profile/<?php echo $akun_data['foto']; ?>">
                         </div>
                      </a>
                      <?php
                    }
                    ?>
                      <a href="<?php echo $url; ?>/merek" class="brand-item">
                         <div class="isi_brand">
                            <p>Lainnya</p>
                         </div>
                      </a>
                   </div>
                </div>
                -->
        </div>
        <input type="hidden" id="time_count_flash_sale" value="<?php echo date("d M Y H:i:s", $wb_fs); ?>">
        <!-- CONTENT -->
        <!-- BOTTOM NAVIGATION -->
        <?php include './partials/bottom-navigation.php'; ?>
        <!-- BOTTOM NAVIGATION -->
        <!-- FOOTER -->
        <?php include './partials/footer.php'; ?>
        <!-- FOOTER -->
        <!-- JS -->
        <script src="./assets/js/index.js"></script>
        <script>
            // Mengambil semua elemen gambar di halaman
            const gambar = document.querySelectorAll('img');
            
            // Iterasi melalui setiap elemen gambar dan menambahkan atribut alt dan title jika tidak ada
            gambar.forEach(img => {
                if (!img.getAttribute('alt')) {
                    img.setAttribute('alt', 'Thumbnail');
                }
            
                if (!img.getAttribute('title')) {
                    img.setAttribute('title', 'Thumbnail');
                }
            });
        </script>
        <!-- JS -->
    </body>
</html> 
