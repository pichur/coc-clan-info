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
    /** @var integer */ public $missingPoints      ;
    /** @var double  */ public $pointsPercentageAvg;
    /** @var double  */ public $maxPointsPercentage;
    
}
