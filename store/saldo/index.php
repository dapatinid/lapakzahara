<?php
   include '../../config.php';
   
   $page_admin = 'saldo';
   
   $sf_tipe_toko = $server->query("SELECT * FROM `setting_fitur` WHERE `nama_fitur`='tipe toko' ");
   $data_sf_tipe_toko = mysqli_fetch_array($sf_tipe_toko);
   
   if ($data_sf_tipe_toko['opsi_fitur'] == 'Marketplace') {
       if (isset($_COOKIE['login'])) {
           if ($profile['provinsi_user'] == '') {
               header("location: " . $url . "/store/start");
           }
       }
   }
   
   function getSaldo($user_id, $conn) {
       $sql_saldo = "SELECT jumlah_saldo FROM saldo WHERE user_id = $user_id";
       $saldo_result = $conn->query($sql_saldo);
   
       $saldo = 0;
   
       if ($saldo_result && $saldo_result->num_rows > 0) {
           $saldo_row = $saldo_result->fetch_assoc();
           $saldo = $saldo_row['jumlah_saldo'];
       }
   
       return $saldo;
   }
   
   function getRiwayatPenarikan($user_id, $conn) {
       $result = [];
   
       // Ambil riwayat penarikan
       $sql_penarikan = "SELECT * FROM riwayat_penarikan WHERE user_id = $user_id";
       $result['riwayat_penarikan'] = $conn->query($sql_penarikan)->fetch_all(MYSQLI_ASSOC);
   
       // Ambil riwayat penjualan
       $sql_penjualan = "SELECT * FROM riwayat_penjualan WHERE user_id = $user_id";
       $result['riwayat_penjualan'] = $conn->query($sql_penjualan)->fetch_all(MYSQLI_ASSOC);
   
       // Ambil riwayat pengembalian
       $sql_pengembalian = "SELECT * FROM riwayat_pengembalian WHERE user_id = $user_id";
       $result['riwayat_pengembalian'] = $conn->query($sql_pengembalian)->fetch_all(MYSQLI_ASSOC);
       
       // Ambil riwayat penarikan
       $sql_deposit = "SELECT * FROM riwayat_deposit WHERE user_id = $user_id";
       $result['riwayat_deposit'] = $conn->query($sql_deposit)->fetch_all(MYSQLI_ASSOC);
   
       return $result;
   }
   
   // Gantilah dengan ID user yang sesuai dengan logika aplikasi Anda
   $user_id = $iduser; // Contoh: ID user yang digunakan di sini adalah 1
   
   // Mengambil informasi riwayat
   $riwayat_info = getRiwayatPenarikan($user_id, $conn);
   $riwayat_deposit = $riwayat_info['riwayat_deposit'];
   $riwayat_penarikan = $riwayat_info['riwayat_penarikan'];
   $riwayat_penjualan = $riwayat_info['riwayat_penjualan'];
   $riwayat_pengembalian = $riwayat_info['riwayat_pengembalian'];
   
   // Mengambil saldo
   $saldo_pengguna = getSaldo($user_id, $conn);
   
   // Query untuk mendapatkan nilai minimal dari tabel setting_fitur dengan id 7
    $query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 7";
   $result = mysqli_query($conn, $query);
   
   if ($result) {
    $row = mysqli_fetch_assoc($result);
    $nilai_minimal = $row['opsi_fitur'];
   } else {
    // Jika query gagal
    $nilai_minimal = 0; // Nilai default jika query gagal
   }
   
   
   // Query untuk mendapatkan nilai minimal dari tabel setting_fitur dengan id 6
    $query = "SELECT opsi_fitur FROM setting_fitur WHERE id = 6";
   $result = mysqli_query($conn, $query);
   
   if ($result) {
    $row = mysqli_fetch_assoc($result);
    $nilai_persen = $row['opsi_fitur']; // Nilai asli dari database
    $nilai_persen_decimal = $nilai_persen / 100; // Mengubah nilai persen ke bentuk desimal
   } else {
    // Jika query gagal
    $nilai_persen = 0; // Nilai default jika query gagal
    $nilai_persen_decimal = 0; // Nilai default jika query gagal
   }
   
   ?>
