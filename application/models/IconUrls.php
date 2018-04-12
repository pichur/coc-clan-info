<?php

class IconUrls extends HistoryModel {
    
    public static $fieldMapping = [
        'id' => ['key' => true],
    ];
    
    /** @var integer */ public $id;
    
    /** @var string */ public $tiny  ;
    /** @var string */ public $small ;
    /** @var string */ public $medium;
    
}
