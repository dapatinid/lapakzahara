masuk.onclick = function () {
    var cek_identitas = false; // Menandakan apakah identitas (email atau nama pengguna) sudah diisi
    var cek_password = false;

    var identitas = document.getElementById('email').value; // Mengambil nilai dari input email

    if (identitas === '') {
        email.style.borderColor = '#EA2027';
        p_email.style.display = 'block';
        p_email.innerHTML = 'Email atau Username Masih Kosong';
    } else {
        email.style.borderColor = '#e2e2e2';
        p_email.style.display = 'none';
        p_email.innerHTML = '';
        cek_identitas = true;
    }

    if (password.value === '') {
        password.style.borderColor = '#EA2027';
        p_password.style.display = 'block';
        p_password.innerHTML = 'Password Masih Kosong';
    } else {
        password.style.borderColor = '#e2e2e2';
        p_password.style.display = 'none';
        p_password.innerHTML = '';
        cek_password = true;
    }

    // CEK ALL FORM
    if (cek_identitas && cek_password) {
        var data_login = new FormData();
        data_login.append("identitas", identitas); // Mengirim identitas (email atau nama pengguna)
        data_login.append("password", document.getElementById('password').value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                masuk_button.style.display = 'none';
                masuk_loading.style.display = 'block';
            }
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                masuk_button.style.display = 'block';
                masuk_loading.style.display = 'none';
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        };
        xhttp.open("POST", "../system/login/login.php", true);
        xhttp.send(data_login);
    }
};


// Fungsi Reset Password
reset.onclick = function () {
    var reset_email = document.querySelector('[name="email"]').value;
    
    if (reset_email == '') {
        document.querySelector('[name="email"]').style.borderColor = '#EA2027';
        p_email.style.display = 'block';
        p_email.innerHTML = 'Email Masih Kosong';
    } else {
        document.querySelector('[name="email"]').style.borderColor = '#e2e2e2';
        p_email.style.display = 'none';
        p_email.innerHTML = '';

        // Kirim permintaan reset password ke server
        var data_reset = new FormData();
        data_reset.append("email", reset_email);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
            }
        }
        xhttp.open("POST", "../system/login/reset_password.php", true);
        xhttp.send(data_reset);
    }
}
 
