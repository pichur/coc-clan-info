<?php

class HistoryModel extends TimestampModel {
    
    protected function historyKey () {
        $key = $this->key();
        
        unset($key['timestamp']);
        
        return $key;
    }
    
    protected function compare ($object) {
        $vars = get_object_vars($this);
        foreach ($vars as $var => $val) {
            if (substr($var, 0, 1) == '_') {
                // System field
                continue;
            }
            if ($var == 'timestamp') {
                // Diff field
                continue;
            }
            if ($object->$var != $val) {
                return false;
            }
        }
        
        return true;
    }
    
    public function save () {
        $previous = static::loadSingleByOrder('timestamp', null, $this->historyKey());
        
        if ($previous) {
            if ($this->compare($previous)) {
                // Same, not change anything
                return;
            } else {
                // Insert new values
                parent::save();
            }
        } else {
            // First entry, insert
            parent::save();
        }
    }
    
}
