<?php

class PlayerWarPeriod extends PlayerPeriod {
    
    public static $fieldMapping = [
        'tag'       => ['key' => true],
        'period'    => ['key' => true],
        'startTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'endTime'   => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'timestamp' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /** @var integer  */ public $count                 ;
    /** @var integer  */ public $mapPositionMin        ;
    /** @var double   */ public $mapPositionAvg        ;
    /** @var integer  */ public $mapPositionMax        ;
                                                       
    /** @var integer  */ public $attacks               ;
    /** @var integer  */ public $stars                 ;
    /** @var integer  */ public $newStars              ;
    /** @var integer  */ public $destruction           ;
    /** @var integer  */ public $newDestruction        ;
    /** @var integer  */ public $defenses              ;
    /** @var integer  */ public $lostStars             ;
    /** @var integer  */ public $lostDestruction       ;
                                                       
    /** @var double   */ public $attacksAvg            ;
    /** @var double   */ public $starsAvg              ;
    /** @var double   */ public $newStarsAvg           ;
    /** @var double   */ public $destructionAvg        ;
    /** @var double   */ public $newDestructionAvg     ;
    /** @var double   */ public $defensesAvg           ;
    /** @var double   */ public $lostStarsAvg          ;
    /** @var double   */ public $lostDestructionAvg    ;
    
    /** @var double   */ public $attackPositionDiffAvg ;
    /** @var double   */ public $defensePositionDiffAvg;
    
    private function initWar (War $war, WarPlayer $warPlayer) {
        $this->count++;
        $this->timestamp = $war->endTime;
        
        $this->mapPositionMin = $warPlayer->mapPosition;
        $this->mapPositionAvg = $warPlayer->mapPosition;
        $this->mapPositionMax = $warPlayer->mapPosition;
        
        $this->attacks         = $warPlayer->attackCount    ;
        $this->stars           = $warPlayer->stars          ;
        $this->newStars        = $warPlayer->newStars       ;
        $this->destruction     = $warPlayer->destruction    ;
        $this->newDestruction  = $warPlayer->newDestruction ;
        $this->defenses        = $warPlayer->opponentAttacks;
        $this->lostStars       = $warPlayer->lostStars      ;
        $this->lostDestruction = $warPlayer->lostDestruction;
        
        $this->attacksAvg         = $warPlayer->attackCount    ;
        $this->starsAvg           = $warPlayer->stars          ;
        $this->newStarsAvg        = $warPlayer->newStars       ;
        $this->destructionAvg     = $warPlayer->destruction    ;
        $this->newDestructionAvg  = $warPlayer->newDestruction ;
        $this->defensesAvg        = $warPlayer->opponentAttacks;
        $this->lostStarsAvg       = $warPlayer->lostStars      ;
        $this->lostDestructionAvg = $warPlayer->lostDestruction;
        
        $this->attackPositionDiffAvg  = $warPlayer->attackPositionDiffAvg ;
        $this->defensePositionDiffAvg = $warPlayer->defensePositionDiffAvg;
    }
    
    public function addWar (War $war, WarPlayer $warPlayer) {
        if (!$this->count) {
            $this->initWar($war, $warPlayer);
            return;
        }
        $this->count++;
        $this->timestamp = $war->endTime;
        
        $this->mapPositionMin = min($this->mapPositionMin, $warPlayer->mapPosition);                     
        $this->mapPositionAvg = avg($this->mapPositionAvg, $warPlayer->mapPosition, $this->count);
        $this->mapPositionMax = max($this->mapPositionMax, $warPlayer->mapPosition);                     
        
        $this->attacks         += $warPlayer->attackCount    ;
        $this->stars           += $warPlayer->stars          ;
        $this->newStars        += $warPlayer->newStars       ;
        $this->destruction     += $warPlayer->destruction    ;
        $this->newDestruction  += $warPlayer->newDestruction ;
        $this->defenses        += $warPlayer->opponentAttacks;
        $this->lostStars       += $warPlayer->lostStars      ;
        $this->lostDestruction += $warPlayer->lostDestruction;
        
        $this->attacksAvg         = avg($this->attacksAvg        , $warPlayer->attackCount    , $this->count);
        $this->starsAvg           = avg($this->starsAvg          , $warPlayer->stars          , $this->count);
        $this->newStarsAvg        = avg($this->newStarsAvg       , $warPlayer->newStars       , $this->count);
        $this->destructionAvg     = avg($this->destructionAvg    , $warPlayer->destruction    , $this->count);
        $this->newDestructionAvg  = avg($this->newDestructionAvg , $warPlayer->newDestruction , $this->count);
        $this->defensesAvg        = avg($this->defensesAvg       , $warPlayer->opponentAttacks, $this->count);
        $this->lostStarsAvg       = avg($this->lostStarsAvg      , $warPlayer->lostStars      , $this->count);
        $this->lostDestructionAvg = avg($this->lostDestructionAvg, $warPlayer->lostDestruction, $this->count);
        
        $this->attackPositionDiffAvg  = avg($this->attackPositionDiffAvg , $warPlayer->attackPositionDiffAvg , $this->count);
        $this->defensePositionDiffAvg = avg($this->defensePositionDiffAvg, $warPlayer->defensePositionDiffAvg, $this->count);
    }
    
}
