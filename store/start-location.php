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
            <?php include '../partials/header.php' ?>

      <!-- POPUP LOKASI PENGIRIMAN -->
         <div class="edit_profile">
            
                      
                     
                     <!-- ALAMAT TOKO -->
                     <div class="box_isi_settings_adm" id="alamat_setting" style="display: block;">
    <h1><span id="tipe_eorc"></span> Lokasi Toko</h1>
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
                     
                 
         </div>
      <div id="res"></div>
      <?php include '../partials/footer.php'; ?>
      <?php include '../partials/bottom-navigation.php'; ?>
      <input type="hidden" value="store" id="tipe_user_vt">
      <!-- JS -->
      <script src="../assets/js/store/start-location.js"></script>
      <!-- JS -->
      <script>
         store_setting.onload = function() {
             p_lokasi_setting.click();
         }
      </script>
      <!-- JS -->
   </body>
</html>
