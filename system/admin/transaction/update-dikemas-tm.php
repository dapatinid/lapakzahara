<?php
include '../../../config.php';
include '../../../system/email/class.phpmailer.php';
include '../../../system/email/send-email.php';

$idinvoice_pss = $_POST['idinvoice_pss'];
$id_usr_tm = $_POST['id_usr_tm'];
$time = date("Y-m-d H:i:s");
$tipe_progress = 'Dikemas';

$select_user_mp = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$idinvoice_pss' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_user_mp = mysqli_fetch_assoc($select_user_mp);

$email_user_mp = $data_select_user_mp['email'];
$nama_user_mp = $data_select_user_mp['nama_lengkap'];
$judul_barang_mp = $data_select_user_mp['judul'];

// UPDATE STOK BARANG
$id_iklan_mp = $data_select_user_mp['id_iklan'];
$iklan_terjual_mp = $data_select_user_mp['terjual'] + $data_select_user_mp['jumlah'];
if ($data_select_user_mp['stok'] == 0) {
    $iklan_stok_mp = '0';
} else {
    $iklan_stok_mp = $data_select_user_mp['stok'] - $data_select_user_mp['jumlah'];
}
$update_stok_mp = $server->query("UPDATE `iklan` SET `stok`='$iklan_stok_mp',`terjual`='$iklan_terjual_mp' WHERE `id`='$id_iklan_mp' ");

$update_invoice = $server->query("UPDATE `invoice` SET `tipe_progress`='$tipe_progress', `transaction`='settlement' WHERE `idinvoice`='$idinvoice_pss'");

// Perhitungan total pembayaran
$exp_harga_i = explode(',', $data_select_user_mp['harga_i']);
$exp_diskon_i = explode(',', $data_select_user_mp['diskon_i']);
$exp_harga_ongkir = explode(',', $data_select_user_mp['harga_ongkir']);
$exp_jumlah = explode(',', $data_select_user_mp['jumlah']);
$diskon_voucher = $data_select_user_mp['diskon_voucher'];

$jumlah_pembayaran = 0; // Inisialisasi jumlah pembayaran

for ($i = 0; $i < count($exp_harga_i); $i++) {
    // Hitung harga setelah diskon
    $harga_setelah_diskon = $exp_harga_i[$i] - ($exp_harga_i[$i] * $exp_diskon_i[$i] / 100);
    
    // Hitung harga total termasuk jumlah dan harga ongkir
    $harga_total = ($harga_setelah_diskon * $exp_jumlah[$i]) + $exp_harga_ongkir[$i];
    
    // Tambahkan ke jumlah pembayaran
    $jumlah_pembayaran += $harga_total;
}

// Kurangi dengan diskon_voucher
$jumlah_pembayaran -= $diskon_voucher;

// Query insert notifikasi untuk Pembayaran Berhasil
$insert_notif_payment = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) 
    VALUES ('$id_usr_tm', '$idinvoice_pss', 'Pembayaran Berhasil', 
    'Pembayaran pesanan sebesar Rp" . number_format($jumlah_pembayaran, 0, ',', '.') . " untuk produk $judul_barang_mp sudah berhasil terverifikasi', '$time', '')");

// Query insert notifikasi untuk Pesanan Dikemas
$insert_notif_dikemas = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) 
VALUES ('$id_usr_tm', '$idinvoice_pss', 'Pesanan Dikemas', 
'Pesanan sedang dalam proses pengemasan oleh penjual untuk produk $judul_barang_mp', '$time', '')");

$judul_progress_produk = "Pembayaran Berhasil ";
$deskripsi_email = "Hi $nama_user_mp
<br><br>
Selamat! Pembayaran Produk <span style='font-weight: bold;'>$judul_barang_mp</span> Telah Berhasil Di Verifikasi, Sekarang Sudah Masuk Dalam Proses Pengemasan Produk Oleh Penjual.
<br><br>
Salam Hangat,
<br>
$title_name - $slogan";

EmailSend("$email_user_mp", "Pembayaran Berhasil!", "$deskripsi_email", "", $server);
?>
