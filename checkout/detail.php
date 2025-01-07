<?php
   include '../config.php';
   include '../system/location/provinsi.php';
   require_once '../assets/composer/midtrans-php-master/Midtrans.php';
   
   $id_invoice = $_GET['idinvoice'];
   $invoice = $server->query("SELECT * FROM `invoice` WHERE `idinvoice`='$id_invoice' ");
   $invoice_data = mysqli_fetch_assoc($invoice);
   $tipe_progres_iv = $invoice_data['tipe_progress'];
   
   $exp_id_iklan = explode(',', $invoice_data['id_iklan']);
   $j_id_iklan = count($exp_id_iklan);
   
   $exp_harga_i = explode(',', $invoice_data['harga_i']);
   $exp_diskon_i = explode(',', $invoice_data['diskon_i']);
   $exp_jumlah = explode(',', $invoice_data['jumlah']);
   $exp_warna_i = explode(',', $invoice_data['warna_i']);
   $exp_ukuran_i = explode(',', $invoice_data['ukuran_i']);
   $exp_id_lokasi_i = explode(',', $invoice_data['id_lokasi_i']);
   $exp_lokasi_i = explode(',', $invoice_data['lokasi_i']);
   
   $harga_final_per_produk = 0;
    
   for ($i_l_v = 0; $i_l_v < $j_id_iklan; $i_l_v++) {
       // HARGA
       $hitung_diskon_fs = ($exp_diskon_i[$i_l_v] / 100) * $exp_harga_i[$i_l_v];
       $harga_diskon_fs = ($exp_harga_i[$i_l_v] - $hitung_diskon_fs) * $exp_jumlah[$i_l_v];
       $harga_final_per_produk += $harga_diskon_fs;
       // $harga_satuan = $harga_diskon_fs / $invoice_data['jumlah'];
   }
   
   if (!$invoice_data) {
       header("Location: " . $url);
   }
   
   // RAJA ONGKIR COST
   if (!$invoice_data['kota'] == '') {
       $prov_exp_li = explode(',', $invoice_data['provinsi']);
       $kota_exp_li = explode(',', $invoice_data['kota']);
       $keca_exp_li = explode(',', $invoice_data['kecamatan']);
       $kota_tujuan  = $kota_exp_li[0];
       $keca_tujuan = $keca_exp_li[0];
       $kurir_ivd = $invoice_data['kurir'];
       $layanan_kurir_ivd = $invoice_data['layanan_kurir'];
       $etd_pengiriman_ivd = $invoice_data['etd'];
       $harga_ongkir = $invoice_data['harga_ongkir'];
   } else {
       $harga_ongkir = 0;
   }
   
   $total_biaya = ($harga_final_per_produk + $harga_ongkir) - $invoice_data['diskon_voucher'];
   $sub_total = ($harga_final_per_produk + $harga_ongkir);
   
   
   // Query untuk mengambil user ID pemilik iklan berdasarkan informasi iklan
   $user_id_queries = [];
   for ($i = 0; $i < $j_id_iklan; $i++) {
    $id_iklan = $exp_id_iklan[$i];
    $user_id_queries[] = "SELECT user_id FROM iklan WHERE id = $id_iklan";
   }
   
   $user_ids = [];
   foreach ($user_id_queries as $query) {
    $result_user_id = $server->query($query);
    if ($result_user_id) {
        $row_user_id = mysqli_fetch_assoc($result_user_id);
        $user_ids[] = $row_user_id['user_id'];
    }
   }
   
   // MIDTRANS
   // Mengambil detail produk dari tabel iklan
   $item_details = array(); // Inisialisasi array untuk detail produk
   
   for ($i = 0; $i < $j_id_iklan; $i++) {
    $id_iklan = $exp_id_iklan[$i];
    
    // Query untuk mengambil detail produk dari tabel iklan
    $query_produk = "SELECT * FROM iklan WHERE id = $id_iklan";
    $result_produk = $server->query($query_produk);
    
    if ($result_produk) {
        $row_produk = mysqli_fetch_assoc($result_produk);
        
        // Menambahkan detail produk ke dalam array item_details
        $item_details[] = array(
            'id' => $row_produk['id'],
            'price' => $total_biaya,
            'quantity' => $exp_jumlah[$i],
            'name' => $row_produk['judul'],
            'brand' => $row_produk['id_brand'], // Opsional, sesuaikan dengan kolom yang sesuai
            'category' => $row_produk['id_kategori'] // Opsional, sesuaikan dengan kolom yang sesuai
            // Tambahkan detail lain jika diperlukan dari tabel iklan
        );
    }
   }
   $sf_midtrans_mode = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='midtrans mode' ");
   $data_sf_midtrans_mode = mysqli_fetch_array($sf_midtrans_mode);
   // Set your Merchant Server Key
   \Midtrans\Config::$serverKey = $midtrans_server_key;
   // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
   if ($data_sf_midtrans_mode['opsi_fitur'] == 'Production') {
       \Midtrans\Config::$isProduction = true;
   } elseif ($data_sf_midtrans_mode['opsi_fitur'] == 'Sandbox') {
       \Midtrans\Config::$isProduction = false;
   }
   // Set sanitization on (default)
   \Midtrans\Config::$isSanitized = true;
   // Set 3DS transaction for credit card to true
   \Midtrans\Config::$is3ds = true;
   
   $order_id_midtrans = $id_invoice . '-midtrans-' . time();
   
   $params = array(
    'transaction_details' => array(
        'order_id' => $order_id_midtrans,
        'gross_amount' => $total_biaya,
    ),
    'item_details' => $item_details, // Gunakan detail produk yang sudah diambil dari tabel iklan
    'customer_details' => array(
        'first_name' => $profile['nama_lengkap'],
        'email' => $profile['email'],
    ),
   );
   $snapToken = \Midtrans\Snap::getSnapToken($params);
   
   // NOMOR REKENING
   $select_norek = $server->query("SELECT * FROM `nomor_rekening`");
