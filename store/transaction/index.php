<?php
   include '../../config.php';
   
   $page_admin = 'transaksi';
   
   $sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
   $data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);
   
   if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
       if (isset($_COOKIE['login'])) {
           if ($profile['provinsi_user'] == '') {
               header("location: " . $url . "/store/start");
           }
       } else {
           header("location: " . $url . "login/");
       }
   } else {
       header("location: " . $url);
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Tambahkan ini di dalam tag <head> untuk mengaktifkan JavaScript -->
<script type="text/javascript">
    function pilih_delman(element, jumdelman) {
        var namedaajd = element.getAttribute("data-nama");

        // Hapus warna latar belakang dari semua elemen
        var elements = document.querySelectorAll('.isi_box_delman');
        elements.forEach(function(el) {
            el.style.background = 'var(--semi-grey)';
            el.style.color = 'var(--black)';
        });

        // Atur warna latar belakang pada elemen yang dipilih
        element.style.background = 'var(--orange)';
        element.style.color = 'var(--white)';

        // Atur kurir yang dipilih ke input tersembunyi
        document.getElementById("kurir_toko_manual").value = namedaajd;
    }
</script>
<title>Transaksi Toko <?php echo $profile['nama_lengkap']; ?> | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <!-- META SEO -->
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/admin/transaction/index.css">
   </head>
   <body>
      <!-- VIEW DETAIL INVOICE -->
      <div class="back_vd_iv" id="back_vd_iv_vi">
         <div class="vd_iv" id="vd_iv">
         </div>
      </div>
      <!-- VIEW DETAIL INVOICE -->
      <!-- UPLOAD RESI -->
<div class="back_up_ri" id="back_up_ri">
    <div class="up_ri">
        <h1>Tambahkan Resi Pengiriman</h1>
        <input type="hidden" id="idinvoice_pss">
        <input type="text" class="input" id="resi_pengiriman_v" placeholder="Resi Pengiriman">
        <h5>Kurir Pengiriman</h5>
        <div class="box_delman">
            <?php
            $s_kpto = $server->query("SELECT * FROM `setting_kurir_toko_orang`");
            $jum_kpto = mysqli_num_rows($s_kpto);
            while ($data_s_kpto = mysqli_fetch_assoc($s_kpto)) {
            ?>
                <div class="isi_box_delman" data-id="<?php echo $data_s_kpto['id']; ?>" data-nama="<?php echo $data_s_kpto['nama']; ?>" onclick="pilih_delman(this, '<?php echo $jum_kpto; ?>')"><?php echo $data_s_kpto['nama']; ?></div>
            <?php
            }
            ?>
        </div>
        <input type="hidden" id="kurir_toko_manual">
        <div class="button" onclick="add_resi_pengiriman()">
            <p>Tambahkan</p>
        </div>
    </div>
</div>
<!-- UPLOAD RESI -->
      <div class="admin">
         <?php include '../partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Transaksi</h1>
            <div class="isi_content_admin">
               <!-- CONTENT -->
               <div class="list_transaksi_admin">
    <div class="isi_list_transaksi_admin_active" id="belum_bayar">
        <p>Belum Dibayar</p>
        <?php if (isset($jumlahPesanan['Belum Bayar']) && $jumlahPesanan['Belum Bayar'] !== 0) : ?>
            <div class="icowls1">
                <h4><?php echo $jumlahPesanan['Belum Bayar']; ?></h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="isi_list_transaksi_admin" id="sudah_bayar">
        <p>Sudah Dibayar</p>
        <?php if (isset($jumlahPesanan['Dikemas']) && $jumlahPesanan['Dikemas'] !== 0) : ?>
            <div class="icowls1">
                <h4><?php echo $jumlahPesanan['Dikemas']; ?></h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="isi_list_transaksi_admin" id="dalam_perjalanan">
        <p>Dikirim</p>
        <?php if (isset($jumlahPesanan['Dikirim']) && $jumlahPesanan['Dikirim'] !== 0) : ?>
            <div class="icowls1">
                <h4><?php echo $jumlahPesanan['Dikirim']; ?></h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="isi_list_transaksi_admin" id="selesai">
        <p>Selesai</p>
        <?php if (isset($jumlahPesanan['Selesai']) && $jumlahPesanan['Selesai'] !== 0) : ?>
            <div class="icowls1">
                <h4><?php echo $jumlahPesanan['Selesai']; ?></h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="isi_list_transaksi_admin" id="dibatalkan">
        <p>Dibatalkan</p>
        <?php if (isset($jumlahPesanan['Dibatalkan']) && $jumlahPesanan['Dibatalkan'] !== 0) : ?>
            <div class="icowls1">
                <h4><?php echo $jumlahPesanan['Dibatalkan']; ?></h4>
            </div>
        <?php endif; ?>
    </div>
</div>

               <div class="box_res_transaksi_admin">
                  <div class="loading_res_transaksi_admin" id="loading_res_transaksi_admin">
                     <center><img src="../../assets/icons/loading-o.svg"></center>
                  </div>
                  <div class="res_transaksi_admin" id="res_transaksi_admin"></div>
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
                     <img src="../../assets/icons/loading-o.svg" alt="">
                  </div>
               </div>
               <!-- CONTENT -->
            </div>
         </div>
      </div>
      <input type="hidden" value="store" id="tipe_user_vt">
      <!-- JS -->
      <script src="../../assets/js/admin/transaction/index.js"></script>
      <!-- JS -->
      <?php include '../../partials/bottom-navigation.php'; ?>
   </body>
</html>
