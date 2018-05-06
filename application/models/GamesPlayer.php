<?php

class GamesPlayer extends Model {
    
    public static $fieldMapping = [
        'number' => ['key' => true],
        'tag'    => ['key' => true],
    ];
    
    /** @var integer */ public $number    ;
    /** @var string  */ public $tag       ;
    /** @var string  */ public $name      ;
    /** @var integer */ public $points    ;
    /** @var double  */ public $percentage;
    
    /**
     * 
     * @param PlayerHistory $playerHistory
     * @param int $newClanGamesPoints
     * @return GamesPlayer
     */
    public static function fromPlayerHistory(PlayerHistory $playerHistory, int $newClanGamesPoints) {
        $gamesPlayer = new GamesPlayer();
        
        $gamesPlayer->tag  = $playerHistory->tag ;
        $gamesPlayer->name = $playerHistory->name;
        
        $gamesPlayer->points = $newClanGamesPoints;
        
        return $gamesPlayer;
    }
    
}