$data_norek = [];

// Looping untuk mengambil setiap baris hasil query
while ($row = mysqli_fetch_assoc($select_norek)) {
    $data_norek[] = $row;
}
   
   // FITUR COD
   $sf_cod = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='cod' ");
   $data_sf_cod = mysqli_fetch_array($sf_cod);
   
   // Query untuk mengambil data voucher dari tabel voucher
$query = "SELECT * FROM vouchers WHERE user_id = $user_ids[0]";
$result = mysqli_query($server, $query);

// Inisialisasi array voucherList
$voucherList = [];

// Loop melalui hasil query dan tambahkan ke voucherList
while ($row = mysqli_fetch_assoc($result)) {
    $voucherList[] = $row;

   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Checkout Sekarang | <?php echo $title_name; ?></title>
      <!-- META SEO -->
      <?php include '../partials/seo.php'; ?>
      <!-- META SEO -->
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
      <link rel="stylesheet" href="../../assets/css/checkout/detail.css">
      <?php
         if ($data_sf_midtrans_mode['opsi_fitur'] == 'Production') {
         ?>
      <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?php echo $midtrans_client_key; ?>"></script>
      <?php
         } elseif ($data_sf_midtrans_mode['opsi_fitur'] == 'Sandbox') {
         ?>
      <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo $midtrans_client_key; ?>"></script>
      <?php
         }
         ?>
   </head>
   <body id="body">
      <!-- LOKASI DIKIRIM DARI -->
      <div class="dikirim_dari" id="dikirim_dari">
         <div class="isi_dikirim_dari">
            <h1>Dikirim Dari</h1>
            <div class="box_list_dikirimdari">
               <?php
                  $s_slocas = $server->query("SELECT * FROM `setting_lokasi`");
                  while ($data_s_slocas = mysqli_fetch_assoc($s_slocas)) {
                  ?>
               <div class="list_dikirimdari" onclick="ubah_dikirim_dari('<?php echo $data_s_slocas['id']; ?>', '<?php echo $id_invoice; ?>')">
                  <p><?php echo $data_s_slocas['provinsi'] . ', ' . $data_s_slocas['kota'] . ', ' . $data_s_slocas['kecamatan']; ?></p>
               </div>
               <?php
                  }
                  ?>
            </div>
         </div>
      </div>
      <!-- LOKASI DIKIRIM DARI -->
      <!-- SETTING LOKASI -->
      <div class="setting_lokasi" id="setting_lokasi">
         <div class="isi_setting_lokasi">
            <h1>Tentukan Alamat Pengiriman</h1>
            <p>1. pilih Provinsi, tunggu. 2. Pilih Kota / Kabupaten, tunggu lagi. 3. Pilih Kecamatan, dan lengkapi kolom lainnya.</p>
            <div class="form_provinsi_kota">
               <div class="isi_form_provinsi_kota">
                  <select class="select" id="provinsi" onchange="changeProvinsi()">
                     <option value="" selected disabled hidden>Pilih Provinsi</option>
                     <?php
                        foreach ($provinsi_isi_data as $key_provinsi_isi_data => $value_provinsi_isi_data) {
                        ?>
                     <option value="<?php echo $value_provinsi_isi_data['province_id'] . '=' . $value_provinsi_isi_data['province']; ?>"><?php echo $value_provinsi_isi_data['province']; ?></option>
                     <?php
                        }
                        ?>
                  </select>
               </div>
               <div class="isi_form_provinsi_kota">
                  <select class="select" id="kota" onchange="changeKota()">
                     <option value="" selected disabled hidden>Pilih Kota</option>
                  </select>
               </div>
               <div class="isi_form_provinsi_kota">
                  <select class="select" id="kecamatan">
                     <option value="" selected disabled hidden>Pilih Kecamatan</option>
                  </select>
               </div>
               <div class="isi_form_provinsi_kota">
                  <input type="number" class="input" id="notelp" placeholder="Nomor Penerima">
               </div>
            </div>
            <textarea class="textarea alamat_lengkap" id="alamat_lengkap" rows="3" placeholder="Tuliskan Gang, Jalan, RT RW, Kampung, Desa"></textarea>
            <div class="button simpan_lokasi" id="simpan_lokasi" onclick="SimpanLlokasi('<?php echo $id_invoice; ?>')">
               <p>Simpan</p>
            </div>
            <div class="button simpan_lokasi" id="loading_lokasi">
               <img src="../../assets/icons/loading-w.svg">
            </div>
            <div class="button batal_lokasi" id="batal_lokasi" onclick="BatalLlokasi()">
               <p>Batalkan</p>
            </div>
         </div>
      </div>
      <!-- SETTING LOKASI -->
      <!-- UBAH ONGKIR -->
      <div class="ubah_ongkir" id="ubah_ongkir">
         <div class="ubah_ongkir_isi">
            <h1>Opsi Pengiriman</h1>
            <center><img src="../../assets/icons/loading-o.svg" class="loading_ubah_ongkir" id="loading_ubah_ongkir"></center>
            <div class="res_ubah_ongkir" id="res_ubah_ongkir"></div>
            <div class="button batal_lokasi" id="batal_ubah_ongkir" onclick="CloseUbahOngkir()">
               <p>Batalkan</p>
            </div>
         </div>
      </div>
      <!-- UBAH ONGKIR -->
      <!-- TRANSFER MANUAL -->
<div class="pop_transfer_manual" id="pop_transfer_manual">
    <div class="pop_transfer_manual_isi">
        <h1>Selesaikan Pembayaran</h1>
        <div class="box_transfer_manual">
            <div class="box_list_pop_transfer_manual">
                <div class="judul_list_pop_transfer_manual">
                    <h1>Metode Pembayaran</h1>
                    <select class="input" id="metode_pembayaran" onchange="showPaymentDetails()">
                     <option value="" selected disabled hidden>Pilih Metode</option>
                     <?php
                        foreach ($data_norek as $norek) {
                        ?>
                     <option value="<?php echo $norek['idnorek']; ?>"><?php echo $norek['nama_bank']; ?></option>
                     <?php
                        }
                        ?>
                  </select>

                </div>
            </div>
           <div class="box_list_pop_transfer_manual" id="detail_pembayaran" style="display:none;">
    <div class="judul_list_pop_transfer_manual">
        <h1>BANK <span id="nama_bank"></span></h1>
        <h5>a.n <span id="an"></span></h5>
        <h1>Nomor Rekening</h1>
        <h5><span id="nomor_rekening"></span><br></h5> <!-- Tambahkan tag br di sini -->
    </div>
    <p></p>
</div>

            <div class="box_list_pop_transfer_manual">
                <div class="judul_list_pop_transfer_manual">
                    <h1>Jumlah Dibayarkan</h1>
                    <h5>Rp<?php echo number_format($total_biaya, 0, ".", "."); ?></h5>
                </div>
                <p></p>
            </div>
            <div class="box">
                <div class="judul_list">
                    <h4 class="p_input">Upload Bukti Transfer</h4>
                    <input type="file" class="input" id="inp_bukti_transfer" accept="image/*" onchange="change_image()">
                    <p class="alert_file_npng_bt" id="alert_file_npng_bt">Pastikan file berformat jpg/png</p>
                </div>
                <p></p>
            </div>
            <p></p>
            <div class="isi_box_transfer_manual">
    <div class="button" id="ubt" onclick="upload_bukti_transfer_manual('<?php echo $id_invoice; ?>', '<?php echo $selected_bank; ?>')">
        <p>Kirim Pembayaran</p>
    </div>
    <div class="button" id="loading_ubt">
        <img src="../../assets/icons/loading-w.svg" alt="">
    </div>
    <div class="button batal_pop_transfer_manual" id="batal_pop_transfer_manual" onclick="closeTransferManual()">
        <p>Batalkan</p>
    </div>
</div>

        </div>
    </div>
</div>
<!-- TRANSFER MANUAL -->
      <!-- TIPE PEMBAYARAN -->
      <div class="back_ubah_tipe_pembayaran" id="back_ubah_tipe_pembayaran">
         <div class="ubah_tipe_pembayaran" id="ubah_tipe_pembayaran">
            <h1>Opsi Pembayaran</h1>
            <div class="box_ubah_tipe_pembayaran">
               <div id="cod" class="<?php if ($invoice_data['tipe_pembayaran'] == 'cod') {
                  echo 'isi_box_ubah_tipe_pembayaran_active';
                  } else {
                  echo 'isi_box_ubah_tipe_pembayaran';
                  } ?>" id="bu_tp_cod" onclick="ubah_tp('<?php echo $id_invoice; ?>', 'cod')">
                  <h5 class="n_utp_judul">COD</h5>
                  <p class="n_utp_desc">Pembayaran Ditempat</p>
               </div>
               <div id="online" class="<?php if ($invoice_data['tipe_pembayaran'] == 'cod') {
                  echo 'isi_box_ubah_tipe_pembayaran';
                  } else {
                  echo 'isi_box_ubah_tipe_pembayaran_active';
                  } ?>" id="bu_tp_online" onclick="ubah_tp('<?php echo $id_invoice; ?>', 'online')">
                  <h5 class="n_utp_judul">Online</h5>
                  <p class="n_utp_desc">Pembayaran Online</p>
               </div>
            </div>
         </div>
      </div>
      <!-- TIPE PEMBAYARAN -->
      <!-- PILIH VOUCHER -->
