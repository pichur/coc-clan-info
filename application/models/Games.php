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
    
}
