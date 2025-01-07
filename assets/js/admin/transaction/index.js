function req_list_transaksi(url_list_transaksi, res_div, num_paging) {
    var limit_paging = new FormData();
    var tipe_user_vt_v = document.getElementById('tipe_user_vt').value;
    limit_paging.append('page_paging', num_paging);
    limit_paging.append('tipe_user_vt', tipe_user_vt_v);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            if (res_div == 'res_transaksi_admin') {
                loading_res_transaksi_admin.style.display = 'block';
                res_transaksi_admin.style.display = 'none';
            }
        }
        if (this.readyState == 4 && this.status == 200) {
            if (res_div == 'res_transaksi_admin') {
                loading_res_transaksi_admin.style.display = 'none';
                res_transaksi_admin.style.display = 'block';
            } else {
                loading_paging.style.display = 'none';
            }
            document.getElementById(res_div).innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', url_list_transaksi, true);
    xhttp.send(limit_paging);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("Window loaded");
    url_lt = '../../system/admin/transaction/belum-bayar.php';
    req_list_transaksi(url_lt, 'res_transaksi_admin', 1);
    paging_belum_bayar.style.display = 'block';
    belum_bayar.className = 'isi_list_transaksi_admin_active';
});

belum_bayar.onclick = function () {
    belum_bayar.className = 'isi_list_transaksi_admin_active';
    sudah_bayar.className = 'isi_list_transaksi_admin';
    dalam_perjalanan.className = 'isi_list_transaksi_admin';
    selesai.className = 'isi_list_transaksi_admin';
    dibatalkan.className = 'isi_list_transaksi_admin';
    url_lt = '../../system/admin/transaction/belum-bayar.php';
    req_list_transaksi(url_lt, 'res_transaksi_admin');
    paging_belum_bayar.style.display = 'block';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'none';
}

sudah_bayar.onclick = function () {
    belum_bayar.className = 'isi_list_transaksi_admin';
    sudah_bayar.className = 'isi_list_transaksi_admin_active';
    dalam_perjalanan.className = 'isi_list_transaksi_admin';
    selesai.className = 'isi_list_transaksi_admin';
    dibatalkan.className = 'isi_list_transaksi_admin';
    url_lt = '../../system/admin/transaction/sudah-bayar.php';
    req_list_transaksi(url_lt, 'res_transaksi_admin');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'block';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'none';
}

dalam_perjalanan.onclick = function () {
    belum_bayar.className = 'isi_list_transaksi_admin';
    sudah_bayar.className = 'isi_list_transaksi_admin';
    dalam_perjalanan.className = 'isi_list_transaksi_admin_active';
    selesai.className = 'isi_list_transaksi_admin';
    dibatalkan.className = 'isi_list_transaksi_admin';
    url_lt = '../../system/admin/transaction/dalam-perjalanan.php';
    req_list_transaksi(url_lt, 'res_transaksi_admin');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'block';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'none';
}

selesai.onclick = function () {
    belum_bayar.className = 'isi_list_transaksi_admin';
    sudah_bayar.className = 'isi_list_transaksi_admin';
    dalam_perjalanan.className = 'isi_list_transaksi_admin';
    selesai.className = 'isi_list_transaksi_admin_active';
    dibatalkan.className = 'isi_list_transaksi_admin';
    url_lt = '../../system/admin/transaction/selesai.php';
    req_list_transaksi(url_lt, 'res_transaksi_admin');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'block';
    paging_dibatalkan.style.display = 'none';
}

dibatalkan.onclick = function () {
    belum_bayar.className = 'isi_list_transaksi_admin';
    sudah_bayar.className = 'isi_list_transaksi_admin';
    dalam_perjalanan.className = 'isi_list_transaksi_admin';
    selesai.className = 'isi_list_transaksi_admin';
    dibatalkan.className = 'isi_list_transaksi_admin_active';
    url_lt = '../../system/admin/transaction/dibatalkan.php';
    req_list_transaksi(url_lt, 'res_transaksi_admin');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'block';
}

