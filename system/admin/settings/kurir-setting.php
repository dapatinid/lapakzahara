<?php
include '../../../config.php';

// RAJAONGKIR
for ($lopkuririd = 1; $lopkuririd <= 24; $lopkuririd++) {
    $v_idkurnam = 'impkur' . $lopkuririd;
    $dn_kurir = $_POST[$v_idkurnam];
    $update_data_ks = $server->query("UPDATE `setting_kurir` SET `status`='$dn_kurir' WHERE `id`='$lopkuririd' ");
}

// SETTING KURIR TOKO
$kirim_instan_kp0 = $_POST['kirim_instan_kp0'];
$kirim_instan_kp1 = $_POST['kirim_instan_kp1'];
$kirim_instan_kp2 = $_POST['kirim_instan_kp2'];
$kirim_instan_kp3 = $_POST['kirim_instan_kp3'];
$status_kurir_ro = $_POST['status_kurir_ro'];
$min_harga_go = $_POST['min_harga_go'];
$min_harga_diss = $_POST['min_harga_diss'];
$min_persen_diss = $_POST['min_persen_diss'];

$upkis0 = $server->query("UPDATE `setting_kurir_toko` SET `status`='$kirim_instan_kp0' WHERE `id`='0' ");
$upkis1 = $server->query("UPDATE `setting_kurir_toko` SET `nominal`='$kirim_instan_kp1' WHERE `id`='1' ");
$upkis2 = $server->query("UPDATE `setting_kurir_toko` SET `nominal`='$kirim_instan_kp2' WHERE `id`='2' ");
$upkis3 = $server->query("UPDATE `setting_kurir_toko` SET `nominal`='$kirim_instan_kp3' WHERE `id`='3' ");
$upskro = $server->query("UPDATE `setting_kurir_rajaongkir` SET `status`='$status_kurir_ro' WHERE `id`='1' ");
$upmingratisongkir = $server->query("UPDATE `setting_gratis_ongkir` SET `min_nominal`='$min_harga_go' WHERE `id`='1' ");
$usdifhsdf = $server->query("UPDATE `setting_diskon` SET `min_nominal`='$min_harga_diss', `persen`='$min_persen_diss' WHERE `id`='1' ");

// KURIR TOKO
$user_id = 1; // User ID yang digunakan dalam query SELECT pada pertanyaan sebelumnya

// Lakukan query SELECT untuk mendapatkan informasi lokasi user dari tabel akun
$query = "SELECT `kecamatan_user`, `kota_user`, `provinsi_user` FROM `akun` WHERE `id`='$user_id'";
$result = $conn->query($query);
$status = $_POST['status'];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Menggabungkan informasi lokasi user menjadi format yang diinginkan untuk etd
    $etd = $row['kecamatan_user'] . ', ' . $row['kota_user'] . ', ' . $row['provinsi_user'];
    
    // Melakukan UPDATE ke dalam tabel `kurir_toko` dengan nilai etd yang baru
    $sql = "UPDATE `kurir_toko` SET `etd`='$etd', `status`='$status' WHERE `user_id`='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diperbarui";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Tidak ada data ditemukan untuk user tersebut";
}


?>
<script>
    text_s_kurir.innerHTML = 'Berhasil Disimpan';
    setTimeout(() => {
        text_s_kurir.innerHTML = 'Simpan';
    }, 2000);
</script>