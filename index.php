<?php
    require "db/db.php";
    $database = new Database;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type = "text/javascript" src= "resources/jquery.min.js"></script>
    <link rel = "stylesheet" href = "resources/styles.css">
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
                        <th colspan = 2>Actions</th>
                    </tr>
                </thead>
                <tbody id = "tBodyBooks">
                    
                </tbody>
            </table>
            <button type = "button" id = "add-btn-book">Add New Book</button>
        </section>

        <section id = "user-information">
            <h2>User Information</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>User Role</th>
                            <th>User Email</th>
                            <th colspan = 2>Actions</th>
                        </tr>
                    </thead>
                    <tbody id = "tBodyUsers">
                        
                    </tbody>
                </table>
        </section>

        <section id = "book-order-information">
            <h2>Book Order Information</h2>
                <label for = "search-bar-bookselect">Search Book: </label>
                <input type = "text" id = "search-bar-bookselect" name = "search-bar-bookselect">

                <label for = "book-select">Select Book:</label>
                <select id = "book-select" name = "book-select">

                </select>
                <table>
                    <thead>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Date and Time</th>
                    </thead>
                    <tbody id = "tBodyOrders">
                        
                    </tbody>
                </table>

                <div id = "book-order-statistics">

                </div>
        </section>
    </main>

    <div id = "addPanelBook" class = "panel-overlay">
        <div class = "panel-content">
            <h2>Add New Book Information</h2>
            <form id = "addBookForm" method = "POST" action = "db/request.php">
                <label for = "book_name">Book Name: </label>
                <input type = "text" name = "book_name" id = "name" placeholder = "Book Name" required>

                <label for = "book_price">Book Price: </label>
                <input type = "number" name = "book_price" id = "price" placeholder = "Book Price" required>

                <label for = "book_genre">Book Genre: </label>
                <input type = "text" name = "book_genre" id = "genre" placeholder = "Book Genre" required>

                <label for = "book_author">Book Author: </label>
                <input type = "text" name = "book_author" id = "author" placeholder = "Book Author" required>

                <label for = "book_stock">Book Stock: </label>
                <input type = "number" name = "book_stock" id = "stock" placeholder = "Current Stock" required>
                <button type = "submit" name = "add_book">Submit</button>
                <button type = "button" id = "closeAddBookPanelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <div id = "editPanelBook" class = "panel-overlay">
        <div class = "panel-content">
            <h2>Edit Book Information</h2>
            <form id = "editBookForm" method = "POST" action = "db/request.php">
                <input type = "hidden" name = "edit_book_id" id = "edit_book_id">

                <label for = "edit_book_name">Book Name:</label>
                <input type = "text" name = "edit_book_name" id = "edit_book_name" placeholder = "Book Name" required>

                <label for = "edit_book_price">Book Price:</label>
                <input type = "number" name = "edit_book_price" id = "edit_book_price" placeholder = "Book Price" required>

                <label for = "edit_book_genre">Book Genre:</label>
                <input type = "text" name = "edit_book_genre" id = "edit_book_genre" placeholder = "Book Genre" required>

                <label for = "edit_book_author">Book Author:</label>
                <input type = "text" name = "edit_book_author" id = "edit_book_author" placeholder = "Book Author" required>

                <label for = "edit_book_stock">Book Stock:</label>
                <input type = "number" name = "edit_book_stock" id = "edit_book_stock" placeholder = "Current Stock" required>

                <label for = "edit_book_sold">Books Sold:</label>
                <input type = "number" name = "edit_book_sold" id = "edit_book_sold" placeholder = "Current Stock" required>
                
                <button type = "submit" name = "edit_book">Save Changes</button>
                <button type = "button" id = "closeBookPanelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <div id = "editPanelUser" class = "panel-overlay">
        <div class = panel-content>
            <h2>Edit User Information</h2>
            <form id = "editUserForm" method = "POST" action = "db/request.php">
                <input type = "hidden" name = "edit_user_id" id = "edit_user_id">

                <label for = "edit_user_name">User Name</label>
                <input type = "text" name = "edit_user_name" id = "edit_user_name">

                <label for = "edit_user_role">User Role</label>
                <select id = "edit_user_role" name = "edit_user_role">
                    <option value = "User">User</option>
                    <option value = "Admin">Admin</option>
                </select>

                <label for = "edit_user_email">User Email</label>
                <input type = email name = "edit_user_email" id = "edit_user_email">
                <button type = "submit" name = "edit_user">Save Changes</button>
                <button type = "button" id = "closeUserPanelBtn">Cancel</button>
            </form>
        </div>
    </div>

    <div id = "deletePanelBook" class = "panel-overlay">
        <div class = "panel-content">
            <h2>Delete Book Confirmation</h2>
            <form id = "deleteBookForm" method = "POST" action = "db/request.php">
                <input type = "hidden" name = "delete_book_id" id = "delete_book_id">
                <p id = "delete-confirmationbook-text"></p>
                <button type = "submit" name = "delete_book">Yes</button>
                <button type = "button" id = "closeDeleteBookPanelBtn">No</button>
            </form>
        </div>
    </div>

    <div id = "deletePanelUser" class = "panel-overlay">
        <div class = "panel-content">
            <h2>Delete User Confirmation</h2>
            <form id = "deleteUserForm" method = "POST" action = "db/request.php">
                <input type = "hidden" name = "delete_user_id" id = "delete_user_id">
                <p id = "delete-confirmationuser-text"></p>
                <button type = "submit" name = "delete_user">Yes</button>
                <button type = "button" id = "closeDeleteUserPanelBtn">No</button>
            </form>
        </div>
    </div>
</body>
<script type = "text/javascript" src = resources/script.js> </script>
</html>