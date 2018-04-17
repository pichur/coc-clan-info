<?php

class PlayerTotals extends Model {
    
    /** @var string   */ public $tag              ;
    
    /** @var DateTime */ public $inClanFirstTime  ;
    /** @var DateTime */ public $inClanCurrentTime;
    /** @var integer  */ public $inClanTotalHours ;
    /** @var integer  */ public $inClanTotalEnters;
    
    /** @var DateTime */ public $lastActiveTime   ;
    
    /** @var integer  */ public $donations        ;
    /** @var integer  */ public $donationsReceived;
    
    /** @var integer  */ public $warCount         ;
    /** @var integer  */ public $warAttackCount   ;
    /** @var integer  */ public $warStars         ;
    /** @var integer  */ public $warNewStars      ;
    /** @var integer  */ public $warDefenses      ;
    /** @var integer  */ public $warLostStars     ;
    /** @var double   */ public $warOpponents     ;
    /** @var double   */ public $warOpponentDiffs ;
    
    /** @var integer  */ public $gamesCount       ;
    /** @var integer  */ public $gamesPoints      ;
    /** @var integer  */ public $gamesMissingPoint;
    /** @var double   */ public $gamesPercentage  ;
    
}
