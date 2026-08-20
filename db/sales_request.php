<?php
session_start();
require_once 'db.php';
require_once 'sale.php';

$database = new Database();
$sale = new Sale($database);

//fetches books for the user-facing shop, optionally filtered by search text and/or genre
if (isset($_POST['fetch_shop_books'])) {
    $searchQuery = $_POST['search_query'] ?? '';
    $genre = $_POST['genre'] ?? '';

    echo json_encode($sale->fetchBooks($searchQuery, $genre));
    exit();
}

//fetches all distinct genres for the shop's genre dropdown
if (isset($_POST['fetch_shop_genres'])) {
    echo json_encode($sale->fetchGenres());
    exit();
}

//handles a buy click: records the sale, then updates stock/sold on the book
if (isset($_POST['buy_book'])) {
    $result = $sale->buy($_POST['book_id']);
    echo json_encode($result);
    exit();
}

?>