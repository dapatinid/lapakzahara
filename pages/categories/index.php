<?php
   include '../../config.php';
   $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
      <title>Jual Produk Terlaris Terbaru & Terlengkap | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../../partials/seo.php'; ?>
      <!-- META SEO -->
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/popular/index.css">
      <link rel="stylesheet" href="../../assets/css/cat-brand.css">
   </head>
   <body>
      <!-- HEADER -->
      <?php include '../../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
        <!-- KATEGORI -->
        <div class="box_judul">
            <p>Index Kategori</p> 
         </div>
        <div class="box_kategori">
            <div class="kategori">
                <?php
                $kategori = $server->query("SELECT * FROM `kategori` ORDER BY `nama` ASC");
                while ($kategori_data = mysqli_fetch_assoc($kategori)) {
                ?>
                    <a href="<?php echo $url; ?>/category/<?php echo $kategori_data['slug']; ?>">
                        <div class="isi_kategori">
                            <img src="../assets/icons/category/compressed/<?php echo $kategori_data['icon']; ?>">
                            <p><?php echo $kategori_data['nama']; ?></p>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- KATEGORI -->
      </div>
      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include '../../partials/footer.php'; ?>
      <?php include '../../partials/bottom-navigation.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="../../assets/js/popular/index.js"></script>
      <!-- JS -->
   </body>
</html>
