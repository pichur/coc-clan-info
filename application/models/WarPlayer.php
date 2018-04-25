<?php

class WarPlayer extends Model {
    
    public static $fieldMapping = [
            'number'             => ['key' => true],
            'tag'                => ['key' => true],
            'bestOpponentAttack' => ['type' => 'ManyToOne', 'target' => Attack::class],
            'attacks'            => ['type' => 'OneToMany', 'target' => Attack::class],
    ];
    
    /** @var integer       */ public $number                ;
    /** @var string        */ public $tag                   ;
                                                            
    /** @var string        */ public $type                  ;
    /** @var integer       */ public $mapPosition           ;
    /** @var string        */ public $name                  ;
    /** @var integer       */ public $townhallLevel         ;
    /** @var integer       */ public $opponentAttacks       ;
    /** @var Attack        */ public $bestOpponentAttack    ;
                                                            
    /** @var array[Attack] */ public $attacks               ;
                                                            
    /** @var integer       */ public $attackCount           ; // T
    /** @var integer       */ public $stars                 ; // T
    /** @var integer       */ public $newStars              ; // T
    /** @var integer       */ public $destruction           ; // T
    /** @var integer       */ public $newDestruction        ; // T
    /** @var integer       */ public $attackPositionDiff    ; // T
    /** @var double        */ public $attackPositionDiffAvg ; // T
    
    /** @var integer       */ public $defenseCount          ; // T
    /** @var integer       */ public $lostStars             ; // T
    /** @var integer       */ public $lostDestruction       ; // T
    /** @var integer       */ public $defensePositionDiff   ; // T
    /** @var double        */ public $defensePositionDiffAvg; // T
    
    /* "tag"               :"#9QLQL2GV8",
       "name"              :"POGROMCA WIOSEK",
       "townhallLevel"     :5,
       "mapPosition"       :49,
       "attacks"           :[{"attackerTag":"#9QLQL2GV8","defenderTag":"#Y89C0VRRP","stars":0,"destructionPercentage":30,"order":1}],
       "opponentAttacks"   :1,
       "bestOpponentAttack":{"attackerTag":"#9C2RVJRVP","defenderTag":"#9QLQL2GV8","stars":3,"destructionPercentage":100,"order":103}
    */
    
    public function save () {
        if ($this->bestOpponentAttack) {
            $this->bestOpponentAttack_nr = $this->bestOpponentAttack->position;
        }
        
        parent::save();
        
        foreach ($this->attacks as $attack) {
            $attack->number = $this->number;
            $attack->save();
        }
    }
    
}
