// Fungsi untuk menghitung dan memperbarui total harga
function updateTotalHarga() {
    var totalHarga = 0; // Inisialisasi total harga

    // Ambil semua elemen dengan class 'box_total_harga' (mengandung harga produk)
    var hargaProdukElements = document.querySelectorAll('.box_total_harga');

    // Loop melalui setiap elemen harga produk dan tambahkan ke total harga
    hargaProdukElements.forEach(function (elem) {
        var hargaProduk = parseFloat(elem.getAttribute('data-harga-produk'));
        if (!isNaN(hargaProduk)) {
            totalHarga += hargaProduk;
        }
    });

    // Perbarui elemen HTML dengan ID 'total-harga'
    var totalHargaElement = document.getElementById('total-harga');
    totalHargaElement.innerHTML = '<span>Rp</span> ' + totalHarga.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // Di sini, kita menggunakan toFixed(0) untuk membulatkan total harga menjadi bilangan bulat
}

// Panggil fungsi updateTotalHarga() setelah halaman dimuat
window.onload = function () {
    updateTotalHarga();
};

// Fungsi checkout
function checkout(id_usr_cko, id_product_cko) {
    // ... Kode checkout yang sudah ada ...

    // Setelah checkout, perbarui total harga
    updateTotalHarga();
}

// Fungsi removecart
function removecart(id_product) {
    // ... Kode removecart yang sudah ada ...

    // Setelah menghapus barang, perbarui total harga
    updateTotalHarga();
}


// Fungsi checkout
function checkout(id_usr_cko, id_product_cko) {
    var btn_cko = 'button_checkout' + id_usr_cko;
    var load_cko = 'loading_checkout' + id_usr_cko;

    console.log(id_product_cko);

    var data_checkout_cart = new FormData();
    data_checkout_cart.append('id_product', id_product_cko);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(btn_cko).style.display = 'none';
            document.getElementById(load_cko).style.display = 'flex';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            document.getElementById(btn_cko).style.display = 'flex';
            document.getElementById(load_cko).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
            }

            // Perbarui total harga setelah checkout
            updateTotalHarga();
        }
    };
    xhttp.open("POST", "../system/cart/checkout-multi.php", true);
    xhttp.send(data_checkout_cart);
}


// Fungsi removecart
function removecart(id_product, warna_value, ukuran_value) {
    var r_isi_cart = 'isi_cart' + id_product;
    var r_icon_cart = 'icon_remove_cart' + id_product;
    var r_loading_cart = 'loading_remove_cart' + id_product;

    var data_remove_cart = new FormData();
    data_remove_cart.append('id_product', id_product);
    data_remove_cart.append('page_product', 'cart');
    data_remove_cart.append('warna_value', warna_value);
    data_remove_cart.append('ukuran_value', ukuran_value);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            document.getElementById(r_icon_cart).style.display = 'none';
            document.getElementById(r_loading_cart).style.display = 'block';
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            document.getElementById(r_icon_cart).style.display = 'block';
            document.getElementById(r_loading_cart).style.display = 'none';
            var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
    xhttp.open("POST", "../system/cart/remove.php", true);
    xhttp.send(data_remove_cart);
}

