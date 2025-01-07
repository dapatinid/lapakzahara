<?php
include '../config.php';

$page = 'NOTIFIKASI';

$update_status_notification = $server->query("UPDATE `notification` SET `status_notif`='Read' WHERE `id_user`='$iduser' ");

$select_notification = $server->query("SELECT * FROM `notification`
    LEFT JOIN `invoice` ON notification.id_invoice = invoice.idinvoice
    LEFT JOIN `iklan` ON invoice.id_iklan = iklan.id
    WHERE notification.id_user='$iduser' ORDER BY `notification`.`id_notif` DESC");

$jumlah_notification = mysqli_num_rows($select_notification);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi | <?php echo $title_name; ?></title>
    <!-- META SEO -->
    <?php include '../partials/seo.php'; ?>
    <!-- META SEO -->
    <link rel="stylesheet" href="../assets/css/notification/index.css">
</head>
<body>
<!-- HEADER -->
<?php include '../partials/header.php'; ?>
<!-- HEADER -->
<!-- CONTENT --> 
<div class="width">
    <?php
    if (isset($_COOKIE['login'])) {
        $select_cart = $server->query("SELECT * FROM `notification` WHERE `id_user`='$iduser'");
        ?>
        <div class="notification">
            <div class="head_notification">
                <h1>Notifikasi</h1>
                <p><?php echo $jumlah_notification; ?></p>
            </div>
            <?php
            if ($jumlah_notification != '0') {
                ?>
                <div class="isi_notification">
                    <?php
                    while ($data_notifikasi = mysqli_fetch_assoc($select_notification)) {
                        $exp_gambar_notif = explode(',', $data_notifikasi['gambar']);
                        ?>
                        <div class="box_isi_notification">
                            <?php if ($data_notifikasi['id_invoice'] == 0) { ?>
                                <img src="../assets/icons/notifikasi.png">
                            <?php } else { ?>
                                <img src="../assets/image/product/compressed/<?php echo $exp_gambar_notif[0]; ?>">
                            <?php } ?>
                            <div class="text_box_isi_notification">
                                <h3><?php echo $data_notifikasi['nama_notif']; ?></h3>
                                <h4><?php echo $data_notifikasi['deskripsi_notif'] ?></h4>
                                <?php
                                if ($data_notifikasi['nama_notif'] == 'Pesanan Dikirim') {
                                    if ($data_notifikasi['kurir_manual'] != '') {
                                        ?>
                                        <a href="<?php echo $url; ?>/resi/hasil.php?waybill=<?php echo $data_notifikasi['resi']; ?>&courier=<?php echo $data_notifikasi['kurir_manual']; ?>&submit=Cek+Resi">Lacak Paket</a>
                                    <?php
                                    }
                                }
                                ?>
                                <p><?php echo $data_notifikasi['waktu_notif'] ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <?php
            } else {
                ?>
                <div class="isi_notification0">
                    <img src="../assets/icons/notification.svg">
                    <p>Belum ada notifikasi</p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    } else {
        include '../partials/belum-login.php';
    }
    ?>
<!-- CONTENT -->
<!-- FOOTER -->
<?php include '../partials/footer.php'; ?>
<!-- FOOTER -->
<!-- FOOTER -->
<?php include '../partials/bottom-navigation.php'; ?>
<!-- FOOTER -->
</body>
</html>
