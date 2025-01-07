<?php
   include '../config.php';
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Tentang Kami | <?php echo $title_name; ?></title>
      <title>Hubungi Kami | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->
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
            <div style='margin: 0px; padding: 0px; position: relative; color: rgb(0, 0, 0); font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;'>
               <div style="padding: 0px; text-align: left;">
                  <h2 style="margin: 0px 0px; padding: 0px; font-weight: 600; font-size: 14px; text-align: left;"><span style="font-size: 23px;">Tentang Kami</span></h2>
                  <p><br></p>
                  <?php
                     $s_tk_ab = $server->query("SELECT * FROM `setting_about` WHERE `id`='2' ");
                     $data_s_tk_ab = mysqli_fetch_assoc($s_tk_ab);
                     echo $data_s_tk_ab['isi'];
                     ?>
               </div>
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
