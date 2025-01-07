<?php
include '../../../config.php';

// Validasi dan sanitasi data POST
$sf_view_produk = isset($_POST['sf_view_produk']) ? mysqli_real_escape_string($server, $_POST['sf_view_produk']) : '';
$sf_tipe_toko = isset($_POST['sf_tipe_toko']) ? mysqli_real_escape_string($server, $_POST['sf_tipe_toko']) : '';
$sf_midtrans_mode = isset($_POST['sf_midtrans_mode']) ? mysqli_real_escape_string($server, $_POST['sf_midtrans_mode']) : '';
$sf_cod = isset($_POST['sf_cod']) ? mysqli_real_escape_string($server, $_POST['sf_cod']) : '';
$sf_format_harga = isset($_POST['sf_format_harga']) ? mysqli_real_escape_string($server, $_POST['sf_format_harga']) : '';
$sf_jumlah_persen = isset($_POST['sf_jumlah_persen']) ? mysqli_real_escape_string($server, $_POST['sf_jumlah_persen']) : '';
$sf_minimal_penarikan = isset($_POST['sf_minimal_penarikan']) ? mysqli_real_escape_string($server, $_POST['sf_minimal_penarikan']) : '';

// Update database dengan prepared statement untuk menghindari SQL injection
$update_view_produk = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='view produk'");
$update_tipe_toko = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='tipe toko'");
$update_midtrans_mode = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='midtrans mode'");
$update_cod = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='cod'");
$update_format_harga = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='format harga'");
$update_jumlah_persen = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='jumlah persen'");
$update_minimal_penarikan = $server->prepare("UPDATE `setting_fitur` SET `opsi_fitur`=? WHERE `nama_fitur`='minimal penarikan'");


// Bind parameter dan eksekusi query
$update_view_produk->bind_param('s', $sf_view_produk);
$update_tipe_toko->bind_param('s', $sf_tipe_toko);
$update_midtrans_mode->bind_param('s', $sf_midtrans_mode);
$update_cod->bind_param('s', $sf_cod);
$update_format_harga->bind_param('s', $sf_format_harga);
$update_jumlah_persen->bind_param('s', $sf_jumlah_persen);
$update_minimal_penarikan->bind_param('s', $sf_minimal_penarikan);


// Eksekusi prepared statement
$success = $update_view_produk->execute() && $update_tipe_toko->execute() && $update_midtrans_mode->execute() && $update_cod->execute() && $update_format_harga->execute() && $update_jumlah_persen->execute() && $update_minimal_penarikan->execute();

// Menutup koneksi database
$server->close();

if ($success) {
    echo '<script>
            var text_s_ealog = document.getElementById("text_s_ealog");
            text_s_ealog.innerHTML = "Berhasil Disimpan";
            setTimeout(function() {
                text_s_ealog.innerHTML = "Simpan";
            }, 3000);
        </script>';
}
?>
