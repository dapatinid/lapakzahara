<?php
   // Ganti dengan user_id yang Anda inginkan
   $ti_user_id = $iduser;
   
   // Query untuk menghitung total harga terjual (harga produk - diskon + ongkir)
   $query = "SELECT SUM((invoice.harga_i - (invoice.harga_i * CAST(invoice.diskon_i AS DECIMAL) / 100)) * CAST(invoice.jumlah AS DECIMAL) + invoice.harga_ongkir) AS total_terjual
             FROM invoice
             INNER JOIN iklan ON invoice.id_iklan = iklan.id
             WHERE iklan.user_id = $ti_user_id AND invoice.tipe_progress = 'Selesai'";
   $result = $server->query($query);
   
   $harga_terjual = null;
   
   if ($result) {
      $row = mysqli_fetch_assoc($result);
      $harga_terjual = $row['total_terjual'];
   }
   
   // Mengambil data akun untuk mendapatkan nama_pengguna
   $resultAkun = $server->query("SELECT nama_pengguna FROM akun WHERE id = $iduser"); // Ganti 'id_akun_anda' dengan ID akun yang ingin Anda gunakan
   
   if ($resultAkun) {
      $rowAkun = mysqli_fetch_assoc($resultAkun);
      $nama_pengguna = $rowAkun['nama_pengguna'];
   }
   
   // Tampilkan saldo pengguna
$querySaldo = "SELECT jumlah_saldo FROM saldo WHERE user_id = $ti_user_id";
$resultSaldo = $server->query($querySaldo);

if ($resultSaldo && $resultSaldo->num_rows > 0) {
    $rowSaldo = $resultSaldo->fetch_assoc();
    $saldo_pengguna = $rowSaldo['jumlah_saldo'];
} else {
    $saldo_pengguna = 0; // Tidak ada saldo tersedia
}

   ?>
<div class="header_responsive_admin" onclick="show_box_menu_admin()">
   <i class="fas fa-bars"></i>
   <p>Navigasi</p>
