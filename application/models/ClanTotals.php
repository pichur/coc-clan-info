<?php

class ClanTotals extends Model {
    
    /**
     * Clan tag
     * @var string
     */
    public $tag;
    
    /** @var DateTime */ public $historyFrom;
    
    /** @var DateTime */ public $clanTimestamp      ;
    /** @var integer  */ public $clanMinPoints      ;
    /** @var integer  */ public $clanMaxPoints      ;
    /** @var integer  */ public $clanMinVersusPoints;
    /** @var integer  */ public $clanMaxVersusPoints;
    /** @var integer  */ public $clanMaxWarWinStreak;
    /** @var integer  */ public $clanMinMembers     ;
    /** @var integer  */ public $clanMaxMembers     ;
    
    /** @var DateTime */ public $warTimestamp               ;
    /** @var integer  */ public $warCount                   ;
    /** @var integer  */ public $warWins                    ;
    /** @var integer  */ public $warTies                    ;
    /** @var integer  */ public $warLosses                  ;
    /** @var double   */ public $warMinAttacksPercentage    ;
    /** @var double   */ public $warAvgAttacksPercentage    ;
    /** @var double   */ public $warMaxAttacksPercentage    ;
    /** @var double   */ public $warMinStarsPercentage      ;
    /** @var double   */ public $warAvgStarsPercentage      ;
    /** @var double   */ public $warMaxStarsPercentage      ;
    /** @var double   */ public $warMinDestructionPercentage;
    /** @var double   */ public $warAvgDestructionPercentage;
    /** @var double   */ public $warMaxDestructionPercentage;
    
    /** @var DateTime */ public $gamesTimestamp    ;
    /** @var integer  */ public $gamesMinPlayers   ;
    /** @var integer  */ public $gamesAvgPlayers   ;
    /** @var integer  */ public $gamesMaxPlayers   ;
    /** @var integer  */ public $gamesMinMaxPlayers;
    /** @var integer  */ public $gamesAvgMaxPlayers;
    /** @var integer  */ public $gamesMaxMaxPlayers;
    
    protected function exist () {
        return $this->historyFrom != $this->clanTimestamp;
    }
    
    public function init (ClanHistory $history) {
        $this->tag         = $history->tag      ;
        $this->historyFrom = $history->timestamp;
    }
    
    public function addClanHistory (ClanHistory $history) {
        $this->clanTimestamp = $history->timestamp;
        
        $this->clanMinPoints       = min($this->clanMinPoints      , $history->clanPoints      );
        $this->clanMaxPoints       = max($this->clanMaxPoints      , $history->clanPoints      );
        $this->clanMinVersusPoints = min($this->clanMinVersusPoints, $history->clanVersusPoints);
        $this->clanMaxVersusPoints = max($this->clanMaxVersusPoints, $history->clanVersusPoints);
        $this->clanMaxWarWinStreak = max($this->clanMaxWarWinStreak, $history->warWinStreak    );
        $this->clanMinMembers      = min($this->clanMinMembers     , $history->members         );
        $this->clanMaxMembers      = max($this->clanMaxMembers     , $history->members         );
    }
    
}
