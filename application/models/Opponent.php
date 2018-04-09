<?php

class Opponent extends Model {
    
    public $tag; //String
    public $name; //String
    public $badgeUrls; //
    public $clanLevel; //int
    public $attacks; //int
    public $stars; //int
    public $destructionPercentage; //double
    public $members; //array(Member)
    
}