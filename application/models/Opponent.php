<?php

class Opponent extends Model {
    
    public static $fieldMapping = [
            'badgeUrls' => ['target' => OpponentBadgeUrls::class, 'relation' => 'OneToOne' ],
            '$members'  => ['target' => Member           ::class, 'relation' => 'OneToMany'],
    ];
    
    /** @var integer           */ public $warNumber            ;
    /** @var string            */ public $tag                  ;
    /** @var string            */ public $name                 ;
    /** @var OpponentBadgeUrls */ public $badgeUrls            ;
    /** @var integer           */ public $clanLevel            ;
    /** @var integer           */ public $attacks              ;
    /** @var integer           */ public $stars                ;
    /** @var double            */ public $destructionPercentage;
    /** @var array[Member]     */ public $members              ;
    
    public function save () {
        parent::save();
        
        $this->badgeUrls->warNumber = $this->warNumber;
        $this->badgeUrls->save();
        
        foreach ($this->members as $member) {
            $member->warNumber = $this->warNumber;
            $member->save();
        }
    }
    
}
