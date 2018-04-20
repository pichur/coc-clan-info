<?php

class SortedModel extends Model {
    
    /** @var integer */ public $number;
    
    /**
     * @return static|NULL
     */
    public static function loadLast () {
        return static::loadSingleByOrder('number');
    }
    
    protected function exist () {
        return $this->number > 0;
    }
    
    protected function autoKey () {
        if ($this->number) {
            return;
        }
        
        $result = static::db()->select_max('number')->from(static::table())->get()->result();
        $count = count($result);
        if ($count == 0) {
            $this->number = 1;
        } else {
            $this->number = $result[0]->number + 1;
        }
    }
    
}
