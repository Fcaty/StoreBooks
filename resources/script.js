$(document).ready(function() {
    //Function call for book info loading
    loadBookData();
    loadBookGenres();

    //Event listener for submit button event listener
    $('#addBookForm').on('submit', function(e) {
    e.preventDefault();
    var datas = $(this).serializeArray();
    var data_array = {};
    $.map(datas, function(data){
        data_array[data['name']]=data['value'];
     });

        $.ajax({
            url: 'db/request.php',
            method: 'POST',
            data: {
                'add_book': true,
                ...data_array,
            },
            success: function(result){
                $('#addBookForm')[0].reset();
                loadBookData();
            },
            error: function(err){
                alert("Error occurred while adding product data");
            }
        });
    });

    //Event Listener for Search
    $('#search-bar').on('keyup', function() {
        loadBookData(keyword);
    });

    $('#genre-select').on('change', function(){
        loadBookData();
    });
});

//Function code for loading book data
function loadBookData() {

        let searchText = $('#search-bar').val();
        let selectedGenre = $('#genre-select').val();

        $.ajax({
            url: 'db/request.php',
            method: 'POST',
            data: { 'fetch_books': true,
                    'search_query': searchText,
                    'genre': selectedGenre
            },
            success: function(result) {
                var tBody = ``;
                var cnt = 1;
                var datas = JSON.parse(result);

                datas.forEach(function(data){
                    tBody += `<tr>`;
                        tBody += `<td> ${cnt++}</td>`
                        tBody += `<td> ${data.book_name}</td>`
                        tBody += `<td> ${data.book_price}</td>`
                        tBody += `<td> ${data.book_genre}</td>`
                        tBody += `<td> ${data.book_author}</td>`
                        tBody += `<td> ${data.book_stock}</td>`
                        tBody += `<td> ${data.book_sold}</td>`
                    tBody += `</tr>`
                });
                $('#tBodyBooks').html(tBody);
            },
            error: function(err){
                alert("Error occured while fetching product data.")
            }
        });
    }

function loadBookGenres(){
    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: { 'fetch_genres': true },
        success: function(result){
            var selectOptions = `<option value = ""> All </option>`;
            var datas = JSON.parse(result);

            datas.forEach(function(data){
                selectOptions += `<option value = "${data.book_genre}"> ${data.book_genre}</option>`
            });
            $('#genre-select').html(selectOptions);
        },
        error: function(err){
            alert("Error occured while fetching genre data.");
        }
    });
}