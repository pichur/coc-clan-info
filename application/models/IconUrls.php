<?php

class IconUrls extends HistoryModel {
    
    public static $fieldMapping = [
        'timestamp' => ['key' => true, 'jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'id'        => ['key' => true],
    ];
    
    /** @var integer */ public $id;
    
    /** @var string */ public $tiny  ;
    /** @var string */ public $small ;
    /** @var string */ public $medium;
    
    public function save () {
        parent::save();
    }
}
