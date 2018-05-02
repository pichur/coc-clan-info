<?php

/**
 * 
 * @author piotr
 * @method WarClan getClan
 * @method WarClan getOpponent
 */
class War extends SortedModel {
    
    public static $fieldMapping = [
            'number'               => ['key' => true],
            'clan'                 => ['type' => 'OneToOne' , 'target' => WarClan::class, 'targetKey' => ['type' => WarClan::CLAN    ]],
            'opponent'             => ['type' => 'OneToOne' , 'target' => WarClan::class, 'targetKey' => ['type' => WarClan::OPPONENT]],
            'attackList'           => ['type' => 'OneToMany', 'target' => Attack ::class],
            'preparationStartTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'startTime'            => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'endTime'              => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
    ];
    
    /** @var string        */ public $state               ;
    /** @var integer       */ public $teamSize            ;
    /** @var DateTime      */ public $preparationStartTime;
    /** @var DateTime      */ public $startTime           ;
    /** @var DateTime      */ public $endTime             ;
    /** @var WarClan       */ public $clan                ;
    /** @var WarClan       */ public $opponent            ;
    /** @var array[Attack] */ public $attackList          ;
    
    public function save () {
        debug('War save');
        if ($this->state == 'notInWar') {
            return;
        }
        
        $war = static::getBy(['preparationStartTime' => $this->preparationStartTime]);
        
        if ($war->state == 'warEnded') {
            // Processed, save not need
            return;
        }
        
        if ($war) {
            $this->number  = $war->number ;
            $this->_status = $war->_status;
        }
        
        // Basic save (insert or update)
        parent::save();
        
        if ($this->state == 'warEnded') {
            debug('War full save');
            // Full save
            $this->membersStats();
            
            $this->clan    ->number = $this->number;
            $this->clan    ->type   = 'clan';
            $this->clan    ->save();
            
            $this->opponent->number = $this->number;
            $this->opponent->type   = 'opponent';
            $this->opponent->save();
        }
        
        debug('War save end');
    }
    
    /**
     * Calculate memver statistics
     */
    private function membersStats () {
        // Transfer data for clan
        $this->transterAttacks ($this->clan, $this->opponent);
        $this->transterDefenses($this->clan, $this->opponent);
        // Transfer data for opponent
        $this->transterAttacks ($this->opponent, $this->clan);
        $this->transterDefenses($this->opponent, $this->clan);
    }
    
    private function transterAttacks ($clan, $opponent) {
        $attackers            = [];
        $defenders            = [];
        $opponentsStars       = [];
        $opponentsDestruction = [];
        $attackList           = [];
        
        foreach ($clan->members as $player) {
            $attackers[$player->tag] = $player;
            $player->attackCount = count($player->attacks);
            if ($player->attackCount) {
                foreach ($player->attacks as $attack) {
                    $attackList[$attack->order] = $attack;
                }
            }
        }
        
        foreach ($opponent->members as $player) {
            $defenders[$player->tag] = $player;
        }
        
        foreach ($attackList as $attack) {
            $player = $attackers[$attack->attackerTag];
            
            $oldStars = $opponentsStars[$attack->defenderTag];
            $player->stars += $attack->stars;
            if ($attack->stars > $oldStars) {
                $opponentsStars[$attack->defenderTag] = $attack->stars;
                $newStars = $attack->stars - $oldStars;
                $player->newStars += $newStars;
            }
            
            $oldDestruction = $opponentsDestruction[$attack->defenderTag];
            $player->destruction += $attack->destructionPercentage;
            if ($attack->destructionPercentage > $oldDestruction) {
                $opponentsDestruction[$attack->defenderTag] = $attack->destructionPercentage;
                $newDestruction = $attack->destructionPercentage - $oldDestruction;
                $player->newDestruction += $newDestruction;
            }
            
            $attackerPosition = $attackers[$attack->attackerTag]->mapPosition;
            $defenderPosition = $defenders[$attack->defenderTag]->mapPosition;
            $player->attackPositionDiff += $defenderPosition - $attackerPosition;
        }
        
        foreach ($clan->members as $player) {
            if ($player->attackCount > 0) {
                $player->attackPositionDiffAvg = $player->attackPositionDiff / $player->attackCount;
            }
        }
    }
    
    private function transterDefenses ($clan, $opponent) {
        $attackers       = [];
        $defenders       = [];
        $lostStars       = [];
        $lostDestruction = [];
        $defenseList     = [];
        
        foreach ($clan->members as $player) {
            $defenders[$player->tag] = $player;
        }
        
        foreach ($opponent->members as $player) {
            $attackers[$player->tag] = $player;
            if (is_array($player->attacks)) {
                foreach ($player->attacks as $attack) {
                    $defenseList[$attack->order] = $attack;
                }
            }
        }
        
        foreach ($defenseList as $attack) {
            $player = $defenders[$attack->defenderTag];
            
            if ($attack->stars > $player->lostStars) {
                $player->lostStars = $attack->stars;
            }
            
            if ($attack->destructionPercentage > $player->lostDestruction) {
                $player->lostDestruction = $attack->destructionPercentage;
            }
            
            $attackerPosition = $attackers[$attack->attackerTag]->mapPosition;
            $defenderPosition = $defenders[$attack->defenderTag]->mapPosition;
            $player->defensePositionDiff += $attackerPosition - $defenderPosition;
        }
        
        foreach ($clan->members as $player) {
            if ($player->opponentAttacks > 0) {
                $player->defensePositionDiffAvg = $player->defensePositionDiff / $player->opponentAttacks;
            }
        }
    }
    
    public function isWin  () { return $this->getClan()->stars >  $this->getOpponent()->stars; }
    public function isTie  () { return $this->getClan()->stars == $this->getOpponent()->stars; }
    public function isLoss () { return $this->getClan()->stars <  $this->getOpponent()->stars; }
    
    public function getAttacksPercentage () { return (100 * $this->getClan()->attacks) / (2 * $this->teamSize); }
    public function getStarsPercentage   () { return (100 * $this->getClan()->stars  ) / (3 * $this->teamSize); }
    
}