function step_dikirim() {
    var idinvoicesb_v = document.getElementById('idinvoice_pss').value;
    var list_sdk_add = 'list_sdk' + idinvoicesb_v;
    var text_sdk_add = 'text_sdk' + idinvoicesb_v;
    var load_sdk_add = 'load_sdk' + idinvoicesb_v;
    var data_update_dikirim = new FormData();
    data_update_dikirim.append('idinvoicesb', idinvoicesb_v);
    data_update_dikirim.append('resi_pengiriman_v', document.getElementById('resi_pengiriman_v').value);
    data_update_dikirim.append('kurir_toko_manual', document.getElementById('kurir_toko_manual').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_sdk_add).style.display = 'none';
            document.getElementById(load_sdk_add).style.display = 'block';
            back_up_ri.style.display = 'none';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(list_sdk_add).style.display = 'none';
            idinvoice_pss.value = '';
            resi_pengiriman_v.value = '';
            // document.getElementById('res_transaksi_admin').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/update-dikirim.php', true);
    xhttp.send(data_update_dikirim);
}

function step_paket_sampai(idinvoice_pss) {
    var lid_dpj_h = 'id_dpj_h' + idinvoice_pss;
    var text_pss_add = 'text_sdk' + idinvoice_pss;
    var load_pss_add = 'load_sdk' + idinvoice_pss;
    var data_update_paket_sampai = new FormData();
    data_update_paket_sampai.append('idinvoice_pss', idinvoice_pss);
    data_update_paket_sampai.append('resi_pengiriman_v', document.getElementById('resi_pengiriman_v').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_pss_add).style.display = 'none';
            document.getElementById(load_pss_add).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(lid_dpj_h).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/paket-sampai.php', true);
    xhttp.send(data_update_paket_sampai);
}

function show_resi_pengiriman(idinvoice_srp) {
    back_up_ri.style.display = 'flex';
    idinvoice_pss.value = idinvoice_srp;
}

function add_resi_pengiriman() {
    // if (resi_pengiriman_v.value == '') {
    //     resi_pengiriman_v.style.borderColor = '#EA2027';
    // } else {
    //     resi_pengiriman_v.style.borderColor = '#e2e2e2';
    //     back_up_ri.style.display = 'none';
    // }
    step_dikirim();
}



function step_paket_konfirmasi(idinvoice_pss, id_usr_tm) {
    var visi_cart_bbt = 'isi_cart_hpp' + idinvoice_pss;
    var text_pss_add_tm = 'text_spk' + idinvoice_pss;
    var load_pss_add_tm = 'load_spk' + idinvoice_pss;
    var data_update_dikitim_tm = new FormData();
    data_update_dikitim_tm.append('idinvoice_pss', idinvoice_pss);
    data_update_dikitim_tm.append('id_usr_tm', id_usr_tm);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_pss_add_tm).style.display = 'none';
            document.getElementById(load_pss_add_tm).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(visi_cart_bbt).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/update-dikemas-tm.php', true);
    xhttp.send(data_update_dikitim_tm);
}

function load_paging_belum_bayar() {
    var paging_j = document.getElementById('page_paging_belum_bayar').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_belum_bayar' + tambah_paging_j;
    document.getElementById('page_paging_belum_bayar').value = tambah_paging_j;

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_belum_bayar').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    req_list_transaksi('../../system/admin/transaction/belum-bayar.php', id_res_paging, tambah_paging_j);
}

function load_paging_dikemas() {
    var paging_j = document.getElementById('page_paging_dikemas').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_dikemas' + tambah_paging_j;
    document.getElementById('page_paging_dikemas').value = tambah_paging_j;

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_dikemas').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    req_list_transaksi('../../system/admin/transaction/sudah-bayar.php', id_res_paging, tambah_paging_j);
}

function load_paging_dikirim() {
    var paging_j = document.getElementById('page_paging_dikirim').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_dikirim' + tambah_paging_j;
    document.getElementById('page_paging_dikirim').value = tambah_paging_j;

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_dikirim').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    req_list_transaksi('../../system/admin/transaction/dalam-perjalanan.php', id_res_paging, tambah_paging_j);
}

