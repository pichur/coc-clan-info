<?php

class WarAnalyzer {
    
    /**
     * @var ClanTotals
     */
    private $totals;
    
    /**
     * @var array[War]
     */
    private $warList;
    
    public function __construct () {
        $this->totals = ClanTotals::getBy(['tag' => config_item('clan_tag')]);
        
        $this->warList = War::loadByOrder('timestamp', $this->totals->warTimestamp, null, 'ASC');
    }
    
    public function analyze () {
        debug('War analyze');
        
        if (!$this->totals) {
            throw new Exception('War analyze need analyzed clan');
        }
        
        if (!$this->warList) {
            debug('Nothing to add');
            return;
        }
        
        $this->process();
        
        debug('Clan totals save');
        $this->totals->save();
        
        debug('War analyze finished');
    }
    
}
