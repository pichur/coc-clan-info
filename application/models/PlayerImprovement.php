<?php

class PlayerImprovement extends PlayerInfo {
    
    protected function compare ($playerImprovement) {
        return $this->level == $playerImprovement->level;
    }
    
    /** @var integer */ public $level   ;
    /** @var integer */ public $maxLevel;
    
}
