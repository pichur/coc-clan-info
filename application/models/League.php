<?php

class League extends StaticModel {
    
    public static $fieldMapping = [
        'iconUrls' => ['target' => IconUrls::class, 'relation' => 'OneToOne' ],
    ];
    
    /** @var string   */ public $name    ;
    /** @var IconUrls */ public $iconUrls;

}
