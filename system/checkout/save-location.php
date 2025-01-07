<?php
include '../../config.php';

$id_invoice = $_POST['id_invoice'];
$exp_id_provinsi = explode('=', $_POST['id_provinsi']);
$exp_id_kota = explode('=', $_POST['id_kota']);
$exp_id_kecamatan = explode('=', $_POST['id_kecamatan']);
$alamat_lengkap = $_POST['alamat_lengkap'];
$notelp = $_POST['notelp'];
$id_kurir = '0';

$select_invoice_sl = $server->query("SELECT *, iklan.id_lokasi AS id_lokasi_iklan FROM `invoice`, `iklan` WHERE invoice.idinvoice=$id_invoice AND invoice.id_iklan=iklan.id ");
$data_invoice_sl = mysqli_fetch_assoc($select_invoice_sl);

$id_lokasi_iklan = $data_invoice_sl['id_lokasi_iklan'];


$berat_barang = $data_invoice_sl['berat'] * $data_invoice_sl['jumlah'];
$jumlah_barang = $data_invoice_sl['jumlah'];

$provinsi_id = $exp_id_provinsi[0];
$kota_id = $exp_id_kota[0];
$kecamatan_id = $exp_id_kecamatan[0];

$provinsi_d = $exp_id_provinsi[1];
$kota_d = $exp_id_kota[1];
$kecamatan_d = $exp_id_kecamatan[1];

// LOKASI FOR INVOICE
$provinsi_inv = $provinsi_id . ',' . $provinsi_d;
$kota_inv = $kota_id . ',' . $kota_d;
$kecamatan_inv = $kecamatan_id . ',' . $kecamatan_d;

$select_lokasi_user = $server->query("SELECT * FROM `lokasi_user` WHERE `id_user`='$iduser'");
$data_lokasi_user = mysqli_fetch_assoc($select_lokasi_user);

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
    CURLOPT_POSTFIELDS => "origin=$id_lokasi_iklan&originType=subdistrict&destination=$kecamatan_id&destinationType=subdistrict&weight=$berat_barang&courier=jne",
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

    $s_skro = $server->query("SELECT * FROM `setting_kurir_rajaongkir` WHERE `id`='1' ");
    $data_s_skro = mysqli_fetch_assoc($s_skro);
    if ($data_s_skro['status'] == 'Aktif') {
        $kurir_ongkir = $data_cost_jne['rajaongkir']['results']['0']['code'];
        $kurir_layanan_ongkir = $data_cost_jne_arr[$id_kurir]['service'];
        $etd_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['etd'];
        if ($data_invoice_sl['go'] == '') {
            $harga_ongkir =  $data_cost_jne_arr[$id_kurir]['cost']['0']['value'];
        } else {
            $harga_ongkir = '0';
        }
    } else {
        $s_kur_toko = $server->query("SELECT * FROM `setting_kurir_toko` WHERE `id`='1' ");
        $data_s_kur_toko = mysqli_fetch_assoc($s_kur_toko);
        $kurir_ongkir = $data_s_kur_toko['nama'];
        $kurir_layanan_ongkir = '';
        $etd_ongkir = $data_s_kur_toko['etd'];
        if ($data_invoice_sl['go'] == '') {
            $harga_ongkir = $data_s_kur_toko['nominal'];
        } else {
            $harga_ongkir = '0';
        }
    }
}

if ($data_lokasi_user) {
    $update_lokasi = $server->query("UPDATE `lokasi_user` SET `provinsi`='$provinsi_d', `id_provinsi`='$provinsi_id', `kota`='$kota_d', `id_kota`='$kota_id', `kecamatan`='$kecamatan_d', `id_kecamatan`='$kecamatan_id', `alamat_lengkap`='$alamat_lengkap', `notelp`='$notelp' WHERE `id_user`='$iduser'");
    $update_lokasi_invoice = $server->query("UPDATE `invoice` SET `provinsi`='$provinsi_inv',`kota`='$kota_inv',`kecamatan`='$kecamatan_inv',`alamat_lengkap`='$alamat_lengkap', `kurir`='$kurir_ongkir', `id_kurir`='$id_kurir', `layanan_kurir`='$kurir_layanan_ongkir', `etd`='$etd_ongkir', `harga_ongkir`='$harga_ongkir', `notelp`='$notelp' WHERE `idinvoice`='$id_invoice'");
    if ($update_lokasi || $update_lokasi_invoice) {
?>
        <script>
            setting_lokasi.style.display = 'none';
            window.location.href = '';
        </script>
    <?php
    }
} else {
    $insert_lokasi = $server->query("INSERT INTO `lokasi_user`(`id_user`, `provinsi`, `id_provinsi`, `kota`, `id_kota`, `kecamatan`, `id_kecamatan`, `kelurahan`, `alamat_lengkap`, `notelp`) VALUES ('$iduser', '$provinsi_d', '$provinsi_id', '$kota_d', '$kota_id', '$kecamatan_d', '$kecamatan_id', '', '$alamat_lengkap', '$notelp')");
    $update_lokasi_invoice = $server->query("UPDATE `invoice` SET `provinsi`='$provinsi_inv',`kota`='$kota_inv',`kecamatan`='$kecamatan_inv',`alamat_lengkap`='$alamat_lengkap', `kurir`='$kurir_ongkir', `id_kurir`='$id_kurir', `layanan_kurir`='$kurir_layanan_ongkir', `etd`='$etd_ongkir', `harga_ongkir`='$harga_ongkir', `notelp`='$notelp' WHERE `idinvoice`='$id_invoice'");
    if ($insert_lokasi || $update_lokasi_invoice) {
    ?>
        <script>
            setting_lokasi.style.display = 'none';
            window.location.href = '';
        </script>
<?php
    }
}
