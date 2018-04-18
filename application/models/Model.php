<?php

class Model extends CI_Model {
    
    public static $null = array();
    
    public static $fieldMapping = array();
    
    public static function parseJson ($timestamp, $json) {
        $object = new static;
        $vars = get_object_vars($object);
        foreach ($vars as $var => $val) {
            $mapping = static::$fieldMapping[$var];
            $jsonName      = $var    ;
            $jsonConverter = false   ;
            $target        = false   ;
            $type          = 'column';
            if ($mapping) {
                $type = $mapping['type'];
                if ($type == 'transient') {
                    continue;
                }
                $target        = $mapping['target'   ];
                $jsonConverter = $mapping['jsonConverter'];
                if (array_key_exists('jsonName', $mapping)) {
                    $jsonName = $mapping['jsonName'];
                }
            }
            
            $jsonValue = $json->$jsonName;
            
            if ($jsonConverter) {
                $jsonValue = static::$jsonConverter($jsonValue);
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
     * @return self
     */
    public function fixDbLoad () {
        foreach (static::$fieldMapping as $var => $properties) {
            $dbConverter = $properties['dbConverter'];
            if ($dbConverter) {
                $this->$var = static::$dbConverter($this->$var);
            }
        }
        
        return $this;
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
    
    public function save () {
        if ($this->exist()) {
            static::db()->update(static::table(), $this->set(), $this->key());
        } else {
            $this->autoKey();
            static::db()->insert(static::table(), $this->set());
        }
    }
    
    /**
     * @return CI_DB_query_builder
     */
    public static function db () {
        return get_instance()->db;
    }
    
    public static function table () {
        return get_called_class();
    }
    
    public static function listBy (array $key) {
        foreach ($key as $var => $val) {
            if ($val instanceof DateTime) {
                $key[$var] = $val->format('Y-m-d H:i:s');
            }
        }
        
        $result = static::db()->select()->from(static::table())->where($key)->get()->custom_result_object(get_called_class());
        
        foreach ($result as $model) {
            $model->fixDbLoad();
        }
        
        return $result;
    }
    
    /**
     * 
     * @param array $key array of keys for object
     * @throws Exception exception if not unique key given
     * @return NULL|static
     */
    public static function getBy (array $key) {
        $result = static::listBy($key);
        $count = count($result);
        if ($count === 0) {
            return null;
        } else if ($count === 1) {
            return $result[0];
        } else {
            throw new Exception('Not unique key ' . json_encode($key). ', ' . $count . ' results');
        }
    }
    
    public static function jsonToDate ($input) {
        $input = substr($input, 0, -5);
        $date = DateTime::createFromFormat('Ymd\THis', $input, new DateTimeZone('UTC'));
        $date->setTimeZone(new DateTimeZone(date_default_timezone_get()));
        return $date;
    }
    
    public static function dbToDate ($input) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $input);
        return $date;
    }
    
}
