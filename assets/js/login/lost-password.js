lupa_masuk_button.onclick = function () {
    if (email.value == '') {
        email.style.borderColor = '#EA2027';
        p_email.style.display = 'block';
        p_email.innerHTML = 'Email Masih Kosong';
        var cek_email = false;
    } else {
        email.style.borderColor = '#e2e2e2';
        p_email.style.display = 'none';
        p_email.innerHTML = '';
        var cek_email = true;
    }
    // CEK ALL FORM
    if (cek_email == true) {
        var data_login = new FormData();
        data_login.append("email", document.getElementById('email').value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                lupa_masuk_button.style.display = 'none';
                masuk_loading.style.display = 'block';
            }
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                lupa_masuk_button.style.display = 'block';
                masuk_loading.style.display = 'none';
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
        xhttp.open("POST", "../system/login/lost-password.php", true);
        xhttp.send(data_login);
    }
}