<?php
include '../../../config.php';

$inp_tipe_mp = $_POST['inp_tipe_mp'];
$inp_tipe_bk = $_POST['inp_tipe_bk'] ?? '';
$nama_bank = $_POST['nama_bank'];
$norek = $_POST['norek'];
$atas_nama = $_POST['atas_nama'];

$update_mp_adm = $server->query("UPDATE `setting_pembayaran` SET `status`='' ");
$update_mp_adm = $server->query("UPDATE `setting_pembayaran` SET `status`='active' WHERE `tipe`='$inp_tipe_mp' ");

if($inp_tipe_bk == 'tambah_bank'){
    // Jika opsi "Tambah Bank" dipilih, tambahkan bank baru ke dalam database nomor_rekening
    $insert_norek_adm = $server->query("INSERT INTO `nomor_rekening` (`nama_bank`, `norek`, `an`) VALUES ('$nama_bank', '$norek', '$atas_nama')");

    if ($insert_norek_adm) {
        ?>
       <script>
    function refreshPage() {
        location.reload();
    }

    // Panggil fungsi refreshPage setelah berhasil disimpan
    refreshPage();
    text_s_lmp.innerHTML = 'Berhasil Disimpan';
    setTimeout(() => {
        text_s_lmp.innerHTML = 'Simpan';
    }, 3000);
</script>

        <?php
    }
} else {
    // Jika opsi bank yang ada dipilih, perbarui detail nomor rekening yang ada
    $update_norek_adm = $server->query("UPDATE `nomor_rekening` SET `nama_bank`='$nama_bank', `norek`='$norek', `an`='$atas_nama' WHERE `idnorek`='$inp_tipe_bk' ");

    if ($update_norek_adm) {
        ?>
        <script>
    function refreshPage() {
        location.reload();
    }

    // Panggil fungsi refreshPage setelah berhasil disimpan
    refreshPage();
    text_s_lmp.innerHTML = 'Berhasil Disimpan';
    setTimeout(() => {
        text_s_lmp.innerHTML = 'Simpan';
    }, 3000);
</script>

        <?php
    }
}
?>
