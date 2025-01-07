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
$select_rating_vp = $server->query("SELECT * FROM `akun`, `invoice`, `rating` WHERE invoice.idloc='$idtoko' AND rating.id_invoice_rat=invoice.idinvoice AND invoice.id_user=akun.id ORDER BY `rating`.`idrating` DESC ");
       $jumlah_rating_vp = mysqli_num_rows($select_rating_vp);
   $total_produk = mysqli_num_rows($produk);
   
           // Ambil detail toko berdasarkan ID
           $detail_toko = $server->query("SELECT * FROM `akun` WHERE `id`='$idtoko'");
           $data_detail_toko = mysqli_fetch_assoc($detail_toko);
   
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
                    <div class="keterangan">
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
                     <a href="https://wa.me/6285891452604">
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
                 <div class="content_view">
            <div class="cv_title" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
               <p>Penilaian Semua Produk</p>
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
<div class="cv_title" style="text-align: center;">
    <p>Belum ada penilaian</p>
</div>
               <?php
                  }
                  ?>
            </div>
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
