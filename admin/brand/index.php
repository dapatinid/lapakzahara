<?php
include '../config.php';

$page_admin = 'brand';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
} 

$select_brand_adm = $server->query("SELECT * FROM `brand`");
$jumlah_brand_adm = mysqli_num_rows($select_brand_adm);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Brand</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/brand/index.css">
</head>
 
<body>
    <!-- TAMBAH brand FORM -->
    <div class="tambah_brand_form" id="tambah_brand_form">
        <div class="isi_tambah_brand_form">
            <h1>Tambah Brand</h1>
            <div class="box_form_tk">
                <div class="isi_box_form_tk">
                    <p id="p_icon_file">Logo</p>
                    <input type="file" class="input" id="icon_file">
                </div>
                <div class="isi_box_form_tk">
                    <p id="p_namab_brand">Nama</p>
                    <input type="text" class="input" id="namab_brand" placeholder="namab brand" oninput="generateSlug()">
                </div>
                <div class="isi_box_form_tk">
                    <p id="p_slug_brand">Slug</p>
                    <input type="text" class="input" id="slug_brand" placeholder="URL brand">
                </div>
            </div>
            <div class="box_button_edit_akun">
                <div class="button_cancel_edit_akun" onclick="batal_add_brand()">
                    <p>Batal</p>
                </div>
                <div class="button_confirm_edit_akun" onclick="simpan_add_brand()">
                    <p id="text_tkat">Simpan</p>
                    <img src="../../assets/icons/loading-w.svg" id="loading_tkat">
                </div>
            </div>
        </div>
    </div>
    <!-- TAMBAH brand FORM -->

    <!-- HAPUS brand -->
    <div class="back_popup_confirm" id="confirm_hapus">
        <div class="popup_confirm">
            <div class="head_popup_confirm">
                <i class="ri-delete-bin-line"></i>
                <p>Hapus Brand</p>
            </div>
            <h5>brand yang sudah di hapus tidak dapat dipulihkan kembali, produk yang brand nya sudah di hapus tidak akan di tampilkan, apakah anda yakin ingin manghapus brand ini?</h5>
            <div class="box_button_popup_confirm">
                <div class="button_cancel_popup_confirm" id="hide_confirm_hapus" onclick="batal_hapus_brand()">
                    <p>Batal</p>
                </div>
                <div class="button_confirm_popup_confirm" onclick="hapus_brand_ya()">
                    <p id="text_ha_kat">Hapus</p>
                    <img src="../../assets/icons/loading-w.svg" id="loading_ha_kat">
                </div>
            </div>
        </div>
        <input type="hidden" id="val_id_brand">
    </div>
    <!-- HAPUS brand -->

    <!-- EDIT brand FORM -->
<div class="tambah_brand_form" id="edit_brand_form">
    <div class="isi_tambah_brand_form">
        <h1>Edit Brand</h1>
        <div class="box_form_tk">
            <div class="isi_box_form_tk">
                <p id="p_icon_now_file_edit">Logo Sekarang</p>
                <img src="" class="img_edit_brand" id="img_edit_brand">
            </div>
            <div class="isi_box_form_tk">
                <p id="p_icon_file_edit">Ubah Logo</p>
                <input type="file" class="input" id="icon_file_edit">
            </div>
            <div class="isi_box_form_tk">
                <p id="p_namab_brand_edit">Ubah Nama</p>
                <input type="text" class="input" id="namab_brand_edit" placeholder="namab brand" required oninput="updateSlug()">
            </div>
            <div class="isi_box_form_tk">
    <p id="p_slug_brand_edit">Ubah Slug</p>
    <input type="text" class="input" id="slug_brand_edit" placeholder="Slug brand" required>
</div>

        </div>
        <div class="box_button_edit_akun">
            <div class="button_cancel_edit_akun" onclick="batal_edit_brand()">
                <p>Batal</p>
            </div>
            <div class="button_confirm_edit_akun" onclick="simpan_edit_brand()">
                <p id="text_ekat">Simpan</p>
                <img src="../../assets/icons/loading-w.svg" id="loading_ekat">
            </div>
        </div>
    </div>
    <input type="hidden" id="val_id_kat_hapus">
