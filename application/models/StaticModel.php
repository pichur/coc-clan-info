<?php

class StaticModel extends Model {
    
    private function keys () {
        $keys = array();
        
        foreach (static::$fieldMapping as $field => $mapping) {
            if (array_key_exists('key', $mapping)) {
                if ($mapping['key']) {
                    array_push($keys, $field);
                }
            }
        }
        
        return $keys;
    }
    
    public function save () {
        $keys = $this->keys();
        $result = $this->listBy($keys);
        $count = count($result);
        if ($count == 0) {
            parent::save();
        } else if ($count == 1) {
            // Found, save not need
        } else {
            $msg = 'Non unique key, ' . $count . ' results for';
            foreach ($keys as $key) {
                $msg .= ' ' . $key . '=' . $this->$key;
            }
            $msg .= ' in table ' . $this->table();
            throw new Exception($msg);
        }
    }
    
}
