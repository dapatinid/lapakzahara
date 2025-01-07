<?php
include '../../config.php';

$idproduk = $_POST['id_product'];

// SELECT CART
$select_cart = $server->query("SELECT * FROM `keranjang` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser'");
$cart_data = mysqli_fetch_assoc($select_cart);

// SELECT PRODUK
$select_iklan = $server->query("SELECT * FROM `iklan` WHERE `id`='$idproduk'");
$iklan_data = mysqli_fetch_assoc($select_iklan);

// SELECT LOKASI USER
$lokasi_user = $server->query("SELECT * FROM `lokasi_user` WHERE `id_user`='$iduser'");
$lokasi_user_data = mysqli_fetch_assoc($lokasi_user);

$id_iklan = $iklan_data['id'];

$jumlah_produk_sementara = $_POST['jumlah_product'];
$jumlah_produk_tulis = $_POST['jumlah_product'];
if ($jumlah_produk_sementara === 'null' || $jumlah_produk_sementara == 0 || $jumlah_produk_sementara === '') {
    $jumlah = 1;
} else {
    $jumlah = $jumlah_produk_tulis;
}

$harga_k = $_POST['ukuran_harga_satuan_value_send'];
$diskon_k = $iklan_data['diskon'];
$warna_k = $_POST['warna_k_val'];
$ukuran_k = $_POST['ukuran_k_val'];
$id_lokasi_k = $_POST['id_lokasi'];
$lokasi_k = $_POST['lokasi'];

$totalhgo = $harga_k * $jumlah;

// CEK DISKON
$s_diskon_bas = $server->query("SELECT * FROM `setting_diskon` WHERE `id`='1' ");
$data_s_diskon_bas = mysqli_fetch_assoc($s_diskon_bas);

if ($data_s_diskon_bas['min_nominal'] < $totalhgo) {
    $jum_dis_bas = ($data_s_diskon_bas['persen'] / 100) * $totalhgo;
} else {
    $jum_dis_bas = '0';
}

$select_lokasi_by_kota = $server->query("SELECT * FROM `akun` WHERE `kecamatan_id_user`='$id_lokasi_k' ");
$data_select_lokasi_by_kota = mysqli_fetch_assoc($select_lokasi_by_kota);

// Pemindahan $id_store dilakukan di luar kondisi karena nilainya tetap
$id_store = $data_select_lokasi_by_kota['id'];
$idkecamatan_store = $data_select_lokasi_by_kota['kecamatan_id_user'];

// Memeriksa apakah user_id pada $iklan_data adalah '0' atau tidak
if ($iklan_data['user_id'] == '0') {
    $kecamatan_cko = $kecamatan_id_toko;
} else {
    // Pemilihan data berdasarkan idproduk sebaiknya langsung dilakukan di query
    $select_user_lokasi_mp = $server->query("SELECT * FROM `iklan` INNER JOIN `akun` ON iklan.user_id=akun.id WHERE iklan.id='$idproduk'");
    $data_user_lokasi_mp = mysqli_fetch_assoc($select_user_lokasi_mp);
    $kecamatan_cko = $data_user_lokasi_mp['kecamatan_id_user'];
}



$time = date('Y-m-d H:i:s');
$tipe_progress = 'Belum Bayar';

$kurir = 'Ongkir Menyusul';
$id_kurir = '0';
$berat_barang = $iklan_data['berat'] * $jumlah;

$cek_invoice = $server->query("SELECT * FROM `invoice` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser' ORDER BY `invoice`.`idinvoice` DESC");
$cek_invoice_data = mysqli_fetch_assoc($cek_invoice);

