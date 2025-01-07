<?php
include '../../../config.php';
include '../../../system/email/class.phpmailer.php';
include '../../../system/email/send-email.php';

$idinvoicesb = $_POST['idinvoice_bpk'];
$time = date("Y-m-d H:i:s"); 

$select_invoice_ud = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$idinvoicesb'");
$data_invoice_ud = mysqli_fetch_assoc($select_invoice_ud);
$iduser_ud = $data_invoice_ud['id_user'];

$jumlah_pengembalian = 0; // Inisialisasi jumlah pengembalian

$exp_harga_i = explode(',', $data_invoice_ud['harga_i']);
$exp_diskon_i = explode(',', $data_invoice_ud['diskon_i']);
$exp_harga_ongkir = explode(',', $data_invoice_ud['harga_ongkir']);
$exp_jumlah = explode(',', $data_invoice_ud['jumlah']);
$diskon_voucher = $data_invoice_ud['diskon_voucher'];

for ($i = 0; $i < count($exp_harga_i); $i++) {
    // Hitung harga setelah diskon
    $harga_setelah_diskon = $exp_harga_i[$i] - ($exp_harga_i[$i] * $exp_diskon_i[$i] / 100);
    
    // Hitung harga total termasuk jumlah dan harga ongkir
    $harga_total = ($harga_setelah_diskon * $exp_jumlah[$i]) + $exp_harga_ongkir[$i];
    
    // Tambahkan ke jumlah pengembalian
    $jumlah_pengembalian += $harga_total;
}

// Kurangi dengan diskon_voucher
$jumlah_pengembalian -= $diskon_voucher;


// Check apakah user sudah ada di tabel saldo
$check_user_query = $server->prepare("SELECT * FROM `saldo` WHERE `user_id`=?");
$check_user_query->bind_param('s', $iduser_ud);
$check_user_query->execute();
$result = $check_user_query->get_result();

if ($result->num_rows === 0) {
    // Jika user belum ada di tabel saldo, lakukan INSERT
    $insert_user_query = $server->prepare("INSERT INTO `saldo` (`user_id`, `jumlah_saldo`) VALUES (?, ?)");
    $insert_user_query->bind_param('ss', $iduser_ud, $jumlah_pengembalian);
    $insert_user_query->execute();
} else {
    // Jika user sudah ada, lakukan UPDATE
    $update_user_query = $server->prepare("UPDATE `saldo` SET `jumlah_saldo` = `jumlah_saldo` + ? WHERE `user_id` = ?");
    $update_user_query->bind_param('ss', $jumlah_pengembalian, $iduser_ud);
    $update_user_query->execute();
}

// Tambahkan ke riwayat pengembalian
$insert_riwayat_pengembalian = $server->query("INSERT INTO `riwayat_pengembalian` (`user_id`, `tanggal`, `jumlah_pengembalian`, `keterangan`) VALUES ('$iduser_ud', '$time', '$jumlah_pengembalian', 'Pengembalian dana dari pesanan dibatalkan')");

// Update status invoice
$update_dikirim = $server->query("UPDATE `invoice` SET `tipe_progress`='Dibatalkan', `waktu_dibatalkan`='$time' WHERE `idinvoice`='$idinvoicesb'");

// Query untuk mendapatkan judul barang dari tabel iklan berdasarkan ID iklan dari tabel invoice
$select_judul_query = $server->query("SELECT iklan.judul FROM invoice INNER JOIN iklan ON invoice.id_iklan = iklan.id WHERE invoice.idinvoice='$idinvoicesb'");
$data_select_judul = mysqli_fetch_assoc($select_judul_query);

$judul_barang_mp = $data_select_judul['judul'];

// Kemudian gunakan nilai $judul_barang_mp dalam query notifikasi
$insert_notif_ud = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) 
    VALUES ('$iduser_ud', '$idinvoicesb', 'Pembatalan Pesanan', 
    'Pesanan Anda telah dibatalkan oleh penjual. Dana pengembalian sebesar Rp" . number_format($jumlah_pengembalian, 0, ',', '.') . " untuk produk $judul_barang_mp telah berhasil masuk ke saldo Anda.', 
    '$time', '')");

// NOTIF EMAIL
$select_user_mp = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$idinvoicesb' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_user_mp = mysqli_fetch_assoc($select_user_mp);

$email_user_mp = $data_select_user_mp['email'];
$nama_user_mp = $data_select_user_mp['nama_lengkap'];
$judul_barang_mp = $data_select_user_mp['judul'];

$deskripsi_email = "Hi $nama_user_mp
<br><br>
Produk <span style='font-weight: bold;'>$judul_barang_mp</span> Telah Berhasil Dibatalkan. Dimohon Menghubungi Admin Untuk Menarik Uang Dari Produk Yang Dibatalkan.
<br><br>
Salam Hangat,
<br>
$title_name - $slogan";

EmailSend("$email_user_mp", "Paket Dibatalkan!", "$deskripsi_email", "", $server);
?>
