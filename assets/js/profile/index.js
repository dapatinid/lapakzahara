const GetOrder = (url, res_div, num_paging) => {
    var limit_paging = new FormData();
    limit_paging.append('page_paging', num_paging);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            if (res_div == 'res_order_menu') {
                loading_order_menu.style.display = 'grid';
                res_order_menu.style.display = 'none';
            }
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(res_div).innerHTML = this.responseText;
            if (res_div == 'res_order_menu') {
                loading_order_menu.style.display = 'none';
                res_order_menu.style.display = 'block';
            } else {
                loading_paging.style.display = 'none';
            }
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', url, true);
    xhttp.send(limit_paging);
}

document.addEventListener('DOMContentLoaded', function() {
    GetOrder('../system/order/belum-bayar.php', 'res_order_menu', 1);
    paging_belum_bayar.style.display = 'block';
});

belum_bayar.onclick = function () {
    belum_bayar.className = 'isi_select_order_menu_active';
    dikemas.className = 'isi_select_order_menu';
    dikirim.className = 'isi_select_order_menu';
    selesai.className = 'isi_select_order_menu';
    dibatalkan.className = 'isi_select_order_menu';
    GetOrder('../system/order/belum-bayar.php', 'res_order_menu');
    paging_belum_bayar.style.display = 'block';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'none';
}

dikemas.onclick = function () {
    belum_bayar.className = 'isi_select_order_menu';
    dikemas.className = 'isi_select_order_menu_active';
    dikirim.className = 'isi_select_order_menu';
    selesai.className = 'isi_select_order_menu';
    dibatalkan.className = 'isi_select_order_menu';
    GetOrder('../system/order/dikemas.php', 'res_order_menu');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'block';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'none';
} 

dikirim.onclick = function () {
    belum_bayar.className = 'isi_select_order_menu';
    dikemas.className = 'isi_select_order_menu';
    dikirim.className = 'isi_select_order_menu_active';
    selesai.className = 'isi_select_order_menu';
    dibatalkan.className = 'isi_select_order_menu';
    GetOrder('../system/order/dikirim.php', 'res_order_menu');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'block';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'none';
}

selesai.onclick = function () {
    belum_bayar.className = 'isi_select_order_menu';
    dikemas.className = 'isi_select_order_menu';
    dikirim.className = 'isi_select_order_menu';
    selesai.className = 'isi_select_order_menu_active';
    dibatalkan.className = 'isi_select_order_menu';
    GetOrder('../system/order/selesai.php', 'res_order_menu');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'block';
    paging_dibatalkan.style.display = 'none';
}

dibatalkan.onclick = function () {
    belum_bayar.className = 'isi_select_order_menu';
    dikemas.className = 'isi_select_order_menu';
    dikirim.className = 'isi_select_order_menu';
    selesai.className = 'isi_select_order_menu';
    dibatalkan.className = 'isi_select_order_menu_active';
    GetOrder('../system/order/dibatalkan.php', 'res_order_menu');
    paging_belum_bayar.style.display = 'none';
    paging_dikemas.style.display = 'none';
    paging_dikirim.style.display = 'none';
    paging_selesai.style.display = 'none';
    paging_dibatalkan.style.display = 'block';
}

c_mo_belum_bayar.onclick = function () {
    user_info.style.display = 'none';
    order_menu.style.display = 'block';
    belum_bayar.onclick();
}

c_mo_dikemas.onclick = function () {
    user_info.style.display = 'none';
    order_menu.style.display = 'block';
    dikemas.onclick();
}

c_mo_dikirim.onclick = function () {
    user_info.style.display = 'none';
    order_menu.style.display = 'block';
    dikirim.onclick();
}

c_mo_selesai.onclick = function () {
    user_info.style.display = 'none';
    order_menu.style.display = 'block';
    selesai.onclick();
}

c_mo_dibatalkan.onclick = function () {
    user_info.style.display = 'none';
    order_menu.style.display = 'block';
    dibatalkan.onclick();
}

close_order_menu.onclick = function () {
    user_info.style.display = 'block';
    order_menu.style.display = 'none';
}

star_c1.onclick = function () {
    star_c1.style.color = '#ff6348';
    star_c2.style.color = '#e2e2e2';
    star_c3.style.color = '#e2e2e2';
    star_c4.style.color = '#e2e2e2';
    star_c5.style.color = '#e2e2e2';
    star_bp_inp.value = '1';
}

star_c2.onclick = function () {
    star_c1.style.color = '#ff6348';
    star_c2.style.color = '#ff6348';
    star_c3.style.color = '#e2e2e2';
    star_c4.style.color = '#e2e2e2';
    star_c5.style.color = '#e2e2e2';
    star_bp_inp.value = '2';
}

star_c3.onclick = function () {
    star_c1.style.color = '#ff6348';
    star_c2.style.color = '#ff6348';
    star_c3.style.color = '#ff6348';
    star_c4.style.color = '#e2e2e2';
    star_c5.style.color = '#e2e2e2';
    star_bp_inp.value = '3';
}

