<?php
class App {

    static $db = null;

    static function getDatabase() {
        if(!self::$db) {
            self::$db = new Database('root', '', 'meetic');
        } 

        return self::$db;
    }

    static function getAuth() {
        return new Auth(Session::getInstance(), ['restriction_msg' => "Vous n'avez pas la permission d'acceder a cette page" ]);
    }

    static function redirect($page) {
        header("Location: $page");
        exit();
    }

    static function recall($data) {
        if (isset($_POST[$data])) { echo $_POST[$data]; }

    }
}