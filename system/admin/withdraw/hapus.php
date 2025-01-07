<?php
include '../../../config.php';

$val_idpenarikan = $_POST['val_idpenarikan'];

$hapus_akun = $server->query("DELETE FROM `riwayat_penarikan` WHERE `idpenarikan`='$val_idpenarikan'");

if ($hapus_akun) {
?>
    <script>
        // Menghilangkan elemen dengan ID 'isi_all_users_admin' + $val_idpenarikan
        var elementToRemove = document.getElementById('isi_all_users_admin<?php echo $val_idpenarikan; ?>');
        if (elementToRemove) {
            elementToRemove.style.display = 'none';
        }
        
        // Menghilangkan elemen dengan ID 'confirm_hapus'
        var confirm_hapus = document.getElementById('confirm_hapus');
        if (confirm_hapus) {
            confirm_hapus.style.display = 'none';
        }

        // Memuat ulang halaman setelah penghapusan berhasil
        window.location.reload();
    </script>
<?php
} else {
?>
    <script>
        // Menghilangkan elemen dengan ID 'confirm_hapus'
        var confirm_hapus = document.getElementById('confirm_hapus');
        if (confirm_hapus) {
            confirm_hapus.style.display = 'none';
        }
    </script>
<?php
}
?>
