<?php
    class Database {
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "starbooks_db";
        public $res;
        private $conn;

        public function __construct(){
            try {
                $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, 3307);
            } catch (Exception $e) {
                die("Database connection error! <br>" . $e);
            }
        }

        public function __destruct() {
            $this -> conn -> close();
        }

        public function insert ($table, $data){
            try {
                $table_columns = implode (',', array_keys($data));
                $prep=$types="";

                foreach($data as $key => $value){
                    $prep .= '?,';
                    $types .= substr(gettype($value), 0, 1);
                }

                $prep = substr($prep, 0, -1);
                $stmt = $this->conn->prepare("INSERT INTO $table($table_columns) VALUES ($prep)");
                $stmt -> bind_param($types, ...array_values($data));
                $stmt -> execute();
                $stmt -> close();

            } catch (Exception $e) {
                die("Error while inserting data! <br>" . $e);
            }
        }

        //Select function serves as a flexible way of reusing a single function for ALL system queries
        //Search, ReturnAll, etc.
        public function select ($table, $row = "*", $where = NULL) {
            try {
                if (!is_null($where)) {
                    $cond=$types="";
                    
                    //This foreach loop will loop through all key-value pairs of the $whereData assoc array, properly formatting them into a condition statement and acquiring its data type to create a preparedStatement.
                    foreach($where as $key => $value) {

                        //By default, WHERE's operator is "=".
                        $operator = "=";
                        $column = $key;

                        //In cases where part of a WHERE statement includes special operators such as >, <, or most importantly, LIKE (for search), these lines of code will separate them.
                        if (strpos($key, ' ') !== false){
                            list($column, $operator) = explode(' ', $key, 2);
                        }

                        //These variables compile all required conditions for the WHERE statement and their respective data types in preparation for creating and binding a prepared statement.
                        $cond .= $column . " " .$operator . " ? AND ";
                        $types .= substr(gettype($value), 0, 1);
                    }

                    //substr() function to remove the last extra " AND".
                    $cond = substr ($cond, 0, -5);
                    $stmt = $this->conn->prepare("SELECT $row FROM $table WHERE $cond");
                    $stmt -> bind_param($types, ...array_values($where));

                } else {
                    $stmt = $this->conn->prepare("SELECT $row FROM $table");
                } 

                $stmt -> execute();
                $this->res = $stmt->get_result();

            } catch (Exception $e){
                die("Error requesting data! <br>" . $e);
            }
        }

        public function selectLike($table, $row = "*", $where = NULL) {
        try {
             if (!is_null($where)) {
                 $cond = "";
                 $values = [];
                 foreach($where as $key => $value) {
                      $cond .= $key . " LIKE ? AND";
                      $values[] = "%" . $value . "%";
                    }
                    $cond = substr($cond, 0, -4);

                    $stmt = $this->conn->prepare("SELECT $row FROM $table WHERE $cond");
                    $types = str_repeat('s', count($values));
                    $stmt->bind_param($types, ...$values);

                } else {
                    $stmt = $this->conn->prepare("SELECT $row FROM $table");
                    }

                $stmt->execute();
                $this->res = $stmt->get_result();

            } catch (Exception $e){
                die("Error requesting data! <br>" . $e);
            }
        }

    }

?>