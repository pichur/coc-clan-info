<?php

class PlayerInfo extends HistoryModel {
    
    public static $fieldMapping = [
        'timestamp' => ['key' => true, 'jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'tag'       => ['key' => true],
        'name'      => ['key' => true],
        'village'   => ['key' => true],
    ];
    
    /** @var string */ public $tag    ;
    /** @var string */ public $name   ;
    /** @var string */ public $village;
    
}
