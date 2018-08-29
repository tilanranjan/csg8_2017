<?php
class Utility {
    
    public static function coloumEncode($value)
    {
        return  base64_encode($value);
    }
    
    public static function coloumDecode($value)
    {
        return base64_decode($value);
    }
}

?>