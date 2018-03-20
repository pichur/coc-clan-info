<?php

class League extends Model {
    
    public static $fieldMapping = [
            iconUrls => IconUrls::class,
    ];
    
    /** @var integer  */ public $id      ;
    /** @var string   */ public $name    ;
    /** @var IconUrls */ public $iconUrls;

}
