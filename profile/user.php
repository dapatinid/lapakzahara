<?php
   include '../config.php';
   $page = 'PROFILE';
   
   // CEK USER LOGIN
   if (!isset($_COOKIE['login'])) {
       header('Location: ../login/');
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
      <title>Profil <?php echo $profile['nama_lengkap']; ?> | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
      <link rel="stylesheet" href="../assets/css/profile/user.css">
   </head>
   <body>
      <!-- HAPUS INVOICE -->
<style>
    .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    text-align: center;
}

.modal-content p {
    margin-bottom: 20px;
}

button {
    padding: 8px 16px;
    margin: 0 10px;
}

</style>
<!-- HAPUS INVOICE -->
<div id="myModal" class="box_bp_produk" style="display: none;">
    <div class="bp_produk">
        <div class="box_close_bp" onclick="tutupKonfirmasi()">
            <i class="fas fa-times"></i>
        </div>
        <h1>Konfirmasi Hapus Pesanan</h1>
        <p style="text-align: center;">Apakah Anda yakin ingin menghapus pesanan ini dari daftar pesanan?</p>
        <div class="box_deskripsi_bp">
            <div class="button" onclick="hapusInvoice()">
                <p id="t_bp_hapus">Hapus</p>
                <img src="../assets/icons/loading-w.svg" id="load_bp_hapus" style="display: none;">
            </div>
            <input type="hidden" id="id_inv_hapus">
        </div>
    </div>
</div>
<!-- HAPUS INVOICE -->



    <!-- BERI PENILAIAN -->
      <div class="box_bp_produk" id="box_bp_produk">
         <div class="bp_produk">
            <div class="box_close_bp" onclick="close_bp()">
               <i class="fas fa-times"></i>
            </div>
            <h1>Beri Penilaian Untuk Produk Ini</h1>
            <div class="box_star_bp">
               <i class="fas fa-star" id="star_c1"></i>
               <i class="fas fa-star" id="star_c2"></i>
               <i class="fas fa-star" id="star_c3"></i>
               <i class="fas fa-star" id="star_c4"></i>
               <i class="fas fa-star" id="star_c5"></i>
            </div>
            <input type="hidden" id="star_bp_inp">
            <div class="box_deskripsi_bp">
               <textarea class="input" id="deskripsi_bp_inp" rows="3" placeholder="Tambahkan Deskripsi..."></textarea>
            </div>
            <div class="box_upload_gambar_rating">
               <p class="p_input" id="p_gambar_bp_a">File harus berformat jpg/png</p>
               <input type="file" class="input" id="gambar_bp_a" onchange="pilih_gambar_bp()" placeholder="Tambahkan Gambar">
            </div>
            <input type="hidden" id="id_inv_bp">
            <p class="bpld_red" id="bpld_red">Berikan penilaian</p>
            <div class="box_deskripsi_bp">
               <div class="button" onclick="kirim_penilaian_bp()">
                  <p id="t_bp_send">Kirimkan</p>
                  <img src="../assets/icons/loading-w.svg" id="load_bp_send">
               </div>
            </div>
         </div>
      </div>
      <!-- BERI PENILAIAN -->
      <!-- HEADER -->
      <?php include '../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <div class="profile">
            <div class="user_info" id="user_info">
               <div class="info_user">
                  <img src="../assets/image/profile/<?php echo $profile['foto']; ?>" alt="">
                  <div class="box_data_user">
                     <h1><?php echo $profile['nama_lengkap']; ?> <?php 
               if ($profile['verifikasi_user'] == 'Ya') {
                  echo '<img id="img-verif" src="../../assets/icons/verifikasi-user.png">';
              }
               ?></h1>
                     <p><?php echo $profile['email']; ?></p>
                     <p><a href="../system/logout.php">Logout</a></p>
                  </div>
               </div>
               <div class="mo_order_menu">
    <div class="box_mo_order_menu">
        <div class="isi_mo_order_menu" id="c_mo_belum_bayar">
            <?php if ($jumlahPesananTerbaru['Belum Bayar'] != 0) : ?>
                <div class="notif_on">
                    <?php echo $jumlahPesananTerbaru['Belum Bayar']; ?>
                </div>
            <?php endif; ?>
            <img src="../assets/icons/belum-bayar.svg" alt="">
            <p>Belum Bayar</p>
        </div>
        <div class="isi_mo_order_menu" id="c_mo_dikemas">
            <?php if ($jumlahPesananTerbaru['Dikemas'] != 0) : ?>
                <div class="notif_on">
                    <?php echo $jumlahPesananTerbaru['Dikemas']; ?>
                </div>
            <?php endif; ?>
            <img src="../assets/icons/dikemas.svg" alt="">
            <p>Dikemas</p>
        </div>
        <div class="isi_mo_order_menu" id="c_mo_dikirim">
            <?php if ($jumlahPesananTerbaru['Dikirim'] != 0) : ?>
                <div class="notif_on">
                    <?php echo $jumlahPesananTerbaru['Dikirim']; ?>
                </div>
            <?php endif; ?>
            <img src="../assets/icons/dikirim.svg" alt="">
            <p>Dikirim</p>
        </div>
        <div class="isi_mo_order_menu" id="c_mo_selesai">
            <?php if ($jumlahPesananTerbaru['Selesai'] != 0) : ?>
                <div class="notif_on">
                    <?php echo $jumlahPesananTerbaru['Selesai']; ?>
                </div>
            <?php endif; ?>
            <img src="../assets/icons/selesai.svg" alt="">
            <p>Selesai</p>
        </div>
        <div class="isi_mo_order_menu" id="c_mo_dibatalkan">
            <?php if ($jumlahPesananTerbaru['Dibatalkan'] != 0) : ?>
                <div class="notif_on">
                    <?php echo $jumlahPesananTerbaru['Dibatalkan']; ?>
                </div>
            <?php endif; ?>
            <img src="../assets/icons/dibatalkan.svg" alt="">
            <p>Dibatalkan</p>
        </div>
    </div>
