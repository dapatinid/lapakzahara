<?php
include '../../config.php';

// Ambil data dari POST
$idproduct = $_POST['idproduct'];
$alasanLaporkan = $_POST['alasanLaporkan'];
$deskripsiMasalah = $_POST['deskripsiMasalah'];

// Tentukan nilai yang akan disimpan berdasarkan opsi alasan
if ($alasanLaporkan == 'lainnya') {
    $nilaiAlasan = $deskripsiMasalah;
} else {
    $nilaiAlasan = $alasanLaporkan;
}

$upcatd = $server->query("INSERT INTO laporan_produk (user_id, id_produk, deskripsi_masalah) VALUES ('$iduser', '$idproduct', '$nilaiAlasan')");

if ($upcatd) {
    ?>
    <script>
        window.location.reload(); // Gunakan window.location.reload() untuk me-refresh halaman
    </script>
    <?php
}
?>