</div>
<div class="box_menu_admin" id="box_menu_admin">
   <div class="menu_admin">
      <div class="info_user">
         <img src="<?php echo $url; ?>/assets/image/profil-toko/<?php echo $profile['logo_toko']; ?>">
         <div class="box_data_user">
            <h1><?php echo $profile['nama_toko']; ?> <?php if ($profile['verifikasi_toko'] == 'Ya') { ?>
               <img id="img-verif" src="../../assets/icons/verifikasi-toko.png">
               <?php } ?>
            </h1>
            <p><?php echo $profile['email']; ?></p>
            <p>
            <h5 style="font-size: 14px;color:var(--black);font-weight: 600;">Saldo <a href="<?php echo $url; ?>/store/saldo">Rp<?php echo number_format($saldo_pengguna, 0, ".", "."); ?></a></h5>
            </p>
         </div>
      </div>
      <div class="menu_user_info">
         <!--
            <a href="<?php echo $url; ?>/store/saldo">
                  <div class="isi_menu_user_info">
                     <div class="<?php if ($page_admin == 'saldo') {
               echo 'menu_list_isi_active';
               } else {
               echo 'menu_list_isi';
               } ?>">
                        <div class="box_icon_menu_list_isi">
                           <i class="ri-wallet-2-fill"></i>
                        </div>
                        <p>Dompet</p>
                        <h5 style="font-size: 14px;font-weight: 600;">Rp<?php echo number_format($saldo_pengguna, 0, ".", "."); ?></h5>
                     </div>
                  </div>
               </a>
               -->
         <a href="<?php echo $url; ?>/store">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'dashboard') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-th-large"></i>
                  </div>
                  <p>Dashboard</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/product">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'produk') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-box"></i>
                  </div>
                  <p>Produk</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/category" style="display: none;">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'kategori') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-th-list"></i>
                  </div>
                  <p>Kategori</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/flashsale" style="display: none;">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'flashsale') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-bolt"></i>
                  </div>
                  <p>Flash Sale</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/promotion" style="display: none;">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'promo') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-sticky-note"></i>
                  </div>
                  <p>Spanduk</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/users" style="display: none;">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'user') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-users-cog"></i>
                  </div>
                  <p>Pengguna</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/transaction">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'transaksi') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-shopping-bag"></i>
                  </div>
                  <p>Transaksi</p>
                  <?php if (isset($jumlahPesanan['Belum Bayar']) && $jumlahPesanan['Belum Bayar'] !== 0) : ?>
                  <div class="icowls1">
                     <h4><?php echo $jumlahPesanan['Belum Bayar']; ?></h4>
                  </div>
                  <?php endif; ?>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/voucher">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'voucher') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-tag"></i>
                  </div>
                  <p>Voucher</p>
                  
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/chat">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'chat') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-comment-alt"></i>
                  </div>
                  <p>Chatting</p>
                  <?php
                     if ($cek_chat_header) {
                     ?>
                  <div class="icowls1">
                     <h4>
                        <?php echo $cek_chat_header; ?>
                     </h4>
                  </div>
                  <?php
                     }
                     ?>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/laporan">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'laporan') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-book"></i>
                  </div>
                  <p>Laporan</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/store/settings">
            <div class="isi_menu_user_info">
               <div class="<?php if ($page_admin == 'pengaturan') {
                  echo 'menu_list_isi_active';
                  } else {
                  echo 'menu_list_isi';
                  } ?>">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-cog"></i>
                  </div>
                  <p>Pengaturan</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <!--<a href="<?php echo $url; ?>/admin/deliveryman">
            <div class="<?php if ($page_admin == 'deliveryman') {
               echo 'menu_list_isi_active';
               } else {
               echo 'menu_list_isi';
               } ?>">
                <div class="box_icon_menu_list_isi">
                    <i class="fas fa-truck"></i>
                </div>
                <p>Delivery Man</p>
            </div>
            </a> -->
         <!--  <a href="<?php echo $url; ?>/admin/store">
            <div class="<?php if ($page_admin == 'store') {
               echo 'menu_list_isi_active';
               } else {
               echo 'menu_list_isi';
               } ?>">
                <div class="box_icon_menu_list_isi">
                    <i class="fas fa-store"></i>
                </div>
                <p>Store</p>
            </div>
            </a> -->
      </div>
      <div style="margin-top: 8px;
         border-bottom: 1px solid var(--border-grey);
         margin-bottom: 10px;"></div>
      <div class="menu_user_info">
         <a href="<?php echo $url; ?>/@<?php echo $nama_pengguna; ?>" style="margin-top: -8px;">
            <div class="isi_menu_user_info">
               <div class="menu_list_isi">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-eye"></i>
                  </div>
                  <p>Lihat Toko</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
         <a href="<?php echo $url; ?>/system/logout.php">
            <div class="isi_menu_user_info">
               <div class="menu_list_isi">
                  <div class="box_icon_menu_list_isi">
                     <i class="fas fa-sign-out-alt"></i>
                  </div>
                  <p>Log Out</p>
                  <i class="ri-arrow-right-s-line"></i>
               </div>
            </div>
         </a>
      </div>
   </div>
