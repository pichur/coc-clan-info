<?php

class BadgeUrls extends HistoryModel {
    
    public static $fieldMapping = [
        'timestamp' => ['key' => true, 'jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /** @var string */ public $small ;
    /** @var string */ public $medium;
    /** @var string */ public $large ;
    
}
