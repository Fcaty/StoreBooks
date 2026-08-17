<?php
    require "db.php";
    $database = new Database();

    //adds new book
    if(isset($_POST['add_book'])){
        unset($_POST['add_book']);

        $database->insert('books', [...$_POST]);
    }

    //updates existing book
    if(isset($_POST['update_book'])){
        unset($_POST['update_book']);

        $id = ["id"=>$_POST['edit_book_id']];
        $updateBookData = [
            'book_name' 
        ]
        unset($_POST['edit_book_id']);

        $database->update('books', [...$_POST], $id);
    }

    //fetches all books based on query conditions
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

    //fetches all genres for dropdown
    if(isset($_POST['fetch_genres'])){
        $database->select('books', 'DISTINCT book_genre');

        $datas = [];

        while($row = $database->res->fetch_assoc()){
            array_push($datas, $row);
        }

        echo json_encode($datas);
    }

    if(isset($_POST['fetch_users'])){
        $database->select('users', '*');

        $datas = [];

        while($row = $database->res->fetch_assoc()){
            array_push($datas, $row);
        }

        echo json_encode($datas);
    }


?>