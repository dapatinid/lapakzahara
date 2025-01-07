<?php
include '../../config.php';
include '../../system/email/class.phpmailer.php';
include '../../system/email/send-email.php';

// Ambil data dari permintaan AJAX
$idInvoice = $_POST['idInvoice'];
$alasanLaporkan = $_POST['alasanLaporkan'];
$deskripsiMasalah = $_POST['deskripsiMasalah'];
// Tentukan waktu saat ini untuk waktu_dibatalkan
$waktuDibatalkan = date("Y-m-d H:i:s");

// Tentukan nilai yang akan disimpan berdasarkan opsi alasan
if ($alasanLaporkan == 'lainnya') {
    $nilaiAlasan = $deskripsiMasalah;
} else {
    $nilaiAlasan = $alasanLaporkan;
}

$select_invoice_ud = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$idInvoice'");
$data_invoice_ud = mysqli_fetch_assoc($select_invoice_ud);
$iduser_ud = $data_invoice_ud['id_user'];


// Tambahkan ke tabel pembatalan_transaksi
$insert_pembatalan_query = $server->prepare("INSERT INTO `pembatalan_transaksi` (`id_invoice`, `id_user`, `alasan_pembatalan`, `waktu_pembatalan`, `status_persetujuan`) VALUES (?, ?, ?, ?, 'Menunggu Persetujuan')");
$insert_pembatalan_query->bind_param('ssss', $idInvoice, $iduser_ud, $nilaiAlasan, $waktuDibatalkan);
$insert_pembatalan_query->execute();


// Query untuk mendapatkan judul barang dari tabel iklan berdasarkan ID iklan dari tabel invoice
$select_judul_query = $server->query("SELECT iklan.judul FROM invoice INNER JOIN iklan ON invoice.id_iklan = iklan.id WHERE invoice.idinvoice='$idInvoice'");
$data_select_judul = mysqli_fetch_assoc($select_judul_query);

$judul_barang_mp = $data_select_judul['judul'];

// Kemudian gunakan nilai $judul_barang_mp dalam query notifikasi
$insert_notif_ud = $server->query("INSERT INTO `notification`(`id_user`, `id_invoice`, `nama_notif`, `deskripsi_notif`, `waktu_notif`, `status_notif`) 
    VALUES ('$iduser_ud', '$idInvoice', 'Pengajuan Pembatalan Pesanan', 
    'Pesanan Anda $judul_barang_mp telah masuk dalam daftar pengajuan pembatalan. Mohon berkenan menunggu hingga proses berhasil disetujui!', 
    '$waktuDibatalkan', '')");

// NOTIF EMAIL
$select_user_mp = $server->query("SELECT * FROM `invoice`, `iklan`, `akun` WHERE invoice.idinvoice='$idInvoice' AND invoice.id_iklan=iklan.id AND invoice.id_user=akun.id ");
$data_select_user_mp = mysqli_fetch_assoc($select_user_mp);

$email_user_mp = $data_select_user_mp['email'];
$nama_user_mp = $data_select_user_mp['nama_lengkap'];
$judul_barang_mp = $data_select_user_mp['judul'];

$deskripsi_email = "Hi $nama_user_mp
<br><br>
Produk <span style='font-weight: bold;'>$judul_barang_mp</span> Telah masuk ke pengajuan untuk dibatalkan.
<br><br>
Salam Hangat,
<br>
$title_name - $slogan";

EmailSend("$email_user_mp", "Pengajuan Pembatalan!", "$deskripsi_email", "", $server);

if ($insert_pembatalan_query) {
    ?>
    <script>
        window.location.href = '';
    </script>
    <?php
}
?>
