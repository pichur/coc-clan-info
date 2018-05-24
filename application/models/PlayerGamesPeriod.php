<?php

class PlayerGamesPeriod extends PlayerPeriod {
    
    public static $fieldMapping = [
        'tag'       => ['key' => true],
        'period'    => ['key' => true],
        'startTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'endTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'timestamp' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /** @var integer */ public $count              ;
    /** @var integer */ public $points             ;
    /** @var double  */ public $pointsPercentageMin;
    /** @var double  */ public $pointsPercentageAvg;
    /** @var double  */ public $pointsPercentageMax;
    
    private function initGames (Games $games, GamesPlayer $gamesPlayer) {
        $this->count++;
        $this->timestamp = $games->endTime;
        
        $this->points              = $gamesPlayer->points    ;
        $this->pointsPercentageMin = $gamesPlayer->percentage;
        $this->pointsPercentageAvg = $gamesPlayer->percentage;
        $this->pointsPercentageMax = $gamesPlayer->percentage;
        
    }
    
    public function addGames (Games $games, GamesPlayer $gamesPlayer) {
        if (!$this->count) {
            $this->initGames($games, $gamesPlayer);
            return;
        }
        $this->count++;
        $this->timestamp = $games->endTime;
        
        $this->points += $gamesPlayer->points;
        
        $this->pointsPercentageMin = min($this->pointsPercentageMin, $gamesPlayer->percentage);
        $this->pointsPercentageAvg = avg($this->pointsPercentageAvg, $gamesPlayer->percentage, $this->count);
        $this->pointsPercentageMax = max($this->pointsPercentageMax, $gamesPlayer->percentage);
    }
    
}
