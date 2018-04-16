<?php

class PlayerHistory extends TimestampModel {
    
    public static $fieldMapping = [
        'league'       => ['type' => 'ManyToOne', 'target' => League     ::class],
        'achievements' => ['type' => 'OneToMany', 'target' => Achievement::class],
        'troops'       => ['type' => 'OneToMany', 'target' => Troop      ::class],
        'heroes'       => ['type' => 'OneToMany', 'target' => Hero       ::class],
        'spells'       => ['type' => 'OneToMany', 'target' => Spell      ::class],
    ];
    
    /** @var string  */ public $tag                 ;
    /** @var string  */ public $name                ;
    /** @var string  */ public $role                ;
    /** @var integer */ public $expLevel            ;
    /** @var integer */ public $trophies            ;
    /** @var integer */ public $versusTrophies      ;
    /** @var integer */ public $donations           ;
    /** @var integer */ public $donationsReceived   ;
    /** @var integer */ public $townHallLevel       ;
    /** @var integer */ public $bestTrophies        ;
    /** @var integer */ public $warStars            ;
    /** @var integer */ public $attackWins          ;
    /** @var integer */ public $defenseWins         ;
    /** @var integer */ public $versusBattleWinCount;
    /** @var integer */ public $builderHallLevel    ;
    /** @var integer */ public $bestVersusTrophies  ;
    /** @var integer */ public $versusBattleWins    ;
    
    /** @var integer */ public $clanRank            ; // member
    /** @var integer */ public $previousClanRank    ; // member
    
    /** @var League  */ public $league              ;
    
    /** @var array[Achievement] */ public $achievements;
    /** @var array[Troop      ] */ public $troops      ;
    /** @var array[Hero       ] */ public $heroes      ;
    /** @var array[Spell      ] */ public $spells      ;
    
    public function save () {
        $this->league->save();
        $this->league_id = $this->league->id;
        
        parent::save();
        
        foreach (['achievements', 'troops', 'heroes', 'spells'] as $list) {
            foreach ($this->$list as $entry) {
                $entry->tag = $this->tag;
                $entry->save();
            }
        }
    }
    
}