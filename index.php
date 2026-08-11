<?php

    require "db/db.php";
    $database = new Database;

    $database->select('books', '*');
    $book_data = $database->res;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type = "text/javascript" src= "resources/jquery.min.js"></script>
    <title>Starbooks</title>
</head>
<body>
    <header>
        <h1>StarBooks: Providing All Your Reading Needs</h1>
        <nav>
        </nav>
    </header>

    <main>
        <h2>Add New Book Information</h2>
        <form id = "addBookForm" method = "POST" action = "db/request.php">
            <input type = "text" name = "book_name" id = "name" placeholder = "Book Name">
            <input type = "number" name = "book_price" id = "price" placeholder = "Book Price">
            <input type = "text" name = "book_genre" id = "genre" placeholder = "Book Genre">
            <input type = "text" name = "book_author" id = "author" placeholder = "Book Author">
            <button type = "submit" name = "add_book">Submit</button>
        </form>
        <h2>Book Information</h2>
        <table>
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Price</th>
                    <th>Genre</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody id = "tBodyBooks">
                
            </tbody>
        </table>
    </main>
</body>
<script type = "text/javascript">
    //Loads table upon entering webpage
    $(document).ready(function() {
        loadBookData();
    });

    //Function responsible for loading 
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
                    tBody += `</tr>`
                });
                $('#tBodyBooks').html(tBody);
            },
            error: function(err){
                alert("Error occured while fetching product data.")
            }
        });
    }

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
    

</script>
</html>