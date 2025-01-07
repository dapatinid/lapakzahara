<?php
   include './config.php';
   
   // Ambil 'nama_pengguna' dari URL
   $nama_pengguna = isset($_GET['nama_pengguna']) ? $_GET['nama_pengguna'] : '';
   
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
   
   // Cek apakah 'nama_pengguna' tidak kosong
   if (!empty($nama_pengguna)) {
       // Cari ID berdasarkan 'nama_pengguna'
       $result = $server->query("SELECT id FROM `akun` WHERE `nama_pengguna`='$nama_pengguna'");
   
       // Periksa apakah query berhasil dieksekusi
       if ($result) {
           $row = mysqli_fetch_assoc($result);
           $idtoko = $row['id'];
   
           $produk = $server->query("SELECT iklan.*, brand.namab AS brand_name, kategori.nama AS kategori_name, akun.nama_pengguna, akun.verifikasi_toko
                         FROM `iklan`
                         LEFT JOIN `brand` ON iklan.id_brand = brand.id
                         LEFT JOIN `kategori` ON iklan.id_kategori = kategori.id
                         LEFT JOIN `akun` ON iklan.user_id = akun.id
                         WHERE iklan.user_id = '$idtoko' AND iklan.status_moderasi = 'Diterima'
                         ORDER BY iklan.id DESC");

   $total_produk = mysqli_num_rows($produk);
   
           // Ambil detail toko berdasarkan ID
           $detail_toko = $server->query("SELECT * FROM `akun` WHERE `id`='$idtoko'");
           $data_detail_toko = mysqli_fetch_assoc($detail_toko);
           $footer = $server->query("SELECT * FROM `setting_footer` WHERE `name_social` = 'Whatsapp'");
            $dataFooter = mysqli_fetch_assoc($footer);
            $linkWhatsApp = $dataFooter['link_social'];

           // Pisahkan waktu gabung
           $bergabung_v = explode(' ', $data_detail_toko['waktu']);
       } else {
           // Handle jika query tidak berhasil
           // Misalnya, arahkan pengguna ke halaman lain atau tampilkan pesan kesalahan
           echo "Tidak dapat memuat data toko.";
           exit(); // Keluar dari skrip
       }
   } else {
       // Handle jika 'nama_pengguna' kosong
       // Misalnya, arahkan pengguna ke halaman lain atau tampilkan pesan kesalahan
       echo "Nama pengguna tidak valid: " . $nama_pengguna;
       exit(); // Keluar dari skrip
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
      <title>Official Store of <?php echo $data_detail_toko['nama_toko']; ?> | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include './partials/seo.php'; ?>
      <!-- META SEO -->
      <link rel="icon" href="../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../assets/css/store/view.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include './partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <div class="content_view">
            <div class="box_profile_penjual">
               <div class="profile_penjual">
                  <div class="profile-picture">
                     <img src="../assets/image/profil-toko/<?php echo $data_detail_toko['logo_toko']; ?>">
                     <?php
                        // Check status online atau offline
                        $status_user = $data_detail_toko['status_user'];
                        
                        
                        // Tampilkan ikon online jika status online
                        if ($status_user === 'online') {
                            echo '<span class="online-icon"></span>';
                        }
                        
                        // Tampilkan ikon offline jika status offline
                        if ($status_user === 'offline') {
                            echo '<span class="offline-icon"></span>';
                        }
                        ?>
                  </div>
                  <div class="name_profile_penjual">
                     <h1><?php echo $data_detail_toko['nama_toko']; ?> 
                        <?php 
                           if ($data_detail_toko['verifikasi_toko'] == 'Ya') {
                              echo '<img id="img-verif" src="../../assets/icons/verifikasi-toko.png">';
                           }
                           ?>
                     </h1>
                     <div class="<?php echo ($status_user === 'offline') ? 'offline' : 'online'; ?>">
                        <span class="status-user">
                        <?php
                           $status_user = $data_detail_toko['status_user'];
                           $last_active = $data_detail_toko['waktu_terakhir_aktif'];
                           
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
                     <?php
                        // Query untuk menghitung total penjualan dari tabel iklan untuk user tertentu
                        $query = "SELECT SUM(terjual) AS jumlah_transaksi FROM iklan WHERE user_id = $idtoko";
                        
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
                     <p><i class="fas fa-map-marker-alt"></i> <span><?php echo $data_detail_toko['kota_user']; ?> - Indonesia</span></p>
                  </div>
               </div>
               <div class="kanan">
                  <div class="atas" style="display: flex;">
                     <div class="keterangan">
                        <?php echo $jumlah_transaksi !== null ? $jumlah_transaksi : 0; ?>
                        <p>Penjualan</p>
                     </div>
                     <div class="separator"></div>
                     <?php
                        // Query untuk menghitung rata-rata waktu proses
                        $sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, waktu_transaksi, waktu_dikirim)) AS rata_rata_waktu
                                FROM invoice
                                WHERE id_iklan IN (SELECT id FROM iklan WHERE user_id = $idtoko)";
                        
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
                     <!-- Tampilkan rata-rata waktu proses dalam HTML -->
                     <div class="keterangan">
                        ± <?php echo $waktu_proses; ?>
                        <p>Proses Pesanan</p>
                     </div>
                     <div class="separator"></div>
                     <?php
                        // Asumsikan $idtoko sudah didefinisikan sebelumnya
                        
                        
                        // Kueri SQL
                        $query = "SELECT AVG(star_rat) AS rata_rata_star
                                  FROM rating
                                  WHERE id_invoice_rat IN (SELECT idinvoice FROM invoice WHERE id_iklan IN (SELECT id FROM iklan WHERE user_id = $idtoko))";
                        
                        $result = $conn->query($query);
                        
                        // Periksa hasil query
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $rata_rata_star = $row['rata_rata_star'];
                        
                            // Format rata-rata bintang
                            $total_ulasan = number_format($rata_rata_star, 1);
                        
                        }
                        ?>
                     <!-- Tampilkan rata-rata rafting dalam HTML -->
