function loadMoreProducts(page) {
    console.log("Load more products request for page " + page);

    $("#loadMoreButton").html('<div class="loading-animation"></div>');

    $.ajax({
        url: window.location.pathname + '?page=' + page,
        method: 'GET',
        success: function(response) {
            console.log("AJAX response received for page " + page);
            var products = $(response).find('.box_produk').html();
            var loadMoreButton = $(response).find('#loadMoreButton').html();
            
            $(".box_produk").append(products);
            $("#loadMoreButton").html(loadMoreButton);

            // Periksa apakah tombol "Tampilkan Lebih" harus ditampilkan
            if (loadMoreButton.trim() === '') {
                $("#loadMoreButton").hide();
            } else {
                $("#loadMoreButton").show();
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error: " + error);
        }
    });
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
    xhttp.open("POST", "../../system/product/add-wishlist.php", true);
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
    xhttp.open("POST", "../../system/product/remove-wishlist.php", true);
    xhttp.send(data_remove_wishlist);
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
