function show_add_kategori() {
    tambah_kategori_form.style.display = 'flex';
}

function batal_add_kategori() {
    tambah_kategori_form.style.display = 'none';
} 

function simpan_add_kategori() {
    var icon_file = document.getElementById('icon_file');
    var nama_kategori = document.getElementById('nama_kategori');
    var slug_kategori = document.getElementById('slug_kategori');
    var p_icon_file = document.getElementById('p_icon_file');
    var p_nama_kategori = document.getElementById('p_nama_kategori');
    var p_slug_kategori = document.getElementById('p_slug_kategori');
    var text_tkat = document.getElementById('text_tkat');
    var loading_tkat = document.getElementById('loading_tkat');
    var resElement = document.getElementById('res');
    
    var valid = true;

    if (!icon_file.value) {
        valid = false;
        icon_file.style.border = '1px solid #EA2027';
        p_icon_file.style.color = '#EA2027';
    } else {
        icon_file.style.border = '1px solid #e2e2e2';
        p_icon_file.style.color = '#505050';
    }

    if (!nama_kategori.value) {
        valid = false;
        nama_kategori.style.border = '1px solid #EA2027';
        p_nama_kategori.style.color = '#EA2027';
    } else {
        nama_kategori.style.border = '1px solid #e2e2e2';
        p_nama_kategori.style.color = '#505050';
    }

    if (!slug_kategori.value) {
        valid = false;
        slug_kategori.style.border = '1px solid #EA2027';
        p_slug_kategori.style.color = '#EA2027';
    } else {
        slug_kategori.style.border = '1px solid #e2e2e2';
        p_slug_kategori.style.color = '#505050';
    }

    if (valid) {
        var data_add_kategori = new FormData();
        data_add_kategori.append('icon_file', icon_file.files[0]);
        data_add_kategori.append('nama_kategori', nama_kategori.value);
        data_add_kategori.append('slug_kategori', slug_kategori.value);
        
        sendDataToServer(data_add_kategori, '../../system/admin/category/add-kategori.php', text_tkat, loading_tkat, resElement);
    }
} 

function show_confirm_hapus(id_kat_hapus) {
    confirm_hapus.style.display = 'flex';
    val_id_kategori.value = id_kat_hapus;
}

function batal_hapus_kategori() {
    confirm_hapus.style.display = 'none';
    val_id_kategori.value = '';
}

function hapus_kategori_ya() {
    var data_hapus_kategori = new FormData();
    data_hapus_kategori.append('val_id_kategori', document.getElementById('val_id_kategori').value);
    sendDataToServer(data_hapus_kategori, '../../system/admin/category/delete-kategori.php', null, null, document.getElementById('res'));
}

function show_edit_kategori(id_kat_edit, nama_kat_edit, img_kat_edit, slug_kat_edit) {
    var edit_kategori_form = document.getElementById('edit_kategori_form');
    var val_id_kat_hapus = document.getElementById('val_id_kat_hapus');
    var nama_kategori_edit = document.getElementById('nama_kategori_edit');
    var img_edit_kategori = document.getElementById('img_edit_kategori');
    var slug_kategori_edit = document.getElementById('slug_kategori_edit');

    edit_kategori_form.style.display = 'flex';
    val_id_kat_hapus.value = id_kat_edit;
    nama_kategori_edit.value = nama_kat_edit;
    img_edit_kategori.src = img_kat_edit;
    slug_kategori_edit.value = slug_kat_edit;
}

function batal_edit_kategori() {
    edit_kategori_form.style.display = 'none';
    val_id_kat_hapus.value = '';
}

function simpan_edit_kategori() {
    var nama_kategori_edit = document.getElementById('nama_kategori_edit');
    var slug_kategori_edit = document.getElementById('slug_kategori_edit'); // Tambah ini
    var p_nama_kategori_edit = document.getElementById('p_nama_kategori_edit');
    var p_slug_kategori_edit = document.getElementById('p_slug_kategori_edit'); // Tambah ini
    var text_ekat = document.getElementById('text_ekat');
    var loading_ekat = document.getElementById('loading_ekat');
    var resElement = document.getElementById('res');
    
    var valid = true;

    if (!nama_kategori_edit.value) {
        valid = false;
        nama_kategori_edit.style.border = '1px solid #EA2027';
        p_nama_kategori_edit.style.color = '#EA2027';
    } else {
        nama_kategori_edit.style.border = '1px solid #e2e2e2';
        p_nama_kategori_edit.style.color = '#505050';
    }

    // Validasi slug
    if (!slug_kategori_edit.value) {
        valid = false;
        slug_kategori_edit.style.border = '1px solid #EA2027';
        p_slug_kategori_edit.style.color = '#EA2027';
    } else {
        slug_kategori_edit.style.border = '1px solid #e2e2e2';
        p_slug_kategori_edit.style.color = '#505050';
    }

    if (valid) {
        var data_edit_kategori = new FormData();
        data_edit_kategori.append('icon_file_edit', document.getElementById('icon_file_edit').files[0]);
        data_edit_kategori.append('nama_kategori_edit', nama_kategori_edit.value);
        data_edit_kategori.append('slug_kategori_edit', slug_kategori_edit.value); // Kirim nilai slug
        data_edit_kategori.append('val_id_kat_hapus', document.getElementById('val_id_kat_hapus').value);
        
        sendDataToServer(data_edit_kategori, '../../system/admin/category/edit-kategori.php', text_ekat, loading_ekat, resElement);
    }
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
