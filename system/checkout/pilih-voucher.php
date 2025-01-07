<?php
// ../../system/checkout/pilih-voucher.php

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
$user_ids = cleanInput($_POST['user_ids']);
$idinvvoucherp = cleanInput($_POST['idinvvoucherp']);
$idvoucherp = cleanInput($_POST['idvoucherp']);

try {
    // Ambil nilai persen potongan, maksimal, dan jenis voucher dari database
    $getVoucherInfo = $conn->prepare("SELECT persen, maksimal, jenis FROM vouchers WHERE id = ?");
    $getVoucherInfo->bind_param('s', $idvoucherp);
    $getVoucherInfo->execute();
    $getVoucherInfo->bind_result($persenPotongan, $maksimalVoucher, $jenisVoucher);
    $getVoucherInfo->fetch();
    $getVoucherInfo->close();

    // Hitung nilai rupiah dari persen potongan
    $nilaiDiskon = ($persenPotongan / 100) * $maksimalVoucher;

    // Update nilai diskon_voucher, jenis_voucher, dan diskon_persen di tabel invoice
    $updateInvoiceDiscount = $conn->prepare("UPDATE invoice SET diskon_voucher = ?, jenis_voucher = ?, diskon_persen = ? WHERE idinvoice = ?");
    $updateInvoiceDiscount->bind_param('ssss', $nilaiDiskon, $jenisVoucher, $persenPotongan, $idinvvoucherp);
    $updateInvoiceDiscount->execute();
    $updateInvoiceDiscount->close();

    // Beri respons berhasil
    echo "Voucher berhasil digunakan!";

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
