<?php

class Attack extends StaticModel {
    
    public static $fieldMapping = [
        'warNumber' => ['key' => true],
        'order'     => ['key' => true],
    ];
    
    /** @var integer */ public $warNumber            ;
    /** @var integer */ public $order                ;
    /** @var string  */ public $attackerTag          ;
    /** @var string  */ public $defenderTag          ;
    /** @var integer */ public $stars                ;
    /** @var integer */ public $destructionPercentage;
    
}
