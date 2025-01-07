<?php
   include '../../config.php';
   
   $page_admin = 'laporan';
   
   $sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
      $data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);
       
      if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
          if (isset($_COOKIE['login'])) {
              if ($profile['provinsi_user'] == '') {
                  header("location: " . $url . "/store/start");
              }
          } else {
              header("location: " . $url . "/login");
          }
      } else {
          header("location: " . $url);
      }
   $sql_today = "SELECT 
                   COUNT(*) AS jumlah_transaksi, 
                   SUM((harga_i - (diskon_i / 100 * harga_i)) * jumlah + harga_ongkir) - SUM(diskon_min) AS total_harga_setelah_diskon, 
                   SUM(harga_ongkir) AS total_biaya_ongkir, 
                   SUM(diskon_i) AS total_diskon 
               FROM invoice 
               INNER JOIN iklan ON invoice.id_iklan = iklan.id 
               WHERE DATE(invoice.waktu_transaksi) = CURDATE() 
                   AND invoice.tipe_progress = 'Selesai' 
                   AND iklan.user_id = $iduser";
   
   $result_today = $conn->query($sql_today);
   
   if ($result_today->num_rows > 0) {
       $row_today = $result_today->fetch_assoc();
       $jumlah_transaksi_td = $row_today['jumlah_transaksi'];
       $semua_harga_lap = $row_today['total_harga_setelah_diskon'];
   }
   
   $sql_month = "SELECT 
                   COUNT(*) AS jumlah_transaksi, 
                   SUM((harga_i - (diskon_i / 100 * harga_i)) * jumlah + harga_ongkir) - SUM(diskon_min) AS total_harga_setelah_diskon, 
                   SUM(harga_ongkir) AS total_biaya_ongkir, 
                   SUM(diskon_i) AS total_diskon 
               FROM invoice 
               INNER JOIN iklan ON invoice.id_iklan = iklan.id 
               WHERE MONTH(invoice.waktu_transaksi) = MONTH(CURDATE()) 
                   AND invoice.tipe_progress = 'Selesai' 
                   AND iklan.user_id = $iduser";
   
   $result_month = $conn->query($sql_month);
   
   if ($result_month->num_rows > 0) {
       $row_month = $result_month->fetch_assoc();
       $bi_jumlah_transaksi_td = $row_month['jumlah_transaksi'];
       $bi_semua_harga_lap = $row_month['total_harga_setelah_diskon'];
   }
   
   $sql_year = "SELECT 
                   COUNT(*) AS jumlah_transaksi, 
                   SUM((harga_i - (diskon_i / 100 * harga_i)) * jumlah + harga_ongkir) - SUM(diskon_min) AS total_harga_setelah_diskon, 
                   SUM(harga_ongkir) AS total_biaya_ongkir, 
                   SUM(diskon_i) AS total_diskon 
               FROM invoice 
               INNER JOIN iklan ON invoice.id_iklan = iklan.id 
               WHERE YEAR(invoice.waktu_transaksi) = YEAR(CURDATE()) 
                   AND invoice.tipe_progress = 'Selesai' 
                   AND iklan.user_id = $iduser";
   
   $result_year = $conn->query($sql_year);
   
   if ($result_year->num_rows > 0) {
       $row_year = $result_year->fetch_assoc();
       $ti_jumlah_transaksi_td = $row_year['jumlah_transaksi'];
       $ti_semua_harga_lap = $row_year['total_harga_setelah_diskon'];
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Laporan Toko <?php echo $profile['nama_lengkap']; ?> | <?php echo $title_name; ?></title>
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/admin/laporan/index.css">
   </head>
   <body>
      <div class="admin">
         <?php include '../../store/partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Laporan</h1>
            <div class="isi_content_admin">
               <!-- CONTENT -->
               <div class="box_c_lap_adm">
                  <div class="isi_box_c_lap_adm">
                     <p>Penjualan produk hari ini</p>
                     <h5>Jumlah Transaksi: <?php echo $jumlah_transaksi_td; ?></h5>
                     <h1>Total Transaksi: Rp<?php echo number_format($semua_harga_lap, 0, ".", "."); ?></h1>
                  </div>
                  <div class="isi_box_c_lap_adm">
                     <p>Penjualan produk bulan ini</p>
                     <h5>Jumlah Transaksi: <?php echo $bi_jumlah_transaksi_td; ?></h5>
                     <h1>Total Transaksi: Rp<?php echo number_format($bi_semua_harga_lap, 0, ".", "."); ?></h1>
                  </div>
                  <div class="isi_box_c_lap_adm">
                     <p>Penjualan produk tahun ini</p>
                     <h5>Jumlah Transaksi: <?php echo $ti_jumlah_transaksi_td; ?></h5>
                     <h1>Total Transaksi: Rp<?php echo number_format($ti_semua_harga_lap, 0, ".", "."); ?></h1>
                  </div>
               </div>
               <!-- CONTENT -->
            </div>
         </div>
      </div>
      <div id="res"></div>
      <!-- JS -->
      <script src="../../assets/js/admin/laporan/index.js"></script>
      <?php include '../../partials/bottom-navigation.php'; ?>
   </body>
</html>
