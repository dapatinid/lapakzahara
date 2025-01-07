<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_voucher_edit = $_POST['jenis_voucher_edit'];
    $persen_diskon_edit = $_POST['persen_diskon_edit'];
    $maksimal_diskon_edit = $_POST['maksimal_diskon_edit'];
    $waktu_berlaku_edit = $_POST['waktu_berlaku_edit'];
    $val_id_voucher = $_POST['val_id_voucher'];

    // Validasi data jika diperlukan
    // ...

    // Hitung waktu_berakhir berdasarkan waktu_berlaku
    $waktu_dibuat_edit = date('Y-m-d H:i:s');
    $waktu_berakhir_edit = date('Y-m-d H:i:s', strtotime("+$waktu_berlaku_edit days", strtotime($waktu_dibuat_edit)));

    // Query untuk mengupdate data voucher
    $sql_edit = "UPDATE vouchers 
                 SET jenis = '$jenis_voucher_edit', 
                     persen = '$persen_diskon_edit', 
                     maksimal = '$maksimal_diskon_edit', 
                     durasi = '$waktu_berlaku_edit', 
                     waktu_berakhir = '$waktu_berakhir_edit' 
                 WHERE id = '$val_id_voucher'";

    if ($conn->query($sql_edit) === TRUE) {
        echo "Voucher berhasil diperbarui!";
    } else {
        echo "Error: " . $sql_edit . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request!";
}

// Tutup koneksi ke database
$conn->close();
?>

<script>
    // Setelah berhasil mengedit voucher, arahkan pengguna ke halaman index.php
    window.location.href = 'index.php';
</script>
