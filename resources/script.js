$(document).ready(function() {
    //Function call for book info loading
    loadBookData();
    loadBookGenres();
    loadBookNames();
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
                $('#addPanelBook').fadeOut();
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
                $('#editBookForm')[0].reset();
                $('#editPanelBook').fadeOut();
                loadBookData();
            },
            error: function(err){
                alert("Error occurred while editing product data");
            }
        });
    });

    $('#editUserForm').on('submit', function(e){
        e.preventDefault();
        var datas = $(this).serializeArray();
        var data_array = {};
        $.map(datas, function(data){
            data_array[data['name']] = data['value'];
        });

        $.ajax({
            url: 'db/request.php',
            method: 'POST',
            data: {
                'update_user': true,
                ...data_array,
            },
            success: function(result){
                $('#editUserForm')[0].reset();
                $('#editPanelUser').fadeOut();
                loadUserData();
            },
            error: function(err){
                alert("Error occured while editing user data");
            }
        })
    })

    $('#deleteBookForm').on('submit', function(e){
        e.preventDefault();
        var datas = $(this).serializeArray();
        var data_array = {};
        $.map(datas, function(data){
            data_array[data['name']] = data['value'];
        });

        $.ajax({
            url: 'db/request.php',
            method: 'POST',
            data: {
                'delete_book': true,
                ...data_array,
            },
            success: function(result){
                $('#deleteBookForm')[0].reset();
                $('#deletePanelBook').fadeOut();
                loadBookData();
                loadBookNames();
            }
        })
    })
    
    $('#deleteUserForm').on('submit', function(e){
        e.preventDefault();
        var datas = $(this).serializeArray();
        var data_array = {};
        $.map(datas, function(data){
            data_array[data['name']] = data['value'];
        });

        $.ajax({
            url: 'db/request.php',
            method: 'POST',
            data: {
                'delete_user': true,
                ...data_array,
            },
            success: function(result){
                $('#deleteUserForm')[0].reset();
                $('deletePanelUser').fadeOut();
                loadUserData();
            },
            error: function(err){
                alert("Error occured while deleting user data");
            }
        })
    })

    //Event Listener for Search
    $('#search-bar').on('keyup', function() {
        loadBookData();
    });

    $('#genre-select').on('change', function(){
        loadBookData();
    });

    $('#search-bar-bookselect').on('keyup', function(){
        loadBookNames();
    });

    $('#book-select').on('change', function(){
        loadOrderData();
    })

    $(document).on('click', '#add-btn-book', function(){
        $('#addPanelBook').fadeIn();
    })

    $(document).on('click', '.edit-btn-book', function(){
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

        $('#editPanelBook').fadeIn();
    });

    $(document).on('click', '.edit-btn-user', function(){
        let id = $(this).data('id');
        let name = $(this).data('name');
        let role = $(this).data('role');
        let email = $(this).data('email');

        $('#edit_user_id').val(id);
        $('#edit_user_name').val(name);
        $('#edit_user_role').val(role);
        $('#edit_user_email').val(email);

        $('#editPanelUser').fadeIn();
    });

    $(document).on('click', '.delete-btn-user', function(){
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#delete_user_id').val(id);

        var confText = `Are you sure you would like to delete user ${name}?`
        $('#delete-confirmationuser-text').html(confText);
        $('#deletePanelUser').fadeIn();
    });

    $(document).on('click', '.delete-btn-book', function(){
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#delete_book_id').val(id);

        var confText = `Are you sure you would like to delete book ${name}?`
        $('#delete-confirmationbook-text').html(confText);
        $('#deletePanelBook').fadeIn();
    })

        $('#closeAddBookPanelBtn').on('click', function(){
        $('#addPanelBook').fadeOut();
    })

        $('#closeBookPanelBtn').on('click', function(){
        $('#editPanelBook').fadeOut();
    });

        $('#closeUserPanelBtn').on('click', function(){
        $('#editPanelUser').fadeOut();
    });

        $('#closeDeleteBookPanelBtn').on('click', function(){
        $('#deletePanelBook').fadeOut();
    })

        $('#closeDeleteUserPanelBtn').on('click', function(){
        $('#deletePanelUser').fadeOut();
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
                        tBody += `<td> <button class = "edit-btn-book"
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
                        tBody += `<td> <button class = "delete-btn-book"
                                                data-id = "${data.book_id}"
                                                data-name = "${data.book_name}">
                                                Delete
                                                </button> </td>`
                    tBody += `</tr>`
                });
                $('#tBodyBooks').html(tBody);
            },
            error: function(err){
                alert("Error occured while fetching product data.")
            }
        });
    }

    //Code for loading book genres 
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

    //Code for loading book names
function loadBookNames(){
    let searchText = $('#search-bar-bookselect').val();

    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: {'fetch_book_names': true,
               'search_book_query': searchText},
        success: function(result){
            var selectOptions = `<option value = ""> None </option>`;
            var datas = JSON.parse(result);

            datas.forEach(function(data){
                selectOptions += `<option value = "${data.book_id}"> ${data.book_name} ID: ${data.book_id}</option>`
            });
            $('#book-select').html(selectOptions);
        },
        error: function(err){
            alert("Error occured while fetching genre data.");
        }
    });
}

    //Code for loading all user data
function loadUserData(){
    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: {'fetch_users': true},
        success: function(result){
            var tBody = ``;
            var datas = JSON.parse(result);

            datas.forEach(function(data){
                tBody += `<tr>`;
                    tBody += `<td>${data.user_id}</td>`
                    tBody += `<td>${data.user_name}</td>`
                    tBody += `<td>${data.user_role}</td>`
                    tBody += `<td>${data.user_email}</td>`
                    tBody += `<td> <button class = "edit-btn-user"
                                           data-id = "${data.user_id}"
                                           data-name = "${data.user_name}"
                                           data-role = "${data.user_role}"
                                           data-email = "${data.user_email}">
                                           Edit
                                           </button> 
                              </td>`
                    tBody += `<td> <button class = "delete-btn-user"
                                    data-id = "${data.user_id}"
                                    data-name = "${data.user_name}"
                                    >
                                    Delete
                                    </button> </td>`
                tBody += `</tr>`;
            });
            $('#tBodyUsers').html(tBody);
        },
        error: function(err){
            alert("Error occured while fetching user data.");
        }
    });
}

function loadOrderData(){
    let selectedBook = $('#book-select').val();

    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: {'fetch_orders': true,
               'selected_book': selectedBook
        },
        success: function(result){
            var tBody = ``;
            var bookStat = ``;
            var datas = JSON.parse(result);
            var total_price = 0;
            var total_orders = 0;

            datas.forEach(function(data){
                tBody += `<tr>`;
                    tBody += `<td> ${data.order_id} </td>`
                    tBody += `<td> ${data.user_id} </td>`
                    tBody += `<td> ${data.user_name} </td>`
                    tBody += `<td> ${data.order_date} </td>`
                tBody += `</tr>`
                
                total_price += parseFloat(data.book_price);
                total_orders++;
            });
            $('#tBodyOrders').html(tBody);

            bookStat += `<p> Total Orders: ${total_orders} </p>
                        <p> Total Price: ${total_price} </p>`
        
            $('#book-order-statistics').html(bookStat);
        }
    })
}