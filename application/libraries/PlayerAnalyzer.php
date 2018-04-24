<?php

class PlayerAnalyzer {
    
    /** @var PlayerHistory */ private $previous;
    /** @var PlayerHistory */ private $current ;
    /** @var PlayerTotals  */ private $totals  ;
    
    public function __construct ($previous, $current) {
        $this->previous = $previous;
        $this->current  = $current ;
        
        $this->totals = PlayerTotals::getBy(['tag' => $history->tag]);
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
        
        $this->totals->actualize($this->current);
        
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
        $this->totals->details->donations         += $donations        ;
        $this->totals->details->donationsReceived += $donationsReceived;
    }
    
}
