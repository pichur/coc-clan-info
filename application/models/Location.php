<?php

class Location extends StaticModel {
    
    public static $fieldMapping = [
        'id' => ['key' => true],
    ];
    
    /** @var integer */ public $id         ;
    /** @var string  */ public $name       ;
    /** @var boolean */ public $isCountry  ;
    /** @var string  */ public $countryCode;
    
}
