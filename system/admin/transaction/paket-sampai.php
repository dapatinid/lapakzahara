<?php
include '../../../config.php';
include '../../../system/email/class.phpmailer.php';
include '../../../system/email/send-email.php';

$idinvoicesb = $_POST['idinvoice_pss'];
$time = date("Y-m-d H:i:s"); 

$select_invoice_ud = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$idinvoicesb'");
$data_invoice_ud = mysqli_fetch_assoc($select_invoice_ud);
$iduser_ud = $data_invoice_ud['id_user'];

// Hitung hasil penjualan dan jumlah produk terjual
$hasil_penjualan_total = 0;
$jumlah_produk_terjual = count(explode(',', $data_invoice_ud['harga_i']));

$exp_harga_i = explode(',', $data_invoice_ud['harga_i']);
$exp_diskon_i = explode(',', $data_invoice_ud['diskon_i']);
$exp_harga_ongkir = explode(',', $data_invoice_ud['harga_ongkir']);
$exp_jumlah = explode(',', $data_invoice_ud['jumlah']); // Kolom jumlah digunakan untuk mengambil jumlah produk
$diskon_voucher = $data_invoice_ud['diskon_voucher']; // Ambil nilai diskon_voucher

for ($i = 0; $i < count($exp_harga_i); $i++) {
    $harga_sebelum_diskon = $exp_harga_i[$i] * $exp_jumlah[$i];
    $diskon = ($exp_diskon_i[$i] / 100) * $harga_sebelum_diskon;
    $harga_setelah_diskon = $harga_sebelum_diskon - $diskon + $exp_harga_ongkir[$i];
    $hasil_penjualan_total += $harga_setelah_diskon;
}

// Kurangi dengan diskon_voucher
$hasil_penjualan_total -= $diskon_voucher;

// Dapatkan user id dari tabel iklan
$id_iklan = $data_invoice_ud['id_iklan'];
$select_iklan_user = $server->query("SELECT `user_id` FROM `iklan` WHERE `id` = '$id_iklan'");
$data_iklan_user = mysqli_fetch_assoc($select_iklan_user);
$id_user_pemilik_iklan = $data_iklan_user['user_id'];

// Periksa apakah user sudah ada di tabel saldo
$select_user_saldo = $server->query("SELECT * FROM `saldo` WHERE `user_id`='$id_user_pemilik_iklan'");
if ($select_user_saldo->num_rows === 0) {
    // Jika user belum ada di tabel saldo, lakukan INSERT
    $insert_user_saldo = $server->query("INSERT INTO `saldo` (`user_id`, `jumlah_saldo`) VALUES ('$id_user_pemilik_iklan', '$hasil_penjualan_total')");
} else {
    // Jika user sudah ada, lakukan UPDATE
    $update_saldo_pemilik_iklan = $server->query("UPDATE `saldo` SET `jumlah_saldo` = `jumlah_saldo` + $hasil_penjualan_total WHERE `user_id` = '$id_user_pemilik_iklan'");
}

// Perbarui status pesanan menjadi 'Selesai'
$update_dikirim = $server->query("UPDATE `invoice` SET `tipe_progress`='Selesai', `waktu_selesai`='$time' WHERE `idinvoice`='$idinvoicesb'");

// Query untuk mendapatkan judul barang dari tabel iklan berdasarkan ID iklan dari tabel invoice
$select_judul_query = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$idinvoicesb' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_judul = mysqli_fetch_assoc($select_judul_query);

$nama_user_mp = $data_select_judul['nama_lengkap'];
$judul_barang_mp = $data_select_judul['judul'];

// Kemudian gunakan nilai $judul_barang_mp dalam query notifikasi
$insert_notif_ud = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) VALUES ('$iduser_ud', '$idinvoicesb', 'Pesanan Telah Sampai', 'Pesanan untuk produk $judul_barang_mp telah sampai ke tempat tujuan. Pesanan diterima oleh: $nama_user_mp', '$time', '')");

// Masukkan ke riwayat penjualan
$insert_riwayat_penjualan = $server->query("INSERT INTO `riwayat_penjualan` (`user_id`, `tanggal`, `total_penjualan`, `jumlah_produk`, `keterangan`) VALUES ('$id_user_pemilik_iklan', '$time', '$hasil_penjualan_total', '$jumlah_produk_terjual', 'Pendapatan dari penjualan')");

// Kirim notifikasi email
$select_user_mp = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$idinvoicesb' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_user_mp = mysqli_fetch_assoc($select_user_mp);

$email_user_mp = $data_select_user_mp['email'];
$nama_user_mp = $data_select_user_mp['nama_lengkap'];
$judul_barang_mp = $data_select_user_mp['judul'];

$judul_progress_produk = "Paket Sampai ";
$deskripsi_email = "Hi $nama_user_mp
<br><br>
Selamat! Produk <span style='font-weight: bold;'>$judul_barang_mp</span> Telah Berhasil Sampai Ke Alamat Tujuan.
<br><br>
Salam Hangat,
<br>
$title_name - $slogan";

EmailSend("$email_user_mp", "Paket Telah Diterima!", "$deskripsi_email", "", $server);
?>
