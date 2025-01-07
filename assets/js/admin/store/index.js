function currency(value, separator) {
    if (typeof value == "undefined") return "0";
    if (typeof separator == "undefined" || !separator) separator = ",";
    return value.toString().replace(/[^\d]+/g, "").replace(/\B(?=(?:\d{3})+(?!\d))/g, separator);
}
window.addEventListener('keyup', function (e) {
    var el = e.target;
    if (el.classList.contains('currency')) {
        el.value = currency(el.value, el.getAttribute('data-separator'));
    }
    false
});

function show_confirm_hapus(id_user) {
    confirm_hapus.style.display = 'flex';
    val_id_user.value = id_user;
}

function batal_hapus_akun() {
    confirm_hapus.style.display = 'none';
}

function hapus_akun() {
    var data_hapus_akun = new FormData();
    data_hapus_akun.append('val_id_user', document.getElementById('val_id_user').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            text_ha.style.display = 'none';
            loading_ha.style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            text_ha.style.display = 'block';
            loading_ha.style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/store/hapus-toko.php', true);
    xhttp.send(data_hapus_akun);
}

function show_edit_akun(id_user_ed, nama_toko_ed, level_toko_ed, nama_pengguna_ed, no_wa_ed, verifikasi_toko_ed, jumlah_deposit_ed) {
    nama_toko_edt.value = nama_toko_ed;
    level_toko_edt.value = level_toko_ed;
    nama_pengguna_edt.value = nama_pengguna_ed;
    no_wa_edt.value = no_wa_ed;
    verifikasi_toko_edt.value = verifikasi_toko_ed;
    jumlah_deposit_edt.value = '';  
    id_user_edit_akun.value = id_user_ed;
    box_edit_akun.style.display = 'flex';
}


function batal_edit_iklan() {
    nama_toko_edt.value = '';
    level_toko_edt.value = '';
    nama_pengguna_edt.value = '';
    no_wa_edt.value = '';
    verifikasi_toko_edt.value = '';
    jumlah_deposit_edt.value = ''; 
    id_user_edit_akun.value = '';
    box_edit_akun.style.display = 'none';
}

function simpan_edit_iklan() {
    var data_edit_akun = new FormData();
    data_edit_akun.append('nama_toko_edt', nama_toko_edt.value);
    data_edit_akun.append('level_toko_edt', level_toko_edt.value);
    data_edit_akun.append('nama_pengguna_edt', nama_pengguna_edt.value);
    data_edit_akun.append('no_wa_edt', no_wa_edt.value);
    data_edit_akun.append('verifikasi_toko_edt', verifikasi_toko_edt.value);
    data_edit_akun.append('id_user_edit_akun', id_user_edit_akun.value);

    // Get the value of jumlah_deposit_edt input field
    var jumlah_deposit_edt_value = document.getElementById('jumlah_deposit_edt').value;
    
    // Append the value to the FormData object
    data_edit_akun.append('jumlah_deposit_edt', jumlah_deposit_edt_value);

    var text_ea = document.getElementById('text_ea');
    var loading_ea = document.getElementById('loading_ea');

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            text_ea.style.display = 'none';
            loading_ea.style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            text_ea.style.display = 'block';
            loading_ea.style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/admin/store/edit-toko.php', true);
    xhttp.send(data_edit_akun);
}

