<?php

class PlayerTotals extends Model {
    
    /**
     * Player tag
     * @var string
     */
    public $tag;
    
    /**
     * Timestamp of last history entry added to totals
     * @var DateTime
     */
    public $timestamp;
    
    /**
     * First time in clan
     * @var DateTime
     */
    public $inClanFirstTime;
    
    /**
     * Time of enter into clan in current turn (if more than one != inClanFirstTime)
     * @var DateTime
     */
    public $inClanCurrentTime;
    
    /**
     * Total hours in clan
     * @var integer
     */
    public $inClanTotalHours;
    
    /**
     * Count of enters into clan
     * @var integer
     */
    public $inClanTotalEnters;
    
    /**
     * Time of any clan active action (donation, war attack, games point get)
     * @var DateTime
     */
    public $lastActiveTime;
    
    /** @var integer  */ public $donations        ;
    /** @var integer  */ public $donationsReceived;
    
    /** @var integer  */ public $warCount         ;
    /** @var integer  */ public $warAttackCount   ;
    /** @var integer  */ public $warStars         ;
    /** @var integer  */ public $warNewStars      ;
    /** @var integer  */ public $warDefenses      ;
    /** @var integer  */ public $warLostStars     ;
    /** @var double   */ public $warOpponents     ;
    /** @var double   */ public $warOpponentDiffs ;
    
    /** @var integer  */ public $gamesCount       ;
    /** @var integer  */ public $gamesPoints      ;
    /** @var integer  */ public $gamesMissingPoint;
    /** @var double   */ public $gamesPercentage  ;
    
    protected function exist () {
        return $this->inClanFirstTime != $this->timestamp;
    }
    
    public function init (PlayerHistory $player) {
        $this->tag = $player->tag;
        
        $this->inClanFirstTime   = $player->timestamp;
        $this->inClanCurrentTime = $player->timestamp;
        $this->inClanTotalHours  = 0;
        $this->inClanTotalEnters = 1;
        
        $this->lastActiveTime    = $player->timestamp;
        
        $this->donations         = 0;
        $this->donationsReceived = 0;
        
        $this->warCount          = 0;
        $this->warAttackCount    = 0;
        $this->warStars          = 0;
        $this->warNewStars       = 0;
        $this->warDefenses       = 0;
        $this->warLostStars      = 0;
        $this->warOpponents      = 0;
        $this->warOpponentDiffs  = 0;
        
        $this->gamesCount        = 0;
        $this->gamesPoints       = 0;
        $this->gamesMissingPoint = 0;
        $this->gamesPercentage   = 0;
    }
    
    public function enter (PlayerHistory $player) {
        $this->inClanCurrentTime = $player->timestamp;
        $this->lastActiveTime    = $player->timestamp;
        
        $this->inClanTotalEnters++;
    }
    
}