<!DOCTYPE html>
<html lang="id">
   <head>
      <title>Dompetku <?php echo $profile['nama_lengkap']; ?> | <?php echo $title_name; ?></title>
      <?php include '../../partials/seo.php'; ?>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Penarikan Saldo</title>
      <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
      <link rel="stylesheet" href="../../assets/css/profile/edit.css">
      <link rel="stylesheet" href="../../assets/css/store/saldo.css">
      <link rel="stylesheet" href="../../assets/css/admin/category/index.css">
      <style>
         .isi_box_input_pro1 {
         display: flex;
         justify-content: space-between;
         }
         .isi_box_input_pro1 .input {
         width: 100%; /* Mengisi lebar penuh */
         }
         .isi_box_input_pro1 p {
         margin: 5px 0;
         }
         .isi_box_input_pro1 p {
         font-size: 13px;
         font-weight: 500;
         color: var(--grey);
         margin-bottom: 5px;
         }
         .btl_fs {
         margin-top: 10px;
         background-color: var(--border-grey);
         }
         .btl_fs p {
         color: var(--semi-black);
         }
      </style>
   </head>
   <body>
      <!-- FORM TARIK SALDO -->
      <div class="tambah_kategori_form" id="tambah_kategori_form">
         <div class="isi_tambah_kategori_form">
            <h1>Penarikan</h1>
            <div class="box_input_pro">
               <div class="isi_box_input_pro">
                  <p id="p_jumlah">Nominal Penarikan</p>
                  <input type="text" class="input currency" data-separator="." id="jumlah" placeholder="Jumlah" inputmode="numeric" oninput="updateJumlahDiterima()">
               </div>
               <div class="isi_box_input_pro1">
                  <div style="margin-right: 5px;">
                     <p id="p_nama_bank">Pilih Bank</p>
                     <select id="nama_bank" class="input" required>
                        <option value="" disabled selected>Pilih</option>
                        <?php
                           // Query untuk mendapatkan daftar nama bank
                           $query = "SELECT * FROM daftar_bank";
                           $result = mysqli_query($server, $query);
                           
                           // Tampilkan opsi bank
                           while ($row = mysqli_fetch_assoc($result)) {
                               echo "<option value='" . $row['nama'] . "'>" . $row['nama'] . "</option>";
                           }
                           ?>
                     </select>
                     <p id="p_nama_bank1"></p>
                  </div>
                  <div>
                     <p id="p_rekening_tujuan">Nomor Rekening</p>
                     <input type="text" class="input" id="rekening_tujuan" placeholder="Nomor Rekening" inputmode="numeric" required>
                     <p id="p_rekening_tujuan1"></p>
                  </div>
               </div>
               <div class="isi_box_input_pro">
                  <p id="p_atas_nama">Nama di Rekening</p>
                  <input type="text" class="input" id="atas_nama" placeholder="Nama Pemilik Rekening">
                  <p id="p_atas_nama1"></p>
               </div>
               <p id="jumlah_diterima" style="font-size: 14px;color: var(--black);font-weight: 600;">
                  Jumlah Diterima Rp<span id="jumlah_diterima_value">0</span>
               </p>
               <p id="p_biaya_admin" style="margin-top: -10px;font-size: 13px;color: var(--grey);">Biaya Administrasi <?php echo $nilai_persen;?>%</p>
               <div>
                  <p id="pesan_penarikan"><?php echo $notification; ?></p>
               </div>
            </div>
            <div class="button" id="bu_e_pro" onclick="simpan_edit_profile()">
               <p>Kirim</p>
            </div>
            <div class="button" id="loading_e_pro" style="display: none;">
               <img src="../../assets/icons/loading-w.svg" alt="">
            </div>
            <div class="button btl_fs" onclick="hide_tp_fs()">
               <p>Batal</p>
            </div>
         </div>
      </div>
      <script>
         // Fungsi untuk memperbarui jumlah yang akan diterima setelah dipotong 5%
         function updateJumlahDiterima() {
             var inputElement = document.getElementById('jumlah');
             var inputValue = inputElement.value;
         
             // Hapus semua titik
             var cleanedValue = inputValue.replace(/\./g, '');
         
             // Ganti koma dengan titik untuk desimal
             cleanedValue = cleanedValue.replace(',', '.');
         
             // Konversi ke angka
             var parsedValue = parseFloat(cleanedValue);
             
         
             // Periksa apakah angka valid
             if (!isNaN(parsedValue)) {
                 // Format angka dengan pemisah ribuan
                 var formattedValue = parsedValue.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
         
                 // Tampilkan kembali di input
                 inputElement.value = formattedValue;
         
                 // Hitung dan tampilkan jumlah diterima
                var jumlahDiterima = parsedValue - (<?php echo $nilai_persen_decimal; ?> * parsedValue);
                 var formattedJumlahDiterima = Math.floor(jumlahDiterima).toLocaleString('id-ID');
                 document.getElementById('jumlah_diterima_value').innerText = formattedJumlahDiterima;
         
                 // Periksa apakah jumlah kurang dari php
                 if (parsedValue < <?php echo $nilai_minimal; ?>) {
                     document.getElementById('pesanfsdf').style.display = 'block'; // Tampilkan pesan kesalahan
                 } else {
                     document.getElementById('pesanfsdf').style.display = 'none';  // Sembunyikan pesan kesalahan jika jumlah valid
                 }
             } else {
                 // Angka tidak valid, tampilkan pesan kesalahan
                 document.getElementById('pesanfsdf').style.display = 'block';
             }
             
         }
         
      </script>
      <!-- FORM TARIK SALDO -->
      <div class="admin">
         <?php include '../../store/partials/menu.php'; ?>
         <div class="content_admin">
            <h1 class="title_content_admin">Dompet</h1>
            <div class="isi_content_admin"></div>
            <div class="saldo">
               <div class="isi_saldo">
                  <h1><i class="ri-arrow-left-line" onclick="gobackheader()"></i> Saldo Anda</h1>
                  <h5><span>Rp</span><?php echo number_format($saldo_pengguna, 0, ".", "."); ?></h5>
               </div>
            </div>
            <div class="add_kategori_adm" onclick="show_add_kategori()">
               <p>Tarik Saldo</p>
               <i class="ri-currency-line"></i>
            </div>
            <div class="saldo">
               <div class="list_riwayat_transaksi">
