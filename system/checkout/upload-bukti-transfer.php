<?php
include '../../config.php';
include '../../system/email/class.phpmailer.php';
include '../../system/email/send-email.php';

$time = date("Y-m-d H:i:s");
$id_inv_bt = $_POST['id_inv_bt'];
$nama_bank_bt = $_POST['nama_bank_bt'];

if (!empty($_FILES["inp_bukti_transfer"]["name"])) {
    $expname1 = explode('.', $_FILES["inp_bukti_transfer"]["name"]);
    $ext1 = end($expname1);
    $name1 = $id_inv_bt . '-bukti-transfer' . '.' . $ext1;
    $path1 = "../../assets/image/bukti-transfer/" . $name1;
    move_uploaded_file($_FILES["inp_bukti_transfer"]["tmp_name"], $path1);
}

$update_bt = $server->query("UPDATE `invoice` SET `bank_manual`='$nama_bank_bt',`bukti_transfer`='$name1',`waktu_transaksi`='$time' WHERE `idinvoice`='$id_inv_bt' ");

// Query untuk mendapatkan email pemilik iklan dan nama pembeli berdasarkan ID invoice yang terjual
$query_get_owner_and_buyer_info = "SELECT akun.email AS email_pemilik, akun.nama_lengkap AS nama_pemilik, pembeli.nama_lengkap AS nama_pembeli, iklan.judul
                                   FROM invoice
                                   JOIN iklan ON invoice.id_iklan = iklan.id
                                   JOIN akun ON iklan.user_id = akun.id
                                   JOIN akun AS pembeli ON invoice.id_user = pembeli.id
                                   WHERE invoice.idinvoice = '$id_inv_bt'";
$result_owner_and_buyer_info = $server->query($query_get_owner_and_buyer_info);

if ($result_owner_and_buyer_info && $result_owner_and_buyer_info->num_rows > 0) {
    $data_owner_buyer_info = $result_owner_and_buyer_info->fetch_assoc();
    $email_pemilik_iklan = $data_owner_buyer_info['email_pemilik'];
    $nama_pemilik_iklan = $data_owner_buyer_info['nama_pemilik'];
    $nama_pembeli = $data_owner_buyer_info['nama_pembeli'];
    $judul_barang_mp = $data_owner_buyer_info['judul'];

    if (!empty($email_pemilik_iklan)) {
    // Persiapan notifikasi email kepada pemilik iklan
    $deskripsi_email_pemilik = "<div style='font-family: Arial, sans-serif; width: 100%; padding: 20px 30px; background-color: #f5f5f5; box-sizing: border-box;'>
    <h1 style='font-size: 17px; font-weight: bold;'>Halo! $nama_pemilik_iklan</h1>
    <p style='font-size: 16px; margin-top: 10px;'>Pembeli dengan nama lengkap <span style='font-weight: bold;'>$nama_pembeli</span> baru saja telah melakukan transaksi pembelian produk <span style='font-weight: bold;'>$judul_barang_mp</span>, mohon untuk segera diperiksa dan segera mungkin untuk menanggapinya. Silahkan masuk ke profil kamu dan cek dibagian Transaksi.</p>
    </div>";

    // NOTIF EMAIL PENJUAL
    $judul_progress_produk = "$nama_pembeli Telah Melakukan Transaksi!";
    EmailSend($email_pemilik_iklan, $judul_progress_produk, $deskripsi_email_pemilik, "", $server);

    // Jika ingin menambahkan tindakan lain setelah pengiriman email, tambahkan di sini
}
}

if ($update_bt) {
?>
    <script>
        window.location.href = '../../checkout/detail/<?php echo $id_inv_bt; ?>';
    </script>
<?php
}
?>
