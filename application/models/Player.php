<?php

class Player extends Model {
    
    use Timestamp;
    
    public static $fieldMapping = [
            'league'       => League     ::class,
            'achievements' => Achievement::class,
            'troops'       => Troop      ::class,
            'heroes'       => Hero       ::class,
            'spells'       => Spell      ::class,
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
    
}
