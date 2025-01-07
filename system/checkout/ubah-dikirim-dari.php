<?php
include '../../config.php';

$idlocdk = $_POST['idlocdk'];
$idinvloc = $_POST['idinvloc'];

$s_locationd = $server->query("SELECT * FROM `setting_lokasi` WHERE `id`='$idlocdk' ");
$data_s_locationd = mysqli_fetch_assoc($s_locationd);

$id_kurir = '0';

$select_invoice_sl = $server->query("SELECT * FROM `invoice`, `iklan` WHERE invoice.idinvoice=$idinvloc AND invoice.id_iklan=iklan.id ");
$data_invoice_sl = mysqli_fetch_assoc($select_invoice_sl);

$berat_barang = $data_invoice_sl['berat'] * $data_invoice_sl['jumlah'];
$jumlah_barang = $data_invoice_sl['jumlah'];

$expleciv = explode(',', $data_invoice_sl['kecamatan']);

$kectokos = $data_s_locationd['kecamatan_id'];
$kecinvoiced = $expleciv[0];

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
    CURLOPT_POSTFIELDS => "origin=$kectokos&originType=subdistrict&destination=$kecinvoiced&destinationType=subdistrict&weight=$berat_barang&courier=jne",
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
    $kurir_ongkir = $data_cost_jne['rajaongkir']['results']['0']['code'];
    $kurir_layanan_ongkir = $data_cost_jne_arr[$id_kurir]['service'];
    $etd_ongkir = $data_cost_jne_arr[$id_kurir]['cost']['0']['etd'];
    $harga_ongkir =  $data_cost_jne_arr[$id_kurir]['cost']['0']['value'];
}

$update_lokasi_invoice = $server->query("UPDATE `invoice` SET `kurir`='$kurir_ongkir', `id_kurir`='$id_kurir', `layanan_kurir`='$kurir_layanan_ongkir', `etd`='$etd_ongkir', `harga_ongkir`='$harga_ongkir', `idloc`='$idlocdk' WHERE `idinvoice`='$idinvloc'");

if ($update_lokasi_invoice) {
?>
    <script>
        window.location.href = '';
    </script>
<?php
}
