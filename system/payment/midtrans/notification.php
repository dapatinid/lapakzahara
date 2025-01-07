<?php
include '../../../config.php';
include '../../../system/email/class.phpmailer.php';
include '../../../system/email/send-email.php';
require_once '../../../assets/composer/midtrans-php-master/Midtrans.php';

$sf_midtrans_mode = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='midtrans mode' ");
$data_sf_midtrans_mode = mysqli_fetch_array($sf_midtrans_mode);

if ($data_sf_midtrans_mode['opsi_fitur'] == 'Production') {
    \Midtrans\Config::$isProduction = true;
} elseif ($data_sf_midtrans_mode['opsi_fitur'] == 'Sandbox') {
    \Midtrans\Config::$isProduction = false;
}
\Midtrans\Config::$serverKey = $midtrans_server_key;
$notif = new \Midtrans\Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

$exp_order_id = explode('-midtrans-', $order_id);
$id_invoice = $exp_order_id[0];
$tipe_progress = 'Dikemas';
$time = date("Y-m-d H:i:s");

$select_invoice_payment = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$id_invoice'");
$data_invoice_payment = mysqli_fetch_assoc($select_invoice_payment);

$iduser_payment = $data_invoice_payment['id_user'];

$select_user_mp = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$id_invoice' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_user_mp = mysqli_fetch_assoc($select_user_mp);

$select_email_setting_n = $server->query("SELECT * FROM `setting_email` WHERE `id`='1' ");
$data_email_setting_n = mysqli_fetch_array($select_email_setting_n);
$email_admin_mp = $data_email_setting_n['email_notif'];

$email_user_mp = $data_select_user_mp['email'];
$nama_user_mp = $data_select_user_mp['nama_lengkap'];
$judul_barang_mp = $data_select_user_mp['judul'];

$select_email_penjual = $server->query("SELECT akun.email AS email_penjual
          FROM akun
          JOIN iklan ON akun.id = iklan.user_id
          JOIN invoice ON iklan.id = invoice.id_iklan
          WHERE invoice.idinvoice='$id_invoice'");
$data_select_email_penjual = mysqli_fetch_assoc($select_email_penjual);
$email_penjual = $data_select_email_penjual['email_penjual'];

    // UPDATE STOK BARANG
    $id_iklan_mp = $data_select_user_mp['id_iklan'];
    $iklan_terjual_mp = $data_select_user_mp['terjual'] + $data_select_user_mp['jumlah'];
    if ($data_select_user_mp['stok'] == 0) {
    $iklan_stok_mp = '0';
    } else {
    $iklan_stok_mp = $data_select_user_mp['stok'] - $data_select_user_mp['jumlah'];
    }

if ($transaction == 'settlement') {
    // UPDATE STOK DAN TERJUAL
    $update_stok_mp = $server->query("UPDATE `iklan` SET `stok`='$iklan_stok_mp',`terjual`='$iklan_terjual_mp' WHERE `id`='$id_iklan_mp' ");
    
    $update_invoice = $server->query("UPDATE `invoice` SET `tipe_progress`='$tipe_progress', `transaction`='$transaction', `type`='$type', `order_id`='$order_id', `fraud`='$fraud', `waktu_transaksi`='$time' WHERE `idinvoice`='$id_invoice'");
    // NOTIFIKASI SETTLEMENT
    $insert_notif_payment = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) VALUES ('$iduser_payment', '$id_invoice', 'Pembayaran Berhasil', 'Pembayaran pesanan sudah berhasil terverifikasi', '$time', '')");
    $insert_notif_dikemas = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) VALUES ('$iduser_payment', '$id_invoice', 'Pesanan Dikemas', 'Pesanan sedang dalam proses pengemasan oleh penjual', '$time', '')");

    // NOTIF EMAIL PEMBELI
    $subject_settlement_buyer = "Pembayaran Berhasil Diverifikasi!";
    $message_settlement_buyer = "Halo $nama_user_mp! Selamat Pembayaran Produk $judul_barang_mp Telah Berhasil Di Verifikasi, Sekarang Sudah Masuk Dalam Proses Pengemasan Produk Oleh Penjual.";
    EmailSend("$email_user_mp", "$subject_settlement_buyer", "$message_settlement_buyer", "", $server);
    
    // NOTIF EMAIL PENJUAL
    $subject_settlement_seller = "Pembayaran Berhasil Diterima!";
    $message_settlement_seller = "Halo Seller! $nama_user_mp Telah Berhasil Melunasi Produk $judul_barang_mp Telah Berhasil Di Verifikasi, Mohon Untuk Segera Update Progress Pemesanan Di Halaman Admin Setelah Barang Dalam Proses Pengiriman.";
    EmailSend("$email_penjual", "$subject_settlement_seller", "$message_settlement_seller", "", $server);
    
} elseif ($transaction == 'expire') {
    // NOTIFIKASI EXPIRE
    $insert_notif_expire = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) VALUES ('$iduser_payment', '$id_invoice', 'Transaksi Kedaluwarsa', 'Transaksi pembayaran kedaluwarsa, harap lakukan pembayaran ulang', '$time', '')");

    // NOTIF EMAIL PEMBELI
    $subject_expire = "Transaksi Pembayaran Kedaluwarsa!";
    $message_expire = "Halo $nama_user_mp! Transaksi pembayaran kedaluwarsa pada pembelian $judul_barang_mp. Harap lakukan pembayaran ulang.";
    $message_expire_seller = "Halo Seller! Transaksi pembayaran oleh $nama_user_mp pada pembelian $judul_barang_mp telah kedaluwarsa. Harap bersabar.";
    EmailSend("$email_user_mp", "$subject_expire", "$message_expire", "", $server);
    // NOTIF EMAIL PENJUAL
    EmailSend("$email_penjual", "$subject_expire", "$message_expire_seller", "", $server);
    
} elseif ($transaction == 'pending') {
    // NOTIFIKASI PENDING
    $insert_notif_pending = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) VALUES ('$iduser_payment', '$id_invoice', 'Menunggu Pembayaran', 'Transaksi pembayaran masih menunggu, silakan selesaikan pembayaran', '$time', '')");

    // NOTIF EMAIL PEMBELI
    $subject_pending = "Transaksi Menunggu Pembayaran!";
    $message_pending = "Halo $nama_user_mp! Transaksi pembayaran untuk $judul_barang_mp masih menunggu, harap selesaikan pembayaran.";
    $message_pending_seller = "Halo Seller! Transaksi pembayaran untuk $judul_barang_mp oleh $nama_user_mp masih menunggu, harap bersabar.";
    EmailSend("$email_user_mp", "$subject_pending", "$message_pending", "", $server);
    // NOTIF EMAIL PENJUAL
    EmailSend("$email_penjual", "$subject_pending", "$message_pending_seller", "", $server);
    
} else {
    $update_invoice = $server->query("UPDATE `invoice` SET `transaction`='$transaction', `type`='$type', `order_id`='$order_id', `fraud`='$fraud', `waktu_transaksi`='$time' WHERE `idinvoice`='$id_invoice'");
}