star_c4.onclick = function () {
    star_c1.style.color = '#ff6348';
    star_c2.style.color = '#ff6348';
    star_c3.style.color = '#ff6348';
    star_c4.style.color = '#ff6348';
    star_c5.style.color = '#e2e2e2';
    star_bp_inp.value = '4';
}

star_c5.onclick = function () {
    star_c1.style.color = '#ff6348';
    star_c2.style.color = '#ff6348';
    star_c3.style.color = '#ff6348';
    star_c4.style.color = '#ff6348';
    star_c5.style.color = '#ff6348';
    star_bp_inp.value = '5';
}

function show_bp(idinvbp) {
    box_bp_produk.style.display = 'flex';
    id_inv_bp.value = idinvbp;
}

function close_bp() {
    box_bp_produk.style.display = 'none';
    id_inv_bp.value = '';
    star_c1.style.color = '#e2e2e2';
    star_c2.style.color = '#e2e2e2';
    star_c3.style.color = '#e2e2e2';
    star_c4.style.color = '#e2e2e2';
    star_c5.style.color = '#e2e2e2';
    star_bp_inp.value = '';
}

function kirim_penilaian_bp() {
    if (star_bp_inp.value == '') {
        bpld_red.style.display = 'block';
    } else {
        bpld_red.style.display = 'none';
        var data_bp = new FormData();
        data_bp.append('star_bp_inp', document.getElementById('star_bp_inp').value);
        data_bp.append('deskripsi_bp_inp', document.getElementById('deskripsi_bp_inp').value);
        data_bp.append('id_inv_bp', document.getElementById('id_inv_bp').value);
        data_bp.append('gambar_bp_a', document.getElementById('gambar_bp_a').files[0]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                t_bp_send.style.display = 'none';
                load_bp_send.style.display = 'block';
            }
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                t_bp_send.style.display = 'block';
                load_bp_send.style.display = 'none';
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
        xhttp.open('POST', '../system/profile/beri-nilai.php', true);
        xhttp.send(data_bp);
    }
}

function load_paging_belum_bayar() {
    var paging_j = document.getElementById('page_paging_belum_bayar').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_belum_bayar' + tambah_paging_j;
    document.getElementById('page_paging_belum_bayar').value = tambah_paging_j;
    console.log(tambah_paging_j);

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_belum_bayar').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    GetOrder('../system/order/belum-bayar.php', id_res_paging, tambah_paging_j);
}

function load_paging_dikemas() {
    var paging_j = document.getElementById('page_paging_dikemas').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_dikemas' + tambah_paging_j;
    document.getElementById('page_paging_dikemas').value = tambah_paging_j;
    console.log(tambah_paging_j);

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_dikemas').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    GetOrder('../system/order/dikemas.php', id_res_paging, tambah_paging_j);
}

function load_paging_dikirim() {
    var paging_j = document.getElementById('page_paging_dikirim').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_dikirim' + tambah_paging_j;
    document.getElementById('page_paging_dikirim').value = tambah_paging_j;
    console.log(tambah_paging_j);

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_dikirim').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    GetOrder('../system/order/dikirim.php', id_res_paging, tambah_paging_j);
}

function load_paging_selesai() {
    var paging_j = document.getElementById('page_paging_selesai').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_selesai' + tambah_paging_j;
    document.getElementById('page_paging_selesai').value = tambah_paging_j;
    console.log(tambah_paging_j);

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_selesai').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    GetOrder('../system/order/selesai.php', id_res_paging, tambah_paging_j);
}

function load_paging_dibatalkan() {
    var paging_j = document.getElementById('page_paging_dibatalkan').value;
    var tambah_paging_j = parseInt(paging_j) + 1;
    var id_res_paging = 'respagingview_dibatalkan' + tambah_paging_j;
    document.getElementById('page_paging_dibatalkan').value = tambah_paging_j;
    console.log(tambah_paging_j);

    var v_res_paging = document.createElement('div');
    v_res_paging.setAttribute('id', id_res_paging);
    document.getElementById('res_paging_dibatalkan').appendChild(v_res_paging);

    loading_paging.style.display = 'flex';
    GetOrder('../system/order/dibatalkan.php', id_res_paging, tambah_paging_j);
}

function pilih_gambar_bp() {
    const file = document.getElementById('gambar_bp_a').files[0];
    const fileType = file['type'];
    const validImageTypes = ['image/jpeg', 'image/png'];
    if (!validImageTypes.includes(fileType)) {
        p_gambar_bp_a.style.display = 'block';
        gambar_bp_a.value = '';
    } else {
        p_gambar_bp_a.style.display = 'none';
    }
}

function step_paket_sampai(idinvoice_pss) {
    var lid_dpj_h = 'id_dpj_h' + idinvoice_pss;
    var text_pss_add = 'text_sdk' + idinvoice_pss;
    var load_pss_add = 'load_sdk' + idinvoice_pss;
    var data_update_paket_sampai = new FormData();
    data_update_paket_sampai.append('idinvoice_pss', idinvoice_pss);
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
    xhttp.open('POST', '../system/admin/transaction/paket-sampai.php', true);
    xhttp.send(data_update_paket_sampai);
}

