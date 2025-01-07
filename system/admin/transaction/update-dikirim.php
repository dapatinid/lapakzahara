<?php
include '../../../config.php';
include '../../../system/email/class.phpmailer.php';
include '../../../system/email/send-email.php';

$idinvoicesb = $_POST['idinvoicesb'];
$resi_pengiriman_v = $_POST['resi_pengiriman_v'];
$kurir_toko_manual = $_POST['kurir_toko_manual'];
$time = date("Y-m-d H:i:s");

$select_invoice_ud = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$idinvoicesb'");
$data_invoice_ud = mysqli_fetch_assoc($select_invoice_ud);
$iduser_ud = $data_invoice_ud['id_user'];
$judul_barang_mp = $data_invoice_ud['judul']; // Ambil judul barang dari data invoice

$update_dikirim = $server->query("UPDATE `invoice` SET `resi`='$resi_pengiriman_v', `tipe_progress`='Dikirim', `waktu_dikirim`='$time', `kurir_manual`='$kurir_toko_manual' WHERE `idinvoice`='$idinvoicesb'");

// Query untuk mendapatkan judul barang dari tabel iklan berdasarkan ID iklan dari tabel invoice
$select_judul_query = $server->query("SELECT iklan.judul FROM invoice INNER JOIN iklan ON invoice.id_iklan = iklan.id WHERE invoice.idinvoice='$idinvoicesb'");
$data_select_judul = mysqli_fetch_assoc($select_judul_query);

$judul_barang_mp = $data_select_judul['judul'];

// Kemudian gunakan nilai $judul_barang_mp dalam query notifikasi
$insert_notif_ud = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) VALUES ('$iduser_ud', '$idinvoicesb', 'Pesanan Dikirim', 'Pesanan $judul_barang_mp sudah dikirim oleh penjual dan sedang dalam perjalanan', '$time', '')");

// NOTIF EMAIL
$select_user_mp = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$idinvoicesb' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_user_mp = mysqli_fetch_assoc($select_user_mp);

$email_user_mp = $data_select_user_mp['email'];
$nama_user_mp = $data_select_user_mp['nama_lengkap'];
$kurir_mp = strtoupper($data_select_user_mp['kurir']);
$resi_mp = strtoupper($data_select_user_mp['resi']);

$judul_progress_produk = "Paket Dikirimkan ";
$deskripsi_email = "Hi $nama_user_mp
<br><br>
Produk <span style='font-weight: bold;'>$judul_barang_mp</span> Telah Dikirimkan Oleh Penjual.
<br>
Jasa Pengiriman: $kurir_mp
<br>
Nomor Resi: $resi_mp
<br><br>
Salam Hangat,
<br>
$title_name - $slogan";

EmailSend("$email_user_mp", "Paket Telah Dikirim!", "$deskripsi_email", "", $server);
?>
