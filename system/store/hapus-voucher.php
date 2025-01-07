<?php
include '../../config.php';

// Menerima data dari AJAX
$val_id_voucher = $_POST['val_id_voucher'];

// Validasi data jika diperlukan
// ...

// Query untuk menghapus voucher berdasarkan id
$sql = "DELETE FROM vouchers WHERE id = '$val_id_voucher'";

if ($conn->query($sql) === TRUE) {
    echo "Voucher berhasil dihapus!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi ke database
$conn->close();
?>

<script>
    // Setelah berhasil menghapus voucher, arahkan pengguna ke halaman index.php
    window.location.href = 'index.php';
</script>