<?php
// Menggabungkan terlebih dahulu riwayat penjualan, riwayat penarikan, dan riwayat pengembalian
$all_transactions = array_merge($riwayat_penjualan, $riwayat_penarikan, $riwayat_pengembalian, $riwayat_deposit);

// Mengurutkan transaksi berdasarkan tanggal secara terbalik (terbaru di atas)
usort($all_transactions, function ($a, $b)
{
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

if (empty($all_transactions))
{
    echo '<p style="padding: 20px 30px;">Belum ada transaksi.</p>';
}
else
{
    foreach ($all_transactions as $riwayat)
    {
        $status_class = "";
        $icon_class = "";

        if (isset($riwayat['total_penjualan']))
        {
            // Jika ini adalah riwayat penjualan
            $jenis_transaksi = "Penjualan Produk";
            $nominal = $riwayat['total_penjualan'];
            $status_transaksi = "Berhasil";
            $status_class = "berhasil";
            $icon_class = "fas fa-cart-arrow-down";
        }
        elseif (isset($riwayat['jumlah']))
        {
            // Jika ini adalah riwayat penarikan
            $jenis_transaksi = "Penarikan Saldo";
            $nominal = - $riwayat['jumlah'];
            if ($riwayat['status'] == 1)
            {
                $status_transaksi = "Berhasil";
                $status_class = "berhasil";
            }
            elseif ($riwayat['status'] == 2)
            {
                $status_transaksi = "Gagal";
                $status_class = "gagal";
            }
            else
            {
                $status_transaksi = "Pending";
                $status_class = "pending";
            }
            $icon_class = "fas fa-exchange-alt";
        }
        elseif (isset($riwayat['jumlah_pengembalian']))
        {
            // Jika ini adalah riwayat pengembalian
            $jenis_transaksi = "Pengembalian Dana";
            $nominal = $riwayat['jumlah_pengembalian'];
            $status_transaksi = "Berhasil"; // Atau status transaksi pengembalian lainnya
            $status_class = "berhasil"; // Atau kelas status transaksi pengembalian lainnya
            $icon_class = "fas fa-undo"; // Atau kelas ikon transaksi pengembalian lainnya
            
        }
        elseif (isset($riwayat['jumlah_deposit']))
        {
            // Jika ini adalah riwayat deposit
            $jenis_transaksi = "Deposit";
            $nominal = $riwayat['jumlah_deposit'];
            $status_transaksi = "Berhasil"; // Atau status transaksi deposit lainnya
            $status_class = "berhasil"; // Atau kelas status transaksi deposit lainnya
            $icon_class = "fas fa-plus"; // Atau kelas ikon transaksi deposit lainnya
            
        }

        // Tentukan warna teks berdasarkan status transaksi
        $text_color = ($status_class === "berhasil") ? "var(--orange)" : (($status_class === "gagal") ? "red" : "black");

        // Tampilkan informasi riwayat transaksi
        echo '<div class="isi_list_riwayat_transaksi ' . $status_class . '">';
        echo '<div class="icon_rt"><i class="' . $icon_class . '"></i></div>';
        echo '<div class="deskripsi_rt">';
        echo '<p class="tipe_rt">' . $jenis_transaksi . '</p>';
        echo '<p class="deskt_rt">' . $jenis_transaksi . ' senilai Rp' . number_format(abs($nominal) , 0, ".", ".") . '</p>';
        // Tampilkan ikon hanya jika keterangan memiliki nilai
    if (!empty($riwayat['keterangan'])) {
        echo '<p class="time_rt" style="color: ' . $text_color . ';"><font style="color:var(--grey)"><i class="fas fa-info-circle"></i> </font> ' . $riwayat['keterangan'] . '</p>';
    }
        echo '<p class="time_rt">' . $riwayat['tanggal'] . '</p>';
        echo '</div>';
        echo '<div class="nominal_rt">';
        echo '<p class="h_nominal_rt">' . ($nominal > 0 ? '+ ' : '- ') . 'Rp' . number_format(abs($nominal) , 0, ".", ".") . '</p>';
        echo '<p class="tt_rt" style="color: ' . $text_color . ';">' . $status_transaksi . '</p>';
        echo '</div>';
        echo '</div>';
    }
}
?>

               </div>
            </div>
            <button id="showMoreButton" onclick="showMoreTransactions()">Lebih Lengkap</button>
         </div>
      </div>
      <div id="res"></div>
      <style>
         #showMoreButton {
         width: 100%;
         padding: 10px 20px;
         font-size: 14px;
         border: none;
         background-color: var(--orange); /* Warna latar belakang hijau */
         color: white; /* Warna teks putih */
         cursor: pointer;
         border-radius: 8px;
         transition: background-color 0.3s ease;
         }
         #showMoreButton:hover {
         background-color: #45a049; /* Warna latar belakang hijau lebih gelap saat dihover */
         }
         #showMoreButton:active {
         background-color: #3e8e41; /* Warna latar belakang hijau lebih gelap saat tombol ditekan */
         }
         /* Mengatur margin atau jarak antar elemen */
         #showMoreButton + div {
         margin-top: 20px;
         }
      </style>
      <script>
         let currentCount = 5; // Tampilkan 5 item awal
         const itemsPerPage = 5;
         
         function showMoreTransactions() {
             const transactionList = <?php echo json_encode($all_transactions); ?>;
             const remainingTransactions = transactionList.slice(currentCount, currentCount + itemsPerPage);
         
             if (remainingTransactions.length > 0) {
                 remainingTransactions.forEach((transaction) => {
                     const newTransactionItem = document.createElement('div');
                     newTransactionItem.classList.add('isi_list_riwayat_transaksi');
         
                     const statusClass = transaction.status === 1 ? 'berhasil' : (transaction.status === 2 ? 'ditolak' : 'pending');
         
                     newTransactionItem.innerHTML = `
                         <div class="icon_rt">
                             <i class="fas fa-exchange-alt"></i>
                         </div>
                         <div class="deskripsi_rt">
                             <p class="tipe_rt">Penarikan Saldo</p>
                             <p class="deskt_rt">Penarikan saldo sebesar Rp${transaction.jumlah}</p>
                             <p class="time_rt">${transaction.tanggal}</p>
                         </div>
                         <div class="nominal_rt">
                             <p class="h_nominal_rt">- Rp${transaction.jumlah}</p>
                             <p class="tt_rt ${statusClass}">${transaction.status === 1 ? 'Berhasil' : (transaction.status === 2 ? 'Ditolak' : 'Pending')}</p>
                         </div>
                     `;
         
                     document.querySelector('.list_riwayat_transaksi').appendChild(newTransactionItem);
                 });
         
                 currentCount += itemsPerPage;
             } else {
                 document.getElementById('showMoreButton').style.display = 'none';
             }
         }
      </script>
      <script>
         var nilaiMinimal = <?php echo $nilai_minimal; ?>;
      </script>
      <script src="../../assets/js/store/saldo.js"></script>
      <?php include '../../partials/bottom-navigation.php'; ?>
   </body>
</html>
