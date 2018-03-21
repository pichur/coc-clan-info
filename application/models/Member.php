<?php

class Member extends Model {
    
    public static $fieldMapping = [
            'league' => League::class,
    ];
    
    /** @var string  */ public $tag              ;
    /** @var string  */ public $name             ;
    /** @var string  */ public $role             ;
    /** @var integer */ public $expLevel         ;
    /** @var League  */ public $league           ;
    /** @var integer */ public $trophies         ;
    /** @var integer */ public $versusTrophies   ;
    /** @var integer */ public $clanRank         ;
    /** @var integer */ public $previousClanRank ;
    /** @var integer */ public $donations        ;
    /** @var integer */ public $donationsReceived;

}