</div>

               <div class="menu_user_info">
                  <?php
$sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
$data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);

// Mendapatkan status_toko pengguna
$status_toko = $server->query("SELECT `status_toko` FROM `akun` WHERE `id`='$iduser'");
$data_status_toko = mysqli_fetch_array($status_toko);

if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
    if ($data_status_toko['status_toko'] == 'Aktif') {
        ?>
        <a href="../store/">
            <div class="isi_menu_user_info">
                <p>Toko Saya</p>
                <i class="ri-arrow-right-s-line"></i>
            </div>
        </a>
        <?php
    } else {
        echo '<a href="../store/start">
            <div class="isi_menu_user_info">
                <p>Buka Toko</p>
                <i class="ri-arrow-right-s-line"></i>
            </div>
        </a>';
    }
}
?>

    

                  <a href="../wishlist/">
                     <div class="isi_menu_user_info">
                        <p>Wishlist</p>
                        <?php
                           if ($cek_favorit_header) {
                           ?>
                        <div class="icowls">
                           <h4><?php echo $cek_favorit_header; ?></h4>
                        </div>
                        <?php
                           }
                           ?>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  <a href="edit">
                     <div class="isi_menu_user_info">
                        <p>Edit Profil</p>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  <a href="../help/bantuan.php">
                     <div class="isi_menu_user_info">
                        <p>Bantuan</p>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  <a href="../help/syarat-ketentuan.php">
                     <div class="isi_menu_user_info">
                        <p>Syarat dan Ketentuan</p>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  <a href="../help/kebijakan-privasi.php">
                     <div class="isi_menu_user_info">
                        <p>Kebijakan Privasi</p>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  <a href="../help/tentang-kami.php">
                     <div class="isi_menu_user_info">
                        <p>Tentang Kami</p>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  <a href="../help/hubungi-kami.php">
                     <div class="isi_menu_user_info">
                        <p>Hubungi Kami</p>
                        <i class="ri-arrow-right-s-line"></i>
                     </div>
                  </a>
                  
               </div>
            </div>
            <div class="order_menu" id="order_menu">
    <div class="box_select_order_menu">
        <div class="box_header_order_menu_mobile">
            <p>Pesanan Saya</p>
            <i class="ri-close-line" id="close_order_menu"></i>
        </div>
        <div class="box_select_order_menu2">
            <div class="select_order_menu">
                <div class="isi_select_order_menu_active" id="belum_bayar">
                    <p>Belum Bayar</p>
                    <?php if ($jumlahPesananTerbaru['Belum Bayar'] > 0) : ?>
                        <div class="icowls1">
                            <h4><?php echo $jumlahPesananTerbaru['Belum Bayar']; ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="isi_select_order_menu" id="dikemas">
                    <p>Dikemas</p>
                    <?php if ($jumlahPesananTerbaru['Dikemas'] > 0) : ?>
                        <div class="icowls1">
                            <h4><?php echo $jumlahPesananTerbaru['Dikemas']; ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="isi_select_order_menu" id="dikirim">
                    <p>Dikirim</p>
                    <?php if ($jumlahPesananTerbaru['Dikirim'] > 0) : ?>
                        <div class="icowls1">
                            <h4><?php echo $jumlahPesananTerbaru['Dikirim']; ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="isi_select_order_menu" id="selesai">
                    <p>Selesai</p>
                    <?php if ($jumlahPesananTerbaru['Selesai'] > 0) : ?>
                        <div class="icowls1">
                            <h4><?php echo $jumlahPesananTerbaru['Selesai']; ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="isi_select_order_menu" id="dibatalkan">
                    <p>Dibatalkan</p>
                    <?php if ($jumlahPesananTerbaru['Dibatalkan'] > 0) : ?>
                        <div class="icowls1">
                            <h4><?php echo $jumlahPesananTerbaru['Dibatalkan']; ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

               <div class="res_order_menu">
                  <div class="box_loading_order_menu" id="loading_order_menu">
                     <center><img src="../assets/icons/loading-o.svg" class="loading_order_menu"></center>
                  </div>
                  <div id="res_order_menu"></div>
                  <!-- PAGING BELUM BAYAR -->
                  <div id="paging_belum_bayar">
                     <div id="res_paging_belum_bayar"></div>
                     <div class="load_paging" id="load_paging_belum_bayar_id">
                        <div class="button" onclick="load_paging_belum_bayar()">
                           <p>Lebih Banyak</p>
                        </div>
                     </div>
                     <input type="hidden" id="page_paging_belum_bayar" value="1">
                  </div>
                  <!-- PAGING DIKEMAS -->
                  <div id="paging_dikemas">
                     <div id="res_paging_dikemas"></div>
                     <div class="load_paging" id="load_paging_dikemas_id">
                        <div class="button" onclick="load_paging_dikemas()">
                           <p>Lebih Banyak</p>
                        </div>
                     </div>
                     <input type="hidden" id="page_paging_dikemas" value="1">
                  </div>
                  <!-- PAGING DIKIRIM -->
                  <div id="paging_dikirim">
                     <div id="res_paging_dikirim"></div>
                     <div class="load_paging" id="load_paging_dikirim_id">
                        <div class="button" onclick="load_paging_dikirim()">
                           <p>Lebih Banyak</p>
                        </div>
                     </div>
                     <input type="hidden" id="page_paging_dikirim" value="1">
                  </div>
                  <!-- PAGING SELESAI -->
                  <div id="paging_selesai">
                     <div id="res_paging_selesai"></div>
                     <div class="load_paging" id="load_paging_selesai_id">
                        <div class="button" onclick="load_paging_selesai()">
                           <p>Lebih Banyak</p>
                        </div>
                     </div>
                     <input type="hidden" id="page_paging_selesai" value="1">
                  </div>
                  <!-- PAGING DIBATALKAN -->
                  <div id="paging_dibatalkan">
                     <div id="res_paging_dibatalkan"></div>
                     <div class="load_paging" id="load_paging_dibatalkan_id">
                        <div class="button" onclick="load_paging_dibatalkan()">
                           <p>Lebih Banyak</p>
                        </div>
                     </div>
                     <input type="hidden" id="page_paging_dibatalkan" value="1">
                  </div>
                  <!-- LOADING PAGING -->
                  <div class="loading_paging" id="loading_paging">
                     <img src="../assets/icons/loading-o.svg" alt="">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="res"></div>
      <!-- CONTENT -->
      <!-- BOTTOM NAVIGATION -->
      <?php include '../partials/bottom-navigation.php'; ?>
      <!-- BOTTOM NAVIGATION -->
      <!-- FOOTER -->
      <?php include '../partials/footer.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="../assets/js/profile/index.js"></script>
      <!-- JS -->
   </body>
</html>