function load_paging_selesai() {
    var paging_j = document.getElementById('page_paging_selesai').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_selesai' + tambah_paging_j;
    document.getElementById('page_paging_selesai').value = tambah_paging_j;

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_selesai').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    req_list_transaksi('../../system/admin/transaction/selesai.php', id_res_paging, tambah_paging_j);
}

function load_paging_dibatalkan() {
    var paging_j = document.getElementById('page_paging_dibatalkan').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_dibatalkan' + tambah_paging_j;
    document.getElementById('page_paging_dibatalkan').value = tambah_paging_j;

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_dibatalkan').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    req_list_transaksi('../../system/admin/transaction/dibatalkan.php', id_res_paging, tambah_paging_j);
}

function hapus_pesanan(idinvoice_hpp) {
    var lid_dpj_h = 'isi_cart_hpp' + idinvoice_hpp;
    var text_pss_add = 'text_hpp' + idinvoice_hpp;
    var load_pss_add = 'load_hpp' + idinvoice_hpp;
    var data_hapus_paket = new FormData();
    data_hapus_paket.append('idinvoice_hpp', idinvoice_hpp);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_pss_add).style.display = 'none';
            document.getElementById(load_pss_add).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(lid_dpj_h).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/hapus-pesanan.php', true);
    xhttp.send(data_hapus_paket);
}

function show_detail_iv(idinv_vi) {
    back_vd_iv_vi.style.display = 'block';
    var data_show_invoice = new FormData();
    data_show_invoice.append('idinv_vi', idinv_vi);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {}
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('vd_iv').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/detail-invoice.php', true);
    xhttp.send(data_show_invoice);
}

function close_detail_iv() {
    back_vd_iv_vi.style.display = 'none';
}





function selesai_kas(idinvoice_kas) {
    var lid_dpj_h = 'list_sdk' + idinvoice_kas;
    var text_pss_add = 'text_kas' + idinvoice_kas;
    var load_pss_add = 'load_kas' + idinvoice_kas;
    var data_update_paket_selesaikas = new FormData();
    data_update_paket_selesaikas.append('idinvoice_pss', idinvoice_kas);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_pss_add).style.display = 'none';
            document.getElementById(load_pss_add).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(lid_dpj_h).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/paket-sampai.php', true);
    xhttp.send(data_update_paket_selesaikas);
}

function batalkan_kas(idinvoice_kasbat) {
    var lid_dpj_h = 'list_sdk' + idinvoice_kasbat;
    var text_pss_add = 'text_btlkn' + idinvoice_kasbat;
    var load_pss_add = 'load_btlkn' + idinvoice_kasbat;
    var data_update_paket_selesaikasbtkps = new FormData();
    data_update_paket_selesaikasbtkps.append('idinvoice_bpk', idinvoice_kasbat);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_pss_add).style.display = 'none';
            document.getElementById(load_pss_add).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(lid_dpj_h).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/batalkan-pesanan.php', true);
    xhttp.send(data_update_paket_selesaikasbtkps);
}

function batalkan_terima(idinvoice_terima) {
    var lid_dpj_h = 'list_sdk' + idinvoice_terima;
    var text_pss_add = 'text_sdk' + idinvoice_terima;
    var load_pss_add = 'load_sdk' + idinvoice_terima;
    var data_update_paket_terimakasbtkps = new FormData();
    data_update_paket_terimakasbtkps.append('idinvoice_hp', idinvoice_terima); // Sesuaikan dengan nama parameter yang sesuai dengan Ajax Anda
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(text_pss_add).style.display = 'none';
            document.getElementById(load_pss_add).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(lid_dpj_h).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/transaction/terima-pembatalan.php', true); // Sesuaikan dengan URL yang sesuai dengan Ajax Anda
    xhttp.send(data_update_paket_terimakasbtkps);
}

