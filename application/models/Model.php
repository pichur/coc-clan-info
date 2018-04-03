<?php

class Model extends CI_Model {
    
    public static $fieldMapping = array();
    
    public static function parseJson ($timestamp, $json) {
        $object = new static;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $class = static::$fieldMapping[$var];
            if ($class) {
                if (is_array($json->$var)) {
                    $object->$var = array();
                    foreach ($json->$var as $entry) {
                        $entryObject = $class::parseJson($timestamp, $entry);
                        if ($entryObject) {
                            array_push($object->$var, $entryObject);
                        }
                    }
                } else {
                    $object->$var = $class::parseJson($timestamp, $json->$var);
                }
            } else {
                if ($var == 'timestamp') {
                    $object->timestamp = date('Y-m-d H:i:s', $timestamp);
                } else {
                    $object->$var = $json->$var;
                }
            }
        }
        
        return $object;
    }
    
}
