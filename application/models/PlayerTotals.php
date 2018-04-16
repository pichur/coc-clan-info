<?php

class PlayerTotals extends Model {
    
    /** @var string  */ public $tag              ;
    
    /** @var integer */ public $inClanFirstTime  ;
    /** @var integer */ public $inClanCurrentTime;
    /** @var integer */ public $inClanTotalHours ;
    /** @var integer */ public $inClanTotalEnters;
                                                 
    /** @var integer */ public $donations        ;
    /** @var integer */ public $donationsReceived;
                                                 
    /** @var integer */ public $warCount         ;
    /** @var integer */ public $warAttacks       ;
    /** @var integer */ public $warStars         ;
    /** @var integer */ public $warNewStars      ;
    /** @var integer */ public $warDefenses      ;
    /** @var integer */ public $warLostStars     ;
    /** @var integer */ public $warOpponents     ;
    /** @var integer */ public $warOpponentDiffs ;
                                                 
    /** @var integer */ public $gamesCount       ;
    /** @var integer */ public $gamesPoints      ;
    /** @var integer */ public $gamesMissingPoint;
    /** @var integer */ public $gamesPercentage  ;
    
}
