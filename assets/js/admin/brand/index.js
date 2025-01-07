function show_add_brand() {
    tambah_brand_form.style.display = 'flex';
}

function batal_add_brand() {
    tambah_brand_form.style.display = 'none';
} 

function simpan_add_brand() {
    var icon_file = document.getElementById('icon_file');
    var namab_brand = document.getElementById('namab_brand');
    var slug_brand = document.getElementById('slug_brand');
    var p_icon_file = document.getElementById('p_icon_file');
    var p_namab_brand = document.getElementById('p_namab_brand');
    var p_slug_brand = document.getElementById('p_slug_brand');
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

    if (!namab_brand.value) {
        valid = false;
        namab_brand.style.border = '1px solid #EA2027';
        p_namab_brand.style.color = '#EA2027';
    } else {
        namab_brand.style.border = '1px solid #e2e2e2';
        p_namab_brand.style.color = '#505050';
    }

    if (!slug_brand.value) {
        valid = false;
        slug_brand.style.border = '1px solid #EA2027';
        p_slug_brand.style.color = '#EA2027';
    } else {
        slug_brand.style.border = '1px solid #e2e2e2';
        p_slug_brand.style.color = '#505050';
    }

    if (valid) {
        var data_add_brand = new FormData();
        data_add_brand.append('icon_file', icon_file.files[0]);
        data_add_brand.append('namab_brand', namab_brand.value);
        data_add_brand.append('slug_brand', slug_brand.value);
        
        sendDataToServer(data_add_brand, '../../system/admin/brand/add-brand.php', text_tkat, loading_tkat, resElement);
    }
}

function show_confirm_hapus(id_kat_hapus) {
    confirm_hapus.style.display = 'flex';
    val_id_brand.value = id_kat_hapus;
}

function batal_hapus_brand() {
    confirm_hapus.style.display = 'none';
    val_id_brand.value = '';
}

function hapus_brand_ya() {
    var data_hapus_brand = new FormData();
    data_hapus_brand.append('val_id_brand', document.getElementById('val_id_brand').value);
    sendDataToServer(data_hapus_brand, '../../system/admin/brand/delete-brand.php', null, null, document.getElementById('res'));
}

function show_edit_brand(id_kat_edit, namab_kat_edit, img_kat_edit, slug_kat_edit) {
    var edit_brand_form = document.getElementById('edit_brand_form');
    var val_id_kat_hapus = document.getElementById('val_id_kat_hapus');
    var namab_brand_edit = document.getElementById('namab_brand_edit');
    var img_edit_brand = document.getElementById('img_edit_brand');
    var slug_brand_edit = document.getElementById('slug_brand_edit');

    edit_brand_form.style.display = 'flex';
    val_id_kat_hapus.value = id_kat_edit;
    namab_brand_edit.value = namab_kat_edit;
    img_edit_brand.src = img_kat_edit;
    slug_brand_edit.value = slug_kat_edit;
}

function batal_edit_brand() {
    edit_brand_form.style.display = 'none';
    val_id_kat_hapus.value = '';
}

function simpan_edit_brand() {
    var namab_brand_edit = document.getElementById('namab_brand_edit');
    var slug_brand_edit = document.getElementById('slug_brand_edit'); // Tambah ini
    var p_namab_brand_edit = document.getElementById('p_namab_brand_edit');
    var p_slug_brand_edit = document.getElementById('p_slug_brand_edit'); // Tambah ini
    var text_ekat = document.getElementById('text_ekat');
    var loading_ekat = document.getElementById('loading_ekat');
    var resElement = document.getElementById('res');
    
    var valid = true;

    if (!namab_brand_edit.value) {
        valid = false;
        namab_brand_edit.style.border = '1px solid #EA2027';
        p_namab_brand_edit.style.color = '#EA2027';
    } else {
        namab_brand_edit.style.border = '1px solid #e2e2e2';
        p_namab_brand_edit.style.color = '#505050';
    }

    // Validasi slug
    if (!slug_brand_edit.value) {
        valid = false;
        slug_brand_edit.style.border = '1px solid #EA2027';
        p_slug_brand_edit.style.color = '#EA2027';
    } else {
        slug_brand_edit.style.border = '1px solid #e2e2e2';
        p_slug_brand_edit.style.color = '#505050';
    }

    if (valid) {
        var data_edit_brand = new FormData();
        data_edit_brand.append('icon_file_edit', document.getElementById('icon_file_edit').files[0]);
        data_edit_brand.append('namab_brand_edit', namab_brand_edit.value);
        data_edit_brand.append('slug_brand_edit', slug_brand_edit.value); // Kirim nilai slug
        data_edit_brand.append('val_id_kat_hapus', document.getElementById('val_id_kat_hapus').value);
        
        sendDataToServer(data_edit_brand, '../../system/admin/brand/edit-brand.php', text_ekat, loading_ekat, resElement);
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
