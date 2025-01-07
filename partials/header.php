<header>
   <div class="tag_header">
      <div class="width">
         <div class="isi_tag_header">
            <div class="isi_tag_header_lr">
               <div class="d_isi_tag_header_lr">
                  <img src="<?php echo $url; ?>/assets/icons/hedaer-tag/safety.png">
                  <p>Terpercaya, Aman &amp; Amanah</p>
               </div>
               <div class="d_isi_tag_header_lr">
                  <img src="<?php echo $url; ?>/assets/icons/hedaer-tag/warr.png">
                  <p>Garansi Uang Kembali 100%</p>
               </div>
               <div class="d_isi_tag_header_lr">
                  <img src="<?php echo $url; ?>/assets/icons/hedaer-tag/deliv.png">
                  <p>Gratis Ongkir</p>
               </div>
               <div class="d_isi_tag_header_lr">
               </div>
            </div>
            <div class="isi_tag_header_lr">
               <div class="d_isi_tag_header_lr">
                  <?php
$sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
$data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);

// Mendapatkan status_toko pengguna
$status_toko = $server->query("SELECT `status_toko` FROM `akun` WHERE `id`='$iduser'");
$data_status_toko = mysqli_fetch_array($status_toko);

if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
    if ($data_status_toko['status_toko'] == 'Aktif') {
        ?>
         
        <?php
    } else {
        echo '<p>Belum Punya Toko?</p>';
    }
}
?>
               </div>
               
               <div class="d_isi_tag_header_lr">
                   <img src="https://icon-library.com/images/white-shopping-cart-icon-png/white-shopping-cart-icon-png-19.jpg">
                  <?php
$sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
$data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);

// Mendapatkan status_toko pengguna
$status_toko = $server->query("SELECT `status_toko` FROM `akun` WHERE `id`='$iduser'");
$data_status_toko = mysqli_fetch_array($status_toko);

if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
    if ($data_status_toko['status_toko'] == 'Aktif') {
        ?>
        <a href="../store/">Toko Saya</a>
        <?php
    } else {
        echo '<a href="../store/start">Buka Toko</a>';
    }
}
?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="width">
      <div class="header">
         <a href="<?php echo $url; ?>">
            <div class="logo_header">
               <img src="<?php echo $url; ?>/assets/icons/<?php echo $logo; ?>" class="svg_logo_header">
               <p><?php echo strtoupper($title_name); ?> - <?php echo $slogan; ?></p>
            </div>
         </a>
         <?php if (isset($page) && $page === "HOME") : ?>
         <!-- Jika halaman HOME -->
         <a href="<?php echo $url; ?>/">
            <!-- Tambahkan link ke halaman beranda -->
            <div class="box_back_button">
               <img src="<?php echo $url; ?>/assets/icons/<?php echo $favicon; ?>">
            </div>
         </a>
         <?php endif; ?>
         <?php
            if (isset($page)) {
                if ($page != "HOME") {
            ?>
         <div class="box_back_button" onclick="gobackheader()">
            <i class="ri-arrow-left-line"></i>
         </div>
         <?php
            }
            } else {
            ?>
         <div class="box_back_button" onclick="gobackheader()">
            <i class="ri-arrow-left-line"></i>
         </div>
         <?php
            }
            ?>
         <div class="box_search_header">
            <form action="<?php echo $url; ?>/search.php" method="get">
               <div class="search_header">
                  <?php
                     // Tetapkan nilai berdasarkan kondisi halaman
                     $value = '';
                     $placeholder = isset($slogan) ? htmlspecialchars(strtoupper($title_name)." | ".$slogan." . ") : '';
                     
                     if (basename($_SERVER['SCRIPT_NAME']) === 'search.php') {
                         // Jika di halaman search.php, gunakan kata kunci sebagai nilai
                         $value = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
                     }
                     ?>
                  <input type="text" name="keyword" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" id="search_header" oninput="SearchHeader('<?php echo $url; ?>')">
                  <div class="box_icon_search"><button type="submit"><i class="ri-search-line"></i></button></div>
               </div>
            </form>
         </div>
         <style>
            .box_icon_search button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            }
         </style>
         <div class="menu_header">
            <div class="profile_menu_header">
               <?php
                  if (isset($_COOKIE['login'])) {
                  ?>
               <a href="<?php echo $url; ?>/cart">
                  <div class="box_icon_menu_header">
                     <?php
                        if ($cek_cart_header) {
                        ?>
                     <p><?php echo $cek_cart_header; ?></p>
                     <?php
                        }
                        ?>
                     <i class="ri-shopping-bag-line"></i>
                  </div>
               </a>
               <a href="<?php echo $url; ?>/notification">
                  <div class="box_icon_menu_header">
                     <?php
                        if ($cek_notif_header) {
                        ?>
                     <p><?php echo $cek_notif_header; ?></p>
                     <?php
                        }
                        ?>
                     <h5 class="ri-notification-3-line"></h5>
                  </div>
               </a>
               <a href="<?php echo $url; ?>/chat">
                  <div class="box_icon_menu_header">
                     <?php
                        if ($cek_chat_header) {
                        ?>
                     <p><?php echo $cek_chat_header; ?></p>
                     <?php
                        }
                        ?>
                     <h5 class="ri-chat-1-line"></h5>
                  </div>
               </a>
               <a href="<?php echo $url; ?>/profile/user">
                  <div class="box_img_menu_header">
                     <img src="<?php echo $url; ?>/assets/image/profile/<?php echo $profile['foto']; ?>">
                  </div>
               </a>
               <?php
                  } else {
                  ?>
               <a href="<?php echo $url; ?>/login">
                  <div class="box_img_menu_header">
                     <img src="<?php echo $url; ?>/assets/image/profile/user.png">
                  </div>
               </a>
               <?php
                  }
                  ?>
            </div>
         </div>
      </div>
   </div>
</header>
<div class="back_header"></div>
<script src="<?php echo $url; ?>/assets/js/partials/header.js"></script>

<div class="loading-container" id="loadingContainer">
        <!-- Ganti animasi loading dengan logo -->
        <img src="<?php echo $url; ?>/assets/icons/loading.gif" alt="Logo" class="logo">
    </div>
    <div class="main-content" id="mainContent">
