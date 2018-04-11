<?php

class Model extends CI_Model {
    
    public static $null = array();
    
    public static $fieldMapping = array();
    
    public static function parseJson ($timestamp, $json) {
        $object = new static;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $mapping = static::$fieldMapping[$var];
            $jsonName  = $var;
            $converter = false;
            $target    = false;
            $relation  = false;
            if ($mapping) {
                $target    = $mapping['target'  ];
                $relation  = $mapping['relation'];
                $converter = $mapping['converter'];
                if (array_key_exists('jsonName', $mapping)) {
                    $jsonName = $mapping['jsonName'];
                }
            }
            
            $jsonValue = $json->$jsonName;
            
            if ($converter) {
                $jsonValue = static::$converter($jsonValue);
            }
            
            if ($relation == 'OneToMany') {
                $object->$var = array();
                if (is_array($jsonValue)) {
                    foreach ($jsonValue as $entry) {
                        $entryObject = $target::parseJson($timestamp, $entry);
                        if ($entryObject) {
                            array_push($object->$var, $entryObject);
                        }
                    }
                }
            } else if ($target) {
                if ($jsonValue) {
                    $object->$var = $target::parseJson($timestamp, $jsonValue);
                } else {
                    // Empty object to avoid inserting
                    $object->$var = self::$null;
                }
            } else {
                if ($var == 'timestamp') {
                    $object->timestamp = date('Y-m-d H:i:s', $timestamp);
                } else {
                    $object->$var = $jsonValue;
                }
            }
        }
        
        return $object;
    }
    
    /**
     * @return CI_DB_query_builder
     */
    public function db () {
        return $this->db;
    }
    
    protected function table () {
        return get_class($this);
    }
    
    protected function save () {
        $this->db()->insert($this->table(), $this);
    }
    
    /**
     * @param string|array[string] $key key or keys field names to search for
     * @return array[mixed]
     */
    protected function listBy ($key) {
        if (!is_array($key)) {
            $key = array($key);
        }
        $this->db()->select()->from($this->table());
        foreach ($key as $field) {
            $this->db()->where($field, $this->$field);
        }
        return $this->db()->get()->result();
    }
    
}
