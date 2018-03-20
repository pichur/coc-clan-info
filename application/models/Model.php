<?php

class Model extends CI_Model {
    
    public static $fieldMapping = [];
    
    public static function parseJson ($json) {
        $object = new static;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $class = static::$fieldMapping[$var];
            if ($class) {
                if (is_array($json->$var)) {
                    $object->$var = array();
                    foreach ($json->$var as $entry) {
                        $object->$var[] = $class::parseJson($entry);
                    }
                } else {
                    $object->$var = $class::parseJson($json->$var);
                }
            } else {
                $object->$var = $json->$var;
            }
        }
        
        return $object;
    }
    
}