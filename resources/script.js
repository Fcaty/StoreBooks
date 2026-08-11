$(document).ready(function() {
    loadBookData();

    $('#addBookForm').on('submit', function(e) {
    e.preventDefault();
    var datas = $(this).serializeArray();
    var data_array = {};
    $.map(datas, function(data){
        data_array[data['name']]=data['value'];
     });

    console.log(datas);
    console.log(data_array);

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

function loadBookData() {
        $.ajax({
            url: 'db/request.php',
            method: 'POST',
            data: { 'fetch_books': true},
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