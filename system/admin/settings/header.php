<?php
include '../../../config.php';

$nama_perusahaan_hs = mysqli_real_escape_string($server, $_POST['nama_perusahaan_hs']);
$slogan_hs = mysqli_real_escape_string($server, $_POST['slogan_hs']);
$meta_description_hs = mysqli_real_escape_string($server, $_POST['meta_description_hs']);
$meta_keyword_hs = mysqli_real_escape_string($server, $_POST['meta_keyword_hs']);
$google_verification_hs = mysqli_real_escape_string($server, $_POST['google_verification_hs']);
$bing_verification_hs = mysqli_real_escape_string($server, $_POST['bing_verification_hs']);
$ahrefs_verification_hs = mysqli_real_escape_string($server, $_POST['ahrefs_verification_hs']);
$yandex_verification_hs = mysqli_real_escape_string($server, $_POST['yandex_verification_hs']);
$norton_verification_hs = mysqli_real_escape_string($server, $_POST['norton_verification_hs']);

if (!empty($_FILES["ubah_logo_cf_hs"]["name"])) {
    unlink('../../../assets/icons/' . $logo);
    $expname1 = explode('.', $_FILES["ubah_logo_cf_hs"]["name"]);
    $ext1 = end($expname1);
    $name1 = 'logo' . '.' . $ext1;
    $path1 = "../../../assets/icons/" . $name1;
    move_uploaded_file($_FILES["ubah_logo_cf_hs"]["tmp_name"], $path1);
    $nametodb1 = $name1;
} else {
    $nametodb1 = $logo;
} 

if (!empty($_FILES["ubah_favicon_cf_hs"]["name"])) {
    unlink('../../../assets/icons/' . $favicon);
    $expname1 = explode('.', $_FILES["ubah_favicon_cf_hs"]["name"]);
    $ext1 = end($expname1);
    $name1 = 'favicon' . '.' . $ext1;
    $path1 = "../../../assets/icons/" . $name1;
    move_uploaded_file($_FILES["ubah_favicon_cf_hs"]["tmp_name"], $path1);
    $nametodb2 = $name1;
} else {
    $nametodb2 = $favicon;
}

$update_header_setting = $server->query("UPDATE `setting_header` SET `logo`='$nametodb1', `favicon`='$nametodb2',`title_name`='$nama_perusahaan_hs',`slogan`='$slogan_hs', `meta_description`='$meta_description_hs',`meta_keyword`='$meta_keyword_hs', `google_verification`='$google_verification_hs', `bing_verification`='$bing_verification_hs', `ahrefs_verification`='$ahrefs_verification_hs', `yandex_verification`='$yandex_verification_hs', `norton_verification`='$norton_verification_hs' WHERE `id_hs`='1'");

if ($update_header_setting) {
?>
    <script>
        text_s_hs.innerHTML = 'Berhasil Disimpan';
        setTimeout(() => {
            text_s_hs.innerHTML = 'Simpan';
        }, 2000);
    </script>
<?php
}
