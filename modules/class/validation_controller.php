<?php 

class Validation {
    public function clean_input($data){
        return htmlspecialchars(stripslashes(trim($data)));
    }
    public function only_letters($data){
        if(!preg_match("\/^[a-zA-Z- ]*$\/", $data))
            return false;
        return true;
    }
    public function check_email($data){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
        return true;
    }
    public function only_numbers($data){
        if(!preg_match("\/^[0-9]*$\/", $data))
            return false;
        return true;
    }
    public function only_boolean($data){
        if(!preg_match("\/^[0-1]{1}$\/", $data))
            return false;
        return true;
    }
    public function check_empty($data){
        if(is_null($documento) or empty($documento))
            return true;
        return false;
    }
}
?>