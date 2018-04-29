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
    /** @var integer       */ public $opponentAttacks       ; // defenseCount
    /** @var Attack        */ public $bestOpponentAttack    ;
                                                            
    /** @var array[Attack] */ public $attacks               ;
                                                            
    /** @var integer       */ public $attackCount           ;
    /** @var integer       */ public $stars                 ;
    /** @var integer       */ public $newStars              ;
    /** @var integer       */ public $destruction           ;
    /** @var integer       */ public $newDestruction        ;
    /** @var integer       */ public $attackPositionDiff    ;
    /** @var double        */ public $attackPositionDiffAvg ;
    
    /** @var integer       */ public $defenseCount          ;
    /** @var integer       */ public $lostStars             ;
    /** @var integer       */ public $lostDestruction       ;
    /** @var integer       */ public $defensePositionDiff   ;
    /** @var double        */ public $defensePositionDiffAvg;
    
    public function save () {
        if ($this->bestOpponentAttack) {
            $this->bestOpponentAttack_nr = $this->bestOpponentAttack->order;
        }
        
        parent::save();
        
        foreach ($this->attacks as $attack) {
            $attack->number = $this->number;
            $attack->save();
        }
    }
    
}
