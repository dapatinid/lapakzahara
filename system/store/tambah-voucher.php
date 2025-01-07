<?php
include '../../config.php';

// Menerima data dari AJAX
$jenis_voucher = $_POST['jenis_voucher'];
$persen_diskon = $_POST['persen_diskon'];
$maksimal_diskon = $_POST['maksimal_diskon'];
$waktu_berlaku = $_POST['waktu_berlaku'];
$iduser = $_POST['iduser'];

// Validasi data jika diperlukan
// ...

// Hitung waktu_dibuat dan waktu_berakhir
$waktu_dibuat = date('Y-m-d H:i:s');
$waktu_berakhir = date('Y-m-d H:i:s', strtotime("+$waktu_berlaku days", strtotime($waktu_dibuat)));

// Query untuk menyimpan data ke dalam tabel voucher
$sql = "INSERT INTO vouchers (user_id, jenis, persen, maksimal, waktu_dibuat, waktu_berakhir, durasi) 
        VALUES ('$iduser', '$jenis_voucher', '$persen_diskon', '$maksimal_diskon', '$waktu_dibuat', '$waktu_berakhir', '$waktu_berlaku')";

if ($conn->query($sql) === TRUE) {
    echo "Voucher berhasil ditambahkan!";
    echo '<script>window.location.href = "index.php";</script>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi ke database
$conn->close();
?>
