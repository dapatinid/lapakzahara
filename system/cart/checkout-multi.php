<?php
include '../../config.php';

$idproduk = $_POST['id_product'];
$expidproduk = explode(',', $idproduk);

// ARRAY IMP
$jumlah_a = array();
$harga_k_a = array();
$diskon_k_a = array();
$warna_k_a = array();
$ukuran_k_a = array();
$id_lokasi_k_a = array();
$lokasi_k_a = array();
$berat_barang = 0;
$harga_semua_cmk = 0;

foreach ($expidproduk as $k_idproduk => $v_idproduk) {
    // SELECT CART
    $select_cart = $server->query("SELECT * FROM `keranjang` WHERE `id_iklan`='$v_idproduk' AND `id_user`='$iduser'");

    while ($cart_data = mysqli_fetch_assoc($select_cart)) {
        // SELECT PRODUK
        $select_iklan = $server->query("SELECT * FROM `iklan` WHERE `id`='$v_idproduk'");
        $iklan_data = mysqli_fetch_assoc($select_iklan);

        if ($iklan_data) {
            $jumlah_a[] = $cart_data['jumlah'];
            $harga_k_a[] = $cart_data['harga_k'];
            $diskon_k_a[] = $cart_data['diskon_k'];
            $warna_k_a[] = $cart_data['warna_k'];
            $ukuran_k_a[] = $cart_data['ukuran_k'];
            $id_lokasi_k_a[] = $cart_data['id_lokasi_k'];
            $lokasi_k_a[] = $cart_data['lokasi_k'];

            $berat_per_produk = $iklan_data['berat'] * $cart_data['jumlah'];
            $berat_barang += $berat_per_produk;

            $hkj = $cart_data['harga_k'] * $cart_data['jumlah'];
            $harga_semua_cmk += $hkj;
        }
    }
}


// CEK DISKON
$s_diskon_bas = $server->query("SELECT * FROM `setting_diskon` WHERE `id`='1' ");
$data_s_diskon_bas = mysqli_fetch_assoc($s_diskon_bas);

if ($data_s_diskon_bas['min_nominal'] < $harga_semua_cmk) {
    $jum_dis_bas = ($data_s_diskon_bas['persen'] / 100) * $harga_semua_cmk;
} else {
    $jum_dis_bas = '0';
}

// SELECT LOKASI USER
$lokasi_user = $server->query("SELECT * FROM `lokasi_user` WHERE `id_user`='$iduser'");
$lokasi_user_data = mysqli_fetch_assoc($lokasi_user);

$id_iklan = $idproduk;
$jumlah = implode(',', $jumlah_a);
$harga_k = implode(',', $harga_k_a);
$diskon_k = implode(',', $diskon_k_a);
$warna_k = implode(',', $warna_k_a);
$ukuran_k = implode(',', $ukuran_k_a);
$id_lokasi_k = implode(',', $id_lokasi_k_a);
$lokasi_k = implode(',', $lokasi_k_a);

$time = date('Y-m-d H:i:s');
$tipe_progress = 'Belum Bayar';

$kurir = 'jne';
$id_kurir = '0';

$select_lokasi_by_kota = $server->query("SELECT * FROM `akun` WHERE `kecamatan_id_user`='$id_lokasi_k' ");
$data_select_lokasi_by_kota = mysqli_fetch_assoc($select_lokasi_by_kota);

