<?php
    require "db.php";
    $database = new Database();

    if(isset($_POST['add_book'])){
        unset($_POST['add_book']);

        $database->insert('books', [...$_POST]);
    }

    if(isset($_POST['fetch_books'])){
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
            $database->select('books', '*');
        } else {
            $database->select('books', '*', $whereData);
        }
    
        $datas = [];
        while($row = $database->res->fetch_assoc()){
            array_push($datas, $row);
        }
        echo json_encode($datas);
    }

    if(isset($_POST['fetch_genres'])){
        $database->select('books', 'DISTINCT book_genre');

        $datas = [];

        while($row = $database->res->fetch_assoc()){
            array_push($datas, $row);
        }

        echo json_encode($datas);
    }
?>