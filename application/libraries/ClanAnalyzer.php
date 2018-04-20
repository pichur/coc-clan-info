<?php

class ClanAnalyzer {
    
    /** @var ClanHistory */ private $history;
    /** @var ClanTotals  */ private $totals ;
    
    public function __construct(ClanHistory $history) {
        $this->history = $history;
        $this->totals  = ClanTotals::getBy(['tag' => $tag]);
    }
    
    public function analyze () {
        if ($this->totals == null) {
            $this->totals = new ClanTotals();
            $this->totals->init($this->history);
        }
        
        $this->totals->addClanHistory($this->history);
        
        $this->totals->save();
    }
    
}
