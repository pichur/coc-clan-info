<?php

class Achievement extends PlayerInfo {
    
    protected function compare ($achievement) {
        return $this->value == $achievement->value;
    }
    
    /** @var integer */ public $stars;
    /** @var integer */ public $value;
    /** @var integer */ public $target;
    
}
