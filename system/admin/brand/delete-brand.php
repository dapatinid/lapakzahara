<?php
include '../../../config.php';

$val_id_brand = $_POST['val_id_brand'];

$hapus_brand_adm = $server->query("DELETE FROM `brand` WHERE `id`='$val_id_brand' ");

if ($hapus_brand_adm) {
?>
    <script>
        confirm_hapus.style.display = 'none';
        val_id_brand.value = '';
        location.reload(); // Reload halaman saat proses selesai
    </script>
<?php
}
?>
