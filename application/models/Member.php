<?php

class Member extends Model {
    
    /** @var integer       */ public $warNumber         ;
    /** @var string        */ public $type              ;
    /** @var string        */ public $tag               ;
    /** @var string        */ public $name              ;
    /** @var integer       */ public $townhallLevel     ;
    /** @var integer       */ public $mapPosition       ;
    /** @var integer       */ public $opponentAttacks   ;
    /** @var Attack        */ public $bestOpponentAttack;
    /** @var array[Attack] */ public $attacks           ;
    
    public function save () {
        $this->bestOpponentAttack_id = $this->bestOpponentAttack->save();
        
        parent::save();
        
        foreach ($this->attacks as $attack) {
            $attack->warNumber = $this->warNumber;
            $attack->save();
        }
    }
    
}
