function show_add_voucher() {
    tambah_voucher_form.style.display = 'flex';
}

function batal_add_voucher() {
    tambah_voucher_form.style.display = 'none';
} 

// Fungsi untuk menyimpan data voucher menggunakan AJAX
function simpan_add_voucher() {
    var jenis_voucher = document.getElementById('jenis_voucher');
    var persen_diskon = document.getElementById('persen_diskon');
    var maksimal_diskon = document.getElementById('maksimal_diskon');
    var waktu_berlaku = document.getElementById('waktu_berlaku');
    var text_tkat = document.getElementById('text_tkat');
    var loading_tkat = document.getElementById('loading_tkat');
    var resElement = document.getElementById('res');

    var valid = true;

    // Tambahkan validasi input sesuai kebutuhan
    // Contoh validasi sederhana, pastikan setiap input tidak boleh kosong
    if (!jenis_voucher.value) {
        valid = false;
        jenis_voucher.style.border = '1px solid #EA2027';
        // Tambahan validasi lainnya sesuai kebutuhan
    } else {
        jenis_voucher.style.border = '1px solid #e2e2e2';
        // Reset pesan validasi jika sudah benar
    }

    if (!persen_diskon.value) {
        valid = false;
        persen_diskon.style.border = '1px solid #EA2027';
        // Tambahan validasi lainnya sesuai kebutuhan
    } else {
        persen_diskon.style.border = '1px solid #e2e2e2';
        // Reset pesan validasi jika sudah benar
    }

    if (!maksimal_diskon.value) {
        valid = false;
        maksimal_diskon.style.border = '1px solid #EA2027';
        // Tambahan validasi lainnya sesuai kebutuhan
    } else {
        maksimal_diskon.style.border = '1px solid #e2e2e2';
        // Reset pesan validasi jika sudah benar
    }

    if (!waktu_berlaku.value) {
        valid = false;
        waktu_berlaku.style.border = '1px solid #EA2027';
        // Tambahan validasi lainnya sesuai kebutuhan
    } else {
        waktu_berlaku.style.border = '1px solid #e2e2e2';
        // Reset pesan validasi jika sudah benar
    }

    if (valid) {
        var data_add_voucher = new FormData();
        data_add_voucher.append('jenis_voucher', jenis_voucher.value);
        data_add_voucher.append('persen_diskon', persen_diskon.value);
        data_add_voucher.append('maksimal_diskon', maksimal_diskon.value);
        data_add_voucher.append('waktu_berlaku', waktu_berlaku.value);
        
        // Menambahkan data iduser
        var iduser = document.getElementById('iduser').value;
        data_add_voucher.append('iduser', iduser);

        // Panggil fungsi sendDataToServer dengan parameter yang sesuai
        sendDataToServer(data_add_voucher, '../../system/store/tambah-voucher.php', text_tkat, loading_tkat, resElement);
    }
}


function show_confirm_hapus(id_voucher) {
    confirm_hapus_voucher.style.display = 'flex';
    val_id_voucher.value = id_voucher;
}

function batal_hapus_voucher() {
    confirm_hapus_voucher.style.display = 'none';
    val_id_voucher.value = '';
}

function hapus_voucher_ya() {
    var data_hapus_voucher = new FormData();
    data_hapus_voucher.append('val_id_voucher', document.getElementById('val_id_voucher').value);
    sendDataToServer(data_hapus_voucher, '../../system/store/hapus-voucher.php', null, null, document.getElementById('res'));
}


// ...

