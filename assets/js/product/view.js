window.onload = function () {

}

$('#slider_foto_produk').owlCarousel({
    items: 1,
    loop: true,
    margin: 10,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true
})

$('#slider_produk_serupa').owlCarousel({
    loop: true,
    margin: 25,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    responsiveClass: true,
    responsive: {
        0: {
            items: 2,
            margin: 10,
            nav: false
        },
        700: {
            items: 3,
            margin: 10,
            nav: false
        },
        1000: {
            items: 4,
            nav: false,
        }
    }
})

$('.owl-carousel').owlCarousel({
    items: 1,
    loop: true,
    margin: 10,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true
})

// Tambahkan Magnific Popup untuk galeri produk
  $('.foto_product a').magnificPopup({
    type: 'image',
    gallery: {
      enabled: true // Aktifkan mode galeri untuk menavigasi gambar-gambar
    }
  });

function rubah(angka) {
    var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return ribuan;
}

function kurang() {
    if (jumlah_produk.value > 1) {
        var kurang_jumlah = parseInt(jumlah_produk.value) - parseInt(1);
        jumlah_produk.value = kurang_jumlah;
        var ukuran_harga_value_id = ukuran_harga_satuan_value.value * kurang_jumlah;
        ukuran_harga_value.value = ukuran_harga_value_id;
        harga_varian_produk.innerHTML = rubah(ukuran_harga_value_id);
    }
}

function tambah(max_jumlah) {
    if (jumlah_produk.value < max_jumlah) {
        var tambah_jumlah = parseInt(jumlah_produk.value) + parseInt(1);
        jumlah_produk.value = tambah_jumlah;
        var ukuran_harga_value_id = ukuran_harga_satuan_value.value * tambah_jumlah;
        ukuran_harga_value.value = ukuran_harga_value_id;
        harga_varian_produk.innerHTML = rubah(ukuran_harga_value_id);
    }
}

function addcart(idproduk) {
    var data_add_cart = new FormData();
    data_add_cart.append("idproduk", idproduk);
    data_add_cart.append("jumlah_produk", document.getElementById('jumlah_produk').value);
    data_add_cart.append("warna_value", document.getElementById('warna_value').value);
    data_add_cart.append("ukuran_value", document.getElementById('ukuran_value').value);
    data_add_cart.append("ukuran_harga_satuan_value_send", document.getElementById('ukuran_harga_satuan_value_send').value);
    data_add_cart.append("id_lokasi", document.getElementById('id_lokasi_value').value);
    data_add_cart.append("lokasi", document.getElementById('lokasi_value').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            masukan_keranjang.style.display = 'none';
            loading_masukan_keranjang.style.display = 'flex';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        } 
    }
    xhttp.open("POST", "../../system/cart/add.php", true);
    xhttp.send(data_add_cart);
}

function removecart(idproduk) {
    console.log(idproduk);
    var data_remove_cart = new FormData();
    data_remove_cart.append('id_product', idproduk);
    data_remove_cart.append('page_product', 'produk view');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            masukan_keranjang2.style.display = 'none';
            loading_keranjang.style.display = 'flex';
            hapus_keranjang.style.display = 'none';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open("POST", "../../system/cart/remove.php", true);
    xhttp.send(data_remove_cart);
}

