<?php

class Attack extends StaticModel {
    
    public static $fieldMapping = [
        'number' => ['key' => true],
        'order'  => ['key' => true, 'dbName' => 'order_'],
    ];
    
    /**
     * War number
     * @var integer
     */
    public $number;
    
    /**
     * Position on
     * @var integer
     */
    public $order;
    
    /** @var string  */ public $attackerTag          ;
    /** @var string  */ public $defenderTag          ;
    /** @var integer */ public $stars                ;
    /** @var integer */ public $destructionPercentage;
    
}
