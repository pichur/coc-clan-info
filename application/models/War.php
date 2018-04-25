<?php

class War extends SortedModel {
    
    public static $fieldMapping = [
            'clan'                 => ['type' => 'OneToOne' , 'target' => WarClan::class],
            'opponent'             => ['type' => 'OneToOne' , 'target' => WarClan::class],
            'attackList'           => ['type' => 'OneToMany', 'target' => Attack ::class],
            'preparationStartTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'startTime'            => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'endTime'              => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'number'               => ['key' => true],
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
            $this->clan    ->number = $this->number;
            $this->clan    ->type   = 'clan';
            $this->clan    ->save();
            
            $this->opponent->number = $this->number;
            $this->opponent->type   = 'opponent';
            $this->opponent->save();
            
            $this->memebersStats();
        }
    }
    
    /**
     * Calculate memver statistics
     */
    private function memebersStats () {
        
    }
    
}
