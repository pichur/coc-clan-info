<?php

class Member extends Model {
    
    /** @var integer       */ public $warNumber         ;
    /** @var string        */ public $tag               ;
    /** @var string        */ public $name              ;
    /** @var integer       */ public $townhallLevel     ;
    /** @var integer       */ public $mapPosition       ;
    /** @var integer       */ public $opponentAttacks   ;
    /** @var Attack        */ public $bestOpponentAttack;
    /** @var array[Attack] */ public $attacks           ;
    
    public function save () {
        parent::save();
        
        if ($this->warNumber > 0) {
            foreach ($this->attacks as $attack) {
                $attack->warNumber = $this->warNumber;
                $attack->save();
            }
        }
    }
    
}
