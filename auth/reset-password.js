document.getElementById("resetButton").addEventListener("click", function () {
    var newPassword = document.getElementById('newPassword').value;
    var token = document.querySelector('input[name="token"]').value;
    var p_newPassword = document.getElementById('p_newPassword');
    var newPasswordInput = document.getElementById('newPassword');

    if (newPassword === '') {
        p_newPassword.style.color = '#EA2027'; // Ubah warna teks pesan
        p_newPassword.style.display = 'block'; // Tampilkan pesan
        p_newPassword.innerHTML = 'Password tidak boleh kosong!'; // Isi pesan

        newPasswordInput.style.borderColor = '#EA2027'; // Ubah warna border input

        return;
    }

    if (newPassword.length < 6) {
        p_newPassword.style.color = '#EA2027';
        p_newPassword.style.display = 'block';
        p_newPassword.innerHTML = 'Password harus minimal 6 karakter!';
        
        newPasswordInput.style.borderColor = '#EA2027';

        return;
    }

    if (newPassword.indexOf(' ') >= 0) {
        p_newPassword.style.color = '#EA2027';
        p_newPassword.style.display = 'block';
        p_newPassword.innerHTML = 'Password tidak boleh mengandung spasi!';
        
        newPasswordInput.style.borderColor = '#EA2027';

        return;
    }

    var dataReset = new URLSearchParams();
    dataReset.append("newPassword", newPassword);
    dataReset.append("token", token);

    var requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: dataReset
    };

    document.getElementById("resetButton").style.display = 'none';
    document.getElementById("resetLoading").style.display = 'block';

    fetch('../system/login/proses-reset-password.php', requestOptions)
        .then(response => {
            if (response.ok) {
                return response.text();
            }
            throw new Error('Terjadi kesalahan, coba lagi nanti.');
        })
        .then(data => {
            document.getElementById("res").innerHTML = data;
            document.getElementById("resetButton").style.display = 'block';
            document.getElementById("resetLoading").style.display = 'none';

            // Menampilkan notifikasi setelah berhasil
            document.getElementById("resetPasswordForm").style.display = 'none';
            document.getElementById("resetPasswordForm1").style.display = 'block';
        })
        .catch(error => {
            document.getElementById("res").innerHTML = error.message;
            document.getElementById("resetButton").style.display = 'block';
            document.getElementById("resetLoading").style.display = 'none';
        });
});
