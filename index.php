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
        <h1>StoreBooks: Providing All Your Reading Needs</h1>
        <nav>
        </nav>
    </header>

    <main>
        <section id = "general-book-info">
            <h2>Add New Book Information</h2>
            <form id = "addBookForm" method = "POST" action = "db/request.php">
                <input type = "text" name = "book_name" id = "name" placeholder = "Book Name" required>
                <input type = "number" name = "book_price" id = "price" placeholder = "Book Price" required>
                <input type = "text" name = "book_genre" id = "genre" placeholder = "Book Genre" required>
                <input type = "text" name = "book_author" id = "author" placeholder = "Book Author" required>
                <input type = "number" name = "book_stock" id = "stock" placeholder = "Current Stock" required>
                <button type = "submit" name = "add_book">Submit</button>
            </form>
            <h2>Book Information</h2>
            <label for = "search-bar">Search Book: </label>
            <input type = "text" name = "search-bar" id = "search-bar" placeholder = "Search here...">
            <label for = "genre-select">Select Genre: </label>
            <select id = "genre-select" name = "genre-select">
                
            </select>
            <table>
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Book Name</th>
                        <th>Price</th>
                        <th>Genre</th>
                        <th>Author</th>
                        <th>Stock</th>
                        <th>Amount Sold</th>
                    </tr>
                </thead>
                <tbody id = "tBodyBooks">
                    
                </tbody>
            </table>
        </section>

        <section id = "user-information">
            <h2>User Information</h2>

        </section>
    </main>
</body>
<script type = "text/javascript" src = resources/script.js> </script>
</html>