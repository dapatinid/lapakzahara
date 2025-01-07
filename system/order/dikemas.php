<?php
include '../../config.php';

// PAGINATION
$page_paging = $_POST['page_paging'];
$halaman = 10;
$page = isset($page_paging) ? (int)$page_paging : 1;
$mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
$result = $server->query("SELECT * FROM `invoice`, `iklan`, `kategori` WHERE invoice.id_user='$iduser' AND invoice.tipe_progress='Dikemas' AND invoice.id_iklan=iklan.id AND iklan.id_kategori=kategori.id ORDER BY `invoice`.`idinvoice` DESC ");
$total = mysqli_num_rows($result);

$select_invoice = $server->query("SELECT * FROM `invoice`, `iklan`, `kategori` WHERE invoice.id_user='$iduser' AND invoice.tipe_progress='Dikemas' AND invoice.id_iklan=iklan.id AND iklan.id_kategori=kategori.id ORDER BY `invoice`.`idinvoice` DESC LIMIT $mulai, $halaman ");
$cek_invoice = mysqli_num_rows($select_invoice);

if ($cek_invoice < $halaman) {
?>
    <script>
        load_paging_dikemas_id.style.display = 'none';
    </script>
<?php
} else {
?>
    <script>
        load_paging_dikemas_id.style.display = 'block';
    </script>
<?php
}
if ($total == "0") {
?>
    <div class="box_res_order_0">
        <img src="../assets/icons/list.svg" alt="">
        <p>Belum ada pesanan</p>
    </div>
<?php
} else {
?>
    <div class="box_isi_res_order">
        <?php
        while ($invoice_data = mysqli_fetch_assoc($select_invoice)) {
            $exp_id_iklan = explode(',', $invoice_data['id_iklan']);
            $j_id_iklan = count($exp_id_iklan);

            $exp_harga_i = explode(',', $invoice_data['harga_i']);
            $exp_diskon_i = explode(',', $invoice_data['diskon_i']);
            $exp_jumlah = explode(',', $invoice_data['jumlah']);
            $exp_warna_i = explode(',', $invoice_data['warna_i']);
            $exp_ukuran_i = explode(',', $invoice_data['ukuran_i']);

            $harga_final_per_produk = 0;

            for ($i_l_v = 0; $i_l_v < $j_id_iklan; $i_l_v++) {
                // HARGA
                $hitung_diskon_fs = ($exp_diskon_i[$i_l_v] / 100) * $exp_harga_i[$i_l_v];
                $harga_diskon_fs = ($exp_harga_i[$i_l_v] - $hitung_diskon_fs) * $exp_jumlah[$i_l_v];
                $harga_final_per_produk += $harga_diskon_fs;
            };
            $harga_semua_fs = ($harga_final_per_produk + $invoice_data['harga_ongkir']) - $invoice_data['diskon_voucher'];
            $exp_gambar_od = explode(',', $invoice_data['gambar']);
            
            // Cek apakah produk ada dalam tabel pembatalan_transaksi dengan status menunggu persetujuan
    $check_pembatalan_query = $server->query("SELECT * FROM `pembatalan_transaksi` WHERE `id_invoice`='$invoice_data[idinvoice]' AND `status_persetujuan`='Menunggu Persetujuan'");
    $produk_dalam_pembatalan = mysqli_num_rows($check_pembatalan_query) > 0;

        ?>
        <!-- BATALKAN PRODUK -->
<div class="back_catatan" id="back_catatan<?php echo $invoice_data['idinvoice']; ?>">
    <div class="box_catatan">
        <h1>Alasan Pembatalan</h1>
        <ul class="options-list" id="alasanLaporkan_<?php echo $invoice_data['idinvoice']; ?>" onclick="handleOptionClick(event)">            <li value="Perubahan Keputusan">Perubahan Keputusan</li>
            <li value="Kondisi Keuangan yang Tidak Terduga">Kondisi Keuangan yang Tidak Terduga</li>
            <li value="Temuan Produk yang Lebih Cocok">Temuan Produk yang Lebih Cocok</li>
            <li value="Masalah Pengiriman atau Waktu Tidak Memadai">Masalah Pengiriman atau Waktu Tidak Memadai</li>
            <li value="Ketidakpuasan dengan Ulasan atau Reputasi Toko">Ketidakpuasan dengan Ulasan atau Reputasi Toko</li>
            <li value="lainnya">Lainnya</li>
        </ul>
        <textarea id="deskripsiMasalah_<?php echo $invoice_data['idinvoice']; ?>" rows="3" class="input" placeholder="Tambahkan Alasan Lainnya..." style="margin-top: 20px; display: none;"></textarea>        <div class="button butacat" onclick="simpan_catatan('<?php echo $invoice_data['idinvoice']; ?>')">
            <p id="p_butacat">Kirim</p>
            <img src="<?php echo $url; ?>/assets/icons/loading-w.svg" id="load_butacat">
        </div>
        <div class="button batal_lokasi" id="batal_catatan" onclick="BatalLcatatan(event)" data-idinvoice="<?php echo $invoice_data['idinvoice']; ?>">
            <p>Batalkan</p>
        </div>
    </div>
</div>
<!-- BATALKAN PRODUK -->
            <div class="isi_cart" id="dikemas_isi_box<?php echo $invoice_data['idinvoice']; ?>">
                <a href="<?php echo $url; ?>/checkout/detail/<?php echo $invoice_data['idinvoice']; ?>">
                    <div class="box_gambar_judul">
                        <img src="<?php echo $url; ?>/assets/image/product/compressed/<?php echo $exp_gambar_od[0]; ?>" alt="">
                        <div class="box_judul_ic">
                            <h1><?php echo $invoice_data['judul']; ?></h1>
                            <p>Kategori <span><?php echo $invoice_data['nama']; ?></span></p>
                            <p><span><?php echo $j_id_iklan; ?></span> Produk</p>
                        </div>
                    </div>
                </a>
                <div class="box_detail_isi_cart">
                    <div class="box_total_harga">
                        <p>Proses Dikemas</p>
                        <h1><?php echo $invoice_data['waktu_transaksi']; ?></h1>
                    </div>
                    
                   
                    
                    <div class="<?php echo $produk_dalam_pembatalan ? 'proses' : 'bayar'; ?>" onclick="<?php echo $produk_dalam_pembatalan ? 'return false;' : 'ubahcatatan(event)'; ?>" data-idinvoice="<?php echo $invoice_data['idinvoice']; ?>" <?php echo $produk_dalam_pembatalan ? 'disabled' : ''; ?>>
    <p><?php echo $produk_dalam_pembatalan ? 'Diproses' : 'Batalkan'; ?></p>
</div>


    
                    
                </div>
            </div>
        <?php
        }
        ?>
    </div>
<?php
}
?>

