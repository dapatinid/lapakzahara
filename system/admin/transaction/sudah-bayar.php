<?php
include '../../../config.php';

// PAGINATION
$page_paging = $_POST['page_paging'];
$tipe_user_vt = $_POST['tipe_user_vt'];
$halaman = 10;
$page = isset($page_paging) ? (int)$page_paging : 1;
$mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;

if ($tipe_user_vt == 'store') {
    $query = "SELECT * FROM `iklan`, `kategori`, `akun`, `invoice` WHERE  invoice.tipe_progress='Dikemas' AND invoice.id_iklan=iklan.id AND iklan.id_kategori=kategori.id AND invoice.id_user=akun.id AND iklan.user_id='$iduser' ORDER BY `invoice`.`idinvoice` DESC ";
} else {
    $query = "SELECT * FROM `iklan`, `kategori`, `akun`, `invoice` WHERE  invoice.tipe_progress='Dikemas' AND invoice.id_iklan=iklan.id AND iklan.id_kategori=kategori.id AND invoice.id_user=akun.id ORDER BY `invoice`.`idinvoice` DESC ";
}

$result = $server->query($query);
$select_invoice = $server->query($query . "LIMIT $mulai, $halaman");

$total = mysqli_num_rows($result);
$cek_invoice = mysqli_num_rows($select_invoice);

if ($cek_invoice < $halaman) {
    $load_paging_style = 'none';
} else {
    $load_paging_style = 'block';
}
?>

<script>
    load_paging_dikemas_id.style.display = '<?php echo $load_paging_style; ?>';
</script>

