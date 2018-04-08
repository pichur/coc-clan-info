<?php

class Model extends CI_Model {
    
    public static $fieldMapping = array();
    
    public static function parseJson ($timestamp, $json) {
        $object = new static;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $mapping = static::$fieldMapping[$var];
            if ($mapping) {
                $target   = $mapping['target'  ];
                $relation = $mapping['relation'];
                
                if ($relation == 'OneToMany') {
                    $object->$var = array();
                    if (is_array($json->$var)) {
                        foreach ($json->$var as $entry) {
                            $entryObject = $target::parseJson($timestamp, $entry);
                            if ($entryObject) {
                                array_push($object->$var, $entryObject);
                            }
                        }
                    }
                } else if ($json->$var) {
                    $object->$var = $target::parseJson($timestamp, $json->$var);
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
    
    public function table () {
        return get_class($this);
    }
    
}
