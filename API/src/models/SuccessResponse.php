<?php

class SuccessResponse {
    var $status;
    var $data;

    function __construct() {
        $this->status = true;
    }

    function get_status(){
        return $this->status;
    }

    function set_data($data){
        $this->data = $data;
    }

    function get_data(){
        return $this->data;
    }
}

?>