<?php

class PlayerAnalyzer {
    
    /** @var PlayerHistory */ private $previous;
    /** @var PlayerHistory */ private $current ;
    /** @var PlayerTotals  */ private $totals  ;
    
    public function __construct ($previous, $current) {
        $this->previous = $previous;
        $this->current  = $current ;
        
        $this->totals = PlayerTotals::getBy(['tag' => $current->tag]);
    }
    
    public function analyze () {
        if ($this->totals == null) {
            $this->totals = new PlayerTotals();
            $this->totals->init($this->current);
        } else {
            if ($this->previous == null) {
                $this->totals->enter($this->current);
            } else {
                $diff = ($this->current->timestamp->getTimestamp() - $this->previous->timestamp->getTimestamp()) / 86400;
                $this->totals->inClanTotalDays += $diff;
            }
        }
        
        $this->donations();
        
        $this->name();
        
        $this->timestamp();
        
        $this->totals->save();
    }
    
    private function donations () {
        $donations         = 0;
        $donationsReceived = 0;
        
        if ($this->previous) {
            if ($this->previous->donations <= $this->current->donations) {
                $donations -= $this->previous->donations;
            }
            $donations += $this->current->donations;
            
            if ($this->previous->donationsReceived <= $this->current->donationsReceived) {
                $donationsReceived -= $this->previous->donationsReceived;
            }
            $donationsReceived += $this->current->donationsReceived;
        }
        
        if ($donations) {
            $this->totals->lastActiveTime = $this->current->timestamp;
        }
        $this->totals->getClanDetails()->donations         += $donations        ;
        $this->totals->getClanDetails()->donationsReceived += $donationsReceived;
    }
    
    private function name () {
        if (   $this->previous
            && $this->previous->name != $this->current->name) {
                PlayerClanPeriod ::actualizeName($this->current->tag, $this->current->name);
                PlayerWarPeriod  ::actualizeName($this->current->tag, $this->current->name);
                PlayerGamesPeriod::actualizeName($this->current->tag, $this->current->name);
        }
    }
    
    private function timestamp () {
        $this->totals->timestamp                   = $this->current->timestamp;
        $this->totals->getClanDetails()->timestamp = $this->current->timestamp;
    }
    
}
