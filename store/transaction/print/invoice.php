<?php
include '../../../config.php';

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
    <link rel="icon" href="../../../../assets/icons/<?php echo $logo; ?>" type="image/svg">
    <link rel="stylesheet" href="../../../../assets/css/admin/transaction/print/invoice.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="invoice-title">
                    <img src="../../../../assets/icons/<?php echo $logo; ?>" style="width: 40px; height: 40px; margin-right: 15px; margin-top: -12px;">
                    <h2>Nota Pesanan</h2>
                    <h3 class="pull-right">#<?php echo $idinvoiceprint; ?></h3>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                            <strong>Nama Pembeli:</strong><br>
                            <?php echo $data_siuserpembeli['nama_lengkap']; ?><br>
                        </address>
                        <address>
                            <strong>Alamat Pembeli:</strong><br>
                            <?php echo $provinsi_exp_p[1] . ', ' . $kota_exp_p[1] . ', ' . $kecamatan_exp_p[1] . ', ' . $data_invoice_print['alamat_lengkap']; ?><br>
                        </address>
                    </div>
                    <div class="col-xs-6 text-right">
                        <address>
                            <strong>Penjual:</strong><br>
                            <?php echo $data_siklanusertop['nama_lengkap']; ?><br>
                            <?php echo $provinsi_toko . ', ' . $kota_toko . ', ' . $kecamatan_toko; ?><br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <address>
                            <strong>Waktu Pembayaran:</strong><br>
                            <?php echo $data_invoice_print['waktu']; ?><br>
                        </address>
                    </div>
                    <div class="col-xs-3">
                        <address>
                            <strong>Metode Pembayaran:</strong><br>
                            <?php
                            if ($data_invoice_print['bank_manual'] == '') {
                            ?>
                                <?php echo $data_invoice_print['type']; ?><br>
                            <?php
                            } else {
                            ?>
                                <?php echo $data_invoice_print['bank_manual']; ?> (Verifikasi Manual)<br>
                            <?php
                            }
                            ?>
                        </address>
                    </div>
                    <div class="col-xs-3">
                        <address>
                            <strong>Jasa Kirim:</strong><br>
                            <?php echo strtoupper($data_invoice_print['kurir']) . ' ' . $data_invoice_print['layanan_kurir']; ?><br>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Rincian Pesanan</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <td><strong>Produk</strong></td>
                                        <td class="text-center"><strong>Harga Produk</strong></td>
                                        <td class="text-center"><strong>Kuantitas</strong></td>
                                        <td class="text-right"><strong>Subtotal</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                    <?php
                                    $hargaexp = explode(',', $data_invoice_print['harga_i']);
                                    $diskonexp = explode(',', $data_invoice_print['diskon_i']);
                                    $jumlahexp = explode(',', $data_invoice_print['jumlah']);
                                    $jumlahallsatuan = 0;
                                    $hargadiskonall = 0;
                                    for ($jiidiklop = 0; $jiidiklop <= $jidiklaniv; $jiidiklop++) {
                                        $idiklanlop = $exp_idiklaniv[$jiidiklop];
                                        $selectiklanlop = $server->query("SELECT * FROM `iklan` WHERE `id`='$idiklanlop' ");
                                        $data_selectiklanlop = mysqli_fetch_assoc($selectiklanlop);
                                        $hitung_diskon_fs = ($diskonexp[$jiidiklop] / 100) * $hargaexp[$jiidiklop];
                                        $harga_diskon_fs = ($hargaexp[$jiidiklop] - $hitung_diskon_fs) * $jumlahexp[$jiidiklop];
                                        $hargadiskonall += $harga_diskon_fs;
                                        $jumlahallsatuan += $harga_diskon_fs;
                                    ?>
                                        <tr>
                                            <td><?php echo substr($data_selectiklanlop['judul'], 0, 30); ?>...</td>
                                            <td class="text-center">Rp <?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></td>
                                            <td class="text-center"><?php echo $jumlahexp[$jiidiklop]; ?></td>
                                            <td class="text-right">Rp <?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    $harga_semua_fs = $jumlahallsatuan + $data_invoice_print['harga_ongkir'];
                                    ?>
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line"></td>
                                        <td class="thick-line text-center"><strong>Subtotal Produk</strong></td>
                                        <td class="thick-line text-right">Rp <?php echo number_format($hargadiskonall, 0, ".", "."); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Ongkir</strong></td>
                                        <td class="no-line text-right">Rp <?php echo number_format($data_invoice_print['harga_ongkir'], 0, ".", "."); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Total Pembayaran</strong></td>
                                        <td class="no-line text-right">Rp <?php echo number_format($harga_semua_fs, 0, ".", "."); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>