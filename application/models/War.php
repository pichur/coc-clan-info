<?php

class War extends SortedModel {
    
    public static $fieldMapping = [
            'number'               => ['key' => true],
            'clan'                 => ['type' => 'OneToOne' , 'target' => WarClan::class],
            'opponent'             => ['type' => 'OneToOne' , 'target' => WarClan::class],
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
        if ($this->state == 'notInWar') {
            return;
        }
        
        $war = static::getBy(['preparationStartTime' => $this->preparationStartTime]);
        
        if ($war) {
            $this->number = $war->number;
        }
        
        // Basic save (insert or update)
        parent::save();
        
        if ($this->state == 'warEnded') {
            // Full save
            $this->membersStats();
            
            $this->clan    ->number = $this->number;
            $this->clan    ->type   = 'clan';
            $this->clan    ->save();
            
            $this->opponent->number = $this->number;
            $this->opponent->type   = 'opponent';
            $this->opponent->save();
        }
    }
    
    /**
     * Calculate memver statistics
     */
    private function membersStats () {
        $this->transterAttacks();
        
    }
    
    private function transterAttacks () {
        $attackers            = [];
        $defenders            = [];
        $opponentsStars       = [];
        $opponentsDestruction = [];
        $attackList           = [];
        
        foreach ($this->clan->members as $player) {
            $attackers[$player->tag] = $player;
            $player->attackCount = count($player->attacks);
            if ($player->attackCount) {
                foreach ($player->attacks as $attack) {
                    $attackList[$attack->number] = $attack;
                }
            }
        }
        
        foreach ($this->opponent->members as $player) {
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
        
        foreach ($this->clan->members as $player) {
            if ($player->attackCount > 0) {
                $player->attackPositionDiffAvg = $player->attackPositionDiff / $player->attackCount;
            }
        }
    }
    
    private function transterDefenses () {
        $attackers       = [];
        $defenders       = [];
        $lostStars       = [];
        $lostDestruction = [];
        $defenseList     = [];
        
        foreach ($this->clan->members as $player) {
            $defenders[$player->tag] = $player;
        }
        
        foreach ($this->opponent->members as $player) {
            $attackers[$player->tag] = $player;
            if (is_array($player->attacks)) {
                foreach ($player->attacks as $attack) {
                    $defenseList[$attack->number] = $attack;
                }
            }
        }
        
        foreach ($defenseList as $attack) {
            $player = $defenders[$attack->defenderTag];
            
            $player->defenseCount++;
            
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
        
        foreach ($this->clan->members as $player) {
            if ($player->defenseCount > 0) {
                $player->defensePositionDiffAvg = $player->defensePositionDiff / $player->defenseCount;
            }
        }
    }
    
}
