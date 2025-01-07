<?php
   include '../config.php';
   include '../../system/location/provinsi.php';
   
   $page_admin = 'pengaturan';
   
   if (isset($_COOKIE['login_admin'])) {
       if ($akun_adm == 'false') {
           header("location: " . $url . "system/admin/logout");
       }
   } else {
       header("location: " . $url . "admin/login/");
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin Pengaturan</title>
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/admin/settings/index.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">
   </head>
   <body>
      <!-- POPUP LOKASI PENGIRIMAN -->
      <div class="back_plp" id="back_plp">
         <div class="plp">
            <i class="fas fa-times-circle close_plp" onclick="close_plpset()"></i>
            <h1><span id="tipe_eorc"></span> Lokasi</h1>
            <h5 id="loklengkapedt"></h5>
            <div class="box_form_set_adm1">
               <div class="isi_box_form_set_adm1">
                  <p class="p_input" id="p_provinsi_ls">Provinsi</p>
                  <select class="input" id="provinsi_ls" onchange="change_provinsi()">
                     <option value="" selected disabled hidden>Pilih Provinsi</option>
                     <?php
                        foreach ($provinsi_isi_data as $key_provinsi_isi_data => $value_provinsi_isi_data) {
                        ?>
                     <option value="<?php echo $value_provinsi_isi_data['province_id'] . ',' . $value_provinsi_isi_data['province']; ?>"><?php echo $value_provinsi_isi_data['province']; ?></option>
                     <?php
                        }
                        ?> 
                  </select>
               </div>
               <div class="isi_box_form_set_adm1">
                  <p class="p_input" id="p_kota_ls">Kota</p>
                  <select class="input" id="kota_ls" onclick="change_kota()">
                     <option value="" selected disabled hidden>Pilih Kota</option>
                  </select>
               </div>
               <div class="isi_box_form_set_adm1">
                  <p class="p_input" id="p_kecamatan_ls">Kecamatan</p>
                  <select class="input" id="kecamatan_ls">
                     <option value="" selected disabled hidden>Pilih Kecamatan</option>
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
      </div>
      <!-- POPUP LOKASI PENGIRIMAN -->
      <div class="admin">
         <?php include '../partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Pengaturan</h1>
            <div class="isi_content_admin">
               <!-- CONTENT -->
               <div class="settings_adm">
                  <div class="menu_settings_adm">
                     <div class="box_menu_settings_adm">
                        <div class="isi_menu_settings_adm" id="p_header_setting">Header & SEO</div>
                        <div class="isi_menu_settings_adm" id="p_footer_setting">Footer & Sosial</div>
                        <div class="isi_menu_settings_adm" id="p_apikey_setting">Api Key</div>
                        <div class="isi_menu_settings_adm" id="p_lokasi_setting">Lokasi Pengiriman</div>
                        <div class="isi_menu_settings_adm" id="p_metode_pembayaran">Metode Pembayaran</div>
                        <div class="isi_menu_settings_adm" id="p_email_smtp">Email SMTP</div>
                        <div class="isi_menu_settings_adm" id="p_akses_login">Fitur Setting</div>
                        <div class="isi_menu_settings_adm" id="p_kurir">Kurir Pengiriman</div>
                        <div class="isi_menu_settings_adm" id="p_about">Sunting Laman</div>
                        <div class="isi_menu_settings_adm" id="p_warna">Warna Tampilan</div>
                     </div>
                  </div>
                  <div class="isi_settings_adm">
                     <!-- HEADER -->
                     <div class="box_isi_settings_adm" id="header_setting">
                        <h1>Header & SEO</h1>
                        <h3 style='margin-top: 25px;'>Pengaturan HEADER</h3>
                        <div class="box_logo_hs">
                           <img src="../../assets/icons/<?php echo $logo; ?>" id="view_logo_hs">
                           <br/>
                           <div class="text_box_logo_hs">
                              <h1 onclick="c_ubah_logo()">Ubah Logo <i class="ri-pencil-fill"></i></h1>
                              <p>Pastikan logo berformat svg, png, jpeg, jpg</p>
                              <p id="err_foto_hs">File harus format svg, png, jpeg/jpg</p>
                           </div>
                           <input type="file" id="ubah_logo_cf_hs" onchange="change_ubah_logo(event)" accept="image/*">
                        </div>
                        <div class="box_favicon_hs">
                           <img src="../../assets/icons/<?php echo $favicon; ?>" id="view_favicon_hs">
                           <div class="text_box_favicon_hs">
                              <h1 onclick="c_ubah_favicon()">Ubah favicon <i class="ri-pencil-fill"></i></h1>
                              <p>Pastikan favicon berformat png, jpeg, dan aspect ratio 1:1</p>
                              <p id="err_favicon_hs">File harus format png, jpeg, jpg, ico</p>
                           </div>
                           <input type="file" id="ubah_favicon_cf_hs" onchange="change_ubah_favicon(event)" accept="image/*">
                        </div>
                        <div class="box_form_set_adm1">
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Judul Website</p>
                              <input type="text" class="input" id="nama_perusahaan_hs" placeholder="Masukkan Nama Perusahaan" value="<?php echo $title_name; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Slogan Website</p>
                              <input type="text" class="input" id="slogan_hs" placeholder="Masukkan Slogan Website" value="<?php echo $slogan; ?>">
                           </div>
                        </div>
                        <h3 style='margin-top: 25px;'>Pengaturan SEO</h3>
                        <div class="box_form_set_adm1">
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Meta Deskripsi</p>
                              <textarea type="text" class="input" id="meta_description_hs" placeholder="Masukkan Meta Deskripsi"><?php echo $meta_description; ?></textarea>
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Meta Keyword (Pisah Dengan Koma)</p>
                              <input type="text" class="input" id="meta_keyword_hs" placeholder="Masukkan Meta Keyword" value="<?php echo $meta_keyword; ?>">
                              <span>Contoh: <font style='color:var(--orange)'>toko online, toko sembako, toko baju</font></span>
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kode Verifikasi Google</p>
                              <input type="text" class="input" id="google_verification_hs" placeholder="Masukkan Kode Google Verifikasi" value="<?php echo $google_verification; ?>">
                              <span>&lt;meta content=&#039;<font style='color:var(--orange)'>KODE-VEIFIKASI-GOOGLE</font>&#039; name=&#039;google-site-verification&#039;/&gt;</span>
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kode Verifikasi Bing</p>
                              <input type="text" class="input" id="bing_verification_hs" placeholder="Masukkan Kode Bing Verifikasi" value="<?php echo $bing_verification; ?>">
                              <span>&lt;meta content=&#039;<font style='color:var(--orange)'>KODE-VERIFIKASI-BING</font>&#039; name=&#039;msvalidate.01&#039;/&gt;</span>
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kode Verifikasi Ahrefs</p>
                              <input type="text" class="input" id="ahrefs_verification_hs" placeholder="Masukkan Kode Ahrefs Verifikasi" value="<?php echo $ahrefs_verification; ?>">
                              <span>&lt;meta content=&#039;<font style='color:var(--orange)'>KODE-VERIFIKASI-AHREFS</font>&#039; name=&#039;ahrefs-site-verification&#039;/&gt;</span>
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kode Verifikasi Yandex</p>
                              <input type="text" class="input" id="yandex_verification_hs" placeholder="Masukkan Kode Yandex Verifikasi" value="<?php echo $yandex_verification; ?>">
                              <span>&lt;meta content=&#039;<font style='color:var(--orange)'>KODE-VERIFIKASI-YANDEX</font>&#039; name=&#039;yandex-verification&#039;/&gt;</span>
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kode Verifikasi Norton</p>
                              <input type="text" class="input" id="norton_verification_hs" placeholder="Masukkan Kode Norton Verifikasi" value="<?php echo $norton_verification; ?>">
                              <span>&lt;meta content=&#039;<font style='color:var(--orange)'>KODE-VERIFIKASI-NORTON</font>&#039; name=&#039;norton-safeweb-site-verification&#039;/&gt;</span>
                           </div>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_header()">
                              <p id="text_s_hs">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_hs">
                           </div>
                        </div>
                     </div>
                     <!-- HEADER -->
                     <!-- FOOTER -->
                     <div class="box_isi_settings_adm" id="footer_setting">
                        <h1>Footer</h1>
                        <div class="box_form_set_adm2">
                           <?php
                              $select_social_fo = $server->query("SELECT * FROM `setting_footer`");
                              while ($data_social_fo = mysqli_fetch_assoc($select_social_fo)) {
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_link_social_fo<?php echo $data_social_fo['id_fo']; ?>"><?php echo $data_social_fo['icon_social'] ?> <?php echo $data_social_fo['name_social']; ?> Link</p>
                              <input type="text" class="input" placeholder="Tambahkan Link" id="link_social_fo<?php echo $data_social_fo['id_fo']; ?>" value="<?php echo $data_social_fo['link_social']; ?>">
                           </div>
                           <?php
                              }
                              
                              $s_footer_copyright = $server->query("SELECT * FROM `setting_copyright` WHERE `id`='1' ");
                              $data_s_footer_copyright = mysqli_fetch_assoc($s_footer_copyright);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Footer Copyright</p>
                              <input type="text" class="input" placeholder="Footer Copyright..." id="fo_copyright" value="<?php echo $data_s_footer_copyright['name']; ?>">
                           </div>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_footer()">
                              <p id="text_s_fo">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_fo">
                           </div>
                        </div>
                     </div>
                     <!-- FOOTER -->
                     <!-- API KEY -->
                     <div class="box_isi_settings_adm" id="apikey_setting">
                        <h1>Api Key</h1>
                        <div class="box_form_set_adm1">
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_google_client_id_key_ak">Google Client ID Key</p>
                              <input type="text" class="input" id="google_client_id_key_ak" placeholder="Masukkan Google Client ID Key" value="<?php echo $google_client_id; ?>" values="<?php echo $google_client_id; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_google_client_secret_key_ak">Google Client Secret Key</p>
                              <input type="text" class="input" id="google_client_secret_key_ak" placeholder="Masukkan Google Client Secret Key" value="<?php echo $google_client_secret; ?>" values="<?php echo $google_client_secret; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_midtrans_client_key_ak">Midtrans Client Key</p>
                              <input type="text" class="input" id="midtrans_client_key_ak" placeholder="Masukkan Midtrans Client Key" value="<?php echo $midtrans_client_key; ?>" values="<?php echo $midtrans_client_key; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_midtrans_server_key_ak">Midtrans Server Key</p>
                              <input type="text" class="input" id="midtrans_server_key_ak" placeholder="Masukkan Midtrans Server Key" value="<?php echo $midtrans_server_key; ?>" values="<?php echo $midtrans_server_key; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_rajaongkir_key_ak">RajaOngkir Pro Key</p>
                              <input type="text" class="input" id="rajaongkir_key_ak" placeholder="Masukkan RajaOngkir Key" value="<?php echo $rajaongkir_key; ?>" values="<?php echo $rajaongkir_key; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_tinypng_key_ak">TinyPNG Key</p>
                              <input type="text" class="input" id="tinypng_key_ak" placeholder="Masukkan TinyPNG Key" value="<?php echo $tinypng_key; ?>" values="<?php echo $tinypng_key; ?>">
                           </div>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_apikey()">
                              <p id="text_s_ak">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_ak">
                           </div>
                        </div>
                     </div>
                     <!-- API KEY -->
                     <!-- LOKASI -->
                     <?php
                        // Ubah query untuk mengambil lokasi pengguna dari tabel `akun` berdasarkan ID user
                        $s_lokasipeng = $server->query("SELECT `provinsi_user`, `kota_user`, `kecamatan_user` FROM `akun` WHERE `id` = '$iduser'");
                        $data_s_lokasipeng = mysqli_fetch_assoc($s_lokasipeng);
                        ?>
                     <div class="box_isi_settings_adm" id="lokasi_setting">
                        <h1>Lokasi Pengiriman</h1>
                        <div class="box_list_lokasiset">
                           <div class="list_lokasiset" id="shdjhgf">
                              <p>
                                 <?php
                                    // Tampilkan lokasi pengguna berdasarkan data yang diperoleh
                                    echo $data_s_lokasipeng['provinsi_user'] . ', ' . $data_s_lokasipeng['kota_user'] . ', ' . $data_s_lokasipeng['kecamatan_user'];
                                    ?>
                              </p>
                              <!-- Tambahkan aksi edit jika diperlukan -->
                              <i class="fas fa-pen" onclick="edit_lokasi_peng('<?php echo $iduser; ?>', '<?php echo $data_s_lokasipeng['provinsi_user'] . ', ' . $data_s_lokasipeng['kota_user'] . ', ' . $data_s_lokasipeng['kecamatan_user']; ?>')"></i>
                              <!-- Tambahkan aksi hapus jika ID tidak sama dengan 1 -->
                              <?php
                                 if ($iduser != '1') {
                                 ?>
                              <i class="fas fa-trash" onclick="hapus_lokasi_peng('<?php echo $iduser; ?>', 'shdjhgf')"></i>
                              <?php
                                 }
                                 ?>
                           </div>
                        </div>
                     </div>
                     <!-- LOKASI -->
                     <!-- METODE PEMBAYARAN -->
<div class="box_isi_settings_adm" id="metode_pembayaran_setting">
    <h1>Metode Pembayaran</h1>
    <div class="box_form_set_adm1">
        <div class="isi_box_form_set_adm1">
            <p class="p_input" id="p_mep_ls">Pilih Metode Pembayaran</p>
            <select class="input" id="inp_tipe_mp">
                <option value="<?php echo $nama_tipe_pembayaran; ?>" selected disabled hidden><?php echo $nama_tipe_pembayaran; ?></option>
                <option value="Midtrans">Midtrans</option>
                <option value="Manual">Manual</option>
            </select>
        </div>
        <?php
        $select_norek = $server->query("SELECT * FROM `nomor_rekening`");
        $data_norek_adm = [];

        // Looping untuk mengambil setiap baris hasil query
        while ($row = mysqli_fetch_assoc($select_norek)) {
            $data_norek_adm[] = $row;
        }
        ?>
        <div class="isi_box_form_set_adm1">
            <p class="p_input" id="p_mep_mt">Pilih Bank</p>
            <select class="input" id="inp_tipe_bk" onchange="showBankDetails()">
                <option value="">Pilih Bank</option>
                <?php foreach ($data_norek_adm as $norek) { ?>
                    <option value="<?php echo $norek['idnorek']; ?>"><?php echo $norek['nama_bank']; ?></option>
                <?php } ?>
                        <option value="tambah_bank">Tambah Bank</option> <!-- Tambah opsi untuk menambah bank baru -->
            </select>
        </div>
        <div class="isi_box_form_set_adm1" id="bank_details" style="display: none;">
            <p class="p_input" id="p_nama_bank">Nama Bank</p>
            <input type="text" class="input" id="nama_bank" placeholder="Nama Bank...">
            <p class="p_input" id="p_norek">Nomor Rekening</p>
            <input type="text" class="input" id="norek" placeholder="Nomor Rekening...">
            <p class="p_input" id="p_atas_nama">Atas Nama</p>
            <input type="text" class="input" id="atas_nama" placeholder="Atas Nama...">
        </div>
    </div>
    <div class="box_button_set_adm">
        <div class="button" onclick="simpan_metode_pembayaran()">
            <p id="text_s_lmp">Simpan</p>
            <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_lmp">
        </div>
    </div>
</div>

                     <!-- METODE PEMBAYARAN -->
                     <!-- EMAIL SMTP -->
                     <div class="box_isi_settings_adm" id="email_smtp_setting">
                        <h1>Email SMTP</h1>
                        <div class="box_form_set_adm1">
                           <?php
                              $select_email_setting_adm_set = $server->query("SELECT * FROM `setting_email` WHERE `id`='1' ");
                              $data_email_setting_adm_set = mysqli_fetch_array($select_email_setting_adm_set);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_email_notif_adm">Email Notif Untuk Admin</p>
                              <input type="text" class="input" id="email_notif_adm" placeholder="Masukkan Email Notif Untuk Admin" value="<?php echo $data_email_setting_adm_set['email_notif']; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_host_smtp">Host SMTP</p>
                              <input type="text" class="input" id="host_smtp" placeholder="Masukkan Host SMTP" value="<?php echo $data_email_setting_adm_set['host_smtp']; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_port_smtp">Port SMTP</p>
                              <input type="text" class="input" id="port_smtp" placeholder="Masukkan Port SMTP" value="<?php echo $data_email_setting_adm_set['port_smtp']; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_username_smtp">Username SMTP</p>
                              <input type="text" class="input" id="username_smtp" placeholder="Masukkan Username SMTP" value="<?php echo $data_email_setting_adm_set['username_smtp']; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_password_smtp">Password SMTP</p>
                              <input type="text" class="input" id="password_smtp" placeholder="Masukkan Password SMTP" value="<?php echo $data_email_setting_adm_set['password_smtp']; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_setfrom_smtp">Nama Pengirim SMTP</p>
                              <input type="text" class="input" id="setfrom_smtp" placeholder="Masukkan Nama Pengirim SMTP" value="<?php echo $data_email_setting_adm_set['setfrom_smtp']; ?>">
                           </div>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_email_smtp()">
                              <p id="text_s_esmtp">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_esmtp">
                           </div>
                        </div>
                     </div>
                     <!-- EMAIL SMTP -->
                     <!-- FITUR SETTING -->
                     <div class="box_isi_settings_adm" id="akses_login_setting">
                        <h1>Fitur Setting</h1>
                        <div class="box_form_set_adm1">
                           <?php
                              $sf_view_produk = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='view produk' ");
                              $data_sf_view_produk = mysqli_fetch_array($sf_view_produk);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_sf_view_produk">View Produk</p>
                              <select class="input" id="sf_view_produk">
                                 <option value="<?php echo $data_sf_view_produk['opsi_fitur']; ?>" selected disabled hidden><?php echo $data_sf_view_produk['opsi_fitur']; ?></option>
                                 <option value="Tidak Harus Login">Tidak Harus Login</option>
                                 <option value="Harus Login">Harus Login</option>
                              </select>
                           </div>
                           <?php
                              $sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
                              $data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_sf_tipe_toko">Tipe Toko</p>
                              <select class="input" id="sf_tipe_toko">
                                 <option value="<?php echo $data_sf_tipe_toko['opsi_fitur']; ?>" selected disabled hidden><?php echo $data_sf_tipe_toko['opsi_fitur']; ?></option>
                                 <option value="Marketplace">Marketplace</option>
                                 <option value="Toko Online">Toko Online</option>
                              </select>
                           </div>
                           <?php
                              $sf_midtrans_mode = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='midtrans mode' ");
                              $data_sf_midtrans_mode = mysqli_fetch_array($sf_midtrans_mode);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_sf_midtrans_mode">Midtrans Mode</p>
                              <select class="input" id="sf_midtrans_mode">
                                 <option value="<?php echo $data_sf_midtrans_mode['opsi_fitur']; ?>" selected disabled hidden><?php echo $data_sf_midtrans_mode['opsi_fitur']; ?></option>
                                 <option value="Sandbox">Sandbox</option>
                                 <option value="Production">Production</option>
                              </select>
                           </div>
                           <?php
                              $sf_cod = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='cod' ");
                              $data_sf_cod = mysqli_fetch_array($sf_cod);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_sf_cod">Pembayaran Ditempat (COD)</p>
                              <select class="input" id="sf_cod">
                                 <option value="<?php echo $data_sf_cod['opsi_fitur']; ?>" selected disabled hidden><?php echo $data_sf_cod['opsi_fitur']; ?></option>
                                 <option value="Aktif">Aktif</option>
                                 <option value="Tidak Aktif">Tidak Aktif</option>
                              </select>
                           </div>
                           <?php
                              $sf_format_harga = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='format harga' ");
                              $data_sf_format_harga = mysqli_fetch_array($sf_format_harga);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_sf_format_harga">Format Harga</p>
                              <select class="input" id="sf_format_harga">
                                 <option value="<?php echo $data_sf_format_harga['opsi_fitur']; ?>" selected disabled hidden><?php echo $data_sf_format_harga['opsi_fitur']; ?></option>
                                 <option value="Aktif">Aktif</option>
                                 <option value="Tidak Aktif">Tidak Aktif</option>
                              </select>
                              <span>Contoh: <font style="color:var(--orange)">Rp100.000</font> menjadi <font style="color:var(--orange)">Rp100rb</font></span>
                           </div>
                           <?php
                              $sf_jumlah_persen = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='jumlah persen' ");
                              $data_sf_jumlah_persen = mysqli_fetch_array($sf_jumlah_persen);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_jumlah_persen">Potongan Penarikan (%)</p>
                              <input type="text" class="input" id="sf_jumlah_persen" placeholder="Masukan nilai potongan persen" value="<?php echo $data_sf_jumlah_persen['opsi_fitur']; ?>">
                           </div>
                           <?php
                              $sf_minimal_penarikan = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='minimal penarikan' ");
                              $data_sf_minimal_penarikan = mysqli_fetch_array($sf_minimal_penarikan);
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input" id="p_minimal_penarikan">Minimal Penarikan Saldo</p>
                              <input type="text" class="input" id="sf_minimal_penarikan" placeholder="Masukan jumlah minimal" value="<?php echo $data_sf_minimal_penarikan['opsi_fitur']; ?>">
                           </div>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_fitur_setting()">
                              <p id="text_s_ealog">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_ealog">
                           </div>
                        </div>
                     </div>
                     <!-- FITUR SETTING -->
                     <!-- KURIR PENGIRIMAN -->
                     <div class="box_isi_settings_adm" id="kurir_setting">
                        <h1>Kurir Pengiriman</h1>
                        <div class="box_top_kp_hs">
                           <?php
                              $s_min_disss = $server->query("SELECT * FROM `setting_diskon` WHERE `id`='1' ");
                              $data_s_min_disss = mysqli_fetch_assoc($s_min_disss);
                              ?>
                           <!--
                           <h3>Pengaturan Diskon</h3>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Minimal Harga Diskon Potongan</p>
                              <input type="number" class="input" id="min_harga_diss" value="<?php echo $data_s_min_disss['min_nominal']; ?>">
                           </div>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Persen Dari Diskon Potongan</p>
                              <input type="number" class="input" id="min_persen_diss" value="<?php echo $data_s_min_disss['persen']; ?>">
                           </div>
                            -->
                           <?php
                              $s_min_go = $server->query("SELECT * FROM `setting_gratis_ongkir` WHERE `id`='1' ");
                              $data_s_min_go = mysqli_fetch_assoc($s_min_go);
                              ?>
                           <div class="isi_box_form_set_adm1" style="display: none;>
                              <p class="p_input">Minimal Harga Gratis Ongkir</p>
                              <input type="number" class="input" id="min_harga_go" value="<?php echo $data_s_min_go['min_nominal']; ?>">
                           </div>
                           <?php
                              $s_ktoko = $server->query("SELECT * FROM `kurir_toko` WHERE `user_id` = $iduser");
                              while ($data_s_ktoko = mysqli_fetch_assoc($s_ktoko)) {
                                  if ($data_s_ktoko['id'] == '1') {
                              ?>
                           <h3>Pengaturan Kurir Toko</h3>
                           <div class="isi_box_form_set_adm1">
                              <input type="text" class="input" id="etd" name="etd_<?php echo $data_s_ktoko['id']; ?>" value="<?php echo $data_s_ktoko['etd']; ?>" hidden>
                              <p class="p_input">Status</p>
                              <select class="input" id="status" name="status_<?php echo $data_s_ktoko['id']; ?>">
                                 <option value="<?php echo $data_s_ktoko['status']; ?>" selected disabled hidden><?php echo $data_s_ktoko['status']; ?></option>
                                 <option value="Nonaktif">Nonaktif</option>
                                 <option value="Aktif">Aktif</option>
                              </select>
                           </div>
                           <?php
                              }
                              }
                              $s_krostatus = $server->query("SELECT * FROM `setting_kurir_rajaongkir` WHERE `id`='1' ");
                              $data_s_krostatus = mysqli_fetch_assoc($s_krostatus);
                              ?>
                           <h3>Pengaturan Kurir Raja Ongkir</h3>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kurir Rajaongkir</p>
                              <select class="input" id="status_kurir_ro">
                                 <option value="<?php echo $data_s_krostatus['status']; ?>" selected disabled hidden><?php echo $data_s_krostatus['status']; ?></option>
                                 <option value="Nonaktif">Nonaktif</option>
                                 <option value="Aktif">Aktif</option>
                              </select>
                           </div>
                        </div>
                        <!-- <h4>Rajaongkir</h4> -->
                        <div class="box_form_set_adm3">
                           <?php
                              $s_kurir = $server->query("SELECT * FROM `setting_kurir` ");
                              while ($data_s_kurir = mysqli_fetch_assoc($s_kurir)) {
                              ?>
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input <?php if ($data_s_kurir['status'] == '') {
                                 echo 't_a_kp';
                                 } ?>"><?php echo $data_s_kurir['kurir']; ?></p>
                              <select class="input" id="impkur<?php echo $data_s_kurir['id']; ?>" value="<?php echo $data_s_kurir['status']; ?>">
                                 <?php
                                    if ($data_s_kurir['status'] == '') {
                                    ?>
                                 <option value="" selected disabled hidden>Aktif</option>
                                 <?php
                                    } else {
                                    ?>
                                 <option value="0" selected disabled hidden>Nonaktif</option>
                                 <?php
                                    }
                                    ?>
                                 <option value="">Aktif</option>
                                 <option value="0">Nonaktif</option>
                              </select>
                           </div>
                           <?php
                              }
                              ?>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_kurir()">
                              <p id="text_s_kurir">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_kurir">
                           </div>
                        </div>
                     </div>
                     <!-- KURIR PENGIRIMAN -->
                     <!-- ABOUT SETTING -->
                     <div class="box_isi_settings_adm" id="about_setting">
                        <h1>Halaman</h1>
                        <?php
                           $s_kp_ab = $server->query("SELECT * FROM `setting_about` WHERE `id`='1' ");
                           $data_s_kp_ab = mysqli_fetch_assoc($s_kp_ab);
                           
                           $s_tk_ab = $server->query("SELECT * FROM `setting_about` WHERE `id`='2' ");
                           $data_s_tk_ab = mysqli_fetch_assoc($s_tk_ab);
                           
                           $s_sk_ab = $server->query("SELECT * FROM `setting_about` WHERE `id`='3' ");
                           $data_s_sk_ab = mysqli_fetch_assoc($s_sk_ab);
                           ?>
                        <div class="box_form_set_adm1">
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Kebijakan Privasi</p>
                              <input id="kp_about" type="hidden" name="kp_about" value="<?php echo htmlspecialchars($data_s_kp_ab['isi']); ?>">
                              <trix-editor input="kp_about"></trix-editor>
                           </div>
                        </div>
                        <div class="box_form_set_adm1">
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Tentang Kami</p>
                              <input id="tk_about" type="hidden" name="tk_about" value="<?php echo htmlspecialchars($data_s_tk_ab['isi']); ?>">
                              <trix-editor input="tk_about"></trix-editor>
                           </div>
                        </div>
                        <div class="box_form_set_adm1">
                           <div class="isi_box_form_set_adm1">
                              <p class="p_input">Syarat & Ketentuan</p>
                              <input id="sk_about" type="hidden" name="sk_about" value="<?php echo htmlspecialchars($data_s_sk_ab['isi']); ?>">
                              <trix-editor input="sk_about"></trix-editor>
                           </div>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_about()">
                              <p id="text_s_abt">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_abt">
                           </div>
                        </div>
                     </div>
                     <!-- ABOUT SETTING -->
                     <!-- SETTING WARNA -->
                     <style>
                        .input[type="color"] {
                        height: 40px; /* Sesuaikan tinggi sesuai kebutuhan */
                        }
                     </style>
                     <div class="box_isi_settings_adm" id="warna_setting">
                        <h1>Warna Tampilan</h1>
                        <div class="box_form_set_adm1">
                           <?php
                              // Query untuk mengambil nilai warna dari tabel setting_warna
                              $sql = "SELECT variabel, code FROM setting_warna";
                              $result = $conn->query($sql);
                              
                              if ($result->num_rows > 0) {
                                  // Loop melalui setiap baris hasil query
                                  while ($row = $result->fetch_assoc()) {
                                      // Gunakan nilai dari variabel dan code untuk mengisi input dalam HTML
                                      $variabel = $row["variabel"];
                                      $code = $row["code"];
                                      echo '<div class="isi_box_form_set_adm1">';
                                      echo '<p class="p_input" id="p_' . $variabel . '">' . $variabel . '</p>';
                                      echo '<input type="color" class="input" id="' . $variabel . '" value="' . $code . '">';
                                      echo '</div>';
                                  }
                              } else {
                                  echo "Tidak ada data.";
                              }
                              
                              $conn->close();
                              ?>
                        </div>
                        <div class="reset_button" style="margin-top: 10px;">
                           <a href="#" onclick="reset_default()">Reset Default</a>
                        </div>
                        <div class="box_button_set_adm">
                           <div class="button" onclick="simpan_warna()">
                              <p id="text_s_warna">Simpan</p>
                              <img src="../../assets/icons/loading-w.svg" class="loading_s" id="loading_s_warna">
                           </div>
                        </div>
                     </div>
                     <!-- SETTING WARNA -->
                  </div>
               </div>
               <!-- CONTENT -->
            </div>
         </div>
      </div>
      
      <div id="res"></div>
      <input type="hidden" value="admin" id="tipe_user_vt">
      <!-- JS -->
      <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.js"></script>
           <script src="../../assets/js/admin/settings/index.js"></script>
<script type="text/javascript">
function showBankDetails() {
    var select = document.getElementById("inp_tipe_bk");
    var selectedOption = select.options[select.selectedIndex].value;
    var dataNorek = <?php echo json_encode($data_norek_adm); ?>;

    console.log("Selected Option: ", selectedOption); // Debug: Display selected option value

    // Check if dataNorek is valid JSON
    if (typeof dataNorek !== 'object') {
        console.error("Error: dataNorek is not valid JSON");
        return;
    }

    if (selectedOption === "tambah_bank") {
        // Menampilkan formulir kosong dan menyembunyikan detail bank
        document.getElementById('nama_bank').value = "";
        document.getElementById('atas_nama').value = "";
        document.getElementById('norek').value = "";
        document.getElementById('bank_details').style.display = 'block';
    } else {
        // Cari detail nomor rekening dan penerima sesuai dengan metode pembayaran yang dipilih
        for (var i = 0; i < dataNorek.length; i++) {
            if (dataNorek[i]['idnorek'] === selectedOption) {
                console.log("Found matching bank:", dataNorek[i]); // Debug: Display matching bank details
                document.getElementById('nama_bank').value = dataNorek[i]['nama_bank'];
                document.getElementById('atas_nama').value = dataNorek[i]['an'];
                document.getElementById('norek').value = dataNorek[i]['norek']; // Update nomor_rekening
                document.getElementById('bank_details').style.display = 'block';
                break;
            }
        }
    }
}
    
    function simpan_metode_pembayaran() {
    if (nama_bank.value == '') {
        p_nama_bank.style.color = '#EA2027';
        nama_bank.style.border = '1px solid #EA2027';
    } else {
        p_nama_bank.style.color = '#959595';
        nama_bank.style.border = '1px solid #e2e2e2';
    }
    if (norek.value == '') {
        p_norek.style.color = '#EA2027';
        norek.style.border = '1px solid #EA2027';
    } else {
        p_norek.style.color = '#959595';
        norek.style.border = '1px solid #e2e2e2';
    }
    if (atas_nama.value == '') {
        p_atas_nama.style.color = '#EA2027';
        atas_nama.style.border = '1px solid #EA2027';
    } else {
        p_atas_nama.style.color = '#959595';
        atas_nama.style.border = '1px solid #e2e2e2';
    }
    if (nama_bank.value && norek.value && atas_nama.value) {
        var data_metode_pembayaran = new FormData();
        var select = document.getElementById("inp_tipe_bk");
        var selectedOption = select.options[select.selectedIndex].value;
        data_metode_pembayaran.append('inp_tipe_mp', document.getElementById('inp_tipe_mp').value);
        data_metode_pembayaran.append('nama_bank', document.getElementById('nama_bank').value);
        data_metode_pembayaran.append('inp_tipe_bk', selectedOption);
        data_metode_pembayaran.append('norek', document.getElementById('norek').value);
        data_metode_pembayaran.append('atas_nama', document.getElementById('atas_nama').value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                text_s_lmp.style.display = 'none';
                loading_s_lmp.style.display = 'flex';
            }
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                text_s_lmp.style.display = 'flex';
                loading_s_lmp.style.display = 'none';
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
        xhttp.open('POST', '../../system/admin/settings/metode-pembayaran.php', true);
        xhttp.send(data_metode_pembayaran);
    }
}
</script>
      <!-- JS -->
   </body>
</html>
