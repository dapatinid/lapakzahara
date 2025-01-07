<?php
include '../../../config.php';

$id_edit_pro_fs = $_POST['id_edit_pro_fs'];
$persen_e_fs = $_POST['persen_e_fs'];
$total_s_fs = $_POST['total_s_fs'];

$edit_pro_fs = $server->query("UPDATE `iklan` SET `diskon`='$persen_e_fs', `stok`='$total_s_fs' WHERE `id`='$id_edit_pro_fs' ");

if ($edit_pro_fs) {
?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php
}
?>