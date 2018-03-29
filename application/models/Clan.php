<?php

include_once 'application/models/Timestamp.php';

class Clan extends Model {
    
    use Timestamp;
    
    public static $fieldMapping = [
            'location'   => Location ::class,
            'badgeUrls'  => BadgeUrls::class,
            'memberList' => Member   ::class,
    ];
    
    /** @var string        */ public $tag             ;
    /** @var string        */ public $name            ;
    /** @var string        */ public $type            ;
    /** @var string        */ public $description     ;
    /** @var Location      */ public $location        ;
    /** @var BadgeUrls     */ public $badgeUrls       ;
    /** @var integer       */ public $clanLevel       ;
    /** @var integer       */ public $clanPoints      ;
    /** @var integer       */ public $clanVersusPoints;
    /** @var integer       */ public $requiredTrophies;
    /** @var string        */ public $warFrequency    ;
    /** @var integer       */ public $warWinStreak    ;
    /** @var integer       */ public $warWins         ;
    /** @var integer       */ public $warTies         ;
    /** @var integer       */ public $warLosses       ;
    /** @var boolean       */ public $isWarLogPublic  ;
    /** @var integer       */ public $members         ;
    /** @var array[Member] */ public $memberList      ;
    
    public function save () {
        $this->location_id = $this->location->save();
        
        $this->db->insert('Clan', $this);
        
        $this->badgeUrls->save();
        
        //foreach ($this->memberList as $member) $member->save();
    }
    
}
