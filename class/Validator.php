<?php
class Validator {

    private $data;
    private $errors = [];

    public function __construct($data) {
        $this->data = $data;
    }

    private function getField($field) {
        if (!isset($this->data[$field])) {
            return null;
        }
        return $this->data[$field];
    }

    public function isAlpha($field, $errorMsg) {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->getField($field))) {
            $this->errors[$field] = $errorMsg;
            return false;
        }
        return true;
    }


    public function isUniq($field, $db, $table, $errorMsg) {
        $record = $db->query("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
        if ($record) {
            $this->errors[$field] = $errorMsg;
            return false;
        }
        return true;
    }

    public function isEmail($field, $errorMsg) {
        if (!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $errorMsg;
            return false;
        }
        return true;
    }

    public function isConfirmed($field, $errorMsg) {
        $value = $this->getField($field);
        if (empty($value) || $value != $this->getField($field . '_confirm')) {
            $this->errors[$field] = $errorMsg;
            return false;
        }
        return true;
    }

    public function isCodep($field, $db, $table, $errorMsg) {

        $code = $db->query("SELECT code_postal FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
        if(!$code) {
            $this->errors[$field] = $errorMsg;
            return false;
        }
        return true;
    }

    public function isDate($field, $errorMsg) {

        $tab = explode('/', $this->getField($field)); 
        $ok = false;

        if(strlen($this->getField($field)) == 10 && count($tab) == 3 && is_numeric($tab[0]) && is_numeric($tab[1]) && is_numeric($tab[2])) {
            $ok = checkdate($tab[1], $tab[2], $tab[0]); 
        }

        if(!$ok) {
            $this->errors[$field] = $errorMsg;
            return false;
        } 
        return true;
    }


    public function isMajeur($field, $errorMsg) {
        $now = new DateTime('NOW');
        $interval = new DateInterval('P18Y');
        $birth = new DateTime($this->getField($field));

        if($now->sub($interval) < $birth) {
            $this->errors[$field] = $errorMsg;
            return false;
        }
        return true;  
    }
    public function isExtension($field, $errorMsg) {
        $extensions = array('.png', '.gif', '.jpg', '.jpeg','.PNG', '.GIF', '.JPG', '.JPEG');
        $extension = strrchr($_FILES['avatar']['name'], '.'); 
        if(!in_array($extension, $extensions)) {
            $this->errors[$field] = $errorMsg;
            return false;
        }        
        return true;
    }

    public function isSize($field, $errorMsg) {
        $taille_maxi = 500000;
        $taille = filesize($_FILES['avatar']['tmp_name']);
          
        if($taille > $taille_maxi) {
            $this->errors[$field] = $errorMsg;
            return false;        
        } 
        return true;
    }

    public function isValid() {
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}
