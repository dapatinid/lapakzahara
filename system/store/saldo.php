<?php
session_start();
include '../../config.php';

$notification = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['jumlah']) && isset($_POST['jumlah_dipotong']) && isset($_POST['rekening_tujuan']) && isset($_POST['atas_nama'])) {
        $jumlah = mysqli_real_escape_string($server, $_POST['jumlah']);
        $jumlahDipotong = mysqli_real_escape_string($server, $_POST['jumlah_dipotong']);
        $nama_bank = mysqli_real_escape_string($server, $_POST['nama_bank']);
        $rekening_tujuan = mysqli_real_escape_string($server, $_POST['rekening_tujuan']);
        $atas_nama = mysqli_real_escape_string($server, $_POST['atas_nama']);
        $keterangan = "Pending";

        // Dapatkan saldo saat ini dari tabel saldo
        $saldo_query = "SELECT jumlah_saldo FROM saldo WHERE user_id = $iduser";
        $saldo_result = $server->query($saldo_query);

        if ($saldo_result) {
            $saldo_row = $saldo_result->fetch_assoc();
            $saldo_user = $saldo_row['jumlah_saldo'];

            // Periksa apakah saldo mencukupi
            if ($saldo_user >= $jumlah) {
                // Lakukan penarikan
                $insert_penarikan = $server->query("INSERT INTO riwayat_penarikan (tanggal, jumlah, jumlah_dipotong, nama_bank, rekening_tujuan, atas_nama, status, user_id, keterangan) 
                VALUES (NOW(), '$jumlah', '$jumlahDipotong', '$nama_bank', '$rekening_tujuan', '$atas_nama', 0, $iduser, '$keterangan')");

                if ($insert_penarikan) {
    // Pesan berhasil dikirimkan, tampilkan pada halaman saat ini
    $notification = '<span style="color: green;">Permintaan penarikan saldo berhasil dikirim.</span>';
} else {
    $notification = '<span style="color: red;">Gagal mengirim permintaan penarikan saldo.</span>';
}
            } else {
                // Saldo tidak mencukupi, kirim pesan kesalahan
                $notification = "Saldo Anda tidak cukup!";
            }
        } else {
            // Gagal mendapatkan saldo, kirim pesan kesalahan
            $notification = "Gagal mendapatkan saldo.";
        }
    } else {
        $notification = "Data tidak lengkap.";
    }
}

// Display the notification message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags, title, and other head elements go here -->
</head>
<body>
    <div>
        <p style="color: red; font-weight: 500; font-size: 13px;" id="pesan_penarikan"><?php echo $notification; ?></p>
    </div>

    <script>
        <?php
        // Generate JavaScript code based on PHP conditions
        if ($saldo_user < $jumlah) {
            echo 'document.getElementById("pesan_penarikan").style.display = "block";';
        } elseif ($saldo_user >= $jumlah) {
            echo 'window.location.href = "index.php";';
        }
        ?>
    </script>
</body>
</html>
