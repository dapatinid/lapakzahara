<?php
include '../../config.php';

$id_keca_tujuan_v = $_POST['id_keca_tujuan_v'];
$berat_barang = $_POST['berat_barang'];
$idlocuo = $_POST['idlocuo'];
$idivuo = $_POST['idivuo'];
$user_id = $_POST['user_id'];

// Mendapatkan nilai kecamatan_id_user dari tabel akun
$query_akun = $server->query("SELECT `kecamatan_id_user` FROM `akun` WHERE `id`='$user_id'");
$data_akun = mysqli_fetch_assoc($query_akun);

// Menetapkan nilai $kectokos dengan nilai default yang sesuai jika data tidak ditemukan
$kectokos = ($data_akun && isset($data_akun['kecamatan_id_user'])) ? $data_akun['kecamatan_id_user'] : '0';
$kectokos_curl = $kectokos;

// Mendapatkan data invoice
$s_iv_cgo = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$idivuo'");
$data_s_iv_cgo = mysqli_fetch_assoc($s_iv_cgo);

$kectokos1 = $data_s_iv_cgo['id_lokasi_i'];

// Menampilkan opsi Ambil Sendiri jika kurir toko aktif
// $select_kurir_toko = $server->query("SELECT * FROM `kurir_toko` WHERE `user_id` = $user_id AND `status` = 'Aktif'");
$select_kurir_toko = $server->query("SELECT * FROM `kurir_toko`");
while ($data_select_kurir_toko = mysqli_fetch_assoc($select_kurir_toko)) {
    $nomkurhs = ($data_s_iv_cgo['go'] == '') ? $data_select_kurir_toko['nominal'] : '0';
?>
    <div class="box_list_ongkir" onclick="UbahOpsiOngkir('Ambil Sendiri', '0', '', '<?php echo $data_select_kurir_toko['etd']; ?>', '<?php echo $nomkurhs; ?>')">
        <div class="judul_list_ongkir">
            <h1>AMBIL SENDIRI</h1>
            <h5>GRATIS ONGKIR</h5>
        </div>
        <p><?php echo ($data_select_kurir_toko['id'] == '0') ? $data_select_kurir_toko['etd'] : 'Alamat: ' . $data_select_kurir_toko['etd']; ?></p>
    </div>
    <div class="box_list_ongkir" onclick="UbahOpsiOngkir('Ongkir Bayar Nanti', '0', '', '<?php echo $data_select_kurir_toko['etd']; ?>', '<?php echo $nomkurhs; ?>')">
        <div class="judul_list_ongkir">
            <h1>KURIR TOKO KAMI</h1>
            <h5>ONGKIR BAYAR NANTI</h5>
        </div>
    </div>
<?php
}

// Mendapatkan status kurir RajaOngkir
$s_status_krr_ro = $server->query("SELECT * FROM `setting_kurir_rajaongkir` WHERE `id`='1'");
$data_s_status_krr_ro = mysqli_fetch_assoc($s_status_krr_ro);

if ($data_s_status_krr_ro['status'] == 'Aktif') {
    // Mendapatkan data kurir dari RajaOngkir
    $select_kurir = $server->query("SELECT * FROM `setting_kurir` WHERE `status`=''");
    while ($data_kurir = mysqli_fetch_assoc($select_kurir)) {
        $name_kurir = $data_kurir['kurir'];

        // Melakukan permintaan CURL ke RajaOngkir
        $curl_jne = curl_init();
        curl_setopt_array($curl_jne, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$kectokos1&originType=subdistrict&destination=$id_keca_tujuan_v&destinationType=subdistrict&weight=$berat_barang&courier=$name_kurir",
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
            foreach ($data_cost_jne_arr as $keykon => $valuekon) {
                $kurir_ongkir = $data_cost_jne['rajaongkir']['results']['0']['code'];
                $kurir_layanan_ongkir = $data_cost_jne_arr[$keykon]['service'];
                $etd_ongkir = $data_cost_jne_arr[$keykon]['cost']['0']['etd'];
                $harga_ongkir = ($data_s_iv_cgo['go'] == '') ? $data_cost_jne_arr[$keykon]['cost']['0']['value'] : '0';
?>
                <div class="box_list_ongkir" onclick="UbahOpsiOngkir('<?php echo $kurir_ongkir; ?>', '<?php echo $keykon; ?>', '<?php echo $kurir_layanan_ongkir; ?>', '<?php echo $etd_ongkir; ?>', '<?php echo $harga_ongkir; ?>')">
                    <div class="judul_list_ongkir">
                        <h1><?php echo strtoupper($kurir_ongkir); ?> <?php echo $kurir_layanan_ongkir; ?></h1>
                        <?php if ($harga_ongkir == 0) : ?>
                            <h5>Gratis Ongkir</h5>
                        <?php else : ?>
                            <h5>Rp <?php echo number_format($harga_ongkir, 0, ".", "."); ?></h5>
                        <?php endif; ?>
                    </div>
                    <p>Perkiraan sampai <?php echo $etd_ongkir; ?> Hari</p>
                </div>
<?php
            }
        }
    }
}
?>


<style>
    .box_list_ongkir {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid var(--border-grey);
        border-radius: 3px;
        padding: 10px 15px;
        cursor: pointer;
    }

    .judul_list_ongkir {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .judul_list_ongkir h1 {
        font-size: 15px;
        font-weight: 500;
        color: var(--black);
    }

    .judul_list_ongkir h5 {
        font-size: 15px;
        font-weight: 500;
        color: var(--orange);
    }

    .box_list_ongkir p {
        font-size: 12px;
        color: var(--semi-black);
        margin-top: 5px;
    }

    @media only screen and (max-width: 500px) {
        .judul_list_ongkir h1 {
            font-size: 13px;
        }

        .judul_list_ongkir h5 {
            font-size: 13px;
        }

        .box_list_ongkir p {
            font-size: 11px;
        }
    }
</style>