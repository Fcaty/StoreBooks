$(document).ready(function () {
    loadShopBooks();
    loadShopGenres();

    // Re-fetch the (filtered) book list on every keystroke in the search box
    $('#search-bar').on('keyup', function () {
        loadShopBooks();
    });

    // Re-fetch the (filtered) book list whenever the genre dropdown changes
    $('#genre-select').on('change', function () {
        loadShopBooks();
    });

    // Delegated handler: works for Buy buttons even though rows are
    // re-rendered by loadShopBooks() after every search/filter
    $('#tBodyShop').on('click', '.buy-btn', function () {
        var btn = $(this);
        var bookId = btn.data('book-id');

        $.ajax({
            url: 'db/sales_request.php',
            method: 'POST',
            data: { 'buy_book': true, 'book_id': bookId },
            success: function (result) {
                var data = JSON.parse(result);

                if (data.success) {
                    // Update just this row's stock/sold cells and button state,
                    // no need to re-fetch the whole list
                    $('#stock-' + bookId).text(data.book_stock);
                    $('#sold-' + bookId).text(data.book_sold);

                    if (data.book_stock <= 0) {
                        btn.prop('disabled', true).text('Out of Stock');
                    }
                } else {
                    alert(data.message);
                }
            },
            error: function (err) {
                alert("Error occurred while processing your purchase.");
            }
        });
    });
});

function loadShopBooks() {
    let searchText = $('#search-bar').val();
    let selectedGenre = $('#genre-select').val();

    $.ajax({
        url: 'db/sales_request.php',
        method: 'POST',
        data: {
            'fetch_shop_books': true,
            'search_query': searchText,
            'genre': selectedGenre
        },
        success: function (result) {
            var tBody = ``;
            var datas = JSON.parse(result);

            datas.forEach(function (data) {
                var outOfStock = data.book_stock <= 0;

                tBody += `<tr>`;
                    tBody += `<td>${data.book_name}</td>`;
                    tBody += `<td>${data.book_price}</td>`;
                    tBody += `<td>${data.book_genre}</td>`;
                    tBody += `<td>${data.book_author}</td>`;
                    tBody += `<td id="stock-${data.book_id}">${data.book_stock}</td>`;
                    tBody += `<td id="sold-${data.book_id}">${data.book_sold}</td>`;
                    tBody += `<td><button class="buy-btn" data-book-id="${data.book_id}" ${outOfStock ? 'disabled' : ''}>${outOfStock ? 'Out of Stock' : 'Buy'}</button></td>`;
                tBody += `</tr>`;
            });
            $('#tBodyShop').html(tBody);
        },
        error: function (err) {
            alert("Error occurred while fetching book data.");
        }
    });
}

function loadShopGenres() {
    $.ajax({
        url: 'db/sales_request.php',
        method: 'POST',
        data: { 'fetch_shop_genres': true },
        success: function (result) {
            var selectOptions = `<option value="">All</option>`;
            var datas = JSON.parse(result);

            datas.forEach(function (data) {
                selectOptions += `<option value="${data.book_genre}">${data.book_genre}</option>`;
            });
            $('#genre-select').html(selectOptions);
        },
        error: function (err) {
            alert("Error occurred while fetching genre data.");
        }
    });
}