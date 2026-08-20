<?php
    class Sale {
        private $database;

        public function __construct($database) {
            $this->database = $database;
        }

        public function fetchBooks($searchQuery = '', $genre = '') {
            $whereData = [];

            if (!empty($searchQuery)) {
                $whereData['book_name LIKE'] = "%" . $searchQuery . "%";
            }

            if (!empty($genre)) {
                $whereData['book_genre'] = $genre;
            }

            if (!empty($whereData)) {
                $this->database->select('books', '*', $whereData);
            } else {
                $this->database->select('books', '*');
            }

            $datas = [];
            while ($row = $this->database->res->fetch_assoc()) {
                array_push($datas, $row);
            }

            return $datas;
        }  
        
        public function fetchGenres() {
            $this->database->select('books', 'DISTINCT book_genre');

            $datas = [];
            while ($row = $this->database->res->fetch_assoc()) {
                array_push($datas, $row);
            }

            return $datas;
        }

        public function buy($book_id) {
            // Buying requires an active session, the user id comes from
            // the session, never from client input, so it can't be spoofed.
            if (!isset($_SESSION['user_id'])) {
                return ['success' => false, 'message' => 'You must be logged in to buy a book.'];
            }

            $book_id = (int) $book_id;
            $user_id = (int) $_SESSION['user_id'];

            $this->database->select('books', 'book_stock, book_sold', ['book_id' => $book_id]);
            $book = $this->database->res->fetch_assoc();

            if (!$book || $book['book_stock'] <= 0) {
                return ['success' => false, 'message' => 'This book is out of stock.'];
            }

            $this->database->insert('sales', [
                'user_id' => $user_id,
                'book_id' => $book_id,
            ]);

            $newStock = $book['book_stock'] - 1;
            $newSold = $book['book_sold'] + 1;

            $this->database->update('books',
            ['book_stock' => $newStock, 'book_sold' => $newSold],
            ['book_id' => $book_id]
            );

            return [
                'success' => true,
                'book_stock' => $newStock,
                'book_sold' => $newSold,
            ];
        }
    }
?>