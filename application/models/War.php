<?php

class War extends Model {
    
    public static $fieldMapping = [
            'clan'                 => ['target' => WarClan::class, 'relation' => 'OneToOne' ],
            'opponent'             => ['target' => WarClan::class, 'relation' => 'OneToOne' ],
            'attackList'           => ['target' => Attack ::class, 'relation' => 'OneToMany'],
            'preparationStartTime' => ['converter' => 'toDate'],
            'startTime'            => ['converter' => 'toDate'],
            'endTime'              => ['converter' => 'toDate'],
    ];
    
    /** @var integer       */ public $warNumber           ;
    /** @var string        */ public $state               ;
    /** @var integer       */ public $teamSize            ;
    /** @var string        */ public $preparationStartTime;
    /** @var string        */ public $startTime           ;
    /** @var string        */ public $endTime             ;
    /** @var WarClan       */ public $clan                ;
    /** @var WarClan       */ public $opponent            ;
    /** @var array[Attack] */ public $attackList          ;
    
    protected function exsist () {
        return $this->warNumber > 0;
    }
    
    protected function key () {
        return ['warNumber' => $this->warNumber];
    }
    
    public function save () {
        if ($this->state == 'notInWar') {
            return;
        }
        
        $war = $this->getBy('preparationStartTime');
        
        if ($war) {
            $this->warNumber = $war->warNumber;
        } else {
            $result = $this->db()->select_max('warNumber')->from($this->table())->get()->result();
            $count = count($result);
            if ($count == 0) {
                $this->warNumber = 1;
            } else {
                $this->warNumber = $result[0]->warNumber + 1;
            }
        }
        
        // Basic save (insert or update)
        parent::save();
        
        if ($this->state == 'warEnded') {
            // Full save
            $this->clan    ->warNumber = $this->warNumber;
            $this->clan    ->type      = 'clan';
            $this->clan    ->save();
            
            $this->opponent->warNumber = $this->warNumber;
            $this->opponent->type      = 'opponent';
            $this->opponent->save();
        }
    }
    
    public static function toDate ($input) {
        $input = substr($input, 0, -5);
        $date = date_create_from_format('Ymd\THis', $input);
        $result = $date->format('Y-m-d H:i:s');
        return $esult;
    }
    
}
