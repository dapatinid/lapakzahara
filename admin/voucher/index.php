<?php
include '../config.php';

$page_admin = 'voucher';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}

// Query untuk mengambil data voucher berdasarkan user_id
$sql = "SELECT akun.nama_toko, akun.verifikasi_toko, vouchers.*
        FROM vouchers
        INNER JOIN akun ON vouchers.user_id = akun.id";

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

// Query untuk menghitung total voucher
$sql = "SELECT COUNT(id) AS total_voucher FROM vouchers";
$result = mysqli_query($conn, $sql);

// Inisialisasi variabel jumlah voucher
$jumlah_voucher = 0;

// Periksa hasil query
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $jumlah_voucher = $row['total_voucher'];
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
                <input type="text" class="input" id="jenis_voucher" placeholder="Masukan Nama. Contoh HABOLNAS">
            </div>
            <div class="isi_box_form_tk">
                <p>Persen Diskon</p>
                <input type="number" class="input" id="persen_diskon" placeholder="Masukan Angka. Contoh 25">
            </div>
            <div class="isi_box_form_tk">
                <p>Maksimal Diskon</p>
                <input type="number" class="input" id="maksimal_diskon" placeholder="Masukan Angka. Contoh 100000">
            </div>
            <div class="isi_box_form_tk">
                <p>Waktu Berlaku</p>
                <input type="text" class="input" id="waktu_berlaku" placeholder="Masukan Angka. Contoh 5">
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
                <input type="text" class="input" id="jenis_voucher_edit" placeholder="Masukan Nama. Contoh HARBOLNAS" required>
            </div>
            <div class="isi_box_form_tk">
                <p id="p_persen_diskon_edit">Persen Diskon</p>
                <input type="number" class="input" id="persen_diskon_edit" placeholder="Masukan Angka. Contoh 25" required>
            </div>
            <div class="isi_box_form_tk">
                <p id="p_maksimal_diskon_edit">Maksimal Diskon</p>
                <input type="number" class="input" id="maksimal_diskon_edit" placeholder="Masukan Angka. Contoh 100000" required>
            </div>
            <div class="isi_box_form_tk">
                <p id="p_waktu_berlaku_edit">Waktu Berlaku</p>
                <input type="text" class="input" id="waktu_berlaku_edit" placeholder="Masukan Angka. Contoh 5" required>
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
<!-- Tampilkan total voucher di dalam elemen HTML -->
<div class="jumlah_users_admin">
    <h1>Total Voucher</h1>
    <h1><?php echo $jumlah_voucher; ?> Produk</h1>
</div>

<div class="add_button_adm" onclick="show_add_voucher()">
                    <p>Buat Voucher</p>
                    <i class="ri-add-box-line"></i>
                </div>
<div class="all_users_admin" id="voucherList">
        <?php foreach ($voucherData as $voucher) : ?>
            <div class="isi_all_users_admin">
                <div class="box_left_aua">
                    <div class="isi_box_left_aua">
                        <h5><?= $voucher['jenis'] ?></h5>
                        <p><?= $voucher['nama_toko'] ?> <?php 
               if ($voucher['verifikasi_toko'] == 'Ya') {
                  echo '<img id="img-verif" src="../../assets/icons/verifikasi-toko.png">';
              }
               ?></p>
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

