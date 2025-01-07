<?php
include '../../config.php';
 
$idinvoiceprint = $_GET['idinvoiceprint'];

$select_invoice_print = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$idinvoiceprint' ");
$data_invoice_print = mysqli_fetch_assoc($select_invoice_print);

$exp_idiklaniv = explode(',', $data_invoice_print['id_iklan']);
$jidiklaniv = count($exp_idiklaniv) - 1;

$provinsi_exp_p = explode(',', $data_invoice_print['provinsi']);
$kota_exp_p = explode(',', $data_invoice_print['kota']);
$kecamatan_exp_p = explode(',', $data_invoice_print['kecamatan']);

$iklanidsingle = $exp_idiklaniv[0];
$siklanusertop = $server->query("SELECT * FROM `akun`, `iklan` WHERE iklan.id='$iklanidsingle' AND iklan.user_id=akun.id ");
$data_siklanusertop = mysqli_fetch_assoc($siklanusertop);

$idpembel = $data_invoice_print['id_user'];
$siuserpembeli = $server->query("SELECT * FROM `akun` WHERE `id`='$idpembel' ");
$data_siuserpembeli = mysqli_fetch_assoc($siuserpembeli);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice #<?php echo $idinvoiceprint; ?></title>
    <link rel="icon" href="../../../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../../../assets/css/admin/transaction/print/invoice.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body>
    <div style="width: 100%; font-size: 12px; font-weight: 400;">
        <div style="display: flex; flex-dirction: row; align-items: center;">
            <img src="../../../../assets/icons/<?php echo $logo; ?>" style="width: 30px;">
            <h5 style="margin-left: 15px; font-weight: 500; font-size: 16px;">Nota Pesanan</h5>
        </div>
        <div style="margin-top: 20px; line-height: 10px;">
            <p>Pembeli: <?php echo $data_siuserpembeli['nama_lengkap']; ?></p>
            <p>Alamat: <?php echo $provinsi_exp_p[1] . ', ' . $kota_exp_p[1] . ', ' . $kecamatan_exp_p[1] . ', ' . $data_invoice_print['alamat_lengkap']; ?></p>
            <p>Kurir: <?php echo strtoupper($data_invoice_print['kurir']) . ' ' . $data_invoice_print['layanan_kurir']; ?></p>
            <p><?php echo $data_invoice_print['waktu']; ?></p>
        </div>
        <hr style="border-top: 1px solid black;">
        <div style="line-height: 10px;">
            <?php
            $hargaexp = explode(',', $data_invoice_print['harga_i']);
            $diskonexp = explode(',', $data_invoice_print['diskon_i']);
            $jumlahexp = explode(',', $data_invoice_print['jumlah']);
            $hargadiskonall = 0;
            for ($jiidiklop = 0; $jiidiklop <= $jidiklaniv; $jiidiklop++) {
                $idiklanlop = $exp_idiklaniv[$jiidiklop];
                $selectiklanlop = $server->query("SELECT * FROM `iklan` WHERE `id`='$idiklanlop' ");
                $data_selectiklanlop = mysqli_fetch_assoc($selectiklanlop);
                $hitung_diskon_fs = ($diskonexp[$jiidiklop] / 100) * $hargaexp[$jiidiklop];
                $harga_diskon_fs = ($hargaexp[$jiidiklop] - $hitung_diskon_fs) * $jumlahexp[$jiidiklop];
                $hargadiskonall += $harga_diskon_fs;
                $jumlahallsatuan = $hargaexp[$jiidiklop];
            ?>
                <div style="display: grid; grid-template-columns: 5fr 1fr 2fr 2fr; gap: 10px;">
                    <p style="white-space: nowrap; overflow: hidden; text-overflow: clip;"><?php echo $data_selectiklanlop['judul']; ?></p>
                    <p style=" text-align: right;"><?php echo $jumlahexp[$jiidiklop]; ?></p>
                    <p style=" text-align: right;"><?php echo number_format($jumlahallsatuan, 0, ".", "."); ?></p>
                    <p style=" text-align: right;"><?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></p>
                </div>
            <?php
            }
            $harga_semua_fs = ($hargadiskonall + $data_invoice_print['harga_ongkir']) - $data_invoice_print['diskon_min'];
            ?>
        </div>
        <hr style="border-top: 1px solid black;">
        <div style="line-height: 11px;">
            <div style="display: grid; grid-template-columns: 3.5fr 1fr; gap: 10px;">
                <p style="text-align: right;">Sub Total:</p>
                <p style="text-align: right;"><?php echo number_format($hargadiskonall, 0, ".", "."); ?></p>
            </div>
            <div style="display: grid; grid-template-columns: 3.5fr 1fr; gap: 10px;">
                <p style="text-align: right;">Ongkir:</p>
                <p style="text-align: right;"><?php echo number_format($data_invoice_print['harga_ongkir'], 0, ".", "."); ?></p>
            </div>
            <?php
            if ($data_invoice_print['diskon_min'] != '0') {
            ?>
                <div style="display: grid; grid-template-columns: 3.5fr 1fr; gap: 10px;">
                    <p style="text-align: right;">Potongan:</p>
                    <p style="text-align: right;">-<?php echo number_format($data_invoice_print['diskon_min'], 0, ".", "."); ?></p>
                </div>
            <?php
            }
            ?>
            <div style="display: grid; grid-template-columns: 3.5fr 1fr; gap: 10px;">
                <p style="text-align: right;">Total Pembayaran:</p>
                <p style="text-align: right;"><?php echo number_format($harga_semua_fs, 0, ".", "."); ?></p>
            </div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>