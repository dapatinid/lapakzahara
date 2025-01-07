function show_bdmhjsd() {
    box_bdmafd.style.display = 'flex';
    tbdma.innerHTML = 'Tambah';
    id_user_bbbj.value = '';
    nama_sjdhgfjhs.value = '';
}

function show_edit_bjhadvf(idsdjf, namadjhf) {
    box_bdmafd.style.display = 'flex';
    tbdma.innerHTML = 'Edit';
    id_user_bbbj.value = idsdjf;
    nama_sjdhgfjhs.value = namadjhf;
}

function batal_bdm() {
    box_bdmafd.style.display = 'none';
    tbdma.innerHTML = '';
    id_user_bbbj.value = '';
    nama_sjdhgfjhs.value = '';
}

function show_confirm_hapus(iddvmsd) {
    if (confirm("Konfirmasi Hapus") == true) {
        var data_hrdsd = new FormData();
        data_hrdsd.append('iddvmsd', iddvmsd);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {}
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
        xhttp.open('POST', '../../system/admin/deliveryman/hapus.php', true);
        xhttp.send(data_hrdsd);
    }
}