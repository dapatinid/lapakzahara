<?php
include '../config.php';

$page_admin = 'deliveryman';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}

$select_user_all_admin = $server->query("SELECT * FROM `setting_kurir_toko_orang` ORDER BY `setting_kurir_toko_orang`.`id` DESC");
$total_user_all_admin = mysqli_num_rows($select_user_all_admin);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Delivery Man</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/users/index.css">
</head>

<body>
    <!-- POPUP EDIT AKUN -->
    <div class="box_edit_akun" id="box_bdmafd">
        <form action="../../system/admin/deliveryman/buat.php" method="POST" class="edit_akun">
            <h1><span id="tbdma"></span>Delivery Man</h1>
            <div class="form_edit_akun" style="grid-template-columns: 1fr;">
                <div class="isi_form_edit_akun">
                    <p>Contoh: (Ardi: 08998678034)</p>
                    <input type="text" class="input" id="nama_sjdhgfjhs" name="nama_sjdhgfjhs" placeholder="" required>
                </div>
            </div>
            <div class="box_button_edit_akun">
                <div class="button_cancel_edit_akun" onclick="batal_bdm()">
                    <p>Batal</p>
                </div>
                <button class="button_confirm_edit_akun" onclick="simpan_bdm()">
                    <p id="text_ea">Simpan</p>
                    <img src="../../assets/icons/loading-w.svg" id="loading_ea">
                </button>
            </div>
            <input type="hidden" name="id_user_bbbj" id="id_user_bbbj">
        </form>
    </div>
    <!-- POPUP EDIT AKUN -->

    <div class="admin">
        <?php include '../partials/menu.php'; ?>
        <div class="content_admin">
            <h1 class="title_content_admin">Delivery Man</h1>
            <div class="isi_content_admin">
                <!-- CONTENT -->
                <div class="jumlah_users_admin">
                    <h1>Jumlah</h1>
                    <h1><?php echo $total_user_all_admin; ?> Orang</h1>
                </div>
                <div class="button" style="width: 300px; margin-top: 15px;" onclick="show_bdmhjsd()">
                    <p>Tambah Delivery Man</p>
                </div>
                <div class="all_users_admin">
                    <?php
                    while ($data_all_user_admin = mysqli_fetch_assoc($select_user_all_admin)) {
                    ?>
                        <div class="isi_all_users_admin" id="isi_all_users_admin<?php echo $data_all_user_admin['id']; ?>">
                            <div class="box_left_aua">
                                <div class="isi_box_left_aua">
                                    <h5><?php echo $data_all_user_admin['nama']; ?></h5>
                                </div>
                            </div>
                            <div class="box_right_aua">
                            </div>
                            <div class="bu_edit_aua" onclick="show_edit_bjhadvf('<?php echo $data_all_user_admin['id']; ?>', '<?php echo $data_all_user_admin['nama']; ?>')">
                                <i class="ri-pencil-line"></i>
                            </div>
                            <div class="bu_delete_aua" onclick="show_confirm_hapus('<?php echo $data_all_user_admin['id']; ?>')">
                                <i class="ri-delete-bin-line"></i>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- CONTENT -->
            </div>
        </div>
    </div>
    <div id="res"></div>

    <!-- JS -->
    <script src="../../assets/js/admin/deliveryman/index.js"></script>
</body>

</html>