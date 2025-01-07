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

function show_confirm_hapus(idpenarikan) {
    confirm_hapus.style.display = 'flex';
    val_idpenarikan.value = idpenarikan;
}

function batal_hapus_akun() {
    confirm_hapus.style.display = 'none';
}

function hapus_akun() {
    var data_hapus_akun = new FormData();
    data_hapus_akun.append('val_idpenarikan', document.getElementById('val_idpenarikan').value);
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
    xhttp.open('POST', '../../system/admin/withdraw/hapus.php', true);
    xhttp.send(data_hapus_akun);
}

function show_edit_akun(id_user_ed, idpenarikan_ed, nama_lengkap_ed, jumlah_dipotong_ed, nama_bank_ed, rekening_tujuan_ed, atas_nama_ed, status_ed, keterangan_ed) {
    nama_lengkap_edt.value = nama_lengkap_ed;
    jumlah_dipotong_edt.value = jumlah_dipotong_ed;
    nama_bank_edt.value = nama_bank_ed;
    rekening_tujuan_edt.value = rekening_tujuan_ed;
    atas_nama_edt.value = atas_nama_ed;
    status_edt.value = status_ed;
    keterangan_edt.value = keterangan_ed;
    id_user_edit_akun.value = id_user_ed;
    idpenarikan_edt.value = idpenarikan_ed;
    box_edit_akun.style.display = 'flex';
}


function batal_edit_iklan() {
    nama_lengkap_edt.value = '';
    jumlah_dipotong_edt.value = '';
    nama_bank_edt.value = '';
    rekening_tujuan_edt.value = '';
    atas_nama_edt.value = '';
    status_edt.value = ''; 
    keterangan_edt.value = ''; 
    id_user_edit_akun.value = '';
    idpenarikan_edt.value = '';
    box_edit_akun.style.display = 'none';
}

function simpan_edit_iklan() {
    var data_edit_akun = new FormData();
    data_edit_akun.append('nama_lengkap_edt', document.getElementById('nama_lengkap_edt').value);
    data_edit_akun.append('jumlah_dipotong_edt', document.getElementById('jumlah_dipotong_edt').value);
    data_edit_akun.append('nama_bank_edt', document.getElementById('nama_bank_edt').value);
    data_edit_akun.append('rekening_tujuan_edt', document.getElementById('rekening_tujuan_edt').value);
    data_edit_akun.append('atas_nama_edt', document.getElementById('atas_nama_edt').value);
    data_edit_akun.append('status_edt', document.getElementById('status_edt').value);
    data_edit_akun.append('keterangan_edt', document.getElementById('keterangan_edt').value);
    data_edit_akun.append('id_user_edit_akun', document.getElementById('id_user_edit_akun').value);
    data_edit_akun.append('idpenarikan_edt', document.getElementById('idpenarikan_edt').value);
    
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
    xhttp.open('POST', '../../system/admin/withdraw/withdraw.php', true);
    xhttp.send(data_edit_akun);
}
