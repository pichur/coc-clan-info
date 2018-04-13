<?php

class Model extends CI_Model {
    
    public static $null = array();
    
    public static $fieldMapping = array();
    
    public static function parseJson ($timestamp, $json) {
        $object = new static;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $mapping = static::$fieldMapping[$var];
            $jsonName  = $var    ;
            $converter = false   ;
            $target    = false   ;
            $type      = 'column';
            if ($mapping) {
                $type = $mapping['type'];
                if ($type == 'transient') {
                    continue;
                }
                $target    = $mapping['target'   ];
                $converter = $mapping['converter'];
                if (array_key_exists('jsonName', $mapping)) {
                    $jsonName = $mapping['jsonName'];
                }
            }
            
            $jsonValue = $json->$jsonName;
            
            if ($converter) {
                $jsonValue = static::$converter($jsonValue);
            }
            
            if ($type == 'OneToMany') {
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
                    // Empty object to avoid inserting (object set separatly)
                    $object->$var = self::$null;
                }
            } else {
                if ($var == 'timestamp') {
                    $object->timestamp = $timestamp;
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
    
    protected function exist () {
        return false;
    }
    
    protected function key() {
        $key = array();
        
        foreach (static::$fieldMapping as $field => $mapping) {
            if ($mapping['key']) {
                $key[$field] = $this->$field;
            }
        }
        
        return $key;
    }
    
    protected function autoKey () {
        
    }
    
    protected function set () {
        $set = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $val) {
            if (        static::$fieldMapping[$key]
                    &&  static::$fieldMapping[$key]['type']
                    && (static::$fieldMapping[$key]['type'] != 'column')) {
                continue;
            }
            if ($val instanceof DateTime) {
                $val = $val->format('Y-m-d H:i:s');
            }
            if (is_array($val) || is_object($val)) {
                continue;
            }
            $set[$key] = $val;
        }
        
        return $set;
    }
    
    protected function save () {
        if ($this->exist()) {
            $this->db()->update($this->table(), $this->set(), $this->key());
        } else {
            $this->autoKey();
            $this->db()->insert($this->table(), $this->set());
        }
    }
    
    /**
     * @param string|array[string] $key keys field names mapped to values, or single field name (value to get from object) to search for
     * @return array[mixed]
     */
    protected function listBy ($key) {
        if (!is_array($key)) {
            $key = array($key => $this->$key);
        }
        
        return $this->db()->select()->from($this->table())->where($key)->get()->result();
    }
    
    /**
     * @param string|array[string] $key keys field names mapped to values, or single field name (value to get from object) to search for
     * @return self
     */
    protected function getBy ($key) {
        $result = $this->listBy($key);
        $count = count($result);
        if ($count === 0) {
            return null;
        } else if ($count === 1) {
            return $result[0];
        } else {
            throw new Exception("Not unique, $count results");
        }
    }
    
}
