DROP TABLE IF EXISTS Member           CASCADE;
DROP TABLE IF EXISTS Player           CASCADE;
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
DROP TABLE IF EXISTS GamesPlayer      CASCADE;
DROP TABLE IF EXISTS Games            CASCADE;
DROP TABLE IF EXISTS Attack           CASCADE;
DROP TABLE IF EXISTS WarPlayer        CASCADE;
DROP TABLE IF EXISTS WarClanBadgeUrls CASCADE;
DROP TABLE IF EXISTS WarClan          CASCADE;
DROP TABLE IF EXISTS War              CASCADE;
DROP TABLE IF EXISTS Troop            CASCADE;
DROP TABLE IF EXISTS Spell            CASCADE;
DROP TABLE IF EXISTS Hero             CASCADE;
DROP TABLE IF EXISTS Achievement      CASCADE;
DROP TABLE IF EXISTS PlayerHistory    CASCADE;
DROP TABLE IF EXISTS IconUrls         CASCADE;
DROP TABLE IF EXISTS League           CASCADE;
DROP TABLE IF EXISTS BadgeUrls        CASCADE;
DROP TABLE IF EXISTS Clan             CASCADE;
DROP TABLE IF EXISTS PlayerTotals     CASCADE;
DROP TABLE IF EXISTS Location         CASCADE;
DROP TABLE IF EXISTS Version          CASCADE;

CREATE TABLE Version (
    number                INTEGER,
    
    version1              INTEGER,
    version2              INTEGER,
    version3              INTEGER,
    info                  VARCHAR(128),
    updateDate            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updateUser            VARCHAR(64),
    
    PRIMARY KEY (number)
);
CREATE TRIGGER VersionLog BEFORE INSERT ON Version FOR EACH ROW SET new.updateUser = user();

CREATE TABLE Location (
    id                    INTEGER,
    
    name                  VARCHAR(64),
    isCountry             BOOLEAN,
    countryCode           VARCHAR(8),
    
    PRIMARY KEY (id)
);

CREATE TABLE PlayerTotals (
    tag                   CHAR(10),
    
    timestamp             TIMESTAMP,
    
    inClanFirstTime       TIMESTAMP,
    inClanCurrentTime     TIMESTAMP,
    inClanTotalHours      INTEGER,
    inClanTotalEnters     INTEGER,
    
    lastActiveTime        TIMESTAMP,
    
    donations             INTEGER,
    donationsReceived     INTEGER,
    
    warCount              INTEGER,
    warAttackCount        INTEGER,
    warStars              INTEGER,
    warNewStars           INTEGER,
    warDefenses           INTEGER,
    warLostStars          INTEGER,
    warOpponents          DOUBLE,
    warOpponentDiffs      DOUBLE,
    
    gamesCount            INTEGER,
    gamesPoints           INTEGER,
    gamesMissingPoints    INTEGER,
    gamesPercentage       DOUBLE,
    
    PRIMARY KEY (tag)
);

CREATE TABLE Clan (
    timestamp             TIMESTAMP,
                          
    tag                   CHAR(9),
    name                  VARCHAR(64),
    type                  VARCHAR(32),
    description           VARCHAR(255),
    clanLevel             INTEGER,
    clanPoints            INTEGER,
    clanVersusPoints      INTEGER,
    requiredTrophies      INTEGER,
    warFrequency          VARCHAR(32),
    warWinStreak          INTEGER,
    warWins               INTEGER,
    warTies               INTEGER,
    warLosses             INTEGER,
    isWarLogPublic        BOOLEAN,
    members               INTEGER,
    location_id           INTEGER,
    
    PRIMARY KEY (timestamp),
    FOREIGN KEY (location_id) REFERENCES Location (id)
);

CREATE TABLE BadgeUrls (
    timestamp             TIMESTAMP,
                          
    small                 VARCHAR(255),
    medium                VARCHAR(255),
    large                 VARCHAR(255),
    
    PRIMARY KEY (timestamp)
);

CREATE TABLE League (
    id                    INTEGER,
    
    name                  VARCHAR(64),
    
    PRIMARY KEY (id)
);

CREATE TABLE IconUrls (
    timestamp             TIMESTAMP,
    id                    INTEGER,
    
    tiny                  VARCHAR(255),
    small                 VARCHAR(255),
    medium                VARCHAR(255),
    
    PRIMARY KEY (timestamp, id),
    FOREIGN KEY (id) REFERENCES League (id)
);

CREATE TABLE PlayerHistory (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    
    name                  VARCHAR(64),
    role                  VARCHAR(32),
    expLevel              INTEGER,
    trophies              INTEGER,
    versusTrophies        INTEGER,
    donations             INTEGER,
    donationsReceived     INTEGER,
    townHallLevel         INTEGER,
    bestTrophies          INTEGER,
    warStars              INTEGER,
    attackWins            INTEGER,
    defenseWins           INTEGER,
    versusBattleWinCount  INTEGER,
    builderHallLevel      INTEGER,
    bestVersusTrophies    INTEGER,
    versusBattleWins      INTEGER,
    clanRank              INTEGER,
    previousClanRank      INTEGER,
    league_id             INTEGER,
    clanGamesPoints       INTEGER,
    
    PRIMARY KEY (timestamp, tag),
    FOREIGN KEY (league_id) REFERENCES League (id)
);

CREATE TABLE Achievement (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    stars                 INTEGER,
    value                 INTEGER,
    target                INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE Hero (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE Spell (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE Troop (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE War (
    number                INTEGER,
    
    state                 VARCHAR(32),
    teamSize              INTEGER,
    preparationStartTime  TIMESTAMP,
    startTime             TIMESTAMP,
    endTime               TIMESTAMP,
    
    PRIMARY KEY (number),
    UNIQUE      (preparationStartTime)
);

CREATE TABLE WarClan (
    number                INTEGER,
    type                  VARCHAR(32),
    
    tag                   CHAR(9),
    name                  VARCHAR(64),
    clanLevel             INTEGER,
    attacks               INTEGER,
    stars                 INTEGER,
    destructionPercentage DOUBLE,
    
    PRIMARY KEY (number, type),
    FOREIGN KEY (number) REFERENCES War (number)
);

CREATE TABLE WarClanBadgeUrls (
    number                INTEGER,
    type                  VARCHAR(32),
    
    small                 VARCHAR(255),
    medium                VARCHAR(255),
    large                 VARCHAR(255),
    
    PRIMARY KEY (number, type),
    FOREIGN KEY (number, type) REFERENCES WarClan (number, type)
);

CREATE TABLE WarPlayer (
    number                INTEGER,
    tag                   CHAR(10),
    
    type                  VARCHAR(32),
    mapPosition           INTEGER,
    name                  VARCHAR(64),
    townHallLevel         INTEGER,
    opponentAttacks       INTEGER,
    bestOpponentAttack_nr INTEGER,
    
    attackCount           INTEGER,
    stars                 INTEGER,
    newStars              INTEGER,
    defenses              INTEGER,
    lostStars             INTEGER,
    opponents             DOUBLE, 
    
    PRIMARY KEY (number, tag),
    FOREIGN KEY (number, type) REFERENCES WarClan (number, type)
);

CREATE TABLE Attack (
    number                INTEGER,
    position              INTEGER,
    
    attackerTag           CHAR(10),
    defenderTag           CHAR(10),
    stars                 INTEGER,
    destructionPercentage INTEGER,
    
    PRIMARY KEY (number, position),
    FOREIGN KEY (number) REFERENCES War (number)
);

CREATE TABLE Games (
    number                INTEGER,
    
    startTime             TIMESTAMP,
    endTime               TIMESTAMP,
    
    maxPoints             INTEGER,
    
    PRIMARY KEY (number),
    UNIQUE      (startTime)
);

CREATE TABLE GamesPlayer (
    number                INTEGER,
    tag                   CHAR(10),
    
    points                INTEGER,
    percentage            DOUBLE,
    
    PRIMARY KEY (number, tag),
    FOREIGN KEY (number) REFERENCES Games (number)
);

INSERT INTO VERSION (number, version1, version2, version3, info) values (0, 0, 0, 1, 'Install script');
