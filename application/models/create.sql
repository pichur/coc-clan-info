DROP TABLE IF EXISTS Troop       CASCADE;
DROP TABLE IF EXISTS Spell       CASCADE;
DROP TABLE IF EXISTS Hero        CASCADE;
DROP TABLE IF EXISTS Achievement CASCADE;
DROP TABLE IF EXISTS Player      CASCADE;
DROP TABLE IF EXISTS IconUrls    CASCADE;
DROP TABLE IF EXISTS League      CASCADE;
DROP TABLE IF EXISTS BadgeUrls   CASCADE;
DROP TABLE IF EXISTS Clan        CASCADE;
DROP TABLE IF EXISTS Location    CASCADE;
DROP TABLE IF EXISTS Version     CASCADE;

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

CREATE TABLE Player (
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
    FOREIGN KEY (timestamp, tag) REFERENCES Player (timestamp, tag)
);

CREATE TABLE Hero (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    
    name                  VARCHAR(255),
    village               VARCHAR(32),
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES Player (timestamp, tag)
);

CREATE TABLE Spell (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    
    name                  VARCHAR(255),
    village               VARCHAR(32),
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES Player (timestamp, tag)
);

CREATE TABLE Troop (
    timestamp             TIMESTAMP,
    tag                   CHAR(10),
    
    name                  VARCHAR(255),
    village               VARCHAR(32),
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES Player (timestamp, tag)
);

INSERT INTO VERSION (number, version1, version2, version3, info) values (0, 0, 0, 1, 'Install script');
