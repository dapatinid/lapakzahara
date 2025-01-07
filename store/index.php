<?php
   include '../config.php';
   
   $page_admin = 'dashboard';
   
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
   
   $time_today = date("Y-m-d");
   
   // JUMLAH USER
   $sj_user_adm = $server->query("SELECT * FROM `akun`");
   $jumlah_user_adm = mysqli_num_rows($sj_user_adm);
   
   // JUMLAH USER HARI INI
   $sj_user_today_adm = $server->query("SELECT * FROM `akun` WHERE `waktu` LIKE '$time_today%' ORDER BY `akun`.`id` DESC");
   $jumlah_user_today_adm = mysqli_num_rows($sj_user_today_adm);
   
   // JUMLAH TRANSAKSI
   $sj_transaksi_adm = $server->query("SELECT * FROM `invoice`, `iklan` WHERE invoice.transaction='settlement' AND invoice.id_iklan=iklan.id AND iklan.user_id='$iduser' ");
   $jumlah_transaksi_adm = mysqli_num_rows($sj_transaksi_adm);
   
   // JUMLAH TRANSAKSI HARI INI
   $sj_transaksi_today_adm = $server->query("SELECT * FROM `akun`, `iklan`, `invoice` WHERE invoice.id_iklan=iklan.id AND invoice.id_user=akun.id AND `transaction`='settlement' AND iklan.user_id='$iduser' AND `waktu_transaksi`LIKE'$time_today%' ORDER BY `invoice`.`idinvoice` DESC");
   $jumlah_transaksi_today_adm = mysqli_num_rows($sj_transaksi_today_adm);
   
   // JUMLAH TRANSAKSI
   $sj_produk_adm = $server->query("SELECT * FROM `iklan` WHERE `status`='' AND `user_id`='$iduser' ");
   $jumlah_produk_adm = mysqli_num_rows($sj_produk_adm);
   
   // JUMLAH KATEGORI
   $sj_kategori_adm = $server->query("SELECT * FROM `kategori`");
   $jumlah_kategori_adm = mysqli_num_rows($sj_kategori_adm);
   
   // JUMLAH VOUCHERS
   $sj_kategori_adm = $server->query("SELECT * FROM `vouchers` WHERE `user_id`='$iduser'");
   $jumlah_kategori_adm = mysqli_num_rows($sj_kategori_adm);
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Dashboard Toko <?php echo $profile['nama_lengkap']; ?> | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->    
      <link rel="icon" href="../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../assets/css/admin/index.css">
   </head>
   <body>
      <div class="admin">
         <?php include './partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Dashboard Toko</h1>
            <div class="isi_content_admin">
               <!-- CONTENT -->
               <div class="menu_jumlah_adm">
               <div class="isi_menu_jumlah_adm">
               <?php
// Ganti dengan user_id yang Anda inginkan
$ti_user_id = $iduser;

// Query untuk menghitung total harga terjual (harga produk - diskon + ongkir)
$query = "SELECT SUM((invoice.harga_i - (invoice.harga_i * CAST(invoice.diskon_i AS DECIMAL) / 100)) * CAST(invoice.jumlah AS DECIMAL) + invoice.harga_ongkir) AS total_terjual
          FROM invoice
          INNER JOIN iklan ON invoice.id_iklan = iklan.id
          WHERE iklan.user_id = $ti_user_id AND invoice.tipe_progress = 'Selesai'";
$result = $server->query($query);

// Mengambil total harga terjual
$row = mysqli_fetch_assoc($result);
$harga_terjual = $row['total_terjual'];

// Tampilkan saldo pengguna
if ($harga_terjual !== null) {
    $querySaldo = "SELECT jumlah_saldo FROM saldo WHERE user_id = $ti_user_id";
    $resultSaldo = $server->query($querySaldo);
    $rowSaldo = mysqli_fetch_assoc($resultSaldo);
    $saldo_pengguna = $rowSaldo['jumlah_saldo'];
} else {
    $saldo_pengguna = 0; // Tidak ada transaksi, saldo tetap
}
?>

    <i class="ri-wallet-2-fill"></i>
    <div class="box_text_menu_jumlah_adm"> 
        <p>Penghasilan</p>
        <h1>Rp<?php echo number_format($saldo_pengguna, 0, ".", "."); ?></h1>
    </div>
</div>
                  <div class="isi_menu_jumlah_adm">
                     <i class="ri-shopping-bag-fill"></i>
                     <div class="box_text_menu_jumlah_adm">
                        <p>Jumlah Transaksi</p>
                        <h1><?php echo number_format($jumlah_transaksi_adm, 0, ".", "."); ?></h1>
                     </div>
                  </div>
                  <div class="isi_menu_jumlah_adm">
                     <i class="ri-archive-fill"></i>
                     <div class="box_text_menu_jumlah_adm">
                        <p>Jumlah Produk</p>
                        <h1><?php echo number_format($jumlah_produk_adm, 0, ".", "."); ?></h1>
                     </div>
                  </div>
                  <div class="isi_menu_jumlah_adm">
                     <i class="fas fa-tag"></i>
                     <div class="box_text_menu_jumlah_adm">
                        <p>Voucher</p>
                        <h1><?php echo number_format($jumlah_kategori_adm, 0, ".", "."); ?></h1>
                     </div>
                  </div>
               </div> 
               </div> 
               <div class="jumlah_today">
                  <div class="isi_jumlah_today">
                     <div class="head_isi_jumlah_today">
                        <h5>Transaksi Hari Ini</h5>
                        <h5><?php echo number_format($jumlah_transaksi_today_adm, 0, ".", "."); ?></h5>
                     </div>
                     <div class="content_jumlah_today">
                        <?php
                           while ($data_transaksi_today = mysqli_fetch_assoc($sj_transaksi_today_adm)) {
                               $exp_gambar_tt = explode(',', $data_transaksi_today['gambar']);
                           ?>
                        <div class="isi_content_jumlah_today">
                           <img src="../assets/image/product/compressed/<?php echo $exp_gambar_tt[0]; ?>">
                           <div class="text_isi_content_jumlah_today">
                              <h3><?php echo $data_transaksi_today['judul']; ?></h3>
                              <p>Pembeli <?php echo $data_transaksi_today['nama_lengkap']; ?></p>
                           </div>
                           <p class="jam_icjt"><?php echo $data_transaksi_today['jumlah']; ?></p>
                        </div>
                        <?php
                           }
                           ?>
                     </div>
                  </div>
               </div>
               <!-- CONTENT -->
               
            </div>
         </div>
      </div>
      <?php include '../partials/bottom-navigation.php'; ?>
   </body>
</html>
