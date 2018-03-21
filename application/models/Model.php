<?php

class Model extends CI_Model {
    
    public static $fieldMapping = [];
    
    public static function parseJson ($timestamp, $json) {
        $object = new static;
        $object->timestamp = $timestamp;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $class = static::$fieldMapping[$var];
            if ($class) {
                if (is_array($json->$var)) {
                    $object->$var = array();
                    foreach ($json->$var as $entry) {
                        $object->$var[] = $class::parseJson($timestamp, $entry);
                    }
                } else {
                    $object->$var = $class::parseJson($timestamp, $json->$var);
                }
            } else {
                $object->$var = $json->$var;
            }
        }
        
        return $object;
    }
    
    public $timestamp;
    
}
