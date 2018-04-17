<?php

class PlayerAnalizator {
    
    /** @var PlayerHistory */ public $previous;
    /** @var PlayerHistory */ public $current ;
    /** @var PlayerTotals  */ public $totals  ;
    
    public function __construct($previous, $current, $tag) {
        $totals = new PlayerTotals($tag);
        $totals->load();
        
        
    }
    
}