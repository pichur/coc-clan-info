<?php

class Member extends Model {
    
    public static $fieldMapping = [
            'bestOpponentAttack' => ['type' => 'ManyToOne', 'target' => Attack::class],
            'attacks'            => ['type' => 'OneToMany', 'target' => Attack::class],
    ];
    
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
        if ($this->bestOpponentAttack) {
            $this->bestOpponentAttack_nr = $this->bestOpponentAttack->position;
        }
        
        parent::save();
        
        foreach ($this->attacks as $attack) {
            $attack->warNumber = $this->warNumber;
            $attack->save();
        }
    }
    
}
