<?php
include '../../config.php';

$exp_id_kota = explode(',', $_POST['id_kota']);
$id_kota = $exp_id_kota[0];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$id_kota",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "key: $rajaongkir_key"
    ),
));

$res_kota = curl_exec($curl);
$err_kota = curl_error($curl);

curl_close($curl);

if ($err_kota) {
    echo "cURL Error #:" . $err_kota;
} else {
    $kota_data_arr = json_decode($res_kota, true);
    
    // Cek apakah data valid sebelum melakukan foreach
    if(isset($kota_data_arr['rajaongkir']['results']) && is_array($kota_data_arr['rajaongkir']['results'])) {
        $kota_data = $kota_data_arr['rajaongkir']['results'];
        
        foreach ($kota_data as $key_kota_data => $value_kota_data) {
            ?>
            <option value="<?php echo $value_kota_data['subdistrict_id'] . ',' . $value_kota_data['subdistrict_name']; ?>"><?php echo $value_kota_data['subdistrict_name']; ?></option>
            <?php
        }
    } else {
        // Data tidak valid atau kosong
        echo "No data available";
    }
}
?>
