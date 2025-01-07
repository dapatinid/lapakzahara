<?php
include '../config.php';
include '../system/location/provinsi.php';
$page_admin = 'pengaturan';

if (isset($_COOKIE['login'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "/system/admin/logout");
    }
} else {
    header("location: " . $url . "/login");
}

$query = "SELECT provinsi_user, kota_user, kecamatan_user, provinsi_id_user, kota_id_user, kecamatan_id_user FROM akun WHERE id = $iduser";

$result = mysqli_query($conn, $query);

if ($result) {
    $lokasiTersimpan = mysqli_fetch_assoc($result);
} else {
    $lokasiTersimpan = [
        'provinsi_user' => '',
        'kota_user' => '',
        'kecamatan_user' => '',
        'provinsi_id_user' => '',
        'kota_id_user' => '',
        'kecamatan_id_user' => '',
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Pengaturan Lokasi Pengiriman</title>
      <link rel="icon" href="../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../assets/css/admin/settings/index.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">
      <link rel="stylesheet" href="../assets/css/profile/edit.css">
   </head>
   <body>
      <!-- POPUP LOKASI PENGIRIMAN -->
            <?php include '../partials/header.php' ?>

      <!-- POPUP LOKASI PENGIRIMAN -->
         <div class="edit_profile">
            
                      
                     <!-- INFORMASI TOKO -->
                     <div class="box_isi_settings_adm" id="detail_setting" style="display: block;">
                        <h1>Informasi Toko</h1>
                        <div class="box_img_pro">
            <div class="change_img_pro" onclick="click_file_img()">
               <i class="ri-pencil-fill"></i>
            </div>
            <img src="../assets/image/profil-toko/<?php echo $profile['logo_toko']; ?>" id="img_logo_toko_pro">
            <input type="file" accept="image/*" class="cfile_img_pro" id="cfile_img_pro" onchange="change_image(event)">
         </div>
         <p class="err_logo_toko_pro" id="err_logo_toko_pro">Pastikan format logo jpg/png</p>
         <div class="box_input_pro">
            <div class="isi_box_input_pro">
               <p id="p_nama_toko">Nama Toko</p>
               <input type="text" class="input" id="nama_toko" placeholder="Nama Toko" value="<?php echo $profile['nama_toko']; ?>">
            </div>
            <div class="isi_box_input_pro">
               <p id="p_nama_pengguna">Username Toko</p>
               <input type="text" class="input" id="nama_pengguna" placeholder="@namatoko" value="<?php echo $profile['nama_pengguna']; ?>">
            </div>
            <div class="isi_box_input_pro">
               <p id="p_no_wa">Nomor WhatsApp</p>
               <input type="text" class="input" id="no_wa" placeholder="Nomor Toko" value="<?php echo $profile['no_whatsapp']; ?>">
            </div>
         </div>
         
         <div class="b_button_ep"></div>
         <div class="button" id="bu_e_pro" onclick="simpan_edit_profile()">
            <p>Selanjutnya</p>
         </div>
         <div class="button" id="loading_e_pro" onclick="simpan_edit_profile()">
            <img src="../assets/icons/loading-w.svg" alt="">
         </div>
                     </div>
                     <!-- INFORMASI TOKO -->
                     
                 
         </div>
      <div id="res"></div>
      <?php include '../partials/footer.php'; ?>
      <?php include '../partials/bottom-navigation.php'; ?>
      <input type="hidden" value="store" id="tipe_user_vt">
      <!-- JS -->
      <script src="../assets/js/store/start.js"></script>
      <!-- JS -->
      <script>
         store_setting.onload = function() {
             p_lokasi_setting.click();
         }
      </script>
      <!-- JS -->
   </body>
</html>
