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
