<?php
include '../config.php';

$page_admin = 'store';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}

$sql_query = "SELECT akun.*, 
                     COUNT(iklan.id) AS jumlah_produk, 
                     saldo.jumlah_saldo AS jumlah_saldo
              FROM `akun`
              LEFT JOIN `iklan` ON akun.id = iklan.user_id
              LEFT JOIN `saldo` ON akun.id = saldo.user_id
              WHERE akun.`status_toko` = 'Aktif'
              GROUP BY akun.id
              ORDER BY akun.id DESC";


              
$select_user_all_admin = $server->query($sql_query);
$total_user_all_admin = mysqli_num_rows($select_user_all_admin);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Vendor</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/store/index.css">
</head>

<body>
    <!-- POPUP CONFIRM -->
    <div class="back_popup_confirm" id="confirm_hapus">
        <div class="popup_confirm">
            <div class="head_popup_confirm">
                <i class="ri-delete-bin-line"></i>
                <p>Nonaktifkan Toko</p>
            </div>
            <h5>Apakah anda yakin ingin menonaktifkan Toko ini?</h5>
            <div class="box_button_popup_confirm">
                <div class="button_cancel_popup_confirm" id="hide_confirm_hapus" onclick="batal_hapus_akun()">
                    <p>Batal</p>
                </div>
                <div class="button_confirm_popup_confirm" onclick="hapus_akun()">
                    <p id="text_ha">Simpan</p>
                    <img src="../../assets/icons/loading-w.svg" id="loading_ha">
                </div>
            </div>
        </div>
        <input type="hidden" id="val_id_user">
    </div>
    <!-- POPUP CONFIRM --> 

    <!-- POPUP EDIT AKUN -->
    <div class="box_edit_akun" id="box_edit_akun">
        <div class="edit_akun">
            <h1>Edit Toko</h1>
            <div class="form_edit_akun">
                <div class="isi_form_edit_akun">
                    <p>Nama Toko</p>
                    <input type="text" class="input" id="nama_toko_edt" placeholder="Nama Toko...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Username Toko</p>
                    <input type="text" class="input" id="nama_pengguna_edt" placeholder="Username Toko...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Nomor Whatsapp</p>
                    <input type="text" class="input" id="no_wa_edt" placeholder="Nomor Whatsapp...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Verifikasi Akun?</p>
                    <select class="input" id="verifikasi_toko_edt">
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>
                <div class="isi_form_edit_akun">
                    <p>Tambah Saldo</p>
                    <input type="number" class="input currency" data-separator="." id="jumlah_deposit_edt" placeholder="Masukan Nilai...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Ubah Level Toko</p>
                    <select class="input" id="level_toko_edt">
                        <option value="Bronze">Bronze</option>
                        <option value="Silver">Silver</option>
                        <option value="Gold">Gold</option>
                        <option value="Diamond">Diamond</option>
                    </select>
                </div>
            </div>
            <div class="box_button_edit_akun">
                <div class="button_cancel_edit_akun" onclick="batal_edit_iklan()">
                    <p>Batal</p>
                </div>
                <div class="button_confirm_edit_akun" onclick="simpan_edit_iklan()">
                    <p id="text_ea">Simpan</p>
                    <img src="../../assets/icons/loading-w.svg" id="loading_ea">
                </div>
            </div>
        </div>
        <input type="hidden" id="id_user_edit_akun">
    </div>
    <!-- POPUP EDIT AKUN -->
 
    <div class="admin">
        <?php include '../partials/menu.php'; ?>
        <div class="content_admin">
            <h1 class="title_content_admin">Toko Vendor</h1>
            <div class="isi_content_admin">

                <!-- CONTENT -->
                <div class="jumlah_users_admin">
                    <h1>Jumlah Toko</h1>
                    <h1><?php echo $total_user_all_admin; ?> Toko</h1>
                </div>
                <div class="all_users_admin">
                    <?php
                    while ($data_all_user_admin = mysqli_fetch_assoc($select_user_all_admin)) {
                    ?>
                        <div class="isi_all_users_admin" id="isi_all_users_admin<?php echo $data_all_user_admin['id']; ?>">
                            <div class="box_left_aua">
                                <img src="../../assets/image/profil-toko/<?php echo $data_all_user_admin['logo_toko']; ?>">
                                <div class="isi_box_left_aua">
                                    <h5><?php echo $data_all_user_admin['nama_toko']; ?> <?php 
               if ($data_all_user_admin['verifikasi_toko'] == 'Ya') {
                  echo '<img id="img-verif" src="../../assets/icons/verifikasi-toko.png">';
              }
               ?> </h5>
                                    <p><a href='<?php echo $url; ?>/@<?php echo $data_all_user_admin['nama_pengguna']; ?>' target='_blank'><?php echo $url; ?>/@<?php echo $data_all_user_admin['nama_pengguna']; ?></a></p>
                                </div>
                            </div>
                            <div class="box_right_aua">
                                <div class="isi_box_right_aua1">
                                    <h3>Lokasi Toko</h3>
                                    <p><p><?php echo !empty($data_all_user_admin['kota_user']) ? $data_all_user_admin['kota_user'] : "Belum Ditambahkan"; ?></p></p>
                                    
                                </div>
                                <div class="isi_box_right_aua2">
                                    <h3>Produk</h3>
                                    <p><?php echo $data_all_user_admin['jumlah_produk']; ?></p>
                                    
                                </div>
                                <div class="isi_box_right_aua3">
                                    <h3>Status</h3>
                                    <p><?php echo $data_all_user_admin['status_user']; ?></p>
                                </div>
                                <div class="isi_box_right_aua4">
                                    <h3>Saldo Toko</h3>
                                    <p><?php echo number_format($data_all_user_admin['jumlah_saldo'], 0, ".", "."); ?></p>

                                </div>
                            </div>
                            <div class="bu_edit_aua" onclick="show_edit_akun('<?php echo $data_all_user_admin['id']; ?>', '<?php echo $data_all_user_admin['nama_toko']; ?>', '<?php echo $data_all_user_admin['level_toko']; ?>', '<?php echo $data_all_user_admin['nama_pengguna']; ?>', '<?php echo $data_all_user_admin['no_whatsapp']; ?>', '<?php echo $data_all_user_admin['verifikasi_toko']; ?>', '<?php echo isset($data_all_user_admin['jumlah_deposit']) ? $data_all_user_admin['jumlah_deposit'] : ''; ?>')">
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
    <script src="../../assets/js/admin/store/index.js"></script>

</body>

</html>
