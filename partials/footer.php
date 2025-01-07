</div>
<footer>
   <div class="width">
      <div class="footer">
         <div class="footer_grid">
            <h1>LAYANAN PELANGGAN</h1>
            <a href="<?php echo $url; ?>/help/bantuan">
               <p>Bantuan</p>
            </a>
            <a href="<?php echo $url; ?>/help/tentang-kami">
               <p>Tentang Kami</p>
            </a>
            <a href="<?php echo $url; ?>/help/hubungi-kami">
               <p>Hubungi Kami</p>
            </a>
            <a href="<?php echo $url; ?>/help/kebijakan-privasi">
               <p>Kebijakan Privasi</p>
            </a>
            <a href="<?php echo $url; ?>/help/syarat-ketentuan">
               <p>Syarat & Ketentuan</p>
            </a>
            
         </div>
         <div class="footer_grid">
            <h1>PEMBAYARAN</h1>
            <img src="<?php echo $url; ?>/assets/icons/footer/visa.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/bca.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/bni.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/bri.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/cimbniaga.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/mandiri.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/indomaret.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/alfamart.png">
         </div>
         <div class="footer_grid">
            <h1>PENGIRIMAN</h1>
            <img src="<?php echo $url; ?>/assets/icons/footer/jnt.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/jne.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/sicepat.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/ninja.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/anteraja.png">
            <img src="<?php echo $url; ?>/assets/icons/footer/gojek.svg">
            <img src="<?php echo $url; ?>/assets/icons/footer/grab.png">
         </div>
         <div class="footer_grid">
            <h1>IKUTI KAMI</h1>
            <div class="footer_sosmed">
               <?php
                  $select_social_footer = $server->query("SELECT * FROM `setting_footer`");
                  while ($data_social_footer = mysqli_fetch_assoc($select_social_footer)) {
                      if ($data_social_footer['link_social'] != '') {
                  ?>
               <a href="<?php echo $data_social_footer['link_social']; ?>" target="_blank">
                  <div class="isi_footer_sosmed">
                     <?php echo $data_social_footer['icon_social']; ?>
                     <?php echo $data_social_footer['name_social']; ?>
                  </div>
               </a> 
               <?php
                  }
                  }
                  ?>
            </div>
         </div>
      </div>
      <?php
         $s_fo_cr_f = $server->query("SELECT * FROM `setting_copyright` WHERE `id`='1' ");
         $data_s_fo_cr_f = mysqli_fetch_assoc($s_fo_cr_f);
         ?>
      <p class="copyright"><?php echo $data_s_fo_cr_f['name']; ?></p>
   </div>
</footer>
<script>
$(document).ready(function() {
    // Ketika halaman dimuat, set status pengguna menjadi online
    $.post('<?php echo $url; ?>/system/cronjob/update.php', { user_id: <?php echo $iduser; ?>, status: 'online' });

    // Ketika pengguna meninggalkan halaman atau menutup tab
    $(window).on('beforeunload', function() {
        // Atur status pengguna menjadi offline
        $.post('<?php echo $url; ?>/system/cronjob/update.php', { user_id: <?php echo $iduser; ?>, status: 'offline' });
    });
});
</script>
<script>
        document.addEventListener("DOMContentLoaded", function () {
    // Simulasi waktu loading (dapat disesuaikan)
    setTimeout(function () {
        // Sembunyikan elemen loading
        var loadingContainer = document.getElementById("loadingContainer");
        loadingContainer.style.opacity = 0;

        // Tampilkan konten utama
        var mainContent = document.getElementById("mainContent");
        mainContent.style.display = "block";

        // Hilangkan elemen loading setelah transisi selesai
        setTimeout(function () {
            loadingContainer.style.display = "none";
        }, 50); // Contoh waktu transisi 0.5 detik
    }, 150); // Contoh waktu loading selama 2 detik
});

</script>
