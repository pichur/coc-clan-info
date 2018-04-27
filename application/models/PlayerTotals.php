<?php

class PlayerTotals extends Model {
    
    public static $fieldMapping = [
        'tag'               => ['key' => true],
        'timestamp'         => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'inClanFirstTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'inClanCurrentTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'lastActiveTime'    => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'details'           => ['type' => 'OneToOne', 'target' => PlayerPeriod::class, 'targetKey' => ['period' => PlayerPeriod::FULL]],
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
     * Total days in clan
     * @var double
     */
    public $inClanTotalDays;
    
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
    
    /**
     * Details from full period
     * @var PlayerPeriod
     */
    public $details;
    
    public function init (PlayerHistory $player) {
        $this->tag  = $player->tag ;
        
        $this->details = new PlayerPeriod();
        $this->getDetails()->tag  = $player->tag ;
        
        $this->actualize($player);
        
        $this->inClanFirstTime    = $player->timestamp;
        $this->inClanCurrentTime  = $player->timestamp;
        $this->inClanTotalDays    = 0;
        $this->inClanTotalEnters  = 1;
        
        $this->lastActiveTime     = $player->timestamp;
        
        $this->getDetails()->period    = PlayerPeriod::FULL;
        $this->getDetails()->startTime = null;
        $this->getDetails()->endTime   = null;
        
    }
    
    public function actualize (PlayerHistory $player) {
        $this->timestamp = $player->timestamp;
        $this->name      = $player->name     ;
        
        $this->getDetails()->timestamp = $player->timestamp;
        $this->getDetails()->name      = $player->name     ;
    }
    
    public function enter (PlayerHistory $player) {
        $this->inClanCurrentTime = $player->timestamp;
        $this->lastActiveTime    = $player->timestamp;
        
        $this->inClanTotalEnters++;
    }
    
    public function save () {
        parent::save();
        
        if ($this->details) {
            $this->details->save();
        }
    }
    
    /**
     * @return PlayerPeriod
     */
    public function getDetails() {
        return parent::getModelProperty('details');
    }
    
}
