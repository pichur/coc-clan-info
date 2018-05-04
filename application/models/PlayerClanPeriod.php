<?php

class PlayerClanPeriod extends PlayerPeriod {
    
    public static $fieldMapping = [
        'tag'         => ['key' => true],
        'period'      => ['key' => true],
        'startTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'endTime'     => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'timestamp'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'startNumber' => ['type' => 'transient'],
        'endNumber'   => ['type' => 'transient'],
    ];
    
    /** @var integer */ public $donations        ;
    /** @var integer */ public $donationsReceived;
    
}
