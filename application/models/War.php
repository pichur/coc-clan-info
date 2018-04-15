<?php

class War extends Model {
    
    public static $fieldMapping = [
            'clan'                 => ['type' => 'OneToOne' , 'target' => WarClan::class],
            'opponent'             => ['type' => 'OneToOne' , 'target' => WarClan::class],
            'attackList'           => ['type' => 'OneToMany', 'target' => Attack ::class],
            'preparationStartTime' => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'startTime'            => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'endTime'              => ['jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
            'warNumber'            => ['key' => true],
    ];
    
    /** @var integer       */ public $warNumber           ;
    /** @var string        */ public $state               ;
    /** @var integer       */ public $teamSize            ;
    /** @var DateTime      */ public $preparationStartTime;
    /** @var DateTime      */ public $startTime           ;
    /** @var DateTime      */ public $endTime             ;
    /** @var WarClan       */ public $clan                ;
    /** @var WarClan       */ public $opponent            ;
    /** @var array[Attack] */ public $attackList          ;
    
    /**
     * @return War|NULL
     */
    public function loadLast () {
        $war = $this->db()->select()->from('War')->order_by('warNumber', 'DESC')->limit(1)->get()->custom_row_object(0, 'War');
        if ($war) {
            $war->fixDbLoad();
        }
    }
    
    protected function exist () {
        return $this->warNumber > 0;
    }
    
    protected function autoKey () {
        if ($this->warNumber) {
            return;
        }
        
        $result = $this->db()->select_max('warNumber')->from($this->table())->get()->result();
        $count = count($result);
        if ($count == 0) {
            $this->warNumber = 1;
        } else {
            $this->warNumber = $result[0]->warNumber + 1;
        }
    }
    
    public function save () {
        if ($this->state == 'notInWar') {
            return;
        }
        
        $war = $this->getBy('preparationStartTime');
        
        if ($war) {
            $this->warNumber = $war->warNumber;
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
    
    public static function jsonToDate ($input) {
        $input = substr($input, 0, -5);
        $date = DateTime::createFromFormat('Ymd\THis', $input, new DateTimeZone('UTC'));
        $date->setTimeZone(new DateTimeZone(date_default_timezone_get()));
        return $date;
    }
    
    public static function dbToDate ($input) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $input);
        return $date;
    }
    
}
