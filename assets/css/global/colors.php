<?php
include '../../../config.php';

// Query untuk mengambil nilai warna dari tabel
$sql = "SELECT variabel, code FROM setting_warna";
$result = $conn->query($sql);

// Buat dokumen CSS dengan nilai-nilai dari tabel, termasuk --bg-transparent-black
if ($result->num_rows > 0) {
    $css_content = ":root {\n";
    while ($row = $result->fetch_assoc()) {
        $css_content .= $row["variabel"] . ": " . $row["code"] . ";\n";
    }
    // Menambahkan nilai --bg-transparent-black
    $css_content .= "--bg-transparent-black: rgba(0, 0, 0, 0.5);\n";
    $css_content .= "}";

    // Set header dan tampilkan konten CSS
    header("Content-type: text/css; charset: UTF-8");
    echo $css_content;
} else {
    echo "0 hasil";
}

$conn->close();
?>
