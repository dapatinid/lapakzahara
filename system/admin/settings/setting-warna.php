<?php
include '../../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $colorVariables = [
        '--black', 
        '--semi-black', 
        '--bg-transparent-black', 
        '--white', 
        '--orange', 
        '--deep-orange', 
        '--semi-orange', 
        '--grey', 
        '--border-grey', 
        '--semi-grey', 
        '--red'
    ];

    // Menyimpan nilai warna untuk setiap variabel yang diizinkan
    foreach ($colorVariables as $colorVar) {
        if (isset($_POST[$colorVar])) {
            $colorCode = $_POST[$colorVar];
            $sql = "UPDATE setting_warna SET code = '$colorCode' WHERE variabel = '$colorVar'";
            $conn->query($sql);
        }
    }
}
?>
<script>
    text_s_warna.innerHTML = 'Berhasil Disimpan';
    setTimeout(() => {
        text_s_warna.innerHTML = 'Simpan';
    }, 2000);
</script>