<?php
if ($total == 0) {
?>
    <div class="box_res_order_0">
        <img src="<?php echo $url; ?>/assets/icons/list.svg" alt="">
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
            }
            $harga_semua_fs = ($harga_final_per_produk + $invoice_data['harga_ongkir']) - $invoice_data['diskon_voucher'];
            $exp_gambar_od = explode(',', $invoice_data['gambar']);
            $exp_prov_od = explode(',', $invoice_data['provinsi']);
            $exp_kota_od = explode(',', $invoice_data['kota']);

            $id_invoice = $invoice_data['idinvoice'];
            $status_persetujuan_query = $server->query("SELECT status_persetujuan FROM pembatalan_transaksi WHERE id_invoice = '$id_invoice'");
            $status_persetujuan_data = mysqli_fetch_assoc($status_persetujuan_query);
        ?>

            <div class="isi_cart" id="list_sdk<?php echo $invoice_data['idinvoice']; ?>">
                <div class="box_gambar_judul" onclick="show_detail_iv('<?php echo $invoice_data['idinvoice']; ?>')">
                    <img src="<?php echo $url; ?>/assets/image/product/compressed/<?php echo $exp_gambar_od[0]; ?>" alt="">
                    <div class="box_judul_ic">
                        <h1><?php echo $invoice_data['judul']; ?></h1>
                        <p>Kategori <span><?php echo $invoice_data['nama']; ?></span></p>
                        <p>Total Produk <span><?php echo $j_id_iklan; ?></span></p>
                    </div>
                </div>
                <div class="box_detail_isi_cart">
                    <div class="box_total_harga">
                        <p><?php echo $invoice_data['waktu_transaksi']; ?></p>
                        <h1><span>Rp</span> <?php echo number_format($harga_semua_fs, 0, ".", "."); ?></h1>
                    </div>
                    <?php
                        if ($status_persetujuan_data['status_persetujuan'] == 'Menunggu Persetujuan') {
                        ?>
                            <div class="terima" onclick="batalkan_terima('<?php echo $invoice_data['idinvoice']; ?>')">
    <p id="text_sdk<?php echo $invoice_data['idinvoice']; ?>">Terima</p>
    <img src="<?php echo $url; ?>/assets/icons/loading-w.svg" id="load_sdk<?php echo $invoice_data['idinvoice']; ?>">
</div>

                        <?php
                        }
                        ?>

                    <?php
                    if ($invoice_data['kurir'] == 'Ambil Sendiri') {
                    ?>
                        <div class="konfirmasi" onclick="selesai_kas('<?php echo $invoice_data['idinvoice']; ?>')">
                            <p id="text_kas<?php echo $invoice_data['idinvoice']; ?>">Selesai</p>
                            <img src="<?php echo $url; ?>/assets/icons/loading-w.svg" id="load_kas<?php echo $invoice_data['idinvoice']; ?>">
                        </div>
                    <?php
                       
                    } else {
                        ?>
                        <div class="konfirmasi" onclick="show_resi_pengiriman('<?php echo $invoice_data['idinvoice']; ?>')">
                            <p id="text_sdk<?php echo $invoice_data['idinvoice']; ?>">Kirim</p>
                            <img src="<?php echo $url; ?>/assets/icons/loading-w.svg" id="load_sdk<?php echo $invoice_data['idinvoice']; ?>">
                        </div>
                    <?php
                    }
                    ?>
                    <div class="hapus" onclick="batalkan_kas('<?php echo $invoice_data['idinvoice']; ?>')">
                        <i class="ri-delete-bin-line"></i>
                        <img src="<?php echo $url; ?>/assets/icons/loading-w.svg" id="load_btlkn<?php echo $invoice_data['idinvoice']; ?>">
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
    .box_res_order_0 {
        width: 100%;
        background-color: var(--white);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 150px 0;
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

    .jumlah_isi_res_order {
        width: 100%;
        padding: 15px 20px;
        background-color: var(--white);
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        box-sizing: border-box;
        align-items: center;
    }

    .jumlah_isi_res_order h1 {
        font-size: 15px;
        font-weight: 500;
    }

    .jumlah_isi_res_order p {
        font-size: 15px;
        font-weight: 500;
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
        width: 450px;
        overflow: hidden;
        float: left;
        cursor: pointer;
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
        width: calc(100% - 450px);
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
        font-size: 18px;
        font-weight: 600;
        color: var(--orange);
        margin-top: 3px;
    }

    .box_total_harga h1 span {
        font-size: 14px;
    }
    
    .terima {
        background-color: red;
        color: var(--white);
        border-radius: 3px;
        height: 45px;
        font-weight: 500;
        font-size: 16px;
        margin-left: 20px;
        width: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .terima img {
        width: 20px;
        height: 20px;
        display: none;
    }

    .konfirmasi {
        background-color: var(--orange);
        color: var(--white);
        border-radius: 3px;
        height: 45px;
        font-weight: 500;
        font-size: 16px;
        margin-left: 10px;
        width: 85px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .konfirmasi img {
        width: 20px;
        height: 20px;
        display: none;
    }

    .hapus {
    width: 45px;
    height: 45px;
    background-color: var(--border-grey);
    margin-left: 10px;
    border-radius: 3px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: var(--semi-black);
    }

    .hapus img {
        width: 20px;
        height: 20px;
        display: none;
    }

    .loading_checkout {
        display: none;
    }

    .box_profile_isi_cart {
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 10px;
    }

    .box_profile_isi_cart img {
        width: 22px;
        height: 22px;
        border-radius: 22px;
        object-fit: cover;
    }

    .box_profile_isi_cart p {
        margin-right: 10px;
        color: var(--semi-black);
        font-weight: 500;
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
            font-size: 14px;
            font-weight: 600;
            color: var(--orange);
            margin-top: 3px;
        }

        .box_total_harga h1 span {
            font-size: 12px;
        }

        .box_remove_cart {
            margin-left: 15px;
        }
        .terima {
        width: 105px;
    height: 38px;
    font-size: 14px;
    font-weight: 600;
  }

        .konfirmasi {
            width: 105px;
            height: 38px;
            font-size: 14px;
            font-weight: 600;
        }
        .konfirmasi img {
            width: 15px;
            height: 15px;
        }

        .hapus {
            width: 38px;
            height: 38px;
        }

        .hapus img {
            width: 15px;
            height: 15px;
        }

        .box_profile_isi_cart {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .box_profile_isi_cart img {
            width: 22px;
            height: 22px;
            border-radius: 22px;
            object-fit: cover;
        }

        .box_profile_isi_cart p {
            margin-left: 10px;
        }
    }
</style>