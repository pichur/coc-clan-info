<?php

class Games extends SortedModel {
    
    public static $fieldMapping = [
        'startTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'endTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /** @var DateTime */ public $startTime;
    /** @var DateTime */ public $endTime  ;
    /** @var integer  */ public $maxPoints;
    
}
