<?php
   include '../config.php';
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Halaman Bantuan | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->
      <title>Hubungi Kami</title>
      <link rel="icon" href="../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../assets/css/help/articles.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include '../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <div class="articles">
            <h1 class="t_articles">Bantuan</h1>
            <p class="p_articles">Harap DM di social media kami jika anda butuh bantuan.</p>
            <div class="box_sosmed_hk">
               <?php
                  $select_social_hk = $server->query("SELECT * FROM `setting_footer`");
                  while ($data_social_hk = mysqli_fetch_assoc($select_social_hk)) {
                      if ($data_social_hk['link_social'] != '') {
                  ?>
               <a href="<?php echo $data_social_hk['link_social']; ?>" class="link" target="_blank">
                  <div class="isi_box_sosmed_hk">
                     <?php echo $data_social_hk['icon_social']; ?>
                     <p><?php echo $data_social_hk['name_social']; ?></p>
                  </div>
               </a>
               <?php
                  }
                  }
                  ?>
            </div>
         </div>
      </div>
      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include '../partials/footer.php'; ?>
      <?php include '../partials/bottom-navigation.php'; ?>
      <!-- FOOTER -->
   </body>
</html>
