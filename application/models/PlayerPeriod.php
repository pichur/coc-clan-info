<?php

class PlayerPeriod extends Model {
    
    const FULL = 'full';
    
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
     * Start number for period, null if from the begining
     * @var integer
     */
    public $startNumber;
    
    /**
     * End number for period, null if to the end
     * @var integer
     */
    public $endNumber;
    
    /**
     * Timestamp of last entry (for history, for war or games - end time) added to totals
     * @var DateTime
     */
    public $timestamp;
    
    public static function constructFull (PlayerHistory $playerHistory) {
        $playerPeriod = new static;
        
        $playerPeriod->tag       = $playerHistory->tag ;
        $playerPeriod->name      = $playerHistory->name;
        $playerPeriod->period    = PlayerPeriod::FULL;
        $playerPeriod->startTime = null;
        $playerPeriod->endTime   = null;
        
        return $playerPeriod;
    }
    
    public static function actualizeName ($tag, $name) {
        static::db()->update(static::table(), ['name' => $name], ['tag' => $tag]);
    }
    
}