function checkout(idproduk) {
    var data_checkout_cart = new FormData();
    data_checkout_cart.append('id_product', idproduk);
    data_checkout_cart.append('jumlah_product', document.getElementById('jumlah_produk').value);
    data_checkout_cart.append('ukuran_harga_satuan_value_send', document.getElementById('ukuran_harga_satuan_value_send').value);
    data_checkout_cart.append('warna_k_val', document.getElementById('warna_value').value);
    data_checkout_cart.append('ukuran_k_val', document.getElementById('ukuran_value').value);
    data_checkout_cart.append('id_lokasi', document.getElementById('id_lokasi_value').value);
    data_checkout_cart.append('lokasi', document.getElementById('lokasi_value').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            beli_sekarang.style.display = 'none';
            loading_beli_sekarang.style.display = 'flex';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            back_varian_produk.style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open("POST", "../../system/cart/checkout.php", true);
    xhttp.send(data_checkout_cart);
}
 
function click_varian_warna(key_warna_vp, value_warna_vp) {
    x_warna_vp = document.querySelectorAll(".c_id_varian_warna");
    for (i_x_warna_vp = 0; i_x_warna_vp < x_warna_vp.length; i_x_warna_vp++) {
        x_warna_vp[i_x_warna_vp].className = 'isi_box_select_varian c_id_varian_warna';
    }

    var add_id_varian_warna = 'id_varian_warna' + key_warna_vp;
    document.getElementById(add_id_varian_warna).className = 'isi_box_select_varian_active c_id_varian_warna';
    warna_value.value = value_warna_vp;
}

function click_varian_ukuran(key_ukuran_vp, value_ukuran_vp, value_harga_ukuran_vp, value_harga_ukuran_vp_send) {
    x_ukuran_vp = document.querySelectorAll(".c_id_varian_ukuran");
    for (i_x_ukuran_vp = 0; i_x_ukuran_vp < x_ukuran_vp.length; i_x_ukuran_vp++) {
        x_ukuran_vp[i_x_ukuran_vp].className = 'isi_box_select_varian c_id_varian_ukuran';
    }
 
    var add_id_varian_ukuran = 'id_varian_ukuran' + key_ukuran_vp;
    document.getElementById(add_id_varian_ukuran).className = 'isi_box_select_varian_active c_id_varian_ukuran';
    ukuran_value.value = value_ukuran_vp;
    var v_jumlah_produk = jumlah_produk.value;
    var ukuran_harga_value_var = value_harga_ukuran_vp * v_jumlah_produk;
    ukuran_harga_value.value = ukuran_harga_value_var;
    ukuran_harga_satuan_value.value = value_harga_ukuran_vp;
    harga_varian_produk.innerHTML = rubah(ukuran_harga_value_var);
    ukuran_harga_satuan_value_send.value = value_harga_ukuran_vp_send;
}

function click_varian_lokasi(key_lokasi_vp, value_lokasi_vp, value_harga_lokasi_vp, value_harga_lokasi_vp_send) {
    x_lokasi_vp = document.querySelectorAll(".c_id_varian_lokasi");
    for (i_x_lokasi_vp = 0; i_x_lokasi_vp < x_lokasi_vp.length; i_x_lokasi_vp++) {
        x_lokasi_vp[i_x_lokasi_vp].className = 'isi_box_select_varian c_id_varian_lokasi';
    }

    var add_id_varian_lokasi = 'id_varian_lokasi' + key_lokasi_vp;
    document.getElementById(add_id_varian_lokasi).className = 'isi_box_select_varian_active c_id_varian_lokasi';
    lokasi_value.value = value_lokasi_vp;
    var v_jumlah_produk = jumlah_produk.value;
    var lokasi_harga_value_var = value_harga_lokasi_vp * v_jumlah_produk;
    lokasi_harga_value.value = lokasi_harga_value_var;
    lokasi_harga_satuan_value.value = value_harga_lokasi_vp;
    harga_varian_produk.innerHTML = rubah(lokasi_harga_value_var);
    lokasi_harga_satuan_value_send.value = value_harga_lokasi_vp_send;
}

function view_addcart() {
    buvar_masukkan_keranjang.style.display = 'block';
    buvar_beli_sekarang.style.display = 'none';
    back_varian_produk.style.display = 'flex';
}

function view_checkout() {
    buvar_masukkan_keranjang.style.display = 'none';
    buvar_beli_sekarang.style.display = 'block';
    back_varian_produk.style.display = 'flex';
}

function close_back_varian_produk() {
    buvar_masukkan_keranjang.style.display = 'none';
    buvar_beli_sekarang.style.display = 'none';
    back_varian_produk.style.display = 'none';
}

function add_wishlist(id_produk_aw) {
    wishlist_active.className = 'ri-heart-3-fill wishlist_active_an';
    wishlist_nonactive.className = 'ri-heart-3-fill wishlist_nonactive wishlist_hidden';
    var data_add_wishlist = new FormData();
    data_add_wishlist.append('id_product', id_produk_aw);
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
    xhttp.open("POST", "../../system/product/add-wishlist.php", true);
    xhttp.send(data_add_wishlist);
}

function remove_wishlist(id_produk_rw) {
    wishlist_active.className = 'ri-heart-3-fill wishlist_active wishlist_hidden';
    wishlist_nonactive.className = 'ri-heart-3-fill wishlist_nonactive';
    var data_remove_wishlist = new FormData();
    data_remove_wishlist.append('id_product', id_produk_rw);
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
    xhttp.open("POST", "../../system/product/remove-wishlist.php", true);
    xhttp.send(data_remove_wishlist);
}


function add_w4ishlist(related_product_id) {
    var w4ishlist_active = document.getElementById('w4ishlist_active_' + related_product_id);
    var w4ishlist_nonactive = document.getElementById('w4ishlist_nonactive_' + related_product_id);
    w4ishlist_active.className = 'ri-heart-3-fill w4ishlist_active_an';
    w4ishlist_nonactive.className = 'ri-heart-3-fill w4ishlist_nonactive w4ishlist_hidden';
    var data_add_w4ishlist = new FormData();
    data_add_w4ishlist.append('id_product', related_product_id);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {}
        if (this.readyState == 4 && this.status == 200) {
            // Jika Anda memiliki elemen dengan ID 'res' untuk menampilkan respons, Anda bisa mengaktifkan kembali bagian ini
            // document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open("POST", "../../system/product/add-wishlist.php", true);
    xhttp.send(data_add_w4ishlist);
}

function remove_w4ishlist(related_product_id) {
    var w4ishlist_active = document.getElementById('w4ishlist_active_' + related_product_id);
    var w4ishlist_nonactive = document.getElementById('w4ishlist_nonactive_' + related_product_id);
    w4ishlist_active.className = 'ri-heart-3-fill w4ishlist_active w4ishlist_hidden';
    w4ishlist_nonactive.className = 'ri-heart-3-fill w4ishlist_nonactive';
    var data_remove_w4ishlist = new FormData();
    data_remove_w4ishlist.append('id_product', related_product_id);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {}
        if (this.readyState == 4 && this.status == 200) {
            // Jika Anda memiliki elemen dengan ID 'res' untuk menampilkan respons, Anda bisa mengaktifkan kembali bagian ini
            // document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }
    xhttp.open("POST", "../../system/product/remove-wishlist.php", true);
    xhttp.send(data_remove_w4ishlist);
}




function belum_login_klik_beli() {
    window.location.href = '../../login/';
}

function belum_login_klik_chat() {
    window.location.href = '../../login/';
}

// Fungsi copy_link_produk
function copy_link_produk(slug, productId) {
    var link_produk_copy = window.location.origin + slug;  // Menambahkan origin (domain) ke slug
    var cbr = navigator.clipboard.writeText(link_produk_copy);

    if (cbr) {
        var ico_copy_p = document.getElementById('ico_copy_p_' + productId);
        var ico_selesai_copy_p = document.getElementById('ico_selesai_copy_p_' + productId);

        ico_copy_p.style.display = 'none';
        ico_selesai_copy_p.style.display = 'block';

        setTimeout(() => {
            ico_copy_p.style.display = 'block';
            ico_selesai_copy_p.style.display = 'none';
        }, 2000);
    }
}



function close_share_produk() {
    back_share_produk.style.display = 'none';
}

function show_share_produk() {
    back_share_produk.style.display = 'flex';
}

 function ubahcatatan() {
    back_catatan.style.display = 'flex';
 }
 
function handleOptionClick(event) {
    const optionsList = document.getElementById('alasanLaporkan').getElementsByTagName('li');

    for (const option of optionsList) {
        option.classList.remove('selected');
    }

    const selectedOption = event.target;
    selectedOption.classList.add('selected');

    // Mengambil nilai dari opsi yang dipilih
    const selectedValue = selectedOption.getAttribute('value');

    // Menambahkan nilai ke textarea
    const textarea = document.getElementById('deskripsiMasalah');
    if (selectedValue === 'lainnya') {
        textarea.style.display = 'block';
        textarea.value = ''; // Bersihkan textarea jika opsi "lainnya" dipilih
    } else {
        textarea.style.display = 'none';
        textarea.value = selectedValue;
    }
}


function simpan_catatan(idproduct) {
    // Get the selected option
    const selectedOption = document.getElementById('alasanLaporkan').querySelector('.selected');

    // Get the value and text content of the selected option
    const alasanLaporkan = selectedOption ? selectedOption.getAttribute('value') : '';
    const deskripsiMasalah = document.getElementById('deskripsiMasalah').value;

    var data_scat = new FormData();
    data_scat.append('idproduct', idproduct);
    data_scat.append('alasanLaporkan', alasanLaporkan);

    if (alasanLaporkan === 'lainnya') {
        data_scat.append('deskripsiMasalah', deskripsiMasalah);
    } else {
        data_scat.append('deskripsiMasalah', ''); // Kosongkan nilai jika bukan opsi "Lainnya"
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            p_butacat.style.display = 'none';
            load_butacat.style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }
        }
    }

    xhttp.open("POST", "../../system/product/laporkan-produk.php", true);
    xhttp.send(data_scat);
}



 
function BatalLcatatan() {
    document.getElementById('back_catatan').style.display = 'none';
}