<div class="keterangan" onclick="location.href='ulasan/@<?php echo $nama_pengguna; ?>';" style="cursor: pointer;">
    <?php echo $total_ulasan; ?>/5
    <p>Ulasan Toko</p>
</div>

                  </div>
                  <div class="box_chat_visit">
                     <?php
                        // Logika untuk menampilkan tombol "edit profile" kepada pemilik toko dan menyembunyikan tombol chat
                        if ($iduser == $idtoko) {
                            // Jika pengguna adalah pemilik toko, tampilkan tombol "edit profile"
                            ?>
                     <a href="././store/settings<?php echo $data_detail_toko['user_id']; ?>">
                        <div class="isi_chat_visit">
                           <i class="ri-store-2-line"></i>
                           <p>Edit Profile</p>
                        </div>
                     </a>
                     <?php
                        } else {
                            // Jika pengguna bukan pemilik toko, tampilkan tombol chat
                            ?>
                     <!--<div class="isi_chat_visit1">
                        <i class="fas fa-info-circle"></i>
                        
                        </div>!-->
                     <a href="../chat/?mulai=<?php echo $idtoko; ?>">
                        <div class="isi_chat_visit">
                           <i class="ri-chat-1-line"></i>
                           <p>Chat Sekarang</p>
                        </div>
                     </a>
                    <a href="<?= $linkWhatsApp ?>">
                        <div class="isi_chat_visit">
                           <i class="fas fa-flag"></i> <!-- Ganti dengan ikon pelaporan -->
                           <p>Laporkan</p>
                        </div>
                     </a>
                     <?php
                        }
                        ?>
                  </div>
               </div>
            </div>
         </div>
         <div class="box_judul">
            <p>Semua Produk <span><?php echo $total_produk; ?></span></p>
         </div>
         <div class="box_produk">
            <?php
               while ($produk_data = mysqli_fetch_assoc($produk)) {
                  $exp_gambar_pd = explode(',', $produk_data['gambar']);
                  
                  $idproduct = $produk_data['id'];
               
               $select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
               $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
               
               $rata_rating_vp = $server->query("SELECT AVG(star_rat) AS rata_rat FROM rating, invoice WHERE invoice.id_iklan='$idproduct' AND rating.id_invoice_rat=invoice.idinvoice ");
               $data_rata_rating_vp = mysqli_fetch_assoc($rata_rating_vp);
               $hasil_rata_rat = substr($data_rata_rating_vp['rata_rat'], 0, 3);
               $for_star_loop = substr($data_rata_rating_vp['rata_rat'], 0, 1);
               $after_dot_rat = substr($data_rata_rating_vp['rata_rat'], 2, 1);
               
               
                  if ($produk_data['status'] == '') {
                      $stok_habis = $produk_data['stok'] == $produk_data['terjual']; //
                      
                       if ($produk_data['stok'] !== $produk_data['terjual'] or $produk_data['stok'] > $produk_data['terjual']) {
                        // Add a query to get brand information for this product
               $brand_id = $produk_data['id_brand'];
               $brand_query = $server->query("SELECT namab FROM `brand` WHERE id='$brand_id'");
               $brand_data = mysqli_fetch_assoc($brand_query);
               ?> 
            <!-- SHARE PRODUK -->
            <div class="back_share_produk" id="back_share_produk_<?php echo $produk_data['id']; ?>">
               <div class="share_produk">
                  <i class="ri-close-circle-fill close_sp" onclick="close_share_produk(<?php echo $produk_data['id']; ?>)"></i>
                  <h1>Bagikan Produk</h1>
                  <div class="box_link_produk">
                     <div class="isi_link_produk bg_lp_link" onclick="copy_link_produk('<?php echo $url; ?>/product/<?php echo $produk_data['slug']; ?>')">
                        <i class="ri-file-copy-fill" id="ico_copy_p"></i>
                        <i class="ri-checkbox-circle-fill" id="ico_selesai_copy_p" style="display: none;"></i>
                     </div>
                     <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($url . '/product/' . $produk_data['slug']); ?>" target="_blank">
                        <div class="isi_link_produk bg_lp_wa">
                           <i class="ri-whatsapp-fill"></i>
                        </div>
                     </a>
                     <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url . '/product/' . $produk_data['slug']); ?>" target="_blank">
                        <div class="isi_link_produk bg_lp_fb">
                           <i class="ri-facebook-box-fill"></i>
                        </div>
                     </a>
                     <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url . '/product/' . $produk_data['slug']); ?>&text=Check%20this%20out" target="_blank">
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
                  <a href="<?php echo $url; ?>/product/<?php echo $produk_data['slug']; ?>">
                  <img src="../assets/image/product/compressed/<?php echo $exp_gambar_pd[0]; ?>" <?php if ($stok_habis) echo 'class="stok_habis_image"'; ?>>
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
                     <div class="box_wishlist" onclick="show_share_produk('<?php echo $produk_data['id']; ?>')">
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
                        <p><i class="fas fa-cubes"></i> Stok <?php echo $produk_data['stok'] - $produk_data['terjual']; ?></p>
                     </div>
                     <?php if ($produk_data['gratis_ongkir'] == 'ya') { ?>
                     <div class="dua">
                        <p><i class="fas fa-shipping-fast"></i> Free Ongkir</p>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="text_list_produk">
                  <a href="<?php echo $url; ?>/product/<?php echo $produk_data['slug']; ?>">
                     <div class="box_judul_list_produk">
                        <p><?php echo $produk_data['judul']." • ".$produk_data['deskripsi']; ?></p>
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
                                $harga_produk_terlaris = $produk_data['harga'];
                        $exp_ukuran_pt = explode(',', $produk_data['ukuran']);
                        $harga_maksimal_pt = 0;
                        
                        foreach ($exp_ukuran_pt as $key_ukuran_pt => $value_ukuran_pt) {
                        $exp_ukuran_saja_pt = explode('===', $value_ukuran_pt);
                        $harga_produk_pt = isset($exp_ukuran_saja_pt[1]) ? (int)$exp_ukuran_saja_pt[1] : 0;
                        
                        if ($harga_produk_pt > $harga_maksimal_pt) {
                        $harga_maksimal_pt = $harga_produk_pt;
                        }
                        }
                        
                        $produk_memiliki_varian = count($exp_ukuran_pt) > 1;
                        $diskon = $produk_data['diskon'];
                        
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
                                $harga_produk_terlaris = $produk_data['harga'];
                                $exp_ukuran_pt = explode(',', $produk_data['ukuran']);
                                $harga_maksimal_pt = 0;
                        
                                foreach ($exp_ukuran_pt as $key_ukuran_pt => $value_ukuran_pt) {
                                    $exp_ukuran_saja_pt = explode('===', $value_ukuran_pt);
                                    $harga_produk_pt = isset($exp_ukuran_saja_pt[1]) ? (int)$exp_ukuran_saja_pt[1] : 0;
                        
                                    if ($harga_produk_pt > $harga_maksimal_pt) {
                                        $harga_maksimal_pt = $harga_produk_pt;
                                    }
                                }
                        
                                $produk_memiliki_varian = count($exp_ukuran_pt) > 1;
                                $diskon = $produk_data['diskon'];
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
                     <p><?php echo $produk_data['terjual']; ?> Terjual</p>
                  </div>
                  <div class="box_harga_list_produk1">
                     <?php if (!empty($produk_data['diskon'])) { ?>
                     <p><?php
                        // Ambil opsi_fitur dari database berdasarkan ID setting yang diinginkan
                        $opsi_fitur_query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 5";
                        $result = $conn->query($opsi_fitur_query);
                        
                        if ($result) {
                            $row = $result->fetch_assoc();
                            $hasil_query = $row['opsi_fitur'];
                        
                            if ($hasil_query === 'Aktif') {
                                // Misalnya harga dalam variabel $harga
                                $harga = $produk_data['harga'];
                        
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
                                $harga_produk_terlaris = $produk_data['harga'];
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
                     <p style="font-size: 9px;border: 1px solid var(--red);color: var(--red);padding: 0 2px;text-align: center;vertical-align: middle;font-weight: 500;margin-left: 5px;">Hemat <?php echo $produk_data['diskon']; ?>%</p>
                     <?php } ?>
                  </div>
               </div>
               <div class="list_lokasi">
                  <p><i class="fas fa-map-marker-alt"></i> Dikirim dari <?php echo $produk_data['lokasi']; ?></p>
               </div>
            </div>
            <?php
               }
               }
               }
               ?>
         </div>
      </div>
      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include './partials/footer.php'; ?>
      <?php include './partials/bottom-navigation.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="../assets/js/popular/index.js"></script>
      <!-- JS -->
   </body>
</html>