</div>
<style>
   .icowls1 {
   width: 17px;
   height: 17px;
   border-radius: 17px;
   background-color: var(--orange);
   margin: 3.3px;
   font-size: 11px;
   color: var(--white);
   font-weight: 400;
   display: flex;
   align-items: center;
   justify-content: center;
   }
   @media screen and (max-width: 768px) {
   .icowls1 {
   margin-top: 0px;
   }
   }
   @import url('../all.css');
   .profile {
   width: 100%;
   margin-top: 0px;
   display: flex;
   flex-direction: row;
   align-items: start;
   }
   .user_info {
   width: 320px;
   padding: 20px;
   padding-bottom: 1px;
   background-color: var(--white);
   box-sizing: border-box;
   margin-right: 30px;
   }
   .order_menu {
   flex: 1;
   }
   /* WISHLIST */
   .icowls {
   width: 17px;
   height: 17px;
   border-radius: 17px;
   background-color: var(--orange);
   margin-right: 5px;
   font-size: 11px;
   color: var(--white);
   font-weight: 400;
   display: flex;
   align-items: center;
   justify-content: center;
   }
   .icowls1 {
   width: 17px;
   height: 17px;
   border-radius: 17px;
   background-color: var(--orange);
   margin: 3.3px;
   font-size: 11px;
   color: var(--white);
   font-weight: 400;
   display: flex;
   align-items: center;
   justify-content: center;
   }
   .notif_on {
   position: absolute;
   width: 17px;
   height: 17px;
   border-radius: 17px;
   background-color: var(--orange);
   font-size: 11px;
   color: var(--white);
   font-weight: 500;
   display: flex;
   align-items: center;
   justify-content: center;
   margin-left: 30px;
   margin-top: -3px;
   border: 2px solid var(--white);
   }
   /* USER INFO */
   .info_user {
   width: 100%;
   display: flex;
   flex-direction: row;
   align-items: center;
   padding-bottom: 25px;
   border-bottom: 1px solid var(--semi-grey);
   }
   .info_user img {
   width: 45px;
   height: 45px;
   border-radius: 45px;
   object-fit: cover;
   }
   .box_data_user {
   flex: 1;
   margin-left: 16px;
   }
   .box_data_user h1 {
   font-size: 15px;
   font-weight: 500;
   color: var(--black);
   }
   .box_data_user p {
   font-size: 12px;
   color: var(--grey);
   margin-top: 1px;
   }
   .menu_user_info {
   width: 100%;
   display: grid;
   grid-template-columns: 1fr;
   grid-gap: 1px;
   background-color: var(--semi-grey);
   /* margin-top: 20px; */
   }
   .isi_menu_user_info {
   width: 100%;
   height: 45px;
   background-color: var(--white);
   /* background-color: red; */
   display: flex;
   flex-direction: row;
   align-items: center;
   cursor: pointer;
   color: var(--black);
   transition: 0.2s;
   }
   .isi_menu_user_info:hover {
   color: var(--orange);
   }
   .isi_menu_user_info p {
   font-size: 14px;
   font-weight: 500;
   flex: 1;
   }
   /* ORDER MENU */
   .box_select_order_menu2 {
   width: 100%;
   overflow: auto;
   }
   .box_select_order_menu {
   width: 100%;
   }
   .select_order_menu {
   width: 100%;
   background-color: var(--white);
   display: flex;
   flex-direction: row;
   }
   .isi_select_order_menu {
   flex: 1;
   padding: 15px 0;
   display: flex;
   justify-content: center;
   cursor: pointer;
   font-size: 15px;
   color: var(--semi-black);
   font-weight: 500;
   border-top: 2px solid var(--white);
   border-bottom: 2px solid var(--white);
   }
   .isi_select_order_menu_active {
   flex: 1;
   padding: 15px 0;
   display: flex;
   justify-content: center;
   cursor: pointer;
   font-size: 15px;
   color: var(--orange);
   font-weight: 500;
   border-top: 2px solid var(--white);
   border-bottom: 2px solid var(--orange);
   }
   .res_order_menu {
   width: 100%;
   box-sizing: border-box;
   margin-top: 15px;
   }
   .box_loading_order_menu {
   width: 100%;
   padding: 230px 0;
   background-color: var(--white);
   display: none;
   }
   .loading_order_menu {
   width: 35px;
   height: 35px;
   margin-bottom: 0;
   }
   #res_order_menu {
   display: none;
   }
   #load_paging_belum_bayar_id {
   display: none;
   }
   #paging_belum_bayar {
   display: none;
   }
   #paging_dikemas {
   display: none;
   }
   #paging_dikirim {
   display: none;
   }
   #paging_selesai {
   display: none;
   }
   #paging_dibatalkan {
   display: none;
   }
   /* ORDER MENU MOBILE */
   .mo_order_menu {
   width: 100%;
   /* background-color: red; */
   box-sizing: border-box;
   padding: 20px 0;
   border-bottom: 1px solid var(--border-grey);
   display: none;
   }
   .box_mo_order_menu {
   width: 100%;
   display: grid;
   grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
   }
   .isi_mo_order_menu {
   width: 100%;
   display: flex;
   flex-direction: column;
   align-items: center;
   }
   .isi_mo_order_menu img {
   height: 30px;
   }
   .isi_mo_order_menu p {
   font-size: 11px;
   margin-top: 10px;
   font-weight: 500;
   color: var(--semi-black);
   }
   .box_header_order_menu_mobile {
   display: none;
   width: 100%;
   flex-direction: row;
   justify-content: space-between;
   align-items: center;
   padding: 15px 15px 3px 15px;
   box-sizing: border-box;
   }
   .box_header_order_menu_mobile p {
   font-weight: 500;
   font-size: 15px;
   color: var(--black);
   }
   .box_header_order_menu_mobile i {
   font-size: 23px;
   color: var(--orange);
   }
   .box_bp_produk {
   width: 100%;
   height: 100vh;
   background-color: var(--bg-transparent-black);
   position: fixed;
   z-index: 3;
   display: none;
   justify-content: center;
   align-items: center;
   }
   .bp_produk {
   width: 450px;
   padding: 30px;
   box-sizing: border-box;
   background-color: var(--white);
   position: relative;
   }
   .bp_produk h1 {
   font-size: 17px;
   text-align: center;
   color: var(--black);
   }
   .box_star_bp {
   width: 100%;
   margin-top: 30px;
   display: flex;
   flex-direction: row;
   justify-content: center;
   align-items: center;
   }
   .box_star_bp i {
   color: var(--border-grey);
   font-size: 35px;
   margin: 0 6px;
   cursor: pointer;
   }
   .box_deskripsi_bp {
   width: 100%;
   margin-top: 30px;
   }
   .box_close_bp {
   width: 25px;
   height: 25px;
   background-color: var(--red);
   position: absolute;
   right: 5px;
   top: 5px;
   color: var(--white);
   display: flex;
   align-items: center;
   justify-content: center;
   cursor: pointer;
   border-radius: 3px;
   }
   #load_bp_send {
   display: none;
   }
   .bpld_red {
   font-size: 14px;
   font-weight: 500;
   color: var(--red);
   margin-top: 10px;
   display: none;
   }
   .box_upload_gambar_rating {
   margin-top: 10px;
   }
   #p_gambar_bp_a {
   color: var(--red);
   display: none;
   }
   .load_paging {
   width: 150px;
   margin: 15px auto;
   }
   .loading_paging {
   width: 100%;
   display: flex;
   align-items: center;
   justify-content: center;
   margin-top: 15px;
   display: none;
   }
   .loading_paging img {
   height: 30px;
   }
   @media only screen and (max-width: 900px) {
   .profile {
   margin-top: 0;
   }
   .order_menu {
   display: none;
   width: 100%;
   position: absolute;
   }
   .box_header_order_menu_mobile {
   display: flex;
   }
   .box_select_order_menu {
   position: fixed;
   -webkit-box-shadow: 0px 0px 4px 0px var(--border-grey);
   -moz-box-shadow: 0px 0px 4px 0px var(--border-grey);
   box-shadow: 0px 0px 4px 0px var(--border-grey);
   background-color: var(--white);
   }
   .box_select_order_menu2 {
   overflow: auto;
   -ms-overflow-style: none;
   scrollbar-width: none;
   }
   .box_select_order_menu2::-webkit-scrollbar {
   display: none;
   }
   .select_order_menu {
   width: 540px;
   }
   .isi_select_order_menu {
   padding: 12px 0;
   font-size: 12px;
   }
   .isi_select_order_menu_active {
   padding: 12px 0;
   font-size: 12px;
   }
   .res_order_menu {
   /* display: none; */
   margin-top: 95px;
   margin-bottom: 65px;
   }
   .user_info {
   width: 100%;
   /* background-color: red; */
   margin-right: 0;
   /* display: none; */
   }
   .info_user {
   border-bottom: 1px solid var(--border-grey);
   }
   .menu_user_info {
   background-color: var(--border-grey);
   }
   .isi_menu_user_info {
   height: 50px;
   }
   .mo_order_menu {
   display: block;
   }
   .icowls1{
   margin-top:0px;
   }
   }
   @media only screen and (max-width: 600px) {
   .bp_produk {
   width: 90%;
   padding: 25px;
   }
   .bp_produk h1 {
   font-size: 15px;
   }
   }
</style>
<script src="<?php echo $url; ?>/assets/js/admin/menu.js"></script>
