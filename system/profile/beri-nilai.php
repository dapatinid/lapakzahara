<?php
include '../../config.php';

$star_bp_inp = mysqli_real_escape_string($server, $_POST['star_bp_inp']);
$deskripsi_bp_inp = mysqli_real_escape_string($server, $_POST['deskripsi_bp_inp']);
$id_inv_bp = mysqli_real_escape_string($server, $_POST['id_inv_bp']);
$time = date("Y-m-d H:i:s");

$img_name_random = round(microtime(true));

function compress($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }
    imagejpeg($image, $destination, $quality);
    return $destination;
}

if (!empty($_FILES["gambar_bp_a"]["name"])) {
    $expname1 = explode('.', $_FILES["gambar_bp_a"]["name"]);
    $ext1 = end($expname1);
    $name1 = $id_inv_bp . '-' . $img_name_random . '.' . $ext1;
    $path1 = "../../assets/image/penilaian/" . $name1;

    $source_img = $_FILES["gambar_bp_a"]["tmp_name"];
    $destination_img = $path1;

    $d = compress($source_img, $destination_img, 50);
} else {
    $name1 = '';
}

$insert_rating = $server->query("INSERT INTO `rating`(`id_invoice_rat`, `star_rat`, `deskripsi_rat`, `img_rat`, `waktu_rat`) VALUES ('$id_inv_bp', '$star_bp_inp', '$deskripsi_bp_inp', '$name1', '$time')");
if ($insert_rating) {
?>
    <script>
        box_bp_produk.style.display = 'none';
        id_inv_bp.value = '';
        star_c1.style.color = '#e2e2e2';
        star_c2.style.color = '#e2e2e2';
        star_c3.style.color = '#e2e2e2';
        star_c4.style.color = '#e2e2e2';
        star_c5.style.color = '#e2e2e2';
        star_bp_inp.value = '';
        var hid_bu_snilai = 'bu_snilai' + <?php echo $id_inv_bp; ?>;
        var hid_snilai = 'snilai' + <?php echo $id_inv_bp; ?>;
        document.getElementById(hid_bu_snilai).style.display = 'none';
        document.getElementById(hid_snilai).style.display = 'block';
    </script>
<?php
}
?> 