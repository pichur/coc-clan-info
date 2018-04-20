<?php

class PlayerAnalyzer {
    
    /** @var PlayerHistory */ private $previous;
    /** @var PlayerHistory */ private $current ;
    /** @var PlayerTotals  */ private $totals  ;
    
    public static function construct (PlayerHistory $history, $previousTimestamp) {
        $analyzer = new PlayerAnalyzer();
        
        $analyzer->current = $history;
        
        if ($previousTimestamp) {
            $analyzer->previous = PlayerHistory::getBy(['tag' => $history->tag, 'timestamp' => $previousTimestamp]);
        }
        
        $analyzer->totals = PlayerTotals::getBy(['tag' => $history->tag]);
        
        return $analyzer;
    }
    
    public function analyze () {
        if ($this->totals == null) {
            $this->totals = new PlayerTotals();
            $this->totals->init($this->current);
        } else {
            if ($this->previous == null) {
                $this->totals->enter($this->current);
            }
        }
        
        $this->donations();
        
        $this->totals->timestamp = $this->current->timestamp;
        $this->totals->save();
    }
    
    private function donations () {
        $donations         = 0;
        $donationsReceived = 0;
        
        if (       ($this->previous)
                && ($this->previous->donations         < $this->current->donations        )
                && ($this->previous->donationsReceived < $this->current->donationsReceived)) {
            $donations         -= $this->previous->donations        ;
            $donationsReceived -= $this->previous->donationsReceived;
        }
        $donations         += $this->current->donations        ;
        $donationsReceived += $this->current->donationsReceived;
        
        if ($donations) {
            $this->totals->lastActiveTime = $this->current->timestamp;
        }
        $this->totals->donations         += $donations        ;
        $this->totals->donationsReceived += $donationsReceived;
    }
    
}
