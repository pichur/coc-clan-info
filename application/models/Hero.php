<?php

class Hero extends Model {
    
    use Timestamp;
    
    /** @var string  */ public $player;
    
    /** @var string  */ public $name ;
    /** @var integer */ public $level;
    
}
