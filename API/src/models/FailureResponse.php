<?php

class FailureResponse {
    var $status;
    var $error_code;
    var $message;

    function __construct() {
        $this->status = false;
    }

    function get_status(){
        return $this->status;
    }

    function set_error_code($error_code){
        $this->error_code = $error_code;
        return $this;
    }

    function get_error_code(){
        return $this->error_code;
    }

    function set_message($message){
        $this->message = $message;
        return $this;
    }

    function get_message(){
        return $this->message;
    }
}

?>