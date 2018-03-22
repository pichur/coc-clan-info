<?php

class Clan extends Model {
    
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
    
    public function create () {
        /**
         * @var CI_DB_query_builder $db
         */
        $db = $this->db;
        if ($db->table_exists($table_name)) {
            return;
        }
        $this->db->query(<<<EOT
CREATE TABLE clan (
    timestamp         TIMESTAMP PRIMARY KEY,
    tag               CHAR(9),
    name              VARCHAR(32),
    type              VARCHAR(32),
    description       VARCHAR(255),
    clanLevel         INTEGER,
    clanPoints        INTEGER,
    clanVersusPoints  INTEGER,
    requiredTrophies  INTEGER,
    warFrequency      VARCHAR(32),
    warWinStreak      INTEGER,
    warWins           INTEGER,
    warTies           INTEGER,
    warLosses         INTEGER,
    isWarLogPublic    BOOLEAN,
    members           INTEGER)
EOT
        );
        //location         /** @var Location      */ public $;
        //badgeUrls        /** @var BadgeUrls     */ public $;
        //memberList       /** @var array[Member] */ public $;
    }
    
    public function save () {
        $this->db->insert('clan', $this);
    }
    
}
