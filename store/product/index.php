<?php
   include '../../config.php';
   
   $page_admin = 'produk';
   
   $sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
   $data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);
    
   if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
       if (isset($_COOKIE['login'])) {
           if ($profile['provinsi_user'] == '') {
               header("location: " . $url . "/store/start");
           }
       } else {
           header("location: " . $url . "/login");
       }
   } else {
       header("location: " . $url);
   }
    
   if (isset($_GET['kategori'])) {
      $idkatget = $_GET['kategori'];
      if ($idkatget == 'semua') {
          $select_produk_adm = $server->query("SELECT i.*, k.nama as nama, a.nama_lengkap, b.namab as nama_brand
                                               FROM `iklan` i
                                               LEFT JOIN `kategori` k ON i.id_kategori = k.id
                                               LEFT JOIN `akun` a ON i.user_id = a.id
                                               LEFT JOIN `brand` b ON i.id_brand = b.id
                                               WHERE i.status='' AND i.user_id='$iduser'
                                               ORDER BY i.waktu_diperbarui DESC, i.id DESC");
      } else {
          $select_produk_adm = $server->query("SELECT i.*, k.nama as nama, a.nama_lengkap, b.namab as nama_brand
                                               FROM `iklan` i
                                               LEFT JOIN `kategori` k ON i.id_kategori = k.id
                                               LEFT JOIN `akun` a ON i.user_id = a.id
                                               LEFT JOIN `brand` b ON i.id_brand = b.id
                                               WHERE i.id_kategori='$idkatget' AND i.status='' AND i.user_id='$iduser'
                                               ORDER BY i.waktu_diperbarui DESC, i.id DESC");
      }
  } else {
      $select_produk_adm = $server->query("SELECT i.*, k.nama as nama, a.nama_lengkap, b.namab as nama_brand
                                           FROM `iklan` i
                                           LEFT JOIN `kategori` k ON i.id_kategori = k.id
                                           LEFT JOIN `akun` a ON i.user_id = a.id
                                           LEFT JOIN `brand` b ON i.id_brand = b.id
                                           WHERE i.status='' AND i.user_id='$iduser'
                                           ORDER BY i.waktu_diperbarui DESC, i.id DESC");
  }
  
  $jumlah_produk_adm = mysqli_num_rows($select_produk_adm);
  
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
   <title>Produk Toko <?php echo $profile['nama_lengkap']; ?> | <?php echo $title_name; ?></title>
      <!-- META SEO --> 
      <!-- META SEO -->
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/admin/product/index.css">
   </head>
   <body>
      <!-- FORM TAMBAH PRODUK -->
      <div class="back_tp_adm" id="back_tp_adm">
         <div class="isi_tp_adm">
            <h1>Tambah Produk</h1>
            <div class="form_isi_tp_adm">
               <div class="box_form_isi_tp_adm">
                  <p class="p_input">Judul Produk</p>
                  <input type="text" class="input" id="judul_tp" placeholder="Judul Produk">
               </div>
               <div class="box_form_isi_tp_adm" style="display:none;">
                  <p class="p_input">Slug (Contoh: sepatu-olahraga)</p>
                  <input type="text" class="input" id="slug_tp" placeholder="Slug Untuk Url">
               </div>
               <div class="box_form_isi_tp_adm2">
                  <div>
                     <p class="p_input">Harga</p>
                     <input type="text" class="input currency" data-separator="." id="harga_tp" placeholder="Harga">
                  </div>
                  <div>
                     <p class="p_input">Kategori</p>
                     <select class="input" id="kategori_tp">
                        <option value="" selected disabled hidden>Kategori</option>
                        <?php
                           $select_kategori_pro = $server->query("SELECT * FROM `kategori`");
                           while ($kategori_pro_data = mysqli_fetch_assoc($select_kategori_pro)) {
                           ?>
                        <option value="<?php echo $kategori_pro_data['id']; ?>"><?php echo $kategori_pro_data['nama']; ?></option>
                        <?php
                           }
                           ?>
                     </select>
                  </div>
                  <div>
                     <p class="p_input">Brand</p>
                     <select class="input" id="brand_tp">
                        <option value="" selected disabled hidden>Merek</option>
                        <?php
                           $select_brand_pro = $server->query("SELECT * FROM `brand`");
                           while ($brand_pro_data = mysqli_fetch_assoc($select_brand_pro)) {
                           ?>
                        <option value="<?php echo $brand_pro_data['id']; ?>"><?php echo $brand_pro_data['namab']; ?></option>
                        <?php
                           }
                           ?>
                     </select>
                  </div>
                  <div>
                     <p class="p_input">Berat Barang</p>
                     <input type="text" class="input currency" data-separator="." id="berat_tp" placeholder="Berat Barang Satuan Gram">
                  </div>
                  <div>
                     <p class="p_input">Stok Barang</p>
                     <input type="text" class="input currency" data-separator="." id="stok_tp" placeholder="Stok Barang">
                  </div>
                  <div>
                     <p class="p_input">Diskon (%)</p>
                     <input type="text" class="input currency" data-separator="." id="diskon_tp" placeholder="Diskon Dalam Persentase">
                  </div>
               </div>
               <div class="box_form_isi_tp_adm">
                  <p class="p_input">Deskripsi</p>
                  <textarea class="input" id="deskripsi_tp" rows="4" placeholder="Deskripsi"></textarea>
               </div>
               <div>
                  <p class="p_input">Foto Produk</p>
                  <div class="box_form_isi_tp_adm5">
                     <?php
                        for ($w_tp_img = 1; $w_tp_img <= 5; $w_tp_img++) {
                        ?>
                     <div class="box_img_produk_tambah">
                        <div class="isi_box_img_produk_tambah" id="isi_box_img_produk_tambah<?php echo $w_tp_img; ?>" onclick="click_img()">
                           <i class="ri-image-add-line" id="icon_tp_<?php echo $w_tp_img; ?>"></i>
                           <img src="" id="show_img_tp_<?php echo $w_tp_img; ?>">
                        </div>
                        <input type="file" class="ci_tp" id="c_img_tp_<?php echo $w_tp_img; ?>" onchange="preview_image(event, '<?php echo $w_tp_img; ?>')">
                     </div>
                     <?php
                        }
                        ?>
                  </div>
               </div>
               <div class="box_varian_produk_adm">
                  <h5>Varian Warna</h5>
                  <p>Contoh: Merah</p>
                  <div class="varian_produk_adm">
                     <?php
                        for ($v_warna_adm = 1; $v_warna_adm <= 50; $v_warna_adm++) {
                        ?>
                     <div class="isi_varian_produk_adm v_warna_adm" id="box_v_warna_adm<?php echo $v_warna_adm; ?>">
                        <input type="text" class="input" id="v_warna_adm<?php echo $v_warna_adm; ?>" placeholder="Warna...">
                     </div>
                     <?php
                        }
                        ?>
                     <div class="isi_varian_produk_adm" id="box_add_warna_varian_adm" onclick="add_warna_varian()">
                        <div class="add_warna_varian_adm">
                           <span>Tambah</span>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" id="jum_v_warna" value="0">
               </div>
               <div class="box_varian_produk_adm">
                  <h5>Varian Ukuran</h5>
                  <p>Contoh Ukuran: XL Untuk Pakaian & 35 Untuk Sepatu DLL</p>
                  <div class="varian_produk_adm2">
                     <?php
                        for ($v_ukuran_adm = 1; $v_ukuran_adm <= 10; $v_ukuran_adm++) {
                        ?>
                     <div class="isi_varian_produk_adm2 v_warna_adm" id="box_v_ukuran_adm<?php echo $v_ukuran_adm; ?>">
                        <input type="text" class="input" id="v_ukuran_adm<?php echo $v_ukuran_adm; ?>" placeholder="Ukuran...">
                        <input type="number" class="input" id="v_ukuran_harga_adm<?php echo $v_ukuran_adm; ?>" placeholder="Harga...">
                     </div>
                     <?php
                        }
                        ?>
                     <div class="isi_varian_produk_adm" id="box_add_ukuran_varian_adm" onclick="add_ukuran_varian()">
                        <div class="add_warna_varian_adm">
                           <span>Tambah</span>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" id="jum_v_ukuran" value="0">
               </div>
               <div class="box_form_isi_tp_adm2">
                  <div>
    <p class="p_input">Kondisi Produk</p>
    <select class="input" id="kondisi_tp">
    <option value="Baru">Baru</option>
        <option value="Bekas">Bekas</option>
    </select>
                     </div>
<div>
    <p class="p_input">Ongkos Kirim</p>
    <select class="input" id="gratis_ongkir_tp">
        <option value="ya">Termasuk Ongkir</option>
        <option value="0">Tidak Termasuk Ongkir</option>
    </select>
</div>
                     </div>
               <div class="box_varian_produk_adm" style="display:none;">
                  <h5>Lokasi Store</h5>
                  <div class="varian_produk_adm2">
                     <?php
$s_lokasi_pro = $server->query("SELECT provinsi_user, kecamatan_id_user FROM `akun` WHERE id = $iduser");

while ($data_s_lokasi_pro = mysqli_fetch_assoc($s_lokasi_pro)) {
?>
    <div class="isi_varian_produk_adm2 v_warna_adm" style="display: flex;">
        <input type="text" class="input" id="lokasi_tp" placeholder="Kota" disabled value="<?= htmlspecialchars($data_s_lokasi_pro['provinsi_user']); ?>">
        <input type="text" class="input" id="id_lokasi_tp" placeholder="Kota" disabled value="<?= htmlspecialchars($data_s_lokasi_pro['kecamatan_id_user']); ?>">
    </div>
<?php
}
?>

                  </div>
               </div>
            </div>
            <div class="box_button_edit_akun">
               <div class="button_cancel_edit_akun" onclick="batal_tp()">
                  <p>Batal</p>
               </div>
               <div class="button_confirm_edit_akun" onclick="simpan_tp()">
                  <p id="text_tp">Simpan</p>
                  <img src="../../assets/icons/loading-w.svg" id="loading_tp">
               </div>
            </div>
         </div>
      </div>
      <!-- FORM TAMBAH PRODUK -->
      <!-- HAPUS PRODUK -->
      <div class="back_popup_confirm" id="popup_hapus_produk">
         <div class="popup_confirm">
            <div class="head_popup_confirm">
               <i class="ri-delete-bin-line"></i>
               <p>Hapus Produk</p>
            </div>
            <h5>Produk yang sudah di hapus tidak akan di tampilkan dan dapat dipulihkan kembali, apakah anda yakin ingin manghapus produk ini?</h5>
            <div class="box_button_popup_confirm">
               <div class="button_cancel_popup_confirm" onclick="batal_hapus_produk()">
                  <p>Batal</p>
               </div>
               <div class="button_confirm_popup_confirm" onclick="hapus_produk_ya()">
                  <p id="text_ha_hp">Hapus</p>
                  <img src="../../assets/icons/loading-w.svg" id="loading_ha_hp">
               </div>
            </div>
         </div>
         <input type="hidden" id="val_id_h_produk">
      </div>
      <!-- HAPUS PRODUK -->
      <!-- FORM EDIT -->
      <div class="back_tp_adm" id="back_ep_adm">
         <div class="isi_tp_adm">
            <h1>Edit Produk</h1>
            <div class="form_isi_tp_adm">
               <div class="box_form_isi_tp_adm">
                  <p class="p_input">Judul Produk</p>
                  <input type="text" class="input" id="judul_ep" placeholder="Judul Produk">
               </div>
               <div class="box_form_isi_tp_adm" style="display:none;">
                  <p class="p_input">Slug (Contoh: sepatu-olahraga)</p>
                  <input type="text" class="input" id="slug_ep" placeholder="Slug Untuk Url">
               </div>
               <div class="box_form_isi_tp_adm2">
                  <div>
                     <p class="p_input">Harga</p>
                     <input type="text" class="input currency" data-separator="." id="harga_ep" placeholder="Harga">
                  </div>
                  <div>
                     <p class="p_input">Kategori</p>
                     <select class="input" id="kategori_ep">
                        <option id="kategori_ep_val" value="" selected disabled hidden>Kategori</option>
                        <?php
                           $select_kategori_pro = $server->query("SELECT * FROM `kategori`");
                           while ($kategori_pro_data = mysqli_fetch_assoc($select_kategori_pro)) {
                           ?>
                        <option value="<?php echo $kategori_pro_data['id']; ?>"><?php echo $kategori_pro_data['nama']; ?></option>
                        <?php
                           }
                           ?>
                     </select>
                  </div>
                  <div>
                     <p class="p_input">Brand</p>
                     <select class="input" id="brand_ep">
                        <option id="brand_ep_val" value="" selected disabled hidden>Merek</option>
                        <?php
                           $select_brand_pro = $server->query("SELECT * FROM `brand`");
                           while ($brand_pro_data = mysqli_fetch_assoc($select_brand_pro)) {
                           ?>
                        <option value="<?php echo $brand_pro_data['id']; ?>"><?php echo $brand_pro_data['namab']; ?></option>
                        <?php
                           }
                           ?>
                     </select>
                  </div>
                  <div>
                     <p class="p_input">Berat Barang</p>
                     <input type="text" class="input currency" data-separator="." id="berat_ep" placeholder="Berat Barang Satuan Gram">
                  </div>
                  <div>
                     <p class="p_input">Stok Barang</p>
                     <input type="text" class="input currency" data-separator="." id="stok_ep" placeholder="Stok Barang">
                  </div>
                  <div>
                  <p class="p_input">Diskon (%)</p>
                  <input type="number" class="input" id="diskon_ep" placeholder="Diskon dalam persentase">
               </div>
               </div>
               <div class="box_form_isi_tp_adm">
                  <p class="p_input">Deskripsi</p>
                  <textarea class="input" id="deskripsi_ep" rows="4" placeholder="Deskripsi"></textarea>
               </div>
               <div>
                  <p class="p_input">Foto Produk</p>
                  <div class="box_form_isi_tp_adm5">
                     <?php
                        for ($w_tp_img = 1; $w_tp_img <= 5; $w_tp_img++) {
                        ?>
                     <div class="box_img_produk_tambah">
                        <div class="isi_box_img_produk_tambah" id="isi_box_img_produk_tambah_ep<?php echo $w_tp_img; ?>" onclick="click_img_ep('<?php echo $w_tp_img; ?>')">
                           <i class="ri-image-add-line" id="icon_ep_<?php echo $w_tp_img; ?>"></i>
                           <img src="" id="show_img_ep_<?php echo $w_tp_img; ?>">
                        </div>
                        <input type="file" class="ci_tp" id="c_img_ep_<?php echo $w_tp_img; ?>" onchange="preview_image_ep(event, '<?php echo $w_tp_img; ?>')">
                        <input type="hidden" id="val_img_ed_ep<?php echo $w_tp_img; ?>">
                     </div>
                     <?php
                        }
                        ?>
                  </div>
               </div>
               <div class="box_varian_produk_adm">
                  <h5>Varian Warna</h5>
                  <p>Contoh: Merah</p>
                  <div class="varian_produk_adm">
                     <?php
                        for ($v_warna_adm_ep = 1; $v_warna_adm_ep <= 50; $v_warna_adm_ep++) {
                        ?>
                     <div class="isi_varian_produk_adm v_warna_adm" id="box_v_warna_adm_ep<?php echo $v_warna_adm_ep; ?>">
                        <input type="text" class="input" id="v_warna_adm_ep<?php echo $v_warna_adm_ep; ?>" placeholder="Warna...">
                     </div>
                     <?php
                        }
                        ?>
                     <div class="isi_varian_produk_adm" id="box_add_warna_varian_adm_ep" onclick="add_warna_varian_ep()">
                        <div class="add_warna_varian_adm">
                           <span>Tambah</span>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" id="jum_v_warna_ep" value="0">
               </div>
               <div class="box_varian_produk_adm">
               <h5>Varian Ukuran</h5>
                  <p>Contoh Ukuran: XL Untuk Pakaian & 35 Untuk Sepatu DLL</p>
                  <div class="varian_produk_adm2">
                     <?php
                        for ($v_ukuran_adm_ep = 1; $v_ukuran_adm_ep <= 10; $v_ukuran_adm_ep++) {
                        ?>
                     <div class="isi_varian_produk_adm2 v_warna_adm" id="box_v_ukuran_adm_ep<?php echo $v_ukuran_adm_ep; ?>">
                        <input type="text" class="input" id="v_ukuran_adm_ep<?php echo $v_ukuran_adm_ep; ?>" placeholder="Ukuran...">
                        <input type="number" class="input" id="v_ukuran_harga_adm_ep<?php echo $v_ukuran_adm_ep; ?>" placeholder="Harga...">
                     </div>
                     <?php
                        }
                        ?>
                     <div class="isi_varian_produk_adm" id="box_add_ukuran_varian_adm_ep" onclick="add_ukuran_varian_ep()">
                        <div class="add_warna_varian_adm">
                           <span>Tambah</span>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" id="jum_v_ukuran_ep" value="0">
               </div>
               <div class="box_form_isi_tp_adm2">
               <div>
    <p class="p_input">Kondisi Produk</p>
    <select class="input" id="kondisi_ep">
    <option value="Baru">Baru</option>
        <option value="Bekas">Bekas</option>
    </select>
                     </div>
                     <div>
    <p class="p_input">Ongkos Kirim</p>
    <select class="input" id="gratis_ongkir_ep">
        <option value="ya">Termasuk Ongkir</option>
        <option value="0">Tidak Termasuk Ongkir</option>
    </select>
</div>
                     </div>

               <div class="box_varian_produk_adm" style="display:none;">
                  <h5>Varian lokasi</h5>
                  <p>Contoh lokasi: XL Untuk Pakaian & 35 Untuk Sepatu DLL</p>
                  <div class="varian_produk_adm2">
                     <?php
$s_lokasi_pro = $server->query("SELECT provinsi_user, kecamatan_id_user FROM `akun` WHERE id = $iduser");

while ($data_s_lokasi_pro = mysqli_fetch_assoc($s_lokasi_pro)) {
?>
    <div class="isi_varian_produk_adm2 v_warna_adm" style="display: flex;">
        <input type="text" class="input" id="lokasi_ep" value="<?= htmlspecialchars($data_s_lokasi_pro['provinsi_user']); ?>" disabled>
        <input type="text" class="input" id="id_lokasi_ep" placeholder="Kecamatan" disabled value="<?= htmlspecialchars($data_s_lokasi_pro['kecamatan_id_user']); ?>">
    </div>
<?php
}
?>

                        
                        
                         
                        
                        
                  </div>
               </div>
            </div>
            <input type="hidden" id="id_produk_ep">
            <div class="box_button_edit_akun">
               <div class="button_cancel_edit_akun" onclick="batal_ep()">
                  <p>Batal</p>
               </div>
               <div class="button_confirm_edit_akun" onclick="simpan_ep()">
                  <p id="text_ep">Simpan</p>
                  <img src="../../assets/icons/loading-w.svg" id="loading_ep">
               </div>
            </div>
         </div>
      </div>
      <!-- FORM EDIT -->
      <div class="admin">
         <?php include '../partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Produk</h1>
            <div class="isi_content_admin">
               <!-- CONTENT -->
               <div class="jumlah_users_admin">
                  <h1>Total Produk</h1>
                  <h1><?php echo $jumlah_produk_adm; ?> Produk</h1>
               </div>
               <div class="add_kategori_adm" onclick="show_add_produk()">
                  <p>Tambah Produk</p>
                  <i class="ri-add-box-line"></i>
               </div>
               <p class="filbsl">Filter</p>
               <div class="box_search_cl">
                  <form action="" method="GET">
                     <select class="input_scla" name="kategori" onchange="p_kategori_scla()">
                        <option value="semua">Semua Kategori</option>
                        <?php
                           $s_kategori_s_padm = $server->query("SELECT * FROM `kategori` ");
                           while ($data_s_kategori_s_padm = mysqli_fetch_assoc($s_kategori_s_padm)) {
                           ?>
                        <option value="<?php echo $data_s_kategori_s_padm['id']; ?>" <?php if (isset($_GET['kategori'])) {
                           if ($_GET['kategori'] == $data_s_kategori_s_padm['id']) {
                               echo 'selected';
                           }
                           } ?>><?php echo $data_s_kategori_s_padm['nama']; ?></option>
                        <?php
                           }
                           ?> 
                     </select>
                     <button id="c_k_scla" style="display: none;"></button>
                  </form>
                  <input type="text" class="input_scla" id="inputcu" onkeyup="cari_list_user()" placeholder="Cari Produk">
               </div>
               <div class="list_produk_n" id="list_produk_n">
                  <?php
                     while ($list_produk_adm = mysqli_fetch_assoc($select_produk_adm)) {
                         $exp_gambar_lp = explode(',', $list_produk_adm['gambar']);
                         $j_gambar_lp = count($exp_gambar_lp);
                     ?>
                  <div class="isi_all_users_admin" id="isi_all_users_admin<?php echo $list_produk_adm['id']; ?>">
                     <div class="box_left_aua">
                        <img src="../../assets/image/product/compressed/<?php echo $exp_gambar_lp[0]; ?>">
                        <div class="isi_box_left_aua">
                           <h5 class="nl_jss">
                              <?php if ($list_produk_adm['status_moderasi'] === 'Menunggu Moderasi'): ?>
                              <i class="ri-information-fill info-icon" style="font-size: 20px;color: #ff8300;padding: 0 0 3px 0;vertical-align: middle;" title="Menunggu Moderasi"></i>
                              <?php endif; ?>
                              <?php if ($list_produk_adm['status_moderasi'] === 'Ditolak'): ?>
                              <i class="ri-information-fill info-icon" style="font-size: 20px;color: red;padding: 0 0 3px 0;vertical-align: middle;" title="Produk Ditolak"></i>
                              <?php endif; ?>
                              <?php echo $list_produk_adm['judul']; ?>
                           </h5>
                           <p class="nama_kategori_jss">[<?php echo $list_produk_adm['nama_brand']; ?>] [<?php echo $list_produk_adm['nama']; ?>]</p>
                           
                        </div>
                     </div>
                     <div class="box_right_aua">
                        <div class="isi_box_right_aua1">
                           <h3>Harga</h3> 
                           <p>Rp<?php echo number_format($list_produk_adm['harga'], 0, ".", "."); ?></p>
                        </div>
                        <div class="isi_box_right_aua2">
                           <h3>Berat</h3>
                           <p><?php echo $list_produk_adm['berat']; ?> G</p>
                        </div>
                        <div class="isi_box_right_aua3">
                           <h3>Terjual</h3>
                           <p><?php echo $list_produk_adm['terjual']; ?></p>
                        </div>
                        <div class="isi_box_right_aua4">
                           <h3>Total Stok</h3>
                           <p><?php echo $list_produk_adm['stok']; ?></p>
                        </div>
                     </div>
                     <div class="bu_edit_aua" id="show_edit_produk<?php echo $list_produk_adm['id']; ?>">
                        <i class="ri-pencil-line"></i>
                     </div>
                     <div class="bu_delete_aua" onclick="show_hapus_produk('<?php echo $list_produk_adm['id']; ?>')">
                        <i class="ri-delete-bin-line"></i>
                     </div>
                     <script>
                        show_edit_produk<?php echo $list_produk_adm['id']; ?>.onclick = function() {
                            back_ep_adm.style.display = 'block';
                            judul_ep.value = '<?php echo $list_produk_adm['judul']; ?>';
                            slug_ep.value = '<?php echo $list_produk_adm['slug']; ?>';
                            harga_ep.value = '<?php echo $list_produk_adm['harga']; ?>';
                            kategori_ep_val.value = '<?php echo $list_produk_adm['id_kategori']; ?>';
                            kategori_ep_val.innerHTML = '<?php echo $list_produk_adm['nama']; ?>';
                        // Perbaikan untuk menampilkan brand terpilih di form edit
                        var selectedBrandId = '<?php echo $list_produk_adm['id_brand']; ?>';
                        var brandSelect = document.getElementById('brand_ep');
                        
                        // Loop untuk menentukan brand yang terpilih
                        for (var i = 0; i < brandSelect.options.length; i++) {
                        if (brandSelect.options[i].value == selectedBrandId) {
                        brandSelect.selectedIndex = i;
                        break;
                        }
                        }
                            berat_ep.value = '<?php echo $list_produk_adm['berat']; ?>';
                            stok_ep.value = '<?php echo $list_produk_adm['stok']; ?>';
                            deskripsi_ep.value = `<?php echo $list_produk_adm['deskripsi']; ?>`;
                            diskon_ep.value = `<?php echo $list_produk_adm['diskon']; ?>`;
                            gratis_ongkir_ep.value = `<?php echo $list_produk_adm['gratis_ongkir']; ?>`;
                            var selectedValue = document.getElementById('gratis_ongkir_ep').value;
console.log('Nilai yang dipilih: ' + selectedValue);
kondisi_ep.value = `<?php echo $list_produk_adm['kondisi']; ?>`;
                            var selectedValue = document.getElementById('kondisi_ep').value;
console.log('Nilai yang dipilih: ' + selectedValue);
                            lokasi_ep.value = `<?php
    $s_lokasi_pro = $server->query("SELECT provinsi_user FROM `akun` WHERE id = $iduser");
    while ($data_s_lokasi_pro = mysqli_fetch_assoc($s_lokasi_pro)) {
    ?><?php echo $data_s_lokasi_pro['provinsi_user']; ?>
                     <?php
                        }
                        ?>`;
                        id_lokasi_ep.value = `<?php
    $s_lokasi_pro = $server->query("SELECT kecamatan_id_user FROM `akun` WHERE id = $iduser");
    while ($data_s_lokasi_pro = mysqli_fetch_assoc($s_lokasi_pro)) {
    ?><?php echo $data_s_lokasi_pro['kecamatan_id_user']; ?>
                     <?php
                        }
                        ?>`;
                            id_produk_ep.value = '<?php echo $list_produk_adm['id']; ?>';
                            <?php
                           for ($j_gambar_ep = 1; $j_gambar_ep <= $j_gambar_lp; $j_gambar_ep++) {
                               $key_gambar_ep = $j_gambar_ep - 1;
                           ?>
                                var add_icon_ep = 'icon_ep_' + <?php echo $j_gambar_ep; ?>;
                                var add_show_img_ep = 'show_img_ep_' + <?php echo $j_gambar_ep; ?>;
                                var add_val_img_ed_ep = 'val_img_ed_ep' + <?php echo $j_gambar_ep; ?>;
                                document.getElementById(add_icon_ep).style.display = 'none';
                                document.getElementById(add_show_img_ep).style.display = 'block';
                                document.getElementById(add_show_img_ep).src = '../../assets/image/product/compressed/<?php echo $exp_gambar_lp[$key_gambar_ep]; ?>';
                                document.getElementById(add_val_img_ed_ep).value = '<?php echo $exp_gambar_lp[$key_gambar_ep]; ?>';
                            <?php
                           }
                           if ($list_produk_adm['warna'] !== '') {
                               $exp_warna_adm_ep = explode(',', $list_produk_adm['warna']);
                               $jumlah_warna_adm_ep = count($exp_warna_adm_ep);
                           ?>
                                jum_v_warna_ep.value = <?php echo $jumlah_warna_adm_ep; ?>;
                                <?php
                           for ($lop_jw_ep = 1; $lop_jw_ep <= $jumlah_warna_adm_ep; $lop_jw_ep++) {
                               $key_arr_warna_ep = $lop_jw_ep - 1;
                           ?>
                                    box_v_warna_adm_ep<?php echo $lop_jw_ep; ?>.style.display = 'block';
                                    v_warna_adm_ep<?php echo $lop_jw_ep; ?>.value = '<?php echo $exp_warna_adm_ep[$key_arr_warna_ep]; ?>';
                                <?php
                           }
                           }
                           if ($list_produk_adm['ukuran'] !== '') {
                           $exp_ukuran_adm_ep = explode(',', $list_produk_adm['ukuran']);
                           $jumlah_ukuran_adm_ep = count($exp_ukuran_adm_ep);
                           ?>
                                jum_v_ukuran_ep.value = <?php echo $jumlah_ukuran_adm_ep; ?>;
                                <?php
                           for ($lop_ju_ep = 1; $lop_ju_ep <= $jumlah_ukuran_adm_ep; $lop_ju_ep++) {
                               $key_arr_ukuran_ep = $lop_ju_ep - 1;
                               $exp_har_uk_ep = explode('===', $exp_ukuran_adm_ep[$key_arr_ukuran_ep]);
                           ?>
                                    box_v_ukuran_adm_ep<?php echo $lop_ju_ep; ?>.style.display = 'grid';
                                    v_ukuran_adm_ep<?php echo $lop_ju_ep; ?>.value = '<?php echo $exp_har_uk_ep[0]; ?>';
                                    v_ukuran_harga_adm_ep<?php echo $lop_ju_ep; ?>.value = '<?php echo $exp_har_uk_ep[1]; ?>';
                            <?php
                           }
                           }
                           ?>
                        }
                     </script>
                  </div>
                  <?php
                     }
                     ?>
               </div>
               <!-- CONTENT -->
            </div>
         </div>
      </div>
      <div id="res" style="display: block;"></div>
      <input type="hidden" value="store" id="tipe_user_vt">
      <!-- JS -->
      <script>
        function capitalizeFirstLetter(input) {
        return input.replace(/(^|\s|-)[a-z]/g, function(match) {
        return match.toUpperCase();
        });
        }

         document.getElementById('judul_tp').addEventListener('input', function() {
             var judulInput = this.value;
             var capitalizedJudul = capitalizeFirstLetter(judulInput);
             document.getElementById('judul_tp').value = capitalizedJudul;
         });
         
         document.getElementById('judul_ep').addEventListener('input', function() {
             var judulInput = this.value;
             var capitalizedJudul = capitalizeFirstLetter(judulInput);
             document.getElementById('judul_ep').value = capitalizedJudul;
         });
      </script>
      <?php
         for ($v_warna_adm = 1; $v_warna_adm <= 50; $v_warna_adm++) {
         ?>
      <script>
         document.getElementById('v_warna_adm<?php echo $v_warna_adm; ?>').addEventListener('input', function() {
             var judulInput = this.value;
             var capitalizedJudul = capitalizeFirstLetter(judulInput);
             document.getElementById('v_warna_adm<?php echo $v_warna_adm; ?>').value = capitalizedJudul;
         });
      </script>
      <?php
         }
         ?>
      <?php
         for ($v_warna_adm_ep = 1; $v_warna_adm_ep <= 50; $v_warna_adm_ep++) {
         ?>
      <script>
         document.getElementById('v_warna_adm_ep<?php echo $v_warna_adm_ep; ?>').addEventListener('input', function() {
             var judulInput = this.value;
             var capitalizedJudul = capitalizeFirstLetter(judulInput);
             document.getElementById('v_warna_adm_ep<?php echo $v_warna_adm_ep; ?>').value = capitalizedJudul;
         });
      </script>
      <?php
         }
         ?>
      <?php
         for ($v_ukuran_adm = 1; $v_ukuran_adm <= 50; $v_ukuran_adm++) {
         ?>
      <script>
         document.getElementById('v_ukuran_adm<?php echo $v_ukuran_adm; ?>').addEventListener('input', function() {
             var judulInput = this.value;
             var capitalizedJudul = capitalizeFirstLetter(judulInput);
             document.getElementById('v_ukuran_adm<?php echo $v_ukuran_adm; ?>').value = capitalizedJudul;
         });
      </script>
      <?php
         }
         ?>
      <?php
         for ($v_ukuran_adm_ep = 1; $v_ukuran_adm_ep <= 50; $v_ukuran_adm_ep++) {
         ?>
      <script>
         document.getElementById('v_ukuran_adm_ep<?php echo $v_ukuran_adm_ep; ?>').addEventListener('input', function() {
             var judulInput = this.value;
             var capitalizedJudul = capitalizeFirstLetter(judulInput);
             document.getElementById('v_ukuran_adm_ep<?php echo $v_ukuran_adm_ep; ?>').value = capitalizedJudul;
         });
      </script>
      <?php
         }
         ?>
      <script>
         // Fungsi untuk menghasilkan ID unik berdasarkan timestamp saat ini
         function generateUniqueId() {
             return Date.now().toString().slice(-5); // Menggunakan 5 digit terakhir dari timestamp
         }
         
         // Fungsi untuk mengubah judul produk menjadi slug
function generateSlug() {
    var judul = document.getElementById('judul_tp').value;
    var uniqueId = generateUniqueId();

    // Mengganti karakter khusus dengan tanda hubung
    judul = judul.replace(/[^\w\s]/gi, '').trim();

    var slug = uniqueId + '-' + judul.toLowerCase().replace(/\s+/g, '-');
    document.getElementById('slug_tp').value = slug;
}

         
         // Memanggil fungsi generateSlug setiap kali judul produk diubah
         document.getElementById('judul_tp').addEventListener('input', generateSlug);
      </script>
      <script src="../../assets/js/admin/product/index.js"></script>
      <!-- JS -->
      <?php include '../../partials/bottom-navigation.php'; ?>
   </body>
</html>
