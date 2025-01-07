<?php
include '../../config.php';

$page_admin = 'voucher';

$sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
   $data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);
    
   if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
       if (isset($_COOKIE['login'])) {
           if ($profile['provinsi_user'] == '') {
               header("location: " . $url . "/store/start");
           }
       } else {
           header("location: " . $url . "/login");
       }
   } else {
       header("location: " . $url);
   }

// Query untuk mengambil data voucher berdasarkan user_id
$sql = "SELECT * FROM vouchers WHERE user_id = $iduser";
$result = $conn->query($sql);

// Inisialisasi array untuk menyimpan data voucher
$voucherData = array();

if ($result->num_rows > 0) {
    // Menyimpan data voucher ke dalam array
    while ($voucher = $result->fetch_assoc()) {
        $voucherData[] = $voucher;
    }
} else {
    // Tidak ada data voucher.
    $voucherData = array(); // Setelah menghapus echo, inisialisasi array voucherData kosong.
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Voucher</title>
    <link rel="icon" href="../../assets/icons/favicon.png" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/voucher/index.css">
</head>

<body>
    <!-- TAMBAH Voucher FORM -->
<div class="tambah_voucher_form" id="tambah_voucher_form">
    <div class="isi_tambah_voucher_form">
        <h1>Tambah Voucher</h1>
        <div class="box_form_tk">
            <div class="isi_box_form_tk">
                <p>Jenis Voucher</p>
                <input type="text" class="input" id="jenis_voucher" placeholder="Jenis voucher">
            </div>
            <div class="isi_box_form_tk">
                <p>Persen Diskon</p>
                <input type="number" class="input" id="persen_diskon" placeholder="Persen diskon">
            </div>
            <div class="isi_box_form_tk">
                <p>Maksimal Diskon</p>
                <input type="number" class="input" id="maksimal_diskon" placeholder="Maksimal diskon">
            </div>
            <div class="isi_box_form_tk">
                <p>Waktu Berlaku</p>
                <input type="text" class="input" id="waktu_berlaku" placeholder="Waktu berlaku">
            </div>
        </div>
                <input type="hidden" id="iduser" name="iduser" value="<?php echo $iduser; ?>">
        <div class="box_button_edit_akun">
            <div class="button_cancel_edit_akun" onclick="batal_add_voucher()">
                <p>Batal</p>
            </div>
            <div class="button_confirm_edit_akun" onclick="simpan_add_voucher()">
                <p id="text_tkat">Simpan</p>
                <img src="../../assets/icons/loading-w.svg" id="loading_tkat">
            </div>
        </div>
    </div>
</div>

<!-- TAMBAH Voucher FORM -->

<!-- HAPUS Voucher -->
<div class="back_popup_confirm" id="confirm_hapus_voucher">
    <div class="popup_confirm">
        <div class="head_popup_confirm">
            <i class="ri-delete-bin-line"></i>
            <p>Hapus Voucher</p>
        </div>
        <h5>Voucher yang sudah dihapus tidak dapat dipulihkan kembali. Apakah Anda yakin ingin menghapus voucher ini?</h5>
        <div class="box_button_popup_confirm">
            <div class="button_cancel_popup_confirm" id="hide_confirm_hapus_voucher" onclick="batal_hapus_voucher()">
                <p>Batal</p>
            </div>
            <div class="button_confirm_popup_confirm" onclick="hapus_voucher_ya()">
                <p id="text_ha_voucher">Hapus</p>
                <img src="../../assets/icons/loading-w.svg" id="loading_ha_voucher">
            </div>
        </div>
    </div>
    <input type="hidden" id="val_id_voucher">
</div>

<!-- HAPUS Voucher -->

<!-- EDIT Voucher FORM -->
<div class="tambah_voucher_form" id="edit_voucher_form">
    <div class="isi_tambah_voucher_form">
        <h1>Edit Voucher</h1>
        <div class="box_form_tk">
            <div class="isi_box_form_tk">
                <p id="p_jenis_voucher_edit">Jenis Voucher</p>
                <input type="text" class="input" id="jenis_voucher_edit" placeholder="Jenis voucher" required>
            </div>
            <div class="isi_box_form_tk">
                <p id="p_persen_diskon_edit">Persen Diskon</p>
                <input type="number" class="input" id="persen_diskon_edit" placeholder="Persen diskon" required>
            </div>
            <div class="isi_box_form_tk">
                <p id="p_maksimal_diskon_edit">Maksimal Diskon</p>
                <input type="number" class="input" id="maksimal_diskon_edit" placeholder="Maksimal diskon" required>
            </div>
            <div class="isi_box_form_tk">
                <p id="p_waktu_berlaku_edit">Waktu Berlaku</p>
                <input type="text" class="input" id="waktu_berlaku_edit" placeholder="Waktu berlaku" required>
            </div>
        </div>
        <div class="box_button_edit_akun">
            <div class="button_cancel_edit_akun" onclick="batal_edit_voucher()">
                <p>Batal</p>
            </div>
            <div class="button_confirm_edit_akun" onclick="simpan_edit_voucher()">
                <p id="text_evoucher">Simpan</p>
                <img src="../../assets/icons/loading-w.svg" id="loading_evoucher">
            </div>
        </div>
    </div>
    <input type="hidden" id="val_id_voucher">
</div>
    <div id="res"></div>

<!-- EDIT Voucher FORM -->



    <div class="admin">
        <?php include '../partials/menu.php'; ?>
        <div class="header_responsive_admin" onclick="show_box_menu_admin()">
            <i class="fas fa-bars"></i>
            <p>Navigasi</p>
        </div>
        <!-- ... Menu Admin ... -->

        <div class="content_admin">
            <h1 class="title_content_admin">Voucher</h1>
            <div class="isi_content_admin">
<!-- CONTENT -->
<div class="buat_voucher" onclick="show_add_voucher()">
                    <p>Buat Voucher</p>
                    <i class="ri-add-box-line"></i>
                </div>
<div class="all_users_admin" id="voucherList">
        <?php foreach ($voucherData as $voucher) : ?>
            <div class="isi_all_users_admin">
                <div class="box_left_aua">
                    <div class="isi_box_left_aua">
                        <h5>Voucher <?= $voucher['jenis'] ?></h5>
                    </div>
                </div>
                <div class="box_right_aua">
                    <div class="isi_box_right_aua1">
                        <h3>Persen</h3>
                        <p><?= $voucher['persen'] ?>%</p>
                    </div>
                    <div class="isi_box_right_aua2">
                        <h3>Maksimal</h3>
                        <p><?= $voucher['maksimal'] ?></p>
                    </div>
                    <div class="isi_box_right_aua3">
                        <h3>Waktu Dibuat</h3>
                        <p><?= $voucher['waktu_dibuat'] ?></p>
                    </div>
                    <div class="isi_box_right_aua4">
                        <h3>Waktu Berakhir</h3>
                        <p><?= $voucher['waktu_berakhir'] ?></p>
                    </div>
                </div>
                <div class="bu_edit_aua" onclick="show_edit_voucher('<?= $voucher['id'] ?>', '<?= $voucher['jenis'] ?>', '<?= $voucher['persen'] ?>', '<?= $voucher['maksimal'] ?>', '<?= $voucher['durasi'] ?>')">
                    <i class="ri-pencil-line"></i>
                </div>
                
                <div class="bu_delete_aua" onclick="show_confirm_hapus('<?= $voucher['id'] ?>')">
                    <i class="ri-delete-bin-line"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- CONTENT -->
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="../../assets/js/store/voucher.js"></script>
    <?php include '../../partials/bottom-navigation.php'; ?>
</body>
</html>