</div>
<!-- EDIT brand FORM -->
    <div class="admin">
        <?php include '../partials/menu.php'; ?>
        <div class="content_admin">
            <h1 class="title_content_admin">Brand</h1>
            <div class="isi_content_admin">
                <!-- CONTENT -->
                <div class="jumlah_users_admin">
                    <h1>Jumlah Brand</h1>
                    <h1><?php echo $jumlah_brand_adm; ?> Brand</h1>
                </div>
                <div class="add_button_adm" onclick="show_add_brand()">
                    <p>Tambah brand</p>
                    <i class="ri-play-list-add-fill"></i>
                </div>
                <div class="all_users_admin">
                    <?php
                    while ($data_brand_adm = mysqli_fetch_assoc($select_brand_adm)) {
                        $id_brand_adm = $data_brand_adm['id'];
                        $select_produk_kat = $server->query("SELECT * FROM `iklan` WHERE `id_brand`='$id_brand_adm' AND `status`='' ");
                        $jumlah_produk_kat = mysqli_num_rows($select_produk_kat);
                    ?>
                        <div class="isi_all_users_admin">
                            <div class="box_left_aua">
                                <img src="../../assets/icons/brand/compressed/<?php echo $data_brand_adm['icon']; ?>">
                                <div class="isi_box_left_aua">
                                    <h5><?php echo $data_brand_adm['namab']; ?></h5>
                                </div>
                            </div>
                            <div class="box_right_aua">
                                <div class="isi_box_right_aua">
                                    <h3>Jumlah Produk</h3>
                                    <p><?php echo $jumlah_produk_kat; ?></p>
                                </div>
                            </div>
                            <div class="bu_edit_aua" onclick="show_edit_brand('<?php echo $data_brand_adm['id']; ?>', '<?php echo $data_brand_adm['namab']; ?>', '../../assets/icons/brand/compressed/<?php echo $data_brand_adm['icon']; ?>', '<?php echo $data_brand_adm['slug']; ?>')">
    <i class="ri-pencil-line"></i>
</div>

                            <div class="bu_delete_aua" onclick="show_confirm_hapus('<?php echo $data_brand_adm['id']; ?>')">
                                <i class="ri-delete-bin-line"></i>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- CONTENT -->
            </div> 
        </div>
    </div>
    <div id="res"></div>

    <!-- JS -->
    <script>
    function capitalizeFirstLetter(input) {
        return input.replace(/\b\w/g, function(match) {
            return match.toUpperCase();
        });
    }

    document.getElementById('namab_brand').addEventListener('input', function() {
        var judulInput = this.value;
        var capitalizedJudul = capitalizeFirstLetter(judulInput);
        document.getElementById('namab_brand').value = capitalizedJudul;
    });

    document.getElementById('namab_brand_edit').addEventListener('input', function() {
        var judulInput = this.value;
        var capitalizedJudul = capitalizeFirstLetter(judulInput);
        document.getElementById('namab_brand_edit').value = capitalizedJudul;
    });
    </script>
    <script>
    function generateSlug() {
    var namab_brand = document.getElementById('namab_brand').value;
    var slugInput = document.getElementById('slug_brand');
    
    // Menghilangkan karakter non-alphanumeric dan mengganti spasi dengan tanda minus
    var slug = namab_brand.toLowerCase().replace(/[^a-z0-9- ]/g, '').replace(/\s+/g, '-');
    
    slugInput.value = slug;
    }
    </script>
    <script>
    function updateSlug() {
    var namab_brand_edit = document.getElementById('namab_brand_edit');
    var slug_brand_edit = document.getElementById('slug_brand_edit');

    // Mengambil nilai dari input judul brand
    var judul_brand = namab_brand_edit.value;

    // Mengubah nilai judul menjadi format slug
    var slug = judul_brand.toLowerCase().replace(/[^a-z0-9-]/g, '-');

    // Mengisikan nilai slug ke dalam input slug
    slug_brand_edit.value = slug;
    }
    </script>
    <script src="../../assets/js/admin/brand/index.js"></script>
    <!-- JS -->

</body>
 
</html>