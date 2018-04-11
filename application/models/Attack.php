<?php

class Attack extends StaticModel {
    
    public static $fieldMapping = [
        'warNumber' => ['key' => true],
        'position'  => ['key' => true, 'jsonName' => 'order'],
    ];
    
    /** @var integer */ public $warNumber            ;
    /** @var integer */ public $position             ;
    /** @var string  */ public $attackerTag          ;
    /** @var string  */ public $defenderTag          ;
    /** @var integer */ public $stars                ;
    /** @var integer */ public $destructionPercentage;
    
}
