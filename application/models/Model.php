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
            $type          = 'Column';
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
     * DB model status, null for unknown status (mostly the new), 'db' for object in database, 'new' for objects to insert
     * @var string|null
     */
    private $_status;
    
    /**
     * @return self
     */
    public function fixDbLoad () {
        $this->_status = 'db';
        foreach (static::$fieldMapping as $var => $properties) {
            $dbConverter = $properties['dbConverter'];
            if ($dbConverter) {
                $this->$var = static::$dbConverter($this->$var);
            }
        }
        
        return $this;
    }
    
    protected function exist () {
        return $this->_status == 'db';
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
            if (substr($key, 0, 1) == '_') {
                // System field
                continue;
            }
            if (        static::$fieldMapping[$key]
                    &&  static::$fieldMapping[$key]['type']
                    && (static::$fieldMapping[$key]['type'] != 'Column')) {
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
        info(static::db()->last_query());
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
    
    public static function delete ($where = '1 = 1', $limit = NULL, $reset_data = TRUE) {
        static::db()->delete(static::table(), $where, $limit, $reset_data);
    }
    
    /**
     * @param array $key Array of keys for where clause
     * @return array[static]
     */
    public static function listBy (array $key) {
        foreach ($key as $var => $val) {
            if ($val instanceof DateTime) {
                $key[$var] = $val->format('Y-m-d H:i:s');
            }
        }
        
        $result = static::db()->select()->from(static::table())->where($key)->get()->custom_result_object(get_called_class());
        info(static::db()->last_query());
        
        if (is_array($result)) {
            foreach ($result as $model) {
                $model->fixDbLoad();
            }
        }
        
        return $result;
    }
    
    /**
     * @param string $orderby   Field for order, and optionally boundary
     * @param mixed  $boundary  Boundary for order key
     * @param array  $key       Array of keys for where clause
     * @param string $direction Direction of sorting
     * @param int    $limit     Limit of rows to get
     * @throws Exception
     * @return array[static]
     */
    public static function loadByOrder ($orderby, $boundary = null, $key = [], $direction = 'DESC', $limit = null) {
        if ($key == null) {
            $key = [];
        }
        if ($boundary) {
            $key[$orderby . ' ' . (($direction == 'DESC') ? '<' : '>')] = $boundary;
        }
        foreach ($key as $var => $val) {
            if ($val instanceof DateTime) {
                $key[$var] = $val->format('Y-m-d H:i:s');
            }
        }
        
        $result = static::db()->select()->from(static::table())->where($key)->order_by($orderby, $direction)->limit($limit)->get()->custom_result_object(get_called_class());
        info(static::db()->last_query());
        
        if (is_array($result)) {
            foreach ($result as $model) {
                $model->fixDbLoad();
            }
        }
        
        return $result;
    }
    
    /**
     * @param string $orderby   Field for order, and optionally boundary
     * @param mixed  $boundary  Boundary for order key
     * @param array  $key       Array of keys for where clause
     * @param string $direction Direction of sorting
     * @throws Exception
     * @return NULL|static
     */
    public static function loadSingleByOrder ($orderby, $boundary = null, array $key = [], $direction = 'DESC') {
        $result = static::loadByOrder($orderby, $boundary, $key, $direction, 1);
        $count = count($result);
        if ($count === 0) {
            return null;
        } else if ($count === 1) {
            return $result[0];
        } else {
            throw new Exception('Not expected size of result : ' . $count);
        }
    }
    
    /**
     * @param array $key Array of keys for where clause
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
        if (!$input) {
            return null;
        }
        $input = substr($input, 0, -5);
        $date = DateTime::createFromFormat('Ymd\THis', $input, new DateTimeZone('UTC'));
        if ($date === false) {
            throw new Exception('Cannot parse date ' . $input);
        }
        $date->setTimeZone(new DateTimeZone(date_default_timezone_get()));
        return $date;
    }
    
    public static function dbToDate ($input) {
        if (!$input) {
            return null;
        }
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $input);
        return $date;
    }
    
    protected function getModelProperty ($field) {
        $mapping = static::$fieldMapping[$field];
        if (!$mapping) {
            return $this->$field;
        }
        $type = $mapping['type'];
        if (!$type) {
            return $this->$field;
        }
        if ($type == 'Column') {
            return $this->$field;
        }
        $loadedMark = '_loaded_' . $field;
        if ($type == 'OneToOne')  {
            if (is_object($this->$field)) {
                return $this->$field;
            }
            if ($this->$loadedMark) {
                return $this->$field;
            }
            
            $key = $this->key();
            $target = $mapping['target'];
            $targetKey = $mapping['targetKey'];
            if ($targetKey) {
                $key = array_merge($key, $targetKey);
            }
            $this->$field = $target::getBy($key);
            
            $this->$loadedMark = true;
            return $this->$field;
        }
        if ($type == 'OneToMany')  {
            if (is_array($this->$field)) {
                return $this->$field;
            }
            if ($this->$loadedMark) {
                return $this->$field;
            }
            
            $key = $this->key();
            $target = $mapping['target'];
            $this->$field = $target::listBy($key);
            
            $this->$loadedMark = true;
            return $this->$field;
        }
        if ($type == 'ManyToOne')  {
            throw new Exception('ManyToOne implementation need');
        }
        throw new Exception('Unknown field mapping type: ' . $type);
    }
    
}
