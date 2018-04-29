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
    
    /** @var integer  */ public $donations         ;
    /** @var integer  */ public $donationsReceived ;
    
    /** @var integer  */ public $warCount          ;
    /** @var integer  */ public $warAttackCount    ;
    /** @var integer  */ public $warStars          ;
    /** @var integer  */ public $warNewStars       ;
    /** @var integer  */ public $warDefenses       ;
    /** @var integer  */ public $warLostStars      ;
    /** @var double   */ public $warOpponents      ;
    /** @var double   */ public $warOpponentDiffs  ;
    
    /** @var integer  */ public $gamesCount        ;
    /** @var integer  */ public $gamesPoints       ;
    /** @var integer  */ public $gamesMissingPoints;
    /** @var double   */ public $gamesPercentage   ;
    
    public function addWar (WarPlayer $warPlayer) {
        mapPosition
        // - name
        // - townhallLevel
        // + opponentAttacks
        // - bestOpponentAttack
        
        // - attacks
        
        // + attackCount
        // + stars
        // + newStars
        destruction
        newDestruction
        attackPositionDiff
        attackPositionDiffAvg
        
        // + lostStars
        lostDestruction
        defensePositionDiff
        defensePositionDiffAvg
        
        $this->warCount++;
        
        $this->warAttacks += $warPlayer->attackCount    ;
        warAttacksAvg
        
        $this->warStars       += $warPlayer->stars          ;
        warStarsAvg
        
        $this->warNewStars    += $warPlayer->newStars       ;
        warNewStarsAvg
        
        $this->warDefenses    += $warPlayer->opponentAttacks;
        warDefensesAvg
        
        $this->warLostStars   += $warPlayer->lostStars      ;
        warLostStarsAvg
        
        destruction
        destructionAvg
        
        newDestruction
        newDestructionAvg
        
        lostDestruction
        lostDestructionAvg
        
        attackPositionDiffAvg
        defensePositionDiffAvg
        // x $this->warOpponents
        // x $this->warOpponentDiffs  
    }
}