// Pemindahan $id_store dilakukan di luar kondisi karena nilainya tetap
$id_store = $data_select_lokasi_by_kota['id'];
$idkecamatan_store = $data_select_lokasi_by_kota['kecamatan_id_user'];

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
        if (isset($data_s_skro['status']) && $data_s_skro['status'] == 'Aktif') {
            $kurir_ongkir = $data_cost_jne['rajaongkir']['results']['0']['code'];
            $kurir_layanan_ongkir = $data_cost_jne_arr[$id_kurir]['service'];
            $etd_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['etd'];
    
            // Periksa apakah harga iklan lebih tinggi dari minimal nominal
            $min_nominal = isset($data_s_gratis_ongkir['min_nominal']) ? $data_s_gratis_ongkir['min_nominal'] : 0;
            if ($s_gratis_ongkir === 'ya' && $min_nominal <= $harga_semua_cmk) {
                $harga_ongkir = '0'; // Set harga ongkir menjadi 0 (gratis ongkir)
                $gotp = 'ya';
            } else {
                $harga_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['value'];
                $gotp = '';
            }
        } else {
            $kurir_ongkir = $data_cost_jne['rajaongkir']['results']['0']['code'];
            $kurir_layanan_ongkir = $data_cost_jne_arr[$id_kurir]['service'];
            $etd_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['etd'];
    
            // Periksa apakah harga iklan lebih tinggi dari minimal nominal
            $min_nominal = isset($data_s_gratis_ongkir['min_nominal']) ? $data_s_gratis_ongkir['min_nominal'] : 0;
            if ($s_gratis_ongkir === 'ya' && $min_nominal <= $harga_semua_cmk) {
                $harga_ongkir = '0'; // Set harga ongkir menjadi 0 (gratis ongkir)
                $gotp = 'ya';
            } else {
                $harga_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['value'];
                $gotp = '';
            }
        }
    }
    // $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', '$kurir_ongkir', '$id_kurir', '$kurir_layanan_ongkir', '$etd_ongkir', '$harga_ongkir', '', '$prov_inv', '$kota_inv', '$kecamatan_inv', '$alengkap_inv', '$notelp_inv', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
    $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', 'Ambil Sendiri', '$id_kurir', '', '', '0', '', '$prov_inv', '$kota_inv', '$kecamatan_inv', '$alengkap_inv', '$notelp_inv', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
} else {
    // $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', '$kurir', '$id_kurir', '', '', '0', '', '', '', '', '', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
    $insert_checkout = $server->query("INSERT INTO `invoice`(`id_iklan`, `id_user`, `jumlah`, `warna_i`, `ukuran_i`, `id_lokasi_i`, `lokasi_i`, `harga_i`, `diskon_i`, `tipe_pembayaran`, `kurir`, `id_kurir`, `layanan_kurir`, `etd`, `harga_ongkir`, `resi`, `provinsi`, `kota`, `kecamatan`, `alamat_lengkap`, `notelp`, `waktu`, `tipe_progress`, `transaction`, `type`, `order_id`, `fraud`, `bank_manual`, `bukti_transfer`, `waktu_transaksi`, `waktu_dikirim`, `waktu_selesai`, `waktu_dibatalkan`, `idloc`, `go`, `diskon_min`) VALUES ('$id_iklan', '$iduser', '$jumlah', '$warna_k', '$ukuran_k', '$id_lokasi_k', '$lokasi_k', '$harga_k', '$diskon_k', 'online', 'Ambil Sendiri', '$id_kurir', '', '', '0', '', '', '', '', '', '$time', '$tipe_progress', '', '', '', '', '', '', '', '', '', '', '', '$id_store', '$gotp', '$jum_dis_bas')");
}

// HAPUS DARI KERANJANG
foreach ($expidproduk as $khk_idproduk => $vhk_idproduk) {
    $delete_cart_ck = $server->query("DELETE FROM `keranjang` WHERE `id_iklan`='$vhk_idproduk' AND `id_user`='$iduser'");
}

if ($insert_checkout) {
    $select_invoice = $server->query("SELECT * FROM `invoice` WHERE `id_iklan`='$idproduk' AND `id_user`='$iduser' ORDER BY `invoice`.`idinvoice` DESC");
    $invoice_data = mysqli_fetch_assoc($select_invoice);
    $idinvoice_cko = $invoice_data['idinvoice'];
?>
    <script>
        window.location.href = "<?php echo $url; ?>/checkout/detail/<?php echo $idinvoice_cko; ?>";
    </script>
<?php
}
?>
