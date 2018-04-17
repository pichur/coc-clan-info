<?php

class Attack extends StaticModel {
    
    public static $fieldMapping = [
        'number'   => ['key' => true],
        'position' => ['key' => true, 'jsonName' => 'order'],
    ];
    
    /** @var integer */ public $number               ;
    /** @var integer */ public $position             ;
    /** @var string  */ public $attackerTag          ;
    /** @var string  */ public $defenderTag          ;
    /** @var integer */ public $stars                ;
    /** @var integer */ public $destructionPercentage;
    
}
