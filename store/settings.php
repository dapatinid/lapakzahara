<?php
include '../config.php';
include '../system/location/provinsi.php';
$page_admin = 'pengaturan';

if (isset($_COOKIE['login'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
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
      <!-- POPUP LOKASI PENGIRIMAN -->
      <div class="admin">
         <?php include 'partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Pengaturan</h1>
            <div class="isi_content_admin">
               <!-- CONTENT -->
               <div class="settings_adm">
                  <div class="menu_settings_adm">
                     <div class="box_menu_settings_adm">
                        <div class="isi_menu_settings_adm" id="p_detail_setting">Informasi Toko</div>
                        <div class="isi_menu_settings_adm" id="p_alamat_setting">Alamat Toko</div>
                        <div class="isi_menu_settings_adm" id="p_privasi_setting">Privasi</div>
                     </div>
                  </div>
                  <div class="isi_settings_adm">
                      
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
            <p>Simpan</p>
         </div>
         <div class="button" id="loading_e_pro" onclick="simpan_edit_profile()">
            <img src="../assets/icons/loading-w.svg" alt="">
         </div>
                     </div>
                     <!-- INFORMASI TOKO -->
                     <!-- ALAMAT TOKO -->
                     <div class="box_isi_settings_adm" id="alamat_setting">
    <h1><span id="tipe_eorc"></span> Lokasi</h1>
    <h5 id="loklengkapedt"></h5>
    <div class="box_form_set_adm1">
        <div class="isi_box_form_set_adm1">
            <p class="p_input" id="p_provinsi_ls">Provinsi</p>
            <select class="input" id="provinsi_ls" onchange="change_provinsi()">
    <option value="" selected disabled hidden>Pilih Provinsi</option>
    <?php
    if (!empty($lokasiTersimpan['provinsi_id_user'])) {
        echo '<option value="' . $lokasiTersimpan['provinsi_id_user'] . ',' . $lokasiTersimpan['provinsi_user'] . '" selected>';
        echo $lokasiTersimpan['provinsi_user'];
        echo '</option>';
    }
    foreach ($provinsi_isi_data as $key_provinsi_isi_data => $value_provinsi_isi_data) {
        if (empty($lokasiTersimpan['provinsi_id_user']) || $value_provinsi_isi_data['province_id'] !== $lokasiTersimpan['provinsi_id_user']) {
            echo '<option value="' . $value_provinsi_isi_data['province_id'] . ',' . $value_provinsi_isi_data['province'] . '">';
            echo $value_provinsi_isi_data['province'];
            echo '</option>';
        }
    }
    ?>
</select>


        </div>
        <div class="isi_box_form_set_adm1">
            <p class="p_input" id="p_kota_ls">Kota</p>
            <!-- Isi opsi Kota -->
<select class="input" id="kota_ls" onclick="change_kota()">
    <option value="" selected disabled hidden>Pilih Kota</option>
    <?php
    if (!empty($lokasiTersimpan['kota_id_user'])) {
        echo '<option value="' . $lokasiTersimpan['kota_id_user'] . ',' . $lokasiTersimpan['kota_user'] . '" selected>';
        echo $lokasiTersimpan['kota_user'];
        echo '</option>';
    }
    // Tambahkan logika untuk menampilkan opsi kota yang tersedia
    ?>
</select>
        </div>
        <div class="isi_box_form_set_adm1">
            <p class="p_input" id="p_kecamatan_ls">Kecamatan</p>
            <!-- Isi opsi Kecamatan -->
<select class="input" id="kecamatan_ls">
    <option value="" selected disabled hidden>Pilih Kecamatan</option>
    <?php
    if (!empty($lokasiTersimpan['kecamatan_id_user'])) {
        echo '<option value="' . $lokasiTersimpan['kecamatan_id_user'] . ',' . $lokasiTersimpan['kecamatan_user'] . '" selected>';
        echo $lokasiTersimpan['kecamatan_user'];
        echo '</option>';
    }
    // Tambahkan logika untuk menampilkan opsi kecamatan yang tersedia
    ?>
</select>
        </div>
    </div>
    <div class="box_button_set_adm">
        <div class="button" onclick="simpan_lokasi()">
            <p id="text_s_lc">Simpan</p>
            <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_lc">
        </div>
    </div>
    <input type="hidden" id="lokasiidedit">
</div>
                     <!-- ALAMAT TOKO -->
                     <!-- PRIVASI TOKO -->
                     <div class="box_isi_settings_adm" id="privasi_setting">
                        <h1>Lokasi Pengiriman</h1>
                     </div>
                     <!-- PRIVASI TOKO -->
                     
                  </div>
               </div>
               <!-- CONTENT -->
            </div>
         </div>
      </div>
      <div id="res"></div>
      <input type="hidden" value="store" id="tipe_user_vt">
      <!-- JS -->
      <script src="../assets/js/store/setting.js"></script>
      <!-- JS -->
      <script>
         store_setting.onload = function() {
             p_lokasi_setting.click();
         }
      </script>
      <!-- JS -->
      <?php include '../partials/bottom-navigation.php'; ?>
   </body>
</html>
