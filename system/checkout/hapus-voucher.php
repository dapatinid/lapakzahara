<?php
// ../../system/checkout/hapus-voucher.php

// Include file konfigurasi database
include '../../config.php';

// Fungsi untuk membersihkan dan mencegah SQL injection
function cleanInput($input) {
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Hapus tag script
        '@<[\/\!]*?[^<>]*?>@si',            // Hapus tag HTML
        '@<style[^>]*?>.*?</style>@siU',    // Hapus tag style
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Hapus komentar multi-baris
    );

    $output = preg_replace($search, '', $input);
    return $output;
}

// Ambil data dari permintaan POST
$idinvvoucherp = cleanInput($_POST['idinvvoucherp']);

try {
    // Hapus nilai diskon_voucher dan jenis_voucher di tabel invoice
    $hapusInvoiceDiscount = $conn->prepare("UPDATE invoice SET diskon_voucher = NULL, jenis_voucher = NULL, diskon_persen = NULL WHERE idinvoice = ?");
    $hapusInvoiceDiscount->bind_param('s', $idinvvoucherp);
    $hapusInvoiceDiscount->execute();
    $hapusInvoiceDiscount->close();

    // Beri respons berhasil
    echo "Voucher berhasil dihapus!";

    // Tutup koneksi database (jika menggunakan MySQLi)
    $conn->close();
    
    // Tutup popup dan segarkan halaman menggunakan JavaScript
    echo '<script>
             window.location.href = \'\'; // Redirect to an empty URL
          </script>';
} catch (Exception $e) {
    // Tangani kesalahan, misalnya tampilkan pesan kesalahan
    echo "Error: " . $e->getMessage();
}
?>
