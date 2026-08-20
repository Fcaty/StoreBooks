<?php
    require "db.php";
    
    class AdminController{
        private $database;

        public function __construct(Database $database){
            $this -> database = $database;
        }

        public function handleRequest(){
            if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
                return;
            }

            $actions = [
                'add_book' => 'addBook',
                'add_user' => 'addUser',
                'update_book' => 'updateBook',
                'fetch_books' => 'fetchBooks',
                'fetch_genres' => 'fetchGenres',
                'fetch_book_names' => 'fetchBookNames',
                'fetch_users' => 'fetchUsers',
                'fetch_orders' => 'fetchOrders',
                'update_user' => 'updateUser',
                'delete_user' => 'deleteUser',
                'delete_book' => 'deleteBook'
            ];

            foreach($actions as $postKey => $methodName){
                if(isset($_POST[$postKey])){
                    $this->$methodName();
                    return;
                }
            }
        }
        private function addBook(){
            unset($_POST['add_book']);
            $this->database->insert('books', [...$_POST]);
        }

        private function addUser(){
            unset($_POST['add_user']);
            unset($_POST['conf_user_password']);
            
            $_POST['user_password'] = password_hash($_POST['user_password'], PASSWORD_DEFAULT);

            $this->database->insert('users', [...$_POST]);
        }

        private function updateBook(){
            unset($_POST['update_book']);

            $id = ["book_id"=>$_POST['edit_book_id']];

            $updateBookData = [
                'book_name' => $_POST['edit_book_name'],
                'book_price' => $_POST['edit_book_price'],
                'book_genre' => $_POST['edit_book_genre'],
                'book_author' => $_POST['edit_book_author'],
                'book_stock' => $_POST['edit_book_stock'],
                'book_sold' => $_POST['edit_book_sold']
            ];

            unset($_POST['edit_book_id']);

            $this->database->update('books', $updateBookData, $id);
        }

        private function fetchBooks(){
            $searchQuery = $_POST['search_query'];
            $genre = $_POST['genre'];

            $whereData = [];

            if(!empty($searchQuery)){
                $whereData['book_name LIKE'] = "%" . $searchQuery . "%";
            }

            if(!empty($genre)) {
                $whereData['book_genre'] = $genre;
            }

            if(empty($whereData)){
                $this->database->select('books', '*');
            } else {
                $this->database->select('books', '*', $whereData);
            }
        
            $datas = [];
            while($row = $this->database->res->fetch_assoc()){
                array_push($datas, $row);
            }
            
            echo json_encode($datas);
        }

        private function fetchGenres(){
            $this->database->select('books', 'DISTINCT book_genre');

            $datas = [];

            while($row = $this->database->res->fetch_assoc()){
                array_push($datas, $row);
            }

            echo json_encode($datas);
        }

        private function fetchBookNames(){
            $searchQuery = $_POST['search_book_query'];

            $whereData = [];

            if(!empty($searchQuery)){
                $whereData['book_name LIKE'] = "%" . $searchQuery . "%";
            }

            if(empty($whereData)){
                $this->database->select('books', 'DISTINCT book_id, book_name');
            } else {
                $this->database->select('books', 'DISTINCT book_id, book_name', $whereData);
            }

            $datas = [];
            while($row = $this->database->res->fetch_assoc()){
                array_push($datas, $row);
            }

            echo json_encode($datas);
        }

        private function fetchUsers(){
            $this->database->select('users', '*');

            $datas = [];

            while($row = $this->database->res->fetch_assoc()){
                array_push($datas, $row);
            }

            echo json_encode($datas);
        }

        private function fetchOrders(){
            unset($_POST['fetch_orders']);
            $selectedBook = $_POST['selected_book'];

            $table = "orders o JOIN books b ON o.book_id = b.book_id JOIN users u ON o.user_id = u.user_id";
            $rows = "o.order_id, u.user_id, u.user_name, o.order_date, b.book_price";
            $where = ["o.book_id" => $selectedBook];

            $this->database->select($table, $rows, $where);

            $datas = [];

            while($row = $this->database->res->fetch_assoc()){
                array_push($datas, $row);
            }

            echo json_encode($datas);
        }

        private function updateUser(){
            unset($_POST['update_user']);

            $id = ['user_id' => $_POST['edit_user_id']];
            unset($_POST['edit_user_id']);

            $updateUserData = [
                'user_name' => $_POST['edit_user_name'],
                'user_role' => $_POST['edit_user_role'],
                'user_email' => $_POST['edit_user_email']
            ];

            $this->database -> update('users', $updateUserData, $id);
        }

        private function deleteUser(){
            unset($_POST['delete_user']);

            $id = ['user_id' => $_POST['delete_user_id']];
            unset($_POST['delete_user_id']);

            $this->database -> delete('users', $id);
        }

        private function deleteBook(){
            unset($_POST['delete_book']);

            $id = ['book_id' => $_POST['delete_book_id']];
            unset($_POST['delete_book_id']);

            $this->database -> delete('books', $id);
        }
    }

    $database = new Database();
    $controller = new AdminController($database);
    $controller->handleRequest();
?>