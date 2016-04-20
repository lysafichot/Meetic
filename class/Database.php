<?php
class Database {

    public $pdo;

    public function __construct($login, $password, $database_name, $host = 'localhost') {
        $this->pdo = new PDO("mysql:dbname=$database_name;host=$host", $login, $password, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->pdo->query("SET NAMES 'utf8'");
    }

    public function query($query, $params = false) {
        if($params){
            $req = $this->pdo->prepare($query);
            $req->execute($params);
        } else {
            $req = $this->pdo->query($query);
        }
        return $req;
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
}