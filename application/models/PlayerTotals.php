<?php

/**
 *
 * @author piotr
 * @method PlayerClanPeriod  getClanDetails
 * @method PlayerWarPeriod   getWarDetails
 * @method PlayerGamesPeriod getGamesDetails
 */
class PlayerTotals extends Model {
    
    public static $fieldMapping = [
        'tag'               => ['key' => true],
        'timestamp'         => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'inClanFirstTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'inClanCurrentTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'lastActiveTime'    => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'clanDetails'       => ['type' => 'OneToOne', 'target' => PlayerClanPeriod ::class, 'targetKey' => ['period' => PlayerPeriod::FULL]],
        'warDetails'        => ['type' => 'OneToOne', 'target' => PlayerWarPeriod  ::class, 'targetKey' => ['period' => PlayerPeriod::FULL]],
        'gamesDetails'      => ['type' => 'OneToOne', 'target' => PlayerGamesPeriod::class, 'targetKey' => ['period' => PlayerPeriod::FULL]],
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
     * Clan details from full period
     * @var PlayerClanPeriod
     */
    public $clanDetails;
    
    /**
     * War details from full period
     * @var PlayerWarPeriod
     */
    public $warDetails;
    
    /**
     * Games details from full period
     * @var PlayerGamesPeriod
     */
    public $gamesDetails;
    
    public function init (PlayerHistory $player) {
        $this->tag  = $player->tag ;
        $this->name = $player->name;
        
        $this->clanDetails  = PlayerClanPeriod ::constructFull($player);
        $this->warDetails   = PlayerWarPeriod  ::constructFull($player);
        $this->gamesDetails = PlayerGamesPeriod::constructFull($player);
        
        $this->inClanFirstTime    = $player->timestamp;
        $this->inClanCurrentTime  = $player->timestamp;
        $this->inClanTotalDays    = 0;
        $this->inClanTotalEnters  = 1;
        
        $this->lastActiveTime     = $player->timestamp;
    }
    
    public function enter (PlayerHistory $player) {
        $this->inClanCurrentTime = $player->timestamp;
        $this->lastActiveTime    = $player->timestamp;
        
        $this->inClanTotalEnters++;
    }
    
    public function save () {
        parent::save();
        
        if ($this->clanDetails ) $this->clanDetails ->save();
        if ($this->warDetails  ) $this->warDetails  ->save();
        if ($this->gamesDetails) $this->gamesDetails->save();
    }
    
    public function addWar (War $war, WarPlayer $warPlayer) {
        if ($warPlayer->newDestruction) {
            $this->lastActiveTime = $war->endTime;
        }
        $this->getWarDetails()->addWar($war, $warPlayer);
    }
    
}
