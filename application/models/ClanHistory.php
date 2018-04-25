<?php

class ClanHistory extends TimestampModel {
    
    public static $fieldMapping = [
        'timestamp'  => ['key' => true, 'jsonConverter' => 'jsonToDate', 'dbConverter' => 'dbToDate'],
        'location'   => ['type' => 'ManyToOne', 'target' => Location     ::class],
        'badgeUrls'  => ['type' => 'OneToOne' , 'target' => BadgeUrls    ::class],
        'memberList' => ['type' => 'OneToMany', 'target' => PlayerHistory::class],
    ];
    
    /** @var string               */ public $tag             ;
    /** @var string               */ public $name            ;
    /** @var string               */ public $type            ;
    /** @var string               */ public $description     ;
    /** @var Location             */ public $location        ;
    /** @var BadgeUrls            */ public $badgeUrls       ;
    /** @var integer              */ public $clanLevel       ;
    /** @var integer              */ public $clanPoints      ;
    /** @var integer              */ public $clanVersusPoints;
    /** @var integer              */ public $requiredTrophies;
    /** @var string               */ public $warFrequency    ;
    /** @var integer              */ public $warWinStreak    ;
    /** @var integer              */ public $warWins         ;
    /** @var integer              */ public $warTies         ;
    /** @var integer              */ public $warLosses       ;
    /** @var boolean              */ public $isWarLogPublic  ;
    /** @var integer              */ public $members         ;
    /** @var array[PlayerHistory] */ public $memberList      ;
    
    public function save () {
        debug('ClanHistory save start');
        
        debug('location save');
        $this->location->save();
        $this->location_id = $this->location->id;
        
        debug('parent save');
        parent::save();
        
        debug('badgeUrls save');
        $this->badgeUrls->save();
        
        foreach ($this->getMemberList() as $member) {
            debug('member ' . $member->tag . ' save');
            $member->save();
        }
        
        debug('ClanHistory save end');
    }
    
    /**
     * @return array[PlayerHistory]
     */
    public function getMemberList () {
        return parent::getModelProperty('memberList');
    }
    
}
