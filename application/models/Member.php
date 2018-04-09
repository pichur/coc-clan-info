<?php

class Member extends Model {
    public $tag; //String
    public $name; //String
    public $townhallLevel; //int
    public $mapPosition; //int
    public $attacks; //array(Attack)
    public $opponentAttacks; //int
    public $bestOpponentAttack; //Attack
}