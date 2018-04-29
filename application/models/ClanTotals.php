<?php

class ClanTotals extends Model {
    
    public static $fieldMapping = [
        'tag'            => ['key' => true],
        'historyFrom'    => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'clanTimestamp'  => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'warTimestamp'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'gamesTimestamp' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /**
     * Clan tag
     * @var string
     */
    public $tag;
    
    /**
     * First stored clan history
     * @var DateTime
     */
    public $historyFrom;
    
    /**
     * Last stored clan history
     * @var DateTime
     */
    public $clanTimestamp;
    
    /** @var integer  */ public $clanMinPoints      ;
    /** @var integer  */ public $clanMaxPoints      ;
    /** @var integer  */ public $clanMinVersusPoints;
    /** @var integer  */ public $clanMaxVersusPoints;
    /** @var integer  */ public $clanMaxWarWinStreak;
    /** @var integer  */ public $clanMinMembers     ;
    /** @var integer  */ public $clanMaxMembers     ;
    
    /**
     * Last stored end time of war
     * @var DateTime
     */
    public $warTimestamp;
    
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
    
    public function init (ClanHistory $history) {
        $this->tag           = $history->tag      ;
        $this->historyFrom   = $history->timestamp;
        $this->clanTimestamp = $history->timestamp;
        
        $this->clanMinPoints       = $history->clanPoints      ;
        $this->clanMaxPoints       = $history->clanPoints      ;
        $this->clanMinVersusPoints = $history->clanVersusPoints;
        $this->clanMaxVersusPoints = $history->clanVersusPoints;
        $this->clanMaxWarWinStreak = $history->warWinStreak    ;
        $this->clanMinMembers      = $history->members         ;
        $this->clanMaxMembers      = $history->members         ;
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
    
    public function addWarHistory (War $war) {
        $this->warTimestamp = $war->endTime;
        
        $this->warCount++;
        
        if ($war->isWin ()) $this->warWins   ++;
        if ($war->isTie ()) $this->warTies   ++;
        if ($war->isLoss()) $this->warLosses ++;
        
        $attacksPercentage     = $war->getAttacksPercentage()          ;
        $starsPercentage       = $war->getStarsPercentage  ()          ;
        $destructionPercentage = $war->getClan()->destructionPercentage;
        
        $this->warMinAttacksPercentage     = min($this->warMinAttacksPercentage    , $attacksPercentage    );
        $this->warAvgAttacksPercentage     = avg($this->warAvgAttacksPercentage    , $attacksPercentage    , $this->warCount - 1);
        $this->warMaxAttacksPercentage     = max($this->warMaxAttacksPercentage    , $attacksPercentage    );
        $this->warMinStarsPercentage       = min($this->warMinStarsPercentage      , $starsPercentage      );
        $this->warAvgStarsPercentage       = avg($this->warAvgStarsPercentage      , $starsPercentage      , $this->warCount - 1);
        $this->warMaxStarsPercentage       = max($this->warMaxStarsPercentage      , $starsPercentage      );
        $this->warMinDestructionPercentage = min($this->warMinDestructionPercentage, $destructionPercentage);
        $this->warAvgDestructionPercentage = avg($this->warAvgDestructionPercentage, $destructionPercentage, $this->warCount - 1);
        $this->warMaxDestructionPercentage = max($this->warMaxDestructionPercentage, $destructionPercentage);
    }
    
}
