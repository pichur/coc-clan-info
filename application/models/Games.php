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
    
    /** @var DateTime           */ public $startTime;
    /** @var DateTime           */ public $endTime  ;
    /** @var boolean            */ public $finished ;
    /** @var integer            */ public $maxPoints;
    /** @var array[GamesPlayer] */ public $players  ;
    
    public function addPlayerPoints (PlayerHistory $playerHistory, int $newClanGamesPoints) {
        // Actualize date
        $this->endTime = $playerHistory->timestamp;
        
        foreach ($this->getPlayers() as $player) {
            if ($player->tag == $playerHistory->tag) {
                // Add points to existing player
                $player->points += $newClanGamesPoints;
                return;
            }
        }
        
        if (!is_array($this->players)) {
            $this->players = [];
        }
        
        // Add new entry
        $this->players[] = GamesPlayer::fromPlayerHistory($playerHistory, $newClanGamesPoints);
    }
    
    public function save () {
        parent::save();
        
        foreach ($this->getPlayers() as $player) {
            $player->number = $this->number;
            $player->save();
        }
    }
    
}
