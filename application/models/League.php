<?php

class League extends StaticModel {
    
    public static $fieldMapping = [
            'iconUrls' => IconUrls::class,
    ];
    
    /** @var string   */ public $name    ;
    /** @var IconUrls */ public $iconUrls;

}
