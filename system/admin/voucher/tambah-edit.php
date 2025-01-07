<?php
include '../../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idvouceredit = $_POST['idvouceredit'];
    $jenis_vo = $_POST['tipe_vo'];
    $persen_vo = $_POST['persen_vo'];
    $maksimal_vo = $_POST['max_vo'];
    $hari_vo = $_POST['hari_vo'];
    
    // Menghilangkan detik dari waktu saat ini
    $waktu_dibuat = date("Y-m-d H:i:s");
    
    // Menghitung waktu berakhir berdasarkan jumlah hari
    $waktu_berakhir = date("Y-m-d H:i:s", strtotime("+ $hari_vo days"));
    $durasi_vo = $_POST['hari_vo'];


    // Lakukan validasi data di sini sesuai kebutuhan

    // Jika ID voucher tidak kosong, maka ini adalah proses edit
    if (!empty($idvouceredit)) {
        // Lakukan proses edit voucher di sini, misalnya dengan SQL UPDATE
        // Gantilah "vouchers" dengan nama tabel yang sesuai dengan struktur database Anda
        $query = "UPDATE vouchers SET jenis='$jenis_vo', persen='$persen_vo', maksimal='$maksimal_vo', waktu_dibuat='$waktu_dibuat', waktu_berakhir='$waktu_berakhir', durasi='$durasi_vo' WHERE id='$idvouceredit'";
        if (mysqli_query($server, $query)) {
            // Redirect ke halaman voucher setelah berhasil diubah
            header("location: " . $url . "/admin/voucher/");
            exit();
        } else {
            echo "Error: " . mysqli_error($server);
        }
    } else {
        // Jika ID voucher kosong, maka ini adalah proses tambah
        // Lakukan proses tambah voucher di sini, misalnya dengan SQL INSERT
        // Gantilah "vouchers" dengan nama tabel yang sesuai dengan struktur database Anda
        $query = "INSERT INTO vouchers (jenis, persen, maksimal, waktu_dibuat, waktu_berakhir, durasi) VALUES ('$jenis_vo', '$persen_vo', '$maksimal_vo', '$waktu_dibuat', '$waktu_berakhir', '$durasi_vo')";
        if (mysqli_query($server, $query)) {
            // Redirect ke halaman voucher setelah berhasil ditambahkan
            header("location: " . $url . "/admin/voucher/");
            exit();
        } else {
            echo "Error: " . mysqli_error($server);
        }
    }
}
?>
