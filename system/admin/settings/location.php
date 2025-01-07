<?php
include '../../../config.php';

$exp_provinsi_ls = explode(',', $_POST['provinsi_ls']);
$exp_kota_ls = explode(',', $_POST['kota_ls']);
$exp_kecamatan_ls = explode(',', $_POST['kecamatan_ls']);

$prov_name_ls = $exp_provinsi_ls[1];
$prov_id_ls = $exp_provinsi_ls[0];
$kota_name_ls = $exp_kota_ls[1];
$kota_id_ls = $exp_kota_ls[0];
$kecamatan_name_ls = $exp_kecamatan_ls[1];
$kecamatan_id_ls = $exp_kecamatan_ls[0];

$tipe_user_vt = $_POST['tipe_user_vt'];

if ($tipe_user_vt == 'store') {
    $update_lokasi_toko = $server->query("UPDATE `akun` SET `provinsi_user`='$prov_name_ls',`kota_user`='$kota_name_ls',`kecamatan_user`='$kecamatan_name_ls',`provinsi_id_user`='$prov_id_ls',`kota_id_user`='$kota_id_ls',`kecamatan_id_user`='$kecamatan_id_ls' WHERE `id`='$iduser' ");
} else {
    $lokasiidedit = 1; // ID yang akan diubah, sesuaikan dengan ID yang ingin Anda ubah

    // Selanjutnya, lakukan pembaruan dengan ID yang telah ditentukan
    $update_lokasi_toko = $server->query("UPDATE `akun` SET `provinsi_user`='$prov_name_ls',`kota_user`='$kota_name_ls',`kecamatan_user`='$kecamatan_name_ls',`provinsi_id_user`='$prov_id_ls',`kota_id_user`='$kota_id_ls',`kecamatan_id_user`='$kecamatan_id_ls' WHERE `id`='$lokasiidedit' ");
}

if ($update_lokasi_toko) {
?>
    <script>
        text_s_lc.innerHTML = 'Berhasil Disimpan';
        setTimeout(() => {
            text_s_lc.innerHTML = 'Simpan';
        }, 3000);
        window.location.href = '';
    </script>
<?php
}
