<?php

class StaticModel extends Model {
    
    /** @var integer */ public $id;
    
    public function save () {
        $result = $this->listBy('id');
        $count = count($result);
        if ($count == 0) {
            parent::save();
            return $this->id;
        } else if ($count == 1) {
            return $this->id;
        } else {
            throw new Exception('Non unique key, ' . $count . ' results for id=' . $this->id . ' in table ' . $this->table());
        }
    }
    
}