function simpan_edit_voucher() {
    var jenis_voucher_edit = document.getElementById('jenis_voucher_edit');
    var persen_diskon_edit = document.getElementById('persen_diskon_edit');
    var maksimal_diskon_edit = document.getElementById('maksimal_diskon_edit');
    var waktu_berlaku_edit = document.getElementById('waktu_berlaku_edit');
    var p_jenis_voucher_edit = document.getElementById('p_jenis_voucher_edit');
    var p_persen_diskon_edit = document.getElementById('p_persen_diskon_edit');
    var p_maksimal_diskon_edit = document.getElementById('p_maksimal_diskon_edit');
    var p_waktu_berlaku_edit = document.getElementById('p_waktu_berlaku_edit');
    var text_evoucher = document.getElementById('text_evoucher');
    var loading_evoucher = document.getElementById('loading_evoucher');
    var resElement = document.getElementById('res');

    var valid = true;

    if (!jenis_voucher_edit.value) {
        valid = false;
        jenis_voucher_edit.style.border = '1px solid #EA2027';
        p_jenis_voucher_edit.style.color = '#EA2027';
    } else {
        jenis_voucher_edit.style.border = '1px solid #e2e2e2';
        p_jenis_voucher_edit.style.color = '#505050';
    }

    if (!persen_diskon_edit.value) {
        valid = false;
        persen_diskon_edit.style.border = '1px solid #EA2027';
        p_persen_diskon_edit.style.color = '#EA2027';
    } else {
        persen_diskon_edit.style.border = '1px solid #e2e2e2';
        p_persen_diskon_edit.style.color = '#505050';
    }

    if (!maksimal_diskon_edit.value) {
        valid = false;
        maksimal_diskon_edit.style.border = '1px solid #EA2027';
        p_maksimal_diskon_edit.style.color = '#EA2027';
    } else {
        maksimal_diskon_edit.style.border = '1px solid #e2e2e2';
        p_maksimal_diskon_edit.style.color = '#505050';
    }

    if (!waktu_berlaku_edit.value) {
        valid = false;
        waktu_berlaku_edit.style.border = '1px solid #EA2027';
        p_waktu_berlaku_edit.style.color = '#EA2027';
    } else {
        waktu_berlaku_edit.style.border = '1px solid #e2e2e2';
        p_waktu_berlaku_edit.style.color = '#505050';
    }

    if (valid) {
        var data_edit_voucher = new FormData();
        data_edit_voucher.append('jenis_voucher_edit', jenis_voucher_edit.value);
        data_edit_voucher.append('persen_diskon_edit', persen_diskon_edit.value);
        data_edit_voucher.append('maksimal_diskon_edit', maksimal_diskon_edit.value);
        data_edit_voucher.append('waktu_berlaku_edit', waktu_berlaku_edit.value);
        data_edit_voucher.append('val_id_voucher', document.getElementById('val_id_voucher').value);

        sendDataToServer(data_edit_voucher, '../../system/store/edit-voucher.php', text_evoucher, loading_evoucher, resElement);
    }
}


function show_edit_voucher(id_voucher, jenis_voucher, persen_diskon, maksimal_diskon, waktu_berlaku) {
    var edit_voucher_form = document.getElementById('edit_voucher_form');
    var val_id_voucher = document.getElementById('val_id_voucher');
    var jenis_voucher_edit = document.getElementById('jenis_voucher_edit');
    var persen_diskon_edit = document.getElementById('persen_diskon_edit');
    var maksimal_diskon_edit = document.getElementById('maksimal_diskon_edit');
    var waktu_berlaku_edit = document.getElementById('waktu_berlaku_edit');

    val_id_voucher.value = id_voucher;
    jenis_voucher_edit.value = jenis_voucher;
    persen_diskon_edit.value = persen_diskon;
    maksimal_diskon_edit.value = maksimal_diskon;
    waktu_berlaku_edit.value = waktu_berlaku;

    edit_voucher_form.style.display = 'flex';
}

function batal_edit_voucher() {
    var edit_voucher_form = document.getElementById('edit_voucher_form');
    edit_voucher_form.style.display = 'none';
}



function sendDataToServer(data, url, loadingTextElement, loadingElement, resultElement) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 1 && loadingTextElement && loadingElement) {
            loadingTextElement.style.display = 'none';
            loadingElement.style.display = 'block';
        }
        if (this.readyState === 4 && this.status === 200) {
            resultElement.innerHTML = this.responseText;
            if (loadingTextElement && loadingElement) {
                loadingTextElement.style.display = 'block';
                loadingElement.style.display = 'none';
            }
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length; i++) {
                if (getscriptres[i].text) {
                    eval(getscriptres[i].text);
                }
            }
        }
    };
    xhttp.open('POST', url, true);
    xhttp.send(data);
}