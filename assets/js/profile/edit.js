function click_file_img() {
    cfile_img_pro.click();
}

function change_image(event) {
    const file = document.getElementById('cfile_img_pro').files[0];
    const fileType = file['type'];
    const validImageTypes = ['image/jpeg', 'image/png'];
    const err_foto_pro = document.getElementById('err_foto_pro');

    if (!validImageTypes.includes(fileType)) {
        err_foto_pro.style.display = 'block';
        cfile_img_pro.value = '';
    } else {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('img_foto_pro');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        err_foto_pro.style.display = 'none';
    }
}

function simpan_edit_profile() {
    const p_nama_lengkap = document.getElementById('p_nama_lengkap');
    const p_email = document.getElementById('p_email');
    const p_no_wa = document.getElementById('p_no_wa');
    const nama_lengkap = document.getElementById('nama_lengkap');
    const email = document.getElementById('email');
    const no_wa = document.getElementById('no_wa');

    if (nama_lengkap.value === '') {
        p_nama_lengkap.style.color = '#EA2027';
        nama_lengkap.style.border = '1px solid #EA2027';
    } else {
        p_nama_lengkap.style.color = '#959595';
        nama_lengkap.style.border = '1px solid #e2e2e2';
    }

// Pola untuk memeriksa email
const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

if (email.value === '') {
    p_email.style.color = '#EA2027';
    email.style.border = '1px solid #EA2027';
    p_email.innerHTML = 'Email tidak boleh kosong!';
} else if (!emailPattern.test(email.value)) {
    p_email.style.color = '#EA2027';
    email.style.border = '1px solid #EA2027';
    p_email.innerHTML = 'Format email tidak valid';
} else {
    p_email.style.color = '#959595';
    email.style.border = '1px solid #e2e2e2';
    p_email.innerHTML = 'Alamat Email';
}


// Validasi nomor WhatsApp hanya angka
if (no_wa.value === '') {
    p_no_wa.style.color = '#EA2027';
    no_wa.style.border = '1px solid #EA2027';
    p_no_wa.innerHTML = 'Nomor tidak boleh kosong';
} else if (!/^\d+$/.test(no_wa.value)) {
    p_no_wa.style.color = '#EA2027';
    no_wa.style.border = '1px solid #EA2027';
    p_no_wa.innerHTML = 'Format nomor tidak valid';
} else {
    p_no_wa.style.color = '#959595';
    no_wa.style.border = '1px solid #e2e2e2';
    p_no_wa.innerHTML = 'Nomor WhatsApp';
}

// Lanjutkan dengan proses penyimpanan jika validasi lainnya telah sesuai
if (nama_lengkap.value && /^\d+$/.test(no_wa.value) && p_email.innerHTML === 'Alamat Email'
    ) {
        var data_edit_profile = new FormData();
        data_edit_profile.append('cfile_img_pro', document.getElementById('cfile_img_pro').files[0]);
        data_edit_profile.append('nama_lengkap', nama_lengkap.value);
        data_edit_profile.append('email', email.value);
        data_edit_profile.append('no_wa', no_wa.value);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
    if (this.readyState == 1) {
        bu_e_pro.style.display = 'none';
        loading_e_pro.style.display = 'flex';
    }
    if (this.readyState == 4 && this.status == 200) {
        if (this.responseText.trim() === 'Email sudah terdaftar') {
            p_email.style.color = '#EA2027';
            email.style.border = '1px solid #EA2027';
            p_email.innerHTML = 'Email sudah terdaftar';
        } else if (this.responseText.trim() === 'Nomor sudah terdaftar') {
            p_no_wa.style.color = '#EA2027';
            no_wa.style.border = '1px solid #EA2027';
            p_no_wa.innerHTML = 'Nomor sudah terdaftar';
        } else if (this.responseText.trim() === 'Profil berhasil diperbarui') {
            window.location.href = 'user'; // Redirect to the user page upon successful update
        }
        bu_e_pro.style.display = 'flex';
        loading_e_pro.style.display = 'none';
    }
};
xhttp.open('POST', '../system/profile/edit.php', true);
xhttp.send(data_edit_profile);

    }
}
