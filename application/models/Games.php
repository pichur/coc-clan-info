<?php

/**
 * 
 * @author piotr
 * @method array[GamesPlayer] getPlayers
 */
class Games extends SortedModel {
    
    public static $fieldMapping = [
        'number'    => ['key' => true],
        'startTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'endTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'players'   => ['type' => 'OneToMany', 'target' => GamesPlayer::class],
    ];
    
    /** @var DateTime           */ public $startTime     ;
    /** @var DateTime           */ public $endTime       ;
    /** @var boolean            */ public $finished      ;
    /** @var boolean            */ public $analyzed      ;
    /** @var integer            */ public $userMaxPoints ;
    /** @var integer            */ public $totalMaxPoints;
    /** @var integer            */ public $totalPoints   ;
    /** @var integer            */ public $pointPlayers  ;
    /** @var integer            */ public $maxPlayers    ;
    /** @var array[GamesPlayer] */ public $players       ;
    
    public function addPlayerPoints (PlayerHistory $playerHistory, int $newClanGamesPoints) {
        // Actualize date
        $this->endTime = $playerHistory->timestamp;
        
        foreach ($this->getPlayers() as $player) {
            if ($player->tag == $playerHistory->tag) {
                // Add points to existing player
                $player->allPoints += $newClanGamesPoints;
                return;
            }
        }
        
        if (!is_array($this->players)) {
            $this->players = [];
        }
        
        // Add new entry
        $this->players[] = GamesPlayer::fromPlayerHistory($playerHistory, $newClanGamesPoints);
    }
    
    public function analyze () {
        $this->findUserMaxPoints();
        
        $this->totalPoints  = 0;
        $this->pointPlayers = 0;
        $this->maxPlayers   = 0;
        foreach ($this->getPlayers() as $player) {
            $player->points = min($this->userMaxPoints, $player->allPoints);
            $player->percentage = 100 * $player->points / $this->userMaxPoints;
            $this->totalPoints += $player->points;
            if ($player->points) {
                $this->pointPlayers++;
            }
            if ($player->points == $this->userMaxPoints) {
                $this->maxPlayers++;
            }
        }
        
        $this->analyzed = true;
    }
    
    private function findUserMaxPoints () {
        if (!$this->userMaxPoints) {
            $maxUserPoints = 0;
            foreach ($this->getPlayers() as $player) {
                $maxUserPoints = max($maxUserPoints, $player->allPoints);
            }
            $this->userMaxPoints = round($maxUserPoints, 1 - strlen($maxUserPoints));
        }
    }
    
    public function save () {
        if ($this->finished && !$this->analyzed) {
            $this->analyze();
        }
        
        parent::save();
        
        foreach ($this->getPlayers() as $player) {
            $player->number = $this->number;
            $player->save();
        }
    }
    
}
