<?php

class Achievement extends Model {
    
    use Timestamp;
    
    /** @var string  */ public $player;
    
    /** @var string  */ public $name ;
    /** @var integer */ public $stars;
    /** @var integer */ public $value;
    
}
