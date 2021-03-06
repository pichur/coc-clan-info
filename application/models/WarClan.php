<?php

/**
 * @author piotr
 * @method array[WarPlayer] getMembers
 */
class WarClan extends Model {
    
    const CLAN     = 'clan'    ;
    const OPPONENT = 'opponent';
    
    public static $fieldMapping = [
            'number'    => ['key' => true],
            'type'      => ['key' => true],
            'badgeUrls' => ['type' => 'OneToOne' , 'target' => WarClanBadgeUrls::class],
            'members'   => ['type' => 'OneToMany', 'target' => WarPlayer       ::class],
    ];
    
    /** @var integer          */ public $number               ;
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
        
        $this->badgeUrls->number = $this->number;
        $this->badgeUrls->type   = $this->type  ;
        $this->badgeUrls->save();
        
        foreach ($this->getMembers() as $member) {
            $member->number = $this->number;
            $member->type   = $this->type  ;
            $member->save();
        }
    }
    
}
