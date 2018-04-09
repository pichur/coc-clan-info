<?php

class War extends Model {
    
    /** @var string        */ public $state               ;
    /** @var integer       */ public $teamSize            ;
    /** @var string        */ public $preparationStartTime;
    /** @var string        */ public $startTime           ;
    /** @var string        */ public $endTime             ;
    /** @var Opponent      */ public $clan                ;
    /** @var Opponent      */ public $opponent            ;
    /** @var array[Attack] */ public $attackList          ;
    
}
