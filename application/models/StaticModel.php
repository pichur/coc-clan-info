<?php

class StaticModel extends Model {
    
    public function save () {
        $key = $this->key();
        $result = $this->listBy($key);
        $count = count($result);
        if ($count == 0) {
            parent::save();
        } else if ($count == 1) {
            // Found, save not need
        } else {
            $msg = 'Non unique key, ' . $count . ' results for';
            foreach ($key as $field => $value) {
                $msg .= ' ' . $field . '=' . $value;
            }
            $msg .= ' in table ' . $this->table();
            throw new Exception($msg);
        }
    }
    
}
