function ambil_chat() {
    // AMBIL CHAT
    var data_chat = new FormData();
    data_chat.append("v_id_chat", document.getElementById('v_id_chat').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('chat_dc').innerHTML = this.responseText;
            // SCROLL BOTTOM CHAT
            var scbotchat = document.getElementById("chat_dc");
            scbotchat.scrollTop = scbotchat.scrollHeight;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open("POST", "../../system/admin/chat/list.php", true);
    xhttp.send(data_chat);
} 

function show_detail_chat(id_lawan_chat, fp_lawan_chat, name_lawan_chat, verificationStatus) {
    var width_c = window.innerWidth;
    if (width_c < 900) {
        list_chat.style.display = 'none';
        detail_chat.style.display = 'flex';
    }
    box_detail_chat.style.display = 'flex';
    box_detail_chat2.style.display = 'none';
    v_id_chat.value = id_lawan_chat;
    img_dc.src = fp_lawan_chat;
    name_dc.innerHTML = name_lawan_chat;

    // Menangani verifikasi pengguna
    if (verificationStatus == 'Ya') {
       
        // Tampilkan icon verifikasi
        verification_icon_dc.style.display = 'inline-block';
        // Lakukan sesuatu jika pengguna terverifikasi
        console.log('Pengguna terverifikasi');
    } else {
       
        // Sembunyikan icon verifikasi
        verification_icon_dc.style.display = 'none';
        // Lakukan sesuatu jika pengguna tidak terverifikasi
        console.log('Pengguna tidak terverifikasi');
    }

    ambil_chat();
}



function kirim_chat() {
    if (input_chat.value == '') {
    } else {
        var data_kirim_chat = new FormData();
        data_kirim_chat.append("v_id_chat", document.getElementById('v_id_chat').value);
        data_kirim_chat.append("input_chat", document.getElementById('input_chat').value);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
            }
            if (this.readyState == 4 && this.status == 200) {
                input_chat.value = '';
                ambil_chat();
            }
        }
        xhttp.open("POST", "../../system/admin/chat/kirim.php", true);
        xhttp.send(data_kirim_chat);
    }
}

function back_to_list_chat() {
    list_chat.style.display = 'flex';
    detail_chat.style.display = 'none';
}