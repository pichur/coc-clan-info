<?php

class WarClan extends Model {
    
    public static $fieldMapping = [
            'badgeUrls' => ['type' => 'OneToOne' , 'target' => WarClanBadgeUrls::class],
            'members'   => ['type' => 'OneToMany', 'target' => WarPlayer       ::class],
    ];
    
    /** @var integer          */ public $warNumber            ;
    /** @var string           */ public $type                 ;
    /** @var string           */ public $tag                  ;
    /** @var string           */ public $name                 ;
    /** @var WarClanBadgeUrls */ public $badgeUrls            ;
    /** @var integer          */ public $clanLevel            ;
    /** @var integer          */ public $attacks              ;
    /** @var integer          */ public $stars                ;
    /** @var double           */ public $destructionPercentage;
    /** @var array[WarPlayer] */ public $members              ;
    
    public function save () {
        parent::save();
        
        $this->badgeUrls->warNumber = $this->warNumber;
        $this->badgeUrls->type      = $this->type     ;
        $this->badgeUrls->save();
        
        foreach ($this->members as $member) {
            $member->warNumber = $this->warNumber;
            $member->type      = $this->type     ;
            $member->save();
        }
    }
    
}
