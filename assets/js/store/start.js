// INFORMASI TOKO //

function click_file_img() {
    cfile_img_pro.click();
}

function change_image(event) {
    const file = document.getElementById('cfile_img_pro').files[0];
    const fileType = file['type'];
    const validImageTypes = ['image/jpeg', 'image/png'];
    const err_logo_toko_pro = document.getElementById('err_logo_toko_pro');

    if (!validImageTypes.includes(fileType)) {
        err_logo_toko_pro.style.display = 'block';
        cfile_img_pro.value = '';
    } else {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('img_logo_toko_pro');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        err_logo_toko_pro.style.display = 'none';
    }
}

function simpan_edit_profile() {
    const p_nama_toko = document.getElementById('p_nama_toko');
    const p_nama_pengguna = document.getElementById('p_nama_pengguna');
    const p_no_wa = document.getElementById('p_no_wa');
    const nama_toko = document.getElementById('nama_toko');
    const nama_pengguna = document.getElementById('nama_pengguna');
    const no_wa = document.getElementById('no_wa');

    if (nama_toko.value === '') {
        p_nama_toko.style.color = '#EA2027';
        nama_toko.style.border = '1px solid #EA2027';
    } else {
        p_nama_toko.style.color = '#959595';
        nama_toko.style.border = '1px solid #e2e2e2';
    }

    // Validasi Nama Pengguna
    const pattern = /^[a-z0-9_]+$/; // Pola untuk memeriksa nama pengguna

    if (nama_pengguna.value === '') {
        p_nama_pengguna.style.color = '#EA2027';
        nama_pengguna.style.border = '1px solid #EA2027';
        p_nama_pengguna.innerText = 'Username Toko tidak boleh kosong!';
    } else if (nama_pengguna.value.length < 4) {
        p_nama_pengguna.style.color = '#EA2027';
        nama_pengguna.style.border = '1px solid #EA2027';
        p_nama_pengguna.innerText = 'Username Toko minimal 4 karakter';
    } else if (!pattern.test(nama_pengguna.value)) {
        p_nama_pengguna.style.color = '#EA2027';
        nama_pengguna.style.border = '1px solid #EA2027';
        p_nama_pengguna.innerText = 'Username Toko hanya boleh menggunakan huruf kecil, angka, dan garis bawah';
    } else {
        p_nama_pengguna.style.color = '#959595';
        nama_pengguna.style.border = '1px solid #e2e2e2';
        p_nama_pengguna.innerText = 'Username Toko';
    }

    // Validasi nomor WhatsApp hanya angka
    if (no_wa.value === '') {
        p_no_wa.style.color = '#EA2027';
        no_wa.style.border = '1px solid #EA2027';
        p_no_wa.innerText = 'Nomor WhatsApp tidak boleh kosong';
    } else if (!/^\d+$/.test(no_wa.value)) {
        p_no_wa.style.color = '#EA2027';
        no_wa.style.border = '1px solid #EA2027';
        p_no_wa.innerText = 'Nomor WhatsApp hanya boleh berisi angka';
    } else {
        p_no_wa.style.color = '#959595';
        no_wa.style.border = '1px solid #e2e2e2';
        p_no_wa.innerText = 'Nomor WhatsApp';
    }

    // Lanjutkan dengan proses penyimpanan jika validasi lainnya telah sesuai
    if (nama_toko.value && /^\d+$/.test(no_wa.value) && p_nama_pengguna.innerText === 'Username Toko') {
        var data_edit_profile = new FormData();
        data_edit_profile.append('cfile_img_pro', document.getElementById('cfile_img_pro').files[0]);
        data_edit_profile.append('nama_toko', nama_toko.value);
        data_edit_profile.append('nama_pengguna', nama_pengguna.value);
        data_edit_profile.append('no_wa', no_wa.value);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                bu_e_pro.style.display = 'none';
                loading_e_pro.style.display = 'flex';
            }
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText.trim() === 'Username Toko sudah ada') {
                    p_nama_pengguna.style.color = '#EA2027';
                    nama_pengguna.style.border = '1px solid #EA2027';
                    p_nama_pengguna.innerText = 'Username Toko sudah ada';
                } else if (this.responseText.trim() === 'Nomor WhatsApp sudah terdaftar') {
                    p_no_wa.style.color = '#EA2027';
                    no_wa.style.border = '1px solid #EA2027';
                    p_no_wa.innerText = 'Nomor WhatsApp sudah terdaftar';
                } else if (this.responseText.trim() === 'Profil berhasil diperbarui') {
                    window.location.href = 'start-location'; // Redirect to the user page upon successful update
                }
                bu_e_pro.style.display = 'flex';
                loading_e_pro.style.display = 'none';
            }
        };
        xhttp.open('POST', '../system/store/start.php', true);
        xhttp.send(data_edit_profile);
    }
}


