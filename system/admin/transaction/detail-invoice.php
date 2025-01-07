<?php
include '../../../config.php';

$idinv_vi = $_POST['idinv_vi'];
$s_ivdi = $server->query("SELECT * FROM `akun`, `invoice` WHERE invoice.idinvoice='$idinv_vi' AND invoice.id_user=akun.id ");
$data_s_ivdi = mysqli_fetch_assoc($s_ivdi);

// EXP LOKASI PEMBELI
$prov_pem = explode(',', $data_s_ivdi['provinsi']);
$kota_pem = explode(',', $data_s_ivdi['kota']);
$kec_pem = explode(',', $data_s_ivdi['kecamatan']);

// EXP PRODUK
$exp_idiklaniv = explode(',', $data_s_ivdi['id_iklan']);
$jidiklaniv = count($exp_idiklaniv) - 1;

?>
<i class="fas fa-window-close close_vd_iv" onclick="close_detail_iv()"></i>
<div class="box_g_produk_vd_iv">
    <?php
    $hargaexp = explode(',', $data_s_ivdi['harga_i']);
    $diskonexp = explode(',', $data_s_ivdi['diskon_i']);
    $jumlahexp = explode(',', $data_s_ivdi['jumlah']);
    $warnaexpa = explode(',', $data_s_ivdi['warna_i']);
    $ukuranexpa = explode(',', $data_s_ivdi['ukuran_i']);
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

        $expgambardi = explode(',', $data_selectiklanlop['gambar']);
    ?>
        <div class="produk_vd_iv">
            <img id="img_produk_vd_iv" src="../../assets/image/product/compressed/<?php echo $expgambardi[0]; ?>">
            <div class="rincian_produk_vd_iv">
                <h1 id="judul_produk_vd_iv">
                    <?php echo $data_selectiklanlop['judul']; ?>
                </h1>
                <p>Kuantitas <span id="kategori_vd_iv"><?php echo $jumlahexp[$jiidiklop]; ?></span></p>
                <p>Varian <span id="kuantitas_vd_iv"><?php echo $warnaexpa[$jiidiklop]; ?>, <?php echo $ukuranexpa[$jiidiklop]; ?></span></p>
            </div>
        </div>
    <?php
    }
    $harga_semua_fs = ($jumlahallsatuan + $data_s_ivdi['harga_ongkir']) - $data_s_ivdi['diskon_voucher'];
    ?>
</div>
<div class="rincian_vd_iv">
    <div class="isi_rincian_vd_iv">
        <h3>No. Pesanan</h3>
        <h4 id="id_pesanan_vd_iv">#<?php echo $data_s_ivdi['idinvoice']; ?></h4>
    </div>
    <div class="isi_rincian_vd_iv">
        <h3>Status Pesanan</h3>
        <h4 id="status_pesanan_vd_iv"><?php echo $data_s_ivdi['tipe_progress']; ?></h4>
    </div>
    <div class="isi_rincian_vd_iv">
        <h3>Status Pembayaran</h3>
        <h4 id="status_pembayaran_vd_iv">
            <?php
            if ($data_s_ivdi['tipe_progress'] == 'Selesai') {
                echo 'Lunas';
            } else {
                echo 'Belum Lunas';
            }
            if ($data_s_ivdi['tipe_pembayaran'] == 'cod') {
                echo ' (COD)';
            }
            ?>
        </h4>
    </div>
    <div class="isi_rincian_vd_iv">
        <h3>Nama Pembeli</h3>
        <h4 id="nama_pembeli_vd_iv"><?php echo $data_s_ivdi['nama_lengkap']; ?></h4>
    </div>
    <div class="isi_rincian_vd_iv">
        <h3>Alamat Pembeli</h3>
        <h4 id="alamat_pembeli_vd_iv"><?php echo $prov_pem[1] . ', ' . $kota_pem[1] . ', ' . $kec_pem[1]  . ', ' . $data_s_ivdi['alamat_lengkap']; ?></h4>
    </div>
    <div class="isi_rincian_vd_iv">
        <h3>Kurir Pengiriman</h3>
        <h4 id="kurir_pengiriman_vd_iv"><?php echo $data_s_ivdi['kurir'] . ' ' . $data_s_ivdi['layanan_kurir']; ?></h4>
    </div>
    <?php
    if ($data_s_ivdi['notelp'] != '') {
    ?>
        <div class="isi_rincian_vd_iv">
            <h3>Nomor Telepon Penerima</h3>
            <h4 id="kurir_pengiriman_vd_iv"><?php echo $data_s_ivdi['notelp']; ?></h4>
        </div>
    <?php
    }
    ?>
    <?php
    if ($data_s_ivdi['catatan'] != '') {
    ?>
        <div class="isi_rincian_vd_iv">
            <h3>Catatan</h3>
            <h4 id="kurir_pengiriman_vd_iv"><?php echo $data_s_ivdi['catatan']; ?></h4>
        </div>
    <?php
    }
    ?>
    <div class="isi_rincian_vd_iv">
        <h3>Total Pembayaran</h3>
        <h4 id="total_pembayaran_vd_iv">Rp <?php echo number_format($harga_semua_fs, 0, ".", "."); ?></h4>
    </div>
</div>
<div class="box_button_vd_id">
    <a href="../../admin/transaction/print/invoice/<?php echo $idinv_vi; ?>" class="link" id="link_print_vd_iv">
        <div class="button">
            <p>Print Nota Pesanan</p>
        </div>
    </a>
</div>