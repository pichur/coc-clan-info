<?php

class PlayerPeriod extends Model {
    
    const FULL = 'full';
    
    public static $fieldMapping = [
        'tag'       => ['key' => true],
        'period'    => ['key' => true],
        'startTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'endTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'timestamp' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /**
     * Player tag
     * @var string
     */
    public $tag;
    
    /**
     * Player name
     * @var string
     */
    public $name;
    
    /**
     * Period name
     * @var string
     */
    public $period;
    
    /**
     * Start time for period, null if from the begining
     * @var DateTime
     */
    public $startTime;
    
    /**
     * End time for period, null if to the end
     * @var DateTime
     */
    public $endTime;
    
    /**
     * Timestamp of last history entry added to totals
     * @var DateTime
     */
    public $timestamp;
    
    /** @var integer  */ public $donations                ;
    /** @var integer  */ public $donationsReceived        ;
                                                          
    /** @var integer  */ public $warCount                 ;
    /** @var integer  */ public $warMapPositionMin        ;
    /** @var double   */ public $warMapPositionAvg        ;
    /** @var integer  */ public $warMapPositionMax        ;
                                                          
    /** @var integer  */ public $warAttacks               ;
    /** @var integer  */ public $warStars                 ;
    /** @var integer  */ public $warNewStars              ;
    /** @var integer  */ public $warDestruction           ;
    /** @var integer  */ public $warNewDestruction        ;
    /** @var integer  */ public $warDefenses              ;
    /** @var integer  */ public $warLostStars             ;
    /** @var integer  */ public $warLostDestruction       ;
                                                          
    /** @var double   */ public $warAttacksAvg            ;
    /** @var double   */ public $warStarsAvg              ;
    /** @var double   */ public $warNewStarsAvg           ;
    /** @var double   */ public $warDestructionAvg        ;
    /** @var double   */ public $warNewDestructionAvg     ;
    /** @var double   */ public $warDefensesAvg           ;
    /** @var double   */ public $warLostStarsAvg          ;
    /** @var double   */ public $warLostDestructionAvg    ;
    
    /** @var double   */ public $warAttackPositionDiffAvg ;
    /** @var double   */ public $warDefensePositionDiffAvg;
    
    /** @var integer  */ public $gamesCount        ;
    /** @var integer  */ public $gamesPoints       ;
    /** @var integer  */ public $gamesMissingPoints;
    /** @var double   */ public $gamesPercentage   ;
    
    public function initWar (WarPlayer $warPlayer) {
        $count = $this->warCount;
        $this->warCount++;
        
        $this->warMapPositionMin = $warPlayer->mapPosition;
        $this->warMapPositionAvg = $warPlayer->mapPosition;
        $this->warMapPositionMax = $warPlayer->mapPosition;
        
        $this->warAttacks         = $warPlayer->attackCount    ;
        $this->warStars           = $warPlayer->stars          ;
        $this->warNewStars        = $warPlayer->newStars       ;
        $this->warDestruction     = $warPlayer->destruction    ;
        $this->warNewDestruction  = $warPlayer->newDestruction ;
        $this->warDefenses        = $warPlayer->opponentAttacks;
        $this->warLostStars       = $warPlayer->lostStars      ;
        $this->warLostDestruction = $warPlayer->lostDestruction;
        
        $this->warAttacksAvg         = $warPlayer->attackCount    ;
        $this->warStarsAvg           = $warPlayer->stars          ;
        $this->warNewStarsAvg        = $warPlayer->newStars       ;
        $this->warDestructionAvg     = $warPlayer->destruction    ;
        $this->warNewDestructionAvg  = $warPlayer->newDestruction ;
        $this->warDefensesAvg        = $warPlayer->opponentAttacks;
        $this->warLostStarsAvg       = $warPlayer->lostStars      ;
        $this->warLostDestructionAvg = $warPlayer->lostDestruction;
        
        $this->warAttackPositionDiffAvg  = $warPlayer->lostDestruction;
        $this->warDefensePositionDiffAvg = $warPlayer->lostDestruction;
    }
    
    public function addWar (WarPlayer $warPlayer) {
        if (!$this->warCount) {
            $this->initWar($warPlayer);
            return;
        }
        $count = $this->warCount;
        $this->warCount++;
        
        $this->warMapPositionMin = min($this->warMapPositionMin, $warPlayer->mapPosition);                     
        $this->warMapPositionAvg = avg($this->warMapPositionAvg, $warPlayer->mapPosition, $count);
        $this->warMapPositionMax = max($this->warMapPositionMax, $warPlayer->mapPosition);                     
        
        $this->warAttacks         += $warPlayer->attackCount    ;
        $this->warStars           += $warPlayer->stars          ;
        $this->warNewStars        += $warPlayer->newStars       ;
        $this->warDestruction     += $warPlayer->destruction    ;
        $this->warNewDestruction  += $warPlayer->newDestruction ;
        $this->warDefenses        += $warPlayer->opponentAttacks;
        $this->warLostStars       += $warPlayer->lostStars      ;
        $this->warLostDestruction += $warPlayer->lostDestruction;
        
        $this->warAttacksAvg         = avg($this->warAttacksAvg        , $warPlayer->attackCount    , $count);
        $this->warStarsAvg           = avg($this->warStarsAvg          , $warPlayer->stars          , $count);
        $this->warNewStarsAvg        = avg($this->warNewStarsAvg       , $warPlayer->newStars       , $count);
        $this->warDestructionAvg     = avg($this->warDestructionAvg    , $warPlayer->destruction    , $count);
        $this->warNewDestructionAvg  = avg($this->warNewDestructionAvg , $warPlayer->newDestruction , $count);
        $this->warDefensesAvg        = avg($this->warDefensesAvg       , $warPlayer->opponentAttacks, $count);
        $this->warLostStarsAvg       = avg($this->warLostStarsAvg      , $warPlayer->lostStars      , $count);
        $this->warLostDestructionAvg = avg($this->warLostDestructionAvg, $warPlayer->lostDestruction, $count);
        
        $this->warAttackPositionDiffAvg  = avg($this->warAttackPositionDiffAvg , $warPlayer->lostDestruction, $count);
        $this->warDefensePositionDiffAvg = avg($this->warDefensePositionDiffAvg, $warPlayer->lostDestruction, $count);
    }
    
}
