<?php
session_start();
require_once 'db.php';
$database = new Database();

if (isset($_POST['fetch_shop_books'])) {
    $searchQuery = $_POST['search_query'] ?? '';
    $genre = $_POST['genre'] ?? '';

    $whereData = [];

    if (!empty($searchQuery)) {
        $whereData['book_name LIKE'] = "%" . $searchQuery . "%";
    }

    if (!empty($genre)) {
        $whereData['book_genre'] = $genre;
    }

    if (empty($whereData)) {
        $database->select('books', '*');
    } else {
        $database->select('books', '*', $whereData);
    }

    $datas = [];
    while ($row = $database->res->fetch_assoc()) {
        array_push($datas, $row);
    }
    echo json_encode($datas);
    exit;
}

if (isset($_POST['fetch_shop_genres'])) {
    $database->select('books', 'DISTINCT book_genre');

    $datas = [];
    while ($row = $database->res->fetch_assoc()) {
        array_push($datas, $row);
    }
    echo json_encode($datas);
    exit;
}

if (isset($_POST['buy_book'])) {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please log in to buy books.']);
        exit;
    }

    $book_id = (int) $_POST['book_id'];
    $user_id = (int) $_SESSION['user_id'];

    $database->select('books', 'book_stock, book_sold', ['book_id' => $book_id]);
    $book = $database->res->fetch_assoc();

    if (!$book || $book['book_stock'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'This book is out of stock.']);
        exit;
    }

    $database->insert('sales', [
        'book_id' => $book_id,
        'user_id' => $user_id
    ]);

    $newStock = $book['book_stock'] - 1;
    $newSold = $book['book_sold'] + 1;

    $database->update('books',
        ['book_stock' => $newStock, 'book_sold' => $newSold],
        ['book_id' => $book_id]
    );

    echo json_encode([
        'success' => true,
        'book_stock' => $newStock,
        'book_sold' => $newSold
    ]);
    exit;
}
?>