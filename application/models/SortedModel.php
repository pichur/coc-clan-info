<?php

class SortedModel extends Model {
    
    /** @var integer */ public $number;
    
    /**
     * @return static|NULL
     */
    public function loadLast () {
        $object = $this->db()->select()->from($this->table())->order_by('number', 'DESC')->limit(1)->get()->custom_row_object(0, get_class($this));
        if ($object) {
            $object->fixDbLoad();
        }
    }
    
    protected function exist () {
        return $this->number > 0;
    }
    
    protected function autoKey () {
        if ($this->number) {
            return;
        }
        
        $result = $this->db()->select_max('number')->from($this->table())->get()->result();
        $count = count($result);
        if ($count == 0) {
            $this->number = 1;
        } else {
            $this->number = $result[0]->number + 1;
        }
    }
    
}
