<?php
include '../config.php';

$page_admin = 'user';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}

// Mengubah query SQL untuk menggabungkan tabel 'akun' dan 'saldo' berdasarkan kolom 'id'
$sql_query = "SELECT akun.*, saldo.jumlah_saldo FROM `akun` 
              LEFT JOIN `saldo` ON akun.id = saldo.user_id 
              ORDER BY `akun`.`id` DESC";

$select_user_all_admin = $server->query($sql_query);
$total_user_all_admin = mysqli_num_rows($select_user_all_admin);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Akun User</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/users/index.css">
</head>

<body>
    <!-- POPUP CONFIRM -->
    <div class="back_popup_confirm" id="confirm_hapus">
        <div class="popup_confirm">
            <div class="head_popup_confirm">
                <i class="ri-delete-bin-line"></i>
                <p>Hapus akun user</p>
            </div>
            <h5>Akun user yang sudah dihapus tidak dapat dipulihkan kembali, apakah anda yakin ingin menghapus akun user ini?</h5>
            <div class="box_button_popup_confirm">
                <div class="button_cancel_popup_confirm" id="hide_confirm_hapus" onclick="batal_hapus_akun()">
                    <p>Batal</p>
                </div>
                <div class="button_confirm_popup_confirm" onclick="hapus_akun()">
                    <p id="text_ha">Hapus</p>
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
            <h1>Edit Akun User</h1>
            <div class="form_edit_akun">
                <div class="isi_form_edit_akun">
                    <p>Nama Lengkap</p>
                    <input type="text" class="input" id="nama_lengkap_edt" placeholder="Nama lengkap...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Email</p>
                    <input type="text" class="input" id="email_edt" placeholder="Email...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Nomor Whatsapp</p>
                    <input type="text" class="input" id="no_wa_edt" placeholder="Nomor Whatsapp...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Tipe Akun</p>
                    <select class="input" id="tipe_akun_edt">
                        <option value="">User</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="isi_form_edit_akun" style="display:none">
                    <p>Edit Saldo</p>
                    <input type="number" class="input currency" data-separator="." id="saldo_edt" placeholder="Jumlah Saldo">
                </div>
                
            </div>
            <div class="form_edit_akun" style="grid-template-columns: 1fr;">
            <div class="isi_form_edit_akun">
                    <p>Verifikasi Akun</p>
                    <select class="input" id="verifikasi_user_edt">
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
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
            <h1 class="title_content_admin">Akun User</h1>
            <div class="isi_content_admin">

                <!-- CONTENT -->
                <div class="jumlah_users_admin">
                    <h1>Jumlah User</h1>
                    <h1><?php echo $total_user_all_admin; ?> User</h1>
                </div>
                <div class="all_users_admin">
                    <?php
                    while ($data_all_user_admin = mysqli_fetch_assoc($select_user_all_admin)) {
                    ?>
                        <div class="isi_all_users_admin" id="isi_all_users_admin<?php echo $data_all_user_admin['id']; ?>">
                            <div class="box_left_aua">
                                <img src="../../assets/image/profile/<?php echo $data_all_user_admin['foto']; ?>">
                                <div class="isi_box_left_aua">
                                    <h5><?php echo $data_all_user_admin['nama_lengkap']; ?> <?php 
               if ($data_all_user_admin['verifikasi_user'] == 'Ya') {
                  echo '<img id="img-verif" src="../../assets/icons/verifikasi-user.png">';
              } 
               ?> </h5>
                                    <p><?php echo $data_all_user_admin['email']; ?></p>
                                </div>
                            </div>
                            <div class="box_right_aua">
                                <div class="isi_box_right_aua1">
                                    <h3>WhatsApp</h3>
                                    <p><?php echo !empty($data_all_user_admin['no_whatsapp']) ? $data_all_user_admin['no_whatsapp'] : "Belum Ditambahkan"; ?></p>
                                </div>
                                <div class="isi_box_right_aua2">
                                    <h3>Tipe Daftar</h3>
                                    <p>
                                        <?php
                                        if ($data_all_user_admin['tipe_daftar'] == '') {
                                            echo 'Website';
                                        } else {
                                            echo $data_all_user_admin['tipe_daftar'];
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="isi_box_right_aua3">
                                    <h3>Level Akun</h3>
                                    <p>
                                        <?php
                                        if ($data_all_user_admin['tipe_akun'] == '') {
                                            echo 'User';
                                        } else {
                                            echo $data_all_user_admin['tipe_akun'];
                                        }
                                        ?>
                                    </p>
                                </div>
                                
                            </div>
                            <div class="bu_edit_aua" onclick="show_edit_akun('<?php echo $data_all_user_admin['id']; ?>', '<?php echo $data_all_user_admin['nama_lengkap']; ?>', '<?php echo $data_all_user_admin['verifikasi_user']; ?>', '<?php echo $data_all_user_admin['email']; ?>', '<?php echo $data_all_user_admin['no_whatsapp']; ?>', '<?php echo $data_all_user_admin['tipe_akun']; ?>', '<?php echo $data_all_user_admin['jumlah_saldo']; ?>')">
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
    <script src="../../assets/js/admin/users/index.js"></script>

</body>

</html>
