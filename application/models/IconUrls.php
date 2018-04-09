<?php

class IconUrls extends HistoryModel {
    
    /** @var integer */ public $id;
    
    /** @var string */ public $tiny  ;
    /** @var string */ public $small ;
    /** @var string */ public $medium;
    
    protected function key () {
        return 'id';
    }
    
}
