<?php
    require "db.php";
    $database = new Database();

    if(isset($_POST['add_book'])){
        unset($_POST['add_book']);

        $database->insert('books', [...$_POST]);
    }

    if(isset($_POST['fetch_books'])){
        $database->select('books', '*');
        $datas = [];
        while($row = $database->res->fetch_assoc()){
            array_push($datas, $row);
        }

        echo json_encode($datas);
    }

        if(isset($_POST['filter_books'])){
            $where = [];
            if(!empty($_POST['genre'])){
                $where['book_genre'] = $_POST['genre'];
            }
            if(!empty($_POST['author'])){
                $where['book_author'] = $_POST['author'];
            }

            if(empty($where)){
                $database->select('books', '*');
            } else {
                $database->selectLike('books', '*', $where);
            }

            $datas = [];
            while($row = $database->res->fetch_assoc()){
            array_push($datas, $row);
        }

    echo json_encode($datas);
}
?>