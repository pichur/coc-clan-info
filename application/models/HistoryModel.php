<?php

class HistoryModel extends TimestampModel {
    
    private function compare ($object) {
        $vars = get_object_vars($this);
        foreach ($vars as $var => $val) {
            if (($var != 'timestamp') && ($object->$var != $val)) {
                return false;
            }
        }
        
        return true;
    }
    
    public function save () {
        static::db()->from(static::table());
        $key = $this->key();
        if ($key) {
            static::db()->where($key);
        }
        static::db()->order_by('timestamp', 'desc');
        static::db()->limit(1);
        $query = static::db()->get();
        $result = $query->result();
        if (is_array($result)) {
            $count = count($result);
            if ($count == 0) {
                parent::save();
                return;
            } else if ($count == 1) {
                $result = $result[0];
                if (static::compare($result)) {
                    // Same, not change anything
                    return;
                } else {
                    // Insert new values
                    parent::save();
                    return;
                }
            } else {
                throw new Exception('Fatal error, "LIMIT 1" not work');
            }
        } else {
            throw new Exception('Unknown result ' . $result);
        }
    }
    
}
