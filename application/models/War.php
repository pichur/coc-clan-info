<?php

class War extends Model {
    
    public static $fieldMapping = [
            'clan'       => ['target' => WarClan::class, 'relation' => 'OneToOne' ],
            'opponent'   => ['target' => WarClan::class, 'relation' => 'OneToOne' ],
            'attackList' => ['target' => Attack ::class, 'relation' => 'OneToMany'],
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
    
    public function save () {
        if ($state != 'warEnded') {
            return;
        }
        if (!$this->warNumber) {
            $result = $this->db()->select_max('warNumber')->from($this->table())->get()->result();
            $count = count($result);
            if ($count == 0) {
                $this->warNumber= 1;
            } else {
                $this->warNumber= $result[0]->warNumber+ 1;
            }
        }
        
        parent::save();
        
        $this->clan    ->warNumber = $this->warNumber;
        $this->clan    ->type      = 'clan';
        $this->clan    ->save();
        $this->opponent->warNumber = $this->warNumber;
        $this->opponent->type      = 'opponent';
        $this->opponent->save();
    }
    
}
