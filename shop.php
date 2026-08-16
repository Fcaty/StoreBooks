<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/styles.css">
    <script type="text/javascript" src="resources/jquery.min.js"></script>
    <title>Shop - StoreBooks</title>
</head>
<body>
    <header>
        <h1>StoreBooks: Providing All Your Reading Needs</h1>
        <nav>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="logout.php">Log Out</a>
        </nav>
    </header>

    <main>
        <section id="shop-section">
            <h2>Available Books</h2>
            <label for="search-bar">Search Book: </label>
            <input type="text" name="search-bar" id="search-bar" placeholder="Search here...">
            <label for="genre-select">Select Genre: </label>
            <select id="genre-select" name="genre-select">

            </select>
            <table>
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Price</th>
                        <th>Genre</th>
                        <th>Author</th>
                        <th>Stock</th>
                        <th>Sold</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tBodyShop">

                </tbody>
            </table>
        </section>
    </main>
    <script type="text/javascript" src="resources/shop.js"></script>
</body>
</html>