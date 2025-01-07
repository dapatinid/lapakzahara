$('.owl-carousel').owlCarousel({
    items: 1,
    loop: true,
    margin: 15,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true
})
function makeTimer() {
    var time_count_flash_sale_v = document.getElementById('time_count_flash_sale').value;
    var endTime = new Date(time_count_flash_sale_v);
    endTime = (Date.parse(endTime) / 1000);

    var now = new Date();
    now = (Date.parse(now) / 1000);

    var timeLeft = endTime - now;

    var days = Math.floor(timeLeft / 86400);
    var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
    var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
    var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

    if (hours < "10") { hours = "0" + hours; }
    if (minutes < "10") { minutes = "0" + minutes; }
    if (seconds < "10") { seconds = "0" + seconds; }

    $("#days").html(days);
    $("#hours").html(hours);
    $("#minutes").html(minutes);
    $("#seconds").html(seconds);

    if (days == 0) {
        document.getElementById('days').style.display = 'none';
        document.getElementById('td_days').style.display = 'none';
    }

} 
setInterval(function () { makeTimer(); }, 1000);

function close_promo() {
    promo_home.style.display = 'none';
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
        }
        if (this.readyState == 4 && this.status == 200) {
        }
    }
    xhttp.open("POST", "system/home/promo.php", true);
    xhttp.send();
}

function add_wishlist(id_produk_aw) {
    var wishlist_active = document.getElementById('wishlist_active_' + id_produk_aw);
    var wishlist_nonactive = document.getElementById('wishlist_nonactive_' + id_produk_aw);
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
    xhttp.open("POST", "./../system/product/add-wishlist.php", true);
    xhttp.send(data_add_wishlist);
}
 
function remove_wishlist(id_produk_rw) {
    var wishlist_active = document.getElementById('wishlist_active_' + id_produk_rw);
    var wishlist_nonactive = document.getElementById('wishlist_nonactive_' + id_produk_rw);
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
    xhttp.open("POST", "./../system/product/remove-wishlist.php", true);
    xhttp.send(data_remove_wishlist);
}


function add_w3ishlist(id_produk_aw) {
    var w3ishlist_active = document.getElementById('w3ishlist_active_' + id_produk_aw);
    var w3ishlist_nonactive = document.getElementById('w3ishlist_nonactive_' + id_produk_aw);
    w3ishlist_active.className = 'ri-heart-3-fill w3ishlist_active_an';
    w3ishlist_nonactive.className = 'ri-heart-3-fill w3ishlist_nonactive w3ishlist_hidden';
    var data_add_w3ishlist = new FormData();
    data_add_w3ishlist.append('id_product', id_produk_aw);
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
    xhttp.open("POST", "./../system/product/add-wishlist.php", true);
    xhttp.send(data_add_w3ishlist);
}
 
function remove_w3ishlist(id_produk_rw) {
    var w3ishlist_active = document.getElementById('w3ishlist_active_' + id_produk_rw);
    var w3ishlist_nonactive = document.getElementById('w3ishlist_nonactive_' + id_produk_rw);
    w3ishlist_active.className = 'ri-heart-3-fill w3ishlist_active w3ishlist_hidden';
    w3ishlist_nonactive.className = 'ri-heart-3-fill w3ishlist_nonactive';
    var data_remove_w3ishlist = new FormData();
    data_remove_w3ishlist.append('id_product', id_produk_rw);
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
    xhttp.open("POST", "./../system/product/remove-wishlist.php", true);
    xhttp.send(data_remove_w3ishlist);
}

function add_w4ishlist(id_produk_aw) {
    var w4ishlist_active = document.getElementById('w4ishlist_active_' + id_produk_aw);
    var w4ishlist_nonactive = document.getElementById('w4ishlist_nonactive_' + id_produk_aw);
    w4ishlist_active.className = 'ri-heart-3-fill w4ishlist_active_an';
    w4ishlist_nonactive.className = 'ri-heart-3-fill w4ishlist_nonactive w4ishlist_hidden';
    var data_add_w4ishlist = new FormData();
    data_add_w4ishlist.append('id_product', id_produk_aw);
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
    xhttp.open("POST", "./../system/product/add-wishlist.php", true);
    xhttp.send(data_add_w4ishlist);
}
 
function remove_w4ishlist(id_produk_rw) {
    var w4ishlist_active = document.getElementById('w4ishlist_active_' + id_produk_rw);
    var w4ishlist_nonactive = document.getElementById('w4ishlist_nonactive_' + id_produk_rw);
    w4ishlist_active.className = 'ri-heart-3-fill w4ishlist_active w4ishlist_hidden';
    w4ishlist_nonactive.className = 'ri-heart-3-fill w4ishlist_nonactive';
    var data_remove_w4ishlist = new FormData();
    data_remove_w4ishlist.append('id_product', id_produk_rw);
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
    xhttp.open("POST", "./../system/product/remove-wishlist.php", true);
    xhttp.send(data_remove_w4ishlist);
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


function close_share_produk(productId) {
        var back_share_produk = document.getElementById('back_share_produk_' + productId);
        if (back_share_produk) {
            back_share_produk.style.display = 'none';
        }
    }

function show_share_produk(productId) {
    var back_share_produk = document.getElementById('back_share_produk_' + productId);
    if (back_share_produk) {
        back_share_produk.style.display = 'flex';
    }
}