<div class="back_pv" id="back_pv">
    <div class="voucher">
        <h1>Pilih Voucher</h1>
        <div class="box_isi_voucher">
            <?php if (empty($voucherList)) { ?>
                <!-- ... kode untuk menangani daftar voucher kosong ... -->
                <p>Tidak ada voucher yang tersedia saat ini.</p>
            <?php } else {
                foreach ($voucherList as $voucher) {
                    $isExpired = strtotime($voucher['waktu_berakhir']) < time();

                    // Tambahkan kondisi untuk menyembunyikan tombol "Pakai" jika nilai maksimal lebih tinggi dari $sub_total
                    $isValueGreaterThanSubTotal = $voucher['maksimal'] > $sub_total;

                    ?>
                    <div class="isi_voucher">
                        <div class="persen_voucher">
                            <div class="dot_voucher"></div>
                            <div class="isi_persen_voucher"><?php echo $voucher['jenis']; ?></div>
                        </div>
                        <div class="content_voucher">
                            <div class="deskripsi_voucher">
                                <div class="deskripsi_voucher_judul">
                                    <h5><?php echo $voucher['jenis']; ?> <?php echo $voucher['persen']; ?>%</h5>
                                    <p>Maksimal <?php echo 'Rp' . number_format($voucher['maksimal'], 0, ',', '.'); ?></p>
                                    <?php if ($isValueGreaterThanSubTotal) { ?>
                                    <div class="deskripsi_voucher_waktu">
                                        <h5>Note: Nilai voucher tidak sesuai<h5>
                                            </div>
                                    <?php } ?>
                                </div>
                                <div class="deskripsi_voucher_waktu">
                                    <?php if ($isExpired) { ?>
                                        <h5>Oops! Voucher Kedaluwarsa!</h5>
                                    <?php } else { ?>
                                        <h5>Berlaku s/d: <?php echo $voucher['waktu_berakhir']; ?></h5>
                                    <?php } ?>
                                </div>
                            </div>
                            <img src="../../assets/icons/loading-o.svg" class="loading_voucher" id="loading_voucher<?php echo $voucher['id']; ?>">
                            <?php if (!$isExpired && !$isValueGreaterThanSubTotal) { ?>
                                <div class="button_voucher" id="voucher_bk<?php echo $voucher['id']; ?>" onclick="pakai_voucher('<?php echo $user_ids; ?>', '<?php echo $id_invoice; ?>', '<?php echo $voucher['id']; ?>', 'voucher_bk<?php echo $voucher['id']; ?>', 'loading_voucher<?php echo $voucher['id']; ?>')">
                                    <?php echo ($voucher['digunakan']) ? 'Terpakai' : 'Pakai'; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
        </div>
        <div class="button batal_voucher" id="batal_voucher" onclick="BatalLvoucher()">
            <p>Batalkan</p>
        </div>
    </div>
</div>
<!-- PILIH VOUCHER -->




      <!-- CATATAN -->
<div class="back_catatan" id="back_catatan">
   <div class="box_catatan">
      <h1>Catatan</h1>
      <textarea id="i_catatanb" rows="3" class="input" placeholder="Tambahkan Catatan..." style="margin-top: 20px;"><?php echo $invoice_data['catatan']; ?></textarea>
      <div class="button butacat" onclick="simpan_catatan('<?php echo $id_invoice; ?>')">
         <p id="p_butacat">Simpan</p>
         <img src="../../assets/icons/loading-w.svg" id="load_butacat">
      </div>
      <div class="button batal_lokasi" id="batal_catatan" onclick="BatalLcatatan()">
         <p>Batalkan</p>
      </div>
   </div>
</div>
<!-- CATATAN -->

      <!-- HEADER -->
      <?php include '../partials/header.php'; ?>
      <!-- HEADER -->
      <!-- CONTENT -->
      <div class="width">
         <div class="checkout">
            <div class="alamat">
               <div class="box_alamat">
                  <h1><i class="ri-map-pin-2-line"></i> Alamat Pengiriman</h1>
                  <?php
                     if ($invoice_data['tipe_progress'] == 'Belum Bayar') {
                         if ($invoice_data['bukti_transfer'] == '') {
                     ?>
                  <h5 id="ubah_alamat" onclick="ubahAlamat()"><i class="fa fa-edit"></i> UBAH</h5>
                  <?php
                     }
                     }
                     ?>
               </div>
               <?php
                  if (!$invoice_data['kota'] == '') {
                  ?>
               <p style="color:blue;"><?php echo $invoice_data['alamat_lengkap']; ?>, Kec. <?php echo $keca_exp_li[1]; ?>, <?php echo $kota_exp_li[1]; ?> - <?php echo $prov_exp_li[1]; ?> | <?php echo $invoice_data['notelp']; ?></p>
               <?php
                  } else {
                  ?>
               <p>Alamat pengiriman belum ditentukan</p>
               <?php
                  }
                  ?>
            </div>
            <?php
               $idloca = $invoice_data['idloc'];
               $slokasisloc = $server->query("SELECT * FROM `akun` WHERE `id`='$user_id' ");
               $data_slokasisloc = mysqli_fetch_assoc($slokasisloc);
               if ($data_slokasisloc) {
               ?>
            <?php
               }
               ?>
            
            <div class="detail_checkout">
               <h1>Rincian Pesanan</h1>
               <?php
                  $berat_barang = 0;
                  for ($numlistpro = 0; $numlistpro < $j_id_iklan; $numlistpro++) {
                      $v_id_iklan = $exp_id_iklan[$numlistpro];
                      $v_harga_i = $exp_harga_i[$numlistpro];
                      $v_diskon_i = $exp_diskon_i[$numlistpro];
                      $v_jumlah = $exp_jumlah[$numlistpro];
                      $select_ikkat = $server->query("SELECT * FROM `iklan`, `kategori` WHERE iklan.id='$v_id_iklan' AND iklan.id_kategori=kategori.id ");
                      $data_ikkat = mysqli_fetch_assoc($select_ikkat);
                      $exp_gambar_id = explode(',', $data_ikkat['gambar']);
                  
                      $hitung_diskon_fs_l = ($exp_diskon_i[$numlistpro] / 100) * $exp_harga_i[$numlistpro];
                      $harga_diskon_fs_l = ($exp_harga_i[$numlistpro] - $hitung_diskon_fs_l) * $exp_jumlah[$numlistpro];
                      $harga_satuan_l = $harga_diskon_fs_l / $v_jumlah;
                  
                      $berat_per_produk = $data_ikkat['berat'] * $v_jumlah;
                      $berat_barang += $berat_per_produk;
                  ?> 
               <div class="box_detail_checkout">
                  <div class="rincian_checkout">
                     <img src="../../assets/image/product/compressed/<?php echo $exp_gambar_id[0]; ?>" alt="">
                     <div class="judul_rincian_checkout">
                        <h1><?php echo $data_ikkat['judul']; ?></h1>
                        <p>Kategori <span><?php echo $data_ikkat['nama']; ?></span></p>
                        <?php
                           // Memeriksa apakah setidaknya salah satu varian memiliki nilai
                           if ($exp_warna_i[$numlistpro] != '' || $exp_ukuran_i[$numlistpro] != '') {
                           ?>
                        <p>Varian 
                           <span>
                           <?php 
                              // Mengecek dan menampilkan varian warna jika memiliki nilai
                              if ($exp_warna_i[$numlistpro] != '') {
                                  echo $exp_warna_i[$numlistpro];
                              }
                              // Mengecek dan menampilkan varian ukuran jika memiliki nilai
                              if ($exp_ukuran_i[$numlistpro] != '') {
                                  if ($exp_warna_i[$numlistpro] != '') {
                                      echo ', ';
                                  }
                                  echo $exp_ukuran_i[$numlistpro];
                              }
                              ?>
                           </span>
                        </p>
                        <?php
                           }
                           ?>
                     </div>
                  </div>
                  <div class="box_harga_satuan_checkout">
                     <div class="harga_satuan_checkout" style="text-align: left;">
                        <p>Harga Satuan</p>
                        <h5>Rp<?php echo number_format($harga_satuan_l, 0, ".", "."); ?></h5>
                     </div>
                     <div class="harga_satuan_checkout">
                        <p>Jumlah</p>
                        <h5>x<?php echo $v_jumlah; ?></h5>
                     </div>
                     <div class="harga_satuan_checkout" id="subtotal_produk">
                        <p>Subtotal Produk</p>
                        <h5>Rp<?php echo number_format($harga_diskon_fs_l, 0, ".", "."); ?></h5>
                     </div>
                  </div>
               </div>
               <?php
                  }
                  ?>
               <?php
                  if (!$invoice_data['kota'] == '') {
                  ?>
               <div class="opsi_pengiriman">
                  <div class="isi_opsi_pengiriman_1">
                     <h5>Opsi Pengiriman</h5>
                  </div>
                  <div style="cursor: pointer;" class="box_isi_opsi_pengiriman" id="ubah_onkirrrr" onclick="UbahOngkir('<?php echo $keca_tujuan; ?>', '<?php echo $invoice_data['user_id']; ?>', '<?php echo $id_invoice; ?>', '<?php
                           // Menampilkan user_id
                           foreach ($user_ids as $user_id) {
                               echo "$user_id";
                           }
                           ?>')">
                     <?php
                        if ($invoice_data['tipe_progress'] == 'Belum Bayar') {
                            if ($invoice_data['bukti_transfer'] == '') {
                        ?>
                     
                     <?php
                        }
                        }
                        ?>
                     <div class="isi_opsi_pengiriman isi_opsi_pengiriman_nk">
                        <h5><?php echo strtoupper($kurir_ivd); ?> <?php echo $layanan_kurir_ivd; ?></h5>
                        <?php
                           if ($invoice_data['etd'] != '') {
                               if ($invoice_data['layanan_kurir'] == '') {
                           ?>
                            <?php if ($kurir_ivd === 'Ambil Sendiri') { ?>
                                <p>Ambil di <?php echo $etd_pengiriman_ivd; ?></p>
                            <?php  } else { ?>
                                <p>Pengiriman dari <?php echo $etd_pengiriman_ivd; ?></p>
                            <?php  } ?>
                            <?php
                           } else {
                           ?>
                        <p>Perkiraan sampai <?php echo $etd_pengiriman_ivd; ?> hari</p>
                        <?php
                           }
                           }
                           ?>
                     </div>
                     <div class="isi_opsi_pengiriman isi_opsi_pengiriman_hg">
                        <h5>Rp<?php echo number_format($harga_ongkir, 0, ".", "."); ?></h5>
                     </div>
                     <div class="isi_opsi_pengiriman isi_opsi_pengiriman_uo" style="margin-left: -5px;">
                        <h5><svg class="unf-icon" fill="var(--color-icon-enabled, #2E3137)" style="display: inline-block; vertical-align: middle;margin-right: -5px;" width="24" height="35" viewBox="0 0 25 25"><path d="M9.5 17.75a.75.75 0 0 1-.5-1.28L13.44 12 9 7.53a.75.75 0 0 1 1-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.74.74 0 0 1-.5.22Z" style=""></path></svg></h5>
                     </div>
                  </div>
               </div>
               <?php
                  } else {
                  ?>
               <script>
                  setting_lokasi.style.display = 'flex';
                  batal_lokasi.style.display = 'none';
               </script>
               <?php
                  }
                  ?>
               <?php
                  if ($invoice_data['tipe_progress'] == 'Belum Bayar') {
                      if ($data_sf_cod['opsi_fitur'] == 'Aktif') {
                  ?>
               <div class="opsi_pengiriman">
                  <div class="isi_opsi_pengiriman_1">
                     <h5>Opsi Pembayaran:</h5>
                  </div>
                  <div class="box_isi_opsi_pengiriman">
                     <div class="isi_opsi_pengiriman isi_opsi_pengiriman_uo">
                        <h4 id="ubah_onkirrrr" onclick="ubah_tipe_pembayaran()">UBAH</h4>
                     </div>
                     <div class="isi_opsi_pengiriman isi_opsi_pengiriman_hg">
                        <?php
                           if ($invoice_data['tipe_pembayaran'] == 'online') {
                           ?>
                        <h5>Online</h5>
                        <?php
                           } else {
                           ?>
                        <h5>COD</h5>
                        <?php
                           }
                           ?>
                     </div>
                  </div>
               </div>
               <?php
                  }
                  }
                  ?>

                  <div class="opsi_pengiriman">
    <div class="isi_opsi_pengiriman_1">
        <h5>Voucher</h5>
    </div>
    <div class="box_isi_opsi_pengiriman">
        <div class="isi_opsi_pengiriman isi_opsi_pengiriman_uo">
            <?php
            // Periksa apakah nilai voucher adalah 0
            if ($invoice_data['diskon_voucher'] == 0) {
                echo '<h4 id="pilih_voucher" onclick="ubah_voucher()"><i class="fas fa-tag"></i> PILIH VOUCHER</h4>';
            } else {
                echo '<h4 id="hapus_voucher" onclick="hapus_voucher(\''. $id_invoice .'\')"><i class="fas fa-trash-alt"></i> HAPUS VOUCHER</h4>';
            }
            ?>
        </div>
        <div class="isi_opsi_pengiriman isi_opsi_pengiriman_hg" <?php echo ($invoice_data['diskon_persen'] === null || $invoice_data['diskon_persen'] === '0') ? 'style="display:none;"' : ''; ?>>
    <h5><?php echo $invoice_data['jenis_voucher'] . ' ' . number_format($invoice_data['diskon_persen'], 0, ',', '.') . '%'; ?></h5>
</div>

    </div>
</div>

               <!-- Tampilkan jumlah asli ditambah ongkir di atas kode diskon -->
               <div class="total_pesanan">
                  <p>Subtotal</p>
                  <h5>Rp<?php echo number_format($sub_total, 0, ".", "."); ?></h5>
               </div>
               <?php
                  if ($invoice_data['diskon_voucher'] != '0') {
                  ?>
               <div class="total_pesanan">
                  <p>Diskon Voucher</p>
                  <h5>-Rp<?php echo number_format($invoice_data['diskon_voucher'], 0, ".", "."); ?></h5>
               </div>
               <?php
                  }
                  ?>
               <div class="total_pesanan">
                  <h5 style="color: var(--semi-black);">Total Pembayaran</h5>
                  <?php
                     if (!$invoice_data['kota'] == '') {
                     ?>
                  <h5 style="color: var(--orange);">Rp<?php echo number_format($total_biaya, 0, ".", "."); ?></h5>
                  <?php
                     } else {
                     ?>
                  <h5 style="color: var(--orange);">Rp<?php echo number_format($harga_diskon_fs, 0, ".", "."); ?></h5>
                  <?php
                     }
                     ?>
               </div>
               <?php
                  if ($invoice_data['tipe_progress'] == 'Belum Bayar') {
                      if ($invoice_data['bukti_transfer'] !== '') {
                  ?>
               <div class="status_pembayaran">
                  <p>Status Pembayaran:</p>
                  <h5>Menunggu Konfirmasi</h5>
               </div>
               <?php
                  }
                  } else {
                  ?>
               <div class="status_pembayaran">
                  <p>Tipe Pembayaran:</p>
                  <?php
                     if ($invoice_data['tipe_pembayaran'] == 'online') {
                     ?>
                  <h5>Online</h5>
                  <?php
                     } else {
                     ?>
                  <h5>COD</h5>
                  <?php
                     }
                     ?>
               </div>
               <div class="status_pembayaran">
                  <p>Status Pembayaran:</p>
                  <?php
                     if ($invoice_data['tipe_pembayaran'] == 'online') {
                     ?>
                  <h5>Lunas</h5>
                  <?php
                     } else if ($invoice_data['tipe_progress'] = 'Selesai') {
                     ?>
                  <h5>Belum Lunas</h5>
                  <?php
                     } else {
                     ?>
                  <h5>Lunas</h5>
                  <?php
                     }
                     ?>
               </div>
               <div class="status_pembayaran">
                  <p>Status Pesanan:</p>
                  <h5><?php echo $tipe_progres_iv; ?></h5>
               </div>
               <?php
                  if ($invoice_data['resi'] !== '') {
                  ?>
               <div class="status_pembayaran">
                  <p>Resi Pengiriman:</p>
                  <h5><?php echo $invoice_data['resi']; ?></h5>
               </div>
               <?php
                  }
                  ?>
               <?php
                  }
                  ?>
            </div>
            <div class="alamat" style="margin-top: 15px;">
               <div class="box_alamat">
                  <h1><i class="ri-file-list-line"></i> Catatan</h1>
                  <h5 id="ubah_alamat" onclick="ubahcatatan()"><i class="fa fa-edit"></i> TAMBAH</h5>
               </div>
               <p><?php echo $invoice_data['catatan']; ?></p>
            </div>
            <div class="button_bayar">
               <!-- Bagian pembayaran -->
               <?php if ($invoice_data['tipe_progress'] == 'Belum Bayar') : ?>
               <?php if ($invoice_data['bukti_transfer'] == '') : ?>
               <?php if ($invoice_data['tipe_pembayaran'] == 'online') : ?>
               <div class="box_bayar">
                  <?php if ($nama_tipe_pembayaran == 'Midtrans') : ?>
                  <div class="button" id="pay-button">
                     <p>Bayar Sekarang</p>
                  </div>
                  <?php elseif ($nama_tipe_pembayaran == 'Manual') : ?>
                  <div class="button" onclick="pembayaran_manual_show()">
                     <p>Bayar Sekarang</p>
                  </div>
                  <?php endif; ?>
               </div>
               <?php else : ?>
               <div class="box_bayar">
                  <div class="button" onclick="buat_pesanan_mp_cod('<?php echo $invoice_data['idinvoice']; ?>')">
                     <p id="p_cod">Buat Pesanan</p>
                     <img src="../../assets/icons/loading-w.svg" id="loading_p_cod">
                  </div>
               </div>
               <?php endif; ?>
               <?php else : ?>
               <div class="box_bayar">
                  <a href="<?php echo $url; ?>">
                     <div class="button">
                        <p id="p_cod">Kembali Ke Home</p>
                     </div>
                  </a>
               </div>
               <?php endif; ?>
               <?php endif; ?>
            </div>
            <!-- Tombol "Hubungi Penjual" -->
            <?php foreach ($user_ids as $user_id) : ?>
            <a href="<?php echo $url; ?>/chat/?mulai=<?php echo $user_id; ?>">
               <div class="tombol_hubungi">
                  <p><i class="fas fa-comments"></i> Hubungi Penjual</p>
               </div>
            </a>
            <?php break; // Menghentikan perulangan setelah tombol pertama ditampilkan ?>
            <?php endforeach; ?>
         </div>
      </div>
      <input type="hidden" id="id_invoice" value="<?php echo $id_invoice; ?>">
      <input type="hidden" id="berat_barang" value="<?php echo $berat_barang; ?>">
      <input type="hidden" id="jumlah_barang" value="">
      <div id="res"></div>
<script type="text/javascript">
    // Add event listener to the select element
    var selectElement = document.getElementById('metode_pembayaran');
    selectElement.addEventListener('change', function() {
        // Call the showPaymentDetails() function when the select value changes
        showPaymentDetails();
    });

  function showPaymentDetails() {
    var select = document.getElementById("metode_pembayaran");
    var selectedOption = select.options[select.selectedIndex].value;
    var dataNorek = <?php echo json_encode($data_norek); ?>;

    console.log("Selected Option: ", selectedOption); // Debug: Display selected option value

    // Check if dataNorek is valid JSON
    if (typeof dataNorek !== 'object') {
        console.error("Error: dataNorek is not valid JSON");
        return;
    }

    // Cari detail nomor rekening dan penerima sesuai dengan metode pembayaran yang dipilih
    for (var i = 0; i < dataNorek.length; i++) {
        if (dataNorek[i]['idnorek'] === selectedOption) {
            console.log("Found matching bank:", dataNorek[i]); // Debug: Display matching bank details
            document.getElementById('nama_bank').innerHTML = dataNorek[i]['nama_bank'];
            document.getElementById('an').innerHTML = dataNorek[i]['an'];
            document.getElementById('nomor_rekening').innerHTML = dataNorek[i]['norek']; // Update nomor_rekening
            document.getElementById('detail_pembayaran').style.display = 'block';

            // Update selected_bank value
            var selectedBank = dataNorek[i]['nama_bank'];

            // Update the value of ubt button onclick
            var ubtButton = document.getElementById('ubt');
            ubtButton.setAttribute('onclick', "upload_bukti_transfer_manual('<?php echo $id_invoice; ?>', '" + selectedBank + "')");

            break;
        }
    }
}


    var payButton = document.getElementById('pay-button');
    // For example trigger on button clicked, or any time you need
    payButton.addEventListener('click', function() {
        snap.pay('<?php echo $snapToken; ?>'); // Replace it with your transaction token
    });
</script>


      <!-- CONTENT -->
      <!-- FOOTER -->
      <?php include '../partials/footer.php'; ?>
      <!-- FOOTER -->
      <!-- JS -->
      <script src="../../assets/js/checkout/detail.js"></script>
      <!-- JS -->
   </body>
</html>
