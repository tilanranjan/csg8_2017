<?php
    class Serializer 
    {        
        public static function serialize($obj)
        {
            $encoded_string = json_encode(get_object_vars($obj));        
            return $encoded_string;
        }
        public static function serializeUnescapedSlashes($obj)
        {
            return json_encode(get_object_vars($obj),JSON_UNESCAPED_SLASHES);
        }

        public static function deserialize($jsonString){
            return json_decode($jsonString);
        }
    }
?>