function batalkan_pesanan(idinvoice_bpk) {
    var lid_dpj_h = 'dikemas_isi_box' + idinvoice_bpk;
    var text_pss_add = 'text_bpk' + idinvoice_bpk;
    var load_pss_add = 'load_bpk' + idinvoice_bpk;
    var data_update_paket_dibatalkan = new FormData();
    data_update_paket_dibatalkan.append('idinvoice_bpk', idinvoice_bpk);
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
    xhttp.open('POST', '../system/admin/transaction/batalkan-pesanan.php', true);
    xhttp.send(data_update_paket_dibatalkan);
}

function tampilkanKonfirmasi(idivhap) {
    var modal = document.getElementById("myModal");
    modal.style.display = 'flex';
    document.getElementById('id_inv_hapus').value = idivhap;
}

function hapusInvoice() {
    var t_bp_hapus = document.getElementById('t_bp_hapus');
    var load_bp_hapus = document.getElementById('load_bp_hapus');
    
    var data_hapus = new FormData();
    data_hapus.append('id_inv_hapus', document.getElementById('id_inv_hapus').value);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            t_bp_hapus.style.display = 'none';
            load_bp_hapus.style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            t_bp_hapus.style.display = 'block';
            load_bp_hapus.style.display = 'none';
            tutupKonfirmasi(); // Tutup modal setelah berhasil
            window.location.reload(); // Reload halaman setelah tutup modal
        }
    };

    xhttp.open('POST', '../system/profile/hapus-invoice.php', true);
    xhttp.send(data_hapus);
}

function tutupKonfirmasi() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}




function ubahcatatan(event) {
    var idInvoice = event.currentTarget.getAttribute('data-idinvoice');
    var back_catatan = document.getElementById('back_catatan' + idInvoice);

    if (back_catatan) {
        back_catatan.style.display = 'flex';

        // Menetapkan nilai opsi pilihan pada setiap pembukaan popup
        var alasanLaporkan = document.getElementById('alasanLaporkan_' + idInvoice);
        alasanLaporkan.selectedIndex = 0; // Set ke opsi pertama
        handleOptionClick({ target: alasanLaporkan }); // Panggil fungsi handleOptionClick untuk memastikan tampilan benar
    }
}

function handleOptionClick(event) {
    const selectedOption = event.target;
    const parentPopup = selectedOption.closest('.back_catatan');
    const idInvoice = parentPopup.id.replace('back_catatan', '');

    const optionsList = document.getElementById('alasanLaporkan_' + idInvoice).getElementsByTagName('li');

    for (const option of optionsList) {
        option.classList.remove('selected');
    }

    selectedOption.classList.add('selected');

    // Mengambil nilai dari opsi yang dipilih
    const selectedValue = selectedOption.getAttribute('value');

    // Menambahkan nilai ke textarea
    const textarea = document.getElementById('deskripsiMasalah_' + idInvoice);
    if (selectedValue === 'lainnya') {
        textarea.style.display = 'block';
        textarea.value = ''; // Bersihkan textarea jika opsi "lainnya" dipilih
    } else {
        textarea.style.display = 'none';
        textarea.value = selectedValue;
    }
}



function simpan_catatan(idInvoice) {
    // Get the parent popup element
    const parentPopup = document.getElementById('back_catatan' + idInvoice);

    // Dapatkan opsi yang dipilih dalam popup tertentu
    const selectedOption = parentPopup.querySelector('.options-list li.selected');

    // Dapatkan nilai dan teks dari opsi yang dipilih
    const alasanLaporkan = selectedOption ? selectedOption.getAttribute('value') : '';
    const deskripsiMasalah = parentPopup.querySelector('#deskripsiMasalah_' + idInvoice).value;

    // Buat objek FormData baru dan masukkan data yang relevan
    var data_scat = new FormData();
    data_scat.append('idInvoice', idInvoice); // Menggunakan nomor invoice sebagai parameter
    data_scat.append('alasanLaporkan', alasanLaporkan);

    // Jika alasan pelaporan adalah "lainnya", masukkan deskripsi masalah
    if (alasanLaporkan === 'lainnya') {
        data_scat.append('deskripsiMasalah', deskripsiMasalah);
    } else {
        data_scat.append('deskripsiMasalah', ''); // Kosongkan nilai jika bukan opsi "Lainnya"
    }

    // Buat objek XMLHttpRequest
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            p_butacat.style.display = 'none';
            load_butacat.style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }

    // Buka permintaan POST ke URL yang ditentukan
    xhttp.open("POST", "../system/profile/batalkan-invoice.php", true);

    // Kirim objek FormData dengan data
    xhttp.send(data_scat);
}



function BatalLcatatan(event) {
    var idInvoice = event.currentTarget.getAttribute('data-idinvoice');
    var back_catatan = document.getElementById('back_catatan' + idInvoice);

    if (back_catatan) {
        back_catatan.style.display = 'none';
    }
}