if ($cek_invoice_data['tipe_progress'] == 'Belum Bayar') {
    $idinvoice_cko = $cek_invoice_data['idinvoice'];
    $update_invoice = $server->query("UPDATE `invoice` SET `jumlah`='$jumlah',`harga_i`='$harga_k',`diskon_i`='$diskon_k',`idloc`='$id_store' WHERE `idinvoice`='$idinvoice_cko' AND `id_user`='$iduser'");
    $delete_cart_ck = $server->query("DELETE FROM `keranjang` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser'");
    if ($update_invoice || $delete_cart_ck) {
?>
        <script>
            window.location.href = "<?php echo $url; ?>/checkout/detail/<?php echo $idinvoice_cko; ?>";
        </script>
    <?php
    }
} else {
    if ($lokasi_user_data) {
        $prov_inv = $lokasi_user_data['id_provinsi'] . ',' . $lokasi_user_data['provinsi'];
        $kota_inv = $lokasi_user_data['id_kota'] . ',' . $lokasi_user_data['kota'];
        $kecamatan_inv = $lokasi_user_data['id_kecamatan'] . ',' . $lokasi_user_data['kecamatan'];
        $alengkap_inv = $lokasi_user_data['alamat_lengkap'];
        $notelp_inv = $lokasi_user_data['notelp'];

        $kecamatan_tujuan = $lokasi_user_data['id_kecamatan'];
        // JNE
        $curl_jne = curl_init();
        curl_setopt_array($curl_jne, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$idkecamatan_store&originType=subdistrict&destination=$kecamatan_tujuan&destinationType=subdistrict&weight=$berat_barang&courier=$kurir",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: $rajaongkir_key"
            ),
        )); 
        $response_cost_jne = curl_exec($curl_jne);
        $err_cost_jne = curl_error($curl_jne);
        curl_close($curl_jne);
        if ($err_cost_jne) {
            echo "cURL Error #:" . $err_cost_jne;
        } else {
            $data_cost_jne = json_decode($response_cost_jne, true);
            $data_cost_jne_arr = $data_cost_jne['rajaongkir']['results']['0']['costs'];
        
            // Mendapatkan nilai "gratis_ongkir" dari tabel iklan
            $s_gratis_ongkir = $iklan_data['gratis_ongkir'];
        
            // Tentukan apakah harga ongkir akan menjadi gratis atau menggunakan harga dari API
            if ($data_s_skro['status'] == 'Aktif' && strtolower($s_gratis_ongkir) === 'ya') {
                $harga_ongkir = '0'; // Set harga ongkir menjadi 0 (gratis ongkir)
                $gotp = 'ya';
            } else {
                $kurir_ongkir = $data_cost_jne['rajaongkir']['results']['0']['code'];
                $kurir_layanan_ongkir = $data_cost_jne_arr[$id_kurir]['service'];
                $etd_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['etd'];
        
                if (!empty($s_gratis_ongkir) && $data_s_gratis_ongkir['min_nominal'] < $totalhgo) {
                    $harga_ongkir = '0';
                    $gotp = 'ya';
                } else {
                    $harga_ongkir =  $data_cost_jne_arr[$id_kurir]['cost']['0']['value'];
                    $gotp = '';
                }
            }
        }                
        // $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', '$kurir_ongkir', '$id_kurir', '$kurir_layanan_ongkir', '$etd_ongkir', '$harga_ongkir', '', '$prov_inv', '$kota_inv', '$kecamatan_inv', '$alengkap_inv', '$notelp_inv', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
        $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', 'Ambil Sendiri', '$id_kurir', '$kurir_layanan_ongkir', '$etd_ongkir', '$harga_ongkir', '', '$prov_inv', '$kota_inv', '$kecamatan_inv', '$alengkap_inv', '$notelp_inv', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
    } else {
        // $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', '$kurir', '$id_kurir', '', '', '0', '', '', '', '', '', '', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
        $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', 'Ambil Sendiri', '$id_kurir', '', '', '0', '', '', '', '', '', '', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
    }
    $delete_cart_ck = $server->query("DELETE FROM `keranjang` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser'");
    if ($insert_checkout || $delete_cart_ck) {
        $select_invoice = $server->query("SELECT * FROM `invoice` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser' ORDER BY `invoice`.`idinvoice` DESC");
        $invoice_data = mysqli_fetch_assoc($select_invoice);
        $idinvoice_cko = $invoice_data['idinvoice'];
    ?>
        <script>
            window.location.href = "<?php echo $url; ?>/checkout/detail/<?php echo $idinvoice_cko; ?>";
        </script>
<?php
    }
}
