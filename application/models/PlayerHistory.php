<?php

class PlayerHistory extends TimestampModel {
    
    public static $fieldMapping = [
        'tag'          => ['key' => true],
        'timestamp'    => ['key' => true, 'jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
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
    
    /** @var integer */ public $clanGamesPoints     ;
    
    /** @var array[Achievement] */ public $achievements;
    /** @var array[Troop      ] */ public $troops      ;
    /** @var array[Hero       ] */ public $heroes      ;
    /** @var array[Spell      ] */ public $spells      ;
    
    
    public function save () {
        $this->league->save();
        $this->league_id = $this->league->id;
        
        $this->transferGamesPoints();
        
        parent::save();
        
        foreach (['achievements', 'troops', 'heroes', 'spells'] as $list) {
            foreach ($this->$list as $entry) {
                $entry->tag = $this->tag;
                $entry->save();
            }
        }
    }
    
    private function transferGamesPoints () {
        if ($this->achievements) {
            foreach ($this->achievements as $achievement) {
                if ($achievement->name == 'Games Champion') {
                    $this->clanGamesPoints = $achievement->value;
                    break;
                }
            }
        } else {
            info('Missing achievements for player ' . $this->tag . ' for history at ' . $this->timestamp->format('Y-m-d H:i:s'));
            // Get previous one
            $lastClanGamesPoints = PlayerHistory::loadSingleByOrder('timestamp', $this->timestamp, ['tag' => $this->tag]);
            if ($lastClanGamesPoints) {
                $this->clanGamesPoints = $lastClanGamesPoints->clanGamesPoints;
            }
        }
    }
    
    
}
