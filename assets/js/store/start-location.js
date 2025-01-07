// LOKASI TOKO //

function change_provinsi() {
    var data_lokasi_provinsi = new FormData();
    data_lokasi_provinsi.append('id_provinsi', document.getElementById('provinsi_ls').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            kota_ls.value = '';
            kecamatan_ls.value = '';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('kota_ls').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/location/kota.php', true);
    xhttp.send(data_lokasi_provinsi);
}

function change_kota() {
    var data_lokasi_kota = new FormData();
    data_lokasi_kota.append('id_kota', document.getElementById('kota_ls').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            kecamatan_ls.value = '';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('kecamatan_ls').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open('POST', '../../system/location/kecamatan.php', true);
    xhttp.send(data_lokasi_kota);
}

function simpan_lokasi() {
    if (provinsi_ls.value == '') {
        p_provinsi_ls.style.color = '#EA2027';
        provinsi_ls.style.border = '1px solid #EA2027';
    } else {
        p_provinsi_ls.style.color = '#959595';
        provinsi_ls.style.border = '1px solid #e2e2e2';
    }
    if (kota_ls.value == '') {
        p_kota_ls.style.color = '#EA2027';
        kota_ls.style.border = '1px solid #EA2027';
    } else {
        p_kota_ls.style.color = '#959595';
        kota_ls.style.border = '1px solid #e2e2e2';
    }
    if (kecamatan_ls.value == '') {
        p_kecamatan_ls.style.color = '#EA2027';
        kecamatan_ls.style.border = '1px solid #EA2027';
    } else {
        p_kecamatan_ls.style.color = '#959595';
        kecamatan_ls.style.border = '1px solid #e2e2e2';
    }
    if (provinsi_ls.value && kota_ls.value && kecamatan_ls.value) {
        var data_lokasi_update = new FormData();
        data_lokasi_update.append('tipe_user_vt', document.getElementById('tipe_user_vt').value);
        data_lokasi_update.append('provinsi_ls', document.getElementById('provinsi_ls').value);
        data_lokasi_update.append('kota_ls', document.getElementById('kota_ls').value);
        data_lokasi_update.append('kecamatan_ls', document.getElementById('kecamatan_ls').value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                text_s_lc.style.display = 'none';
                loading_s_lc.style.display = 'flex';
            }
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                text_s_lc.style.display = 'flex';
                loading_s_lc.style.display = 'none';
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
        xhttp.open('POST', '../../system/store/start-location.php', true);
        xhttp.send(data_lokasi_update);
    }
}