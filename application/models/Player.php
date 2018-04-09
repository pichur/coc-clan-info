<?php

class Player extends TimestampModel {
    
    public static $fieldMapping = [
        'league'       => ['target' => League     ::class, 'relation' => 'ManyToOne'],
        'achievements' => ['target' => Achievement::class, 'relation' => 'OneToMany'],
        'troops'       => ['target' => Troop      ::class, 'relation' => 'OneToMany'],
        'heroes'       => ['target' => Hero       ::class, 'relation' => 'OneToMany'],
        'spells'       => ['target' => Spell      ::class, 'relation' => 'OneToMany'],
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
        $this->league_id = $this->league->save();
        
        $this->db->insert($this->table(), $this);
        
        foreach (['achievements', 'troops', 'heroes', 'spells'] as $list) {
            foreach ($this->$list as $entry) {
                $entry->tag = $this->tag;
                $entry->db->insert($entry->table(), $entry);
            }
        }
    }
    
}
