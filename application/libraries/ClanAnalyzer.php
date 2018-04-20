<?php

class ClanAnalyzer {
    
    /** @var ClanHistory */ private $history;
    /** @var ClanTotals  */ private $totals ;
    
    public static function construct(ClanHistory $history) {
        $analyzer = new ClanAnalyzer();
        
        $analyzer->history = $history;
        $analyzer->totals  = ClanTotals::getBy(['tag' => $history->tag]);
        
        return $analyzer;
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
