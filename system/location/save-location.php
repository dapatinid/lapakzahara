<?php
include '../../config.php';

// Menerima data dari permintaan POST
$id_invoice = $_POST['id_invoice'];
$provinsi = $_POST['provinsi'];
$kota = $_POST['kota'];
$kecamatan = $_POST['kecamatan'];
$notelp = $_POST['notelp'];
$alamat_lengkap = $_POST['alamat_lengkap'];

// Pisahkan nilai provinsi dan id_provinsi
list($id_provinsi, $provinsi) = explode('=', $provinsi);

// Pisahkan nilai kota dan id_kota
list($id_kota, $kota) = explode('=', $kota);

// Pisahkan nilai kecamatan dan id_kecamatan
list($id_kecamatan, $kecamatan) = explode('=', $kecamatan);

// Persiapkan dan eksekusi kueri SQL untuk menyimpan data ke dalam tabel
$query = "INSERT INTO lokasi_user (id_user, provinsi, id_provinsi, kota, id_kota, kecamatan, id_kecamatan, notelp, alamat_lengkap) 
          VALUES ('$id_invoice', '$provinsi', '$id_provinsi', '$kota', '$id_kota', '$kecamatan', '$id_kecamatan', '$notelp', '$alamat_lengkap')";
          
// Persiapkan dan eksekusi kueri SQL untuk menyimpan data ke dalam tabel
$query = "UPDATE INTO lokasi_user (id_user, provinsi, id_provinsi, kota, id_kota, kecamatan, id_kecamatan, notelp, alamat_lengkap) 
          VALUES ('$id_invoice', '$provinsi', '$id_provinsi', '$kota', '$id_kota', '$kecamatan', '$id_kecamatan', '$notelp', '$alamat_lengkap')";

$result = mysqli_query($connection, $query);

// Kirim respons JSON ke klien
if ($result) {
    echo json_encode(array("status" => "success", "message" => "Location saved successfully"));
} else {
    echo json_encode(array("status" => "error", "message" => "Error saving location"));
}

// Tutup koneksi database
mysqli_close($connection);
?>