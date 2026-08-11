function renderBooks(result) {
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
}

function loadBookData() {
    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: { 'fetch_books': true },
        success: function(result) {
            renderBooks(result);
        },
        error: function(err) {
            alert("Error occurred while fetching book data.");
        }
    });
}

$(document).ready(function() {
    loadBookData();

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
});

$('#filterButton').on('click', function() {
    $.ajax({
        url: 'db/request.php',
        method: 'POST',
        data: { 
            'filter_books': true,
            'genre': $('#filterGenre').val(),
            'author': $('#filterAuthor').val()},
        success: function(result) {
            renderBooks(result);
        },
        error: function(err) {
            alert("Error occurred while filtering books.");
        }
    });
});

$('#clearFilterButton').on('click', function() {
    $('#filterGenre').val('');
    $('#filterAuthor').val('');
    loadBookData();
});



