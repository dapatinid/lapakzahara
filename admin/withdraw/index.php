<?php
include '../config.php';

$page_admin = 'withdraw';
if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}

// Mengubah query SQL untuk menggabungkan tabel 'akun' dan 'saldo' berdasarkan kolom 'id'
$sql_query = "SELECT riwayat_penarikan.*, akun.nama_lengkap, akun.verifikasi_toko, akun.foto
FROM riwayat_penarikan
JOIN akun ON riwayat_penarikan.user_id = akun.id
WHERE riwayat_penarikan.status IN (0, 2)";

$select_user_all_admin = $server->query($sql_query);

if (!$select_user_all_admin) {
    echo "Error: " . $server->error;
} else {
    $total_user_all_admin = mysqli_num_rows($select_user_all_admin);
    // Proses hasil query
}
?>

 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penarikan</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/withdraw/index.css">
</head>

<body>
    <!-- POPUP CONFIRM -->
    <div class="back_popup_confirm" id="confirm_hapus">
        <div class="popup_confirm">
            <div class="head_popup_confirm">
                <i class="ri-delete-bin-line"></i>
                <p>Hapus Data Penarikan</p>
            </div>
            <h5>Data yang sudah dihapus tidak dapat dipulihkan kembali, apakah anda yakin ingin menghapus data ini?</h5>
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
        <input type="hidden" id="val_idpenarikan">
    </div>
    <!-- POPUP CONFIRM --> 

    <!-- POPUP EDIT AKUN -->
    <div class="box_edit_akun" id="box_edit_akun">
        <div class="edit_akun">
            <h1>Data Penarikan</h1>
            <div class="form_edit_akun">
                <div class="isi_form_edit_akun">
                    <p>Nama Lengkap</p>
                    <input type="text" class="input" id="nama_lengkap_edt" placeholder="Nama lengkap...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Jumlah</p>
                    <input type="text" class="input" id="jumlah_dipotong_edt" placeholder="Email...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Nama Bank</p>
                    <input type="text" class="input" id="nama_bank_edt" placeholder="Nomor Whatsapp...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Rekening</p>
                    <input type="text" class="input" id="rekening_tujuan_edt" placeholder="Nomor Whatsapp...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Atas Nama</p>
                    <input type="text" class="input" id="atas_nama_edt" placeholder="Nomor Whatsapp...">
                </div>
                <div class="isi_form_edit_akun">
                    <p>Ubah Status</p>
                    <select class="input" id="status_edt">
                        <option value="0">Pending</option>
                        <option value="1">Terima</option>
                        <option value="2">Tolak</option>
                    </select>
                </div>
            </div>
            <div class="isi_form_edit_akun" style="width: 100%;margin-top: 25px;">
                    <p>Tambah Keterangan</p>
                    <select class="input" id="keterangan_edt">
                        <option value="Saldo Tidak Cukup">Saldo Tidak Cukup</option>
                        <option value="Informasi Rekening Tidak Valid">Informasi Rekening Tidak Valid</option>
                        <option value="Masalah Teknis di Pihak Bank atau Platform">Masalah Teknis di Pihak Bank atau Platform</option>
                        <option value="Batasan Penarikan Harian/Mingguan/Bulanan">Batasan Penarikan Harian/Mingguan/Bulanan</option>
                        <option value="Akun Dibekukan atau Dibatasi">Akun Dibekukan atau Dibatasi</option>
                    </select>
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
        <input type="hidden" id="idpenarikan_edt">
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var statusEdt = document.getElementById('status_edt');
        var keteranganEdtContainer = document.querySelector('.isi_form_edit_akun[style="width: 100%;margin-top: 25px;"]');

        function toggleKeteranganEdtVisibility() {
            var selectedStatus = statusEdt.value;
            if (selectedStatus === '2') { // Jika status_edt adalah "Tolak"
                keteranganEdtContainer.style.display = 'block';
            } else {
                keteranganEdtContainer.style.display = 'none';
            }
        }

        // Panggil fungsi saat halaman dimuat dan saat status_edt berubah
        if (statusEdt && keteranganEdtContainer) {
            toggleKeteranganEdtVisibility();
            statusEdt.addEventListener('change', toggleKeteranganEdtVisibility);
        } else {
            console.error("Elemen tidak ditemukan.");
        }
    });
</script>


    <!-- POPUP EDIT AKUN -->
 
    <div class="admin">
        <?php include '../partials/menu.php'; ?>
        <div class="content_admin">
            <h1 class="title_content_admin">Daftar Penarikan</h1>
            <div class="isi_content_admin">

                <!-- CONTENT -->
                <div class="jumlah_users_admin">
                    <h1>Jumlah Request</h1>
                    <h1><?php echo $total_user_all_admin; ?> Request</h1>
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
               if ($data_all_user_admin['centang_biru'] == 'verifed') {
                  echo '<img src="../../assets/icons/verifikasi.webp" id="img-verif" data-original-title="null" class=" has-tooltip" style="width: 20px;height: auto;vertical-align: middle;">';
              }
               ?> </h5>
                                    <p><?php echo $data_all_user_admin['email']; ?></p>
                                </div>
                            </div>
                            <div class="box_right_aua">
                                <div class="isi_box_right_aua1">
                                    <h3>Jumlah</h3>
                                    <p><?php echo "Rp" . number_format($data_all_user_admin['jumlah_dipotong'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="isi_box_right_aua2">
                                    <h3>BANK</h3>
                                    <p><?php echo $data_all_user_admin['nama_bank']; ?></p>
                                </div>
                                <div class="isi_box_right_aua3">
                                    <h3>Rekening</h3>
                                    <p><?php echo $data_all_user_admin['rekening_tujuan']; ?></p>
                                </div>
                                <div class="isi_box_right_aua4">
                                    <h3>Atas Nama</h3>
                                    <p><?php echo $data_all_user_admin['atas_nama']; ?></p>
                                </div>
                                <div class="isi_box_right_aua4">
    <h3>Status</h3>
    <?php
    $status = $data_all_user_admin['status'];
    if ($status == 0) {
        echo '<p>Pending</p>';
    } elseif ($status == 1) {
        echo '<p>Berhasil</p>';
    } elseif ($status == 2) {
        echo '<p>Ditolak</p>';
    } else {
        echo '<p>Status tidak valid</p>';
    }
    ?>
</div>

                            </div>
                            <div class="bu_edit_aua" onclick="show_edit_akun('<?php echo $data_all_user_admin['id']; ?>', '<?php echo $data_all_user_admin['idpenarikan']; ?>', '<?php echo $data_all_user_admin['nama_lengkap']; ?>', '<?php echo $data_all_user_admin['jumlah_dipotong']; ?>', '<?php echo $data_all_user_admin['nama_bank']; ?>', '<?php echo $data_all_user_admin['rekening_tujuan']; ?>', '<?php echo $data_all_user_admin['atas_nama']; ?>', '<?php echo $data_all_user_admin['status']; ?>', '<?php echo $data_all_user_admin['keterangan']; ?>')">
                                <i class="ri-pencil-line"></i>
                            </div>
                            <div class="bu_delete_aua" onclick="show_confirm_hapus('<?php echo $data_all_user_admin['idpenarikan']; ?>')">
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
    <script src="../../assets/js/admin/withdraw/index.js"></script>

</body>

</html>

