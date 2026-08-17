$(document).ready(function() {
    //Function call for book info loading
    loadBookData();
    loadBookGenres();
    loadUserData();

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

    $('#editBookForm').on('submit', function(e){
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
                'update_book':true,
                ...data_array,
            },
            success: function(result){
                console.log(result);
                //$('#editBookForm')[0].reset();
                //loadBookData();
            },
            error: function(err){
                alert("Error occurred while editing product data");
            }
        });
    });


    //Event Listener for Search
    $('#search-bar').on('keyup', function() {
        loadBookData();
    });

    $('#genre-select').on('change', function(){
        loadBookData();
    });

    $(document).on('click', '.edit-btn', function(){
        let id = $(this).data('id');
        let name = $(this).data('name');
        let price = $(this).data('price');
        let genre = $(this).data('genre');
        let author = $(this).data('author');
        let stock = $(this).data('stock');
        let sold = $(this).data('sold');

        $('#edit_book_id').val(id);
        $('#edit_book_name').val(name);
        $('#edit_book_price').val(price);
        $('#edit_book_genre').val(genre);
        $('#edit_book_author').val(author);
        $('#edit_book_stock').val(stock);
        $('#edit_book_sold').val(sold);

        $('#editPanel').fadeIn();
    });

    $('#closePanelBtn').on('click', function(){
        $('#editPanel').fadeOut();
    });

    $('.panel-overlay').on('click', function(e) {
        if(e.target === this){
            $(this).fadeOut();
        }
    })
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
                var datas = JSON.parse(result);

                datas.forEach(function(data){
                    tBody += `<tr>`;
                        tBody += `<td> ${data.book_id}</td>`
                        tBody += `<td> ${data.book_name}</td>`
                        tBody += `<td> ${data.book_price}</td>`
                        tBody += `<td> ${data.book_genre}</td>`
                        tBody += `<td> ${data.book_author}</td>`
                        tBody += `<td> ${data.book_stock}</td>`
                        tBody += `<td> ${data.book_sold}</td>`
                        tBody += `<td> <button class = "edit-btn"
                                               data-id= "${data.book_id}"
                                               data-name= "${data.book_name}"
                                               data-price= "${data.book_price}"
                                               data-genre= "${data.book_genre}"
                                               data-author="${data.book_author}"
                                               data-stock= "${data.book_stock}"
                                               data-sold= "${data.book_sold}">
                                               Edit
                                               </button> 
                                  </td>`;
                        tBody += `<td> <button>Delete</button> </td>`
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

function loadUserData(){
    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: {fetch_users: true},
        success: function(result){
            var tBody = ``;
            var datas = JSON.parse(result);

            datas.forEach(function(data){
                tBody += `<tr>`;
                    tBody += `<td>${data.user_id}</td>`
                    tBody += `<td>${data.user_name}</td>`
                    tBody += `<td>${data.user_role}</td>`
                    tBody += `<td>${data.user_email}</td>`
                    tBody += `<td> <button>Edit</button> </td>`
                    tBody += `<td> <button>Delete</button> </td>`
                tBody += `</tr>`;
            });
            $('#tBodyUsers').html(tBody);
        },
        error: function(err){
            alert("Error occured while fetching user data.");
        }
    });
}