<style>
/* BATALKAN PESANAN */

.back_catatan {
    width: 100%;
    height: 100%;
    position: fixed;
    background-color: var(--bg-transparent-black);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    top:0;
    left:0;
}

.box_catatan {
    width: 450px;
    padding: 30px;
    background-color: var(--white);
    box-sizing: border-box;
}

.box_catatan h1 {
    font-size: 20px;
    color: var(--black);
    font-weight: 600;
}

.butacat {
    margin-top: 15px;
}

#load_butacat {
    display: none;
}

.batal_lokasi {
  margin-top: 10px;
  background-color: var(--border-grey);
  color: var(--grey);
}
.batal_lokasi p {
  color: var(--semi-black);
}
@media only screen and (max-width: 900px) {
  .box_catatan {
    width: calc(100% - 30px);
    padding: 20px;
    
  }
  
  .options-list {
    font-size: 13px;
}
}

/* Style untuk unordered list (ul) */
.options-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
    margin-top: 15px;
}

/* Style untuk list item (li) */
.options-list li {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 5px;
    cursor: pointer;
}

/* Style untuk list item yang dipilih */
.options-list li.selected {
    background-color: #e0e0e0;
}

/* Style untuk textarea */
textarea {
    display: none;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 8px;
    font-size: 14px;
    margin-top: 10px;
}

.proses
{
  background-color: var(--semi-grey);
  color: var(--semi-black);
  border-radius: 3px;
  height: 45px;
  font-weight: 500;
  font-size: 16px;
  margin-left: 20px;
  width: 120px;
  display: flex;
  justify-content: center;
  align-items: center;
  
  pointer-events: none; /* Mencegah interaksi dengan elemen */
  cursor: not-allowed;
}

@media only screen and (max-width: 900px) {
  .proses
{
    width: 105px;
    height: 38px;
    font-size: 14px;
    font-weight: 600;
  }
}

/* BATALKAN PESANAN */



    .box_res_order_0 {
        width: 100%;
        background-color: var(--white);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 170px 0;
    }

    .box_res_order_0 img {
        height: 80px;
    }

    .box_res_order_0 p {
        font-size: 15px;
        font-weight: 500;
        text-align: center;
        color: var(--semi-black);
        margin-top: 15px;
    }

    .box_isi_res_order {
        width: 100%;
        margin-top: 5px;
        display: grid;
        grid-template-columns: 1fr;
        grid-gap: 5px;
    }

    .isi_cart {
        width: 100%;
        padding: 15px 20px;
        background-color: var(--white);
        box-sizing: border-box;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .box_gambar_judul {
        width: 350px;
        overflow: hidden;
        float: left;
    }

    .isi_cart img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 3px;
        float: left;
    }

    .box_judul_ic {
        width: calc(100% - 95px);
        float: right;
    }

    .box_judul_ic h1 {
        font-size: 15px;
        font-weight: 500;
        color: var(--black);
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .box_judul_ic p {
        font-size: 13px;
        font-weight: 500;
        color: var(--grey);
        margin-top: 3px;
    }

    .box_judul_ic p span {
        color: var(--orange);
    }

    .box_detail_isi_cart {
        width: calc(100% - 350px);
        float: right;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-end;
    }

    .box_total_harga {
        margin-left: 20px;
        text-align: right;
    }

    .box_total_harga p {
        font-size: 13px;
        font-weight: 500;
        color: var(--grey);
        margin-top: 3px;
    }

    .box_total_harga h1 {
        font-size: 14px;
        font-weight: 500;
        color: var(--grey);
        margin-top: 3px;
    }

    .box_total_harga h1 span {
        font-size: 14px;
    }

    .bayar {
        background-color: var(--orange);
        color: var(--white);
        border-radius: 3px;
        height: 45px;
        font-weight: 500;
        font-size: 16px;
        margin-left: 20px;
        width: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .bayar img {
        width: 20px;
        height: 20px;
        display: none;
    }

    .loading_checkout {
        display: none;
    }

    @media only screen and (max-width: 900px) {
        .isi_cart {
            display: block;
            padding: 15px;
        }

        .box_gambar_judul {
            width: 100%;
            /* background-color: red; */
        }

        .isi_cart img {
            width: 65px;
            height: 65px;
        }

        .box_judul_ic {
            width: calc(100% - 80px);
            float: right;
            /* background-color: blue; */
        }

        .box_judul_ic h1 {
            font-size: 13px;
        }

        .box_judul_ic p {
            font-size: 11.5px;
        }

        .box_detail_isi_cart {
            width: 100%;
            /* background-color: blue; */
            margin-top: 15px;
            padding-top: 13px;
            border-top: 1px solid var(--border-grey);
            justify-content: flex-start;
        }

        .box_total_harga {
            flex: 1;
            margin-left: 0;
            text-align: left;
            /* background-color: red; */
        }

        .box_total_harga p {
            font-size: 11px;
            font-weight: 500;
            color: var(--grey);
            margin-top: 0px;
        }

        .box_total_harga h1 {
            font-size: 12px;
            font-weight: 500;
            color: var(--grey);
            margin-top: 3px;
        }

        .box_total_harga h1 span {
            font-size: 12px;
        }

        .box_remove_cart {
            margin-left: 15px;
        }

        .bayar {
            width: 105px;
            height: 38px;
            font-size: 14px;
            font-weight: 600;
        }

        .bayar img {
            width: 15px;
            height: 15px;
        }
    }
</style>