DROP TABLE IF EXISTS Member           CASCADE;
DROP TABLE IF EXISTS Player           CASCADE;
DROP TABLE IF EXISTS Clan             CASCADE;
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
DROP TABLE IF EXISTS ClanHistory      CASCADE;
DROP TABLE IF EXISTS PlayerPeriod     CASCADE;
DROP TABLE IF EXISTS PlayerTotals     CASCADE;
DROP TABLE IF EXISTS ClanTotals       CASCADE;
DROP TABLE IF EXISTS Location         CASCADE;
DROP TABLE IF EXISTS Version          CASCADE;

CREATE TABLE Version (
    number                INTEGER      NOT NULL,
    
    version1              INTEGER      NOT NULL,
    version2              INTEGER      NOT NULL,
    version3              INTEGER      NOT NULL,
    info                  VARCHAR(128) NOT NULL,
    updateDate            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updateUser            VARCHAR(64)  NOT NULL,
    
    PRIMARY KEY (number)
);
CREATE TRIGGER VersionLog BEFORE INSERT ON Version FOR EACH ROW SET new.updateUser = user();

CREATE TABLE Location (
    id                    INTEGER NOT NULL,
    
    name                  VARCHAR(64),
    isCountry             BOOLEAN,
    countryCode           VARCHAR(8),
    
    PRIMARY KEY (id)
);

CREATE TABLE ClanTotals (
    tag                         VARCHAR(16),
    
    historyFrom                 TIMESTAMP NULL DEFAULT NULL,
    
    clanTimestamp               TIMESTAMP NULL DEFAULT NULL,
    clanMinPoints               INTEGER,
    clanMaxPoints               INTEGER,
    clanMinVersusPoints         INTEGER,
    clanMaxVersusPoints         INTEGER,
    clanMaxWarWinStreak         INTEGER,
    clanMinMembers              INTEGER,
    clanMaxMembers              INTEGER,
    
    warTimestamp                TIMESTAMP NULL DEFAULT NULL,
    warCount                    INTEGER,
    warWins                     INTEGER,
    warTies                     INTEGER,
    warLosses                   INTEGER,
    warMinAttacksPercentage     DOUBLE,
    warAvgAttacksPercentage     DOUBLE,
    warMaxAttacksPercentage     DOUBLE,
    warMinStarsPercentage       DOUBLE,
    warAvgStarsPercentage       DOUBLE,
    warMaxStarsPercentage       DOUBLE,
    warMinDestructionPercentage DOUBLE,
    warAvgDestructionPercentage DOUBLE,
    warMaxDestructionPercentage DOUBLE,
    
    gamesTimestamp              TIMESTAMP NULL DEFAULT NULL,
    gamesMinPlayers             INTEGER,
    gamesAvgPlayers             INTEGER,
    gamesMaxPlayers             INTEGER,
    gamesMinMaxPlayers          INTEGER,
    gamesAvgMaxPlayers          INTEGER,
    gamesMaxMaxPlayers          INTEGER,
    
    PRIMARY KEY (tag)
);

CREATE TABLE PlayerTotals (
    tag                   VARCHAR(16),
    name                  VARCHAR(64),
    
    timestamp             TIMESTAMP NULL DEFAULT NULL,
    
    inClanFirstTime       TIMESTAMP NULL DEFAULT NULL,
    inClanCurrentTime     TIMESTAMP NULL DEFAULT NULL,
    inClanTotalDays       DOUBLE,
    inClanTotalEnters     INTEGER,
    
    lastActiveTime        TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (tag)
);
    
CREATE TABLE PlayerPeriod (
    tag                   VARCHAR(16),
    name                  VARCHAR(64),
    
    period                VARCHAR(16),
    
    startTime             TIMESTAMP NULL DEFAULT NULL,
    endTime               TIMESTAMP NULL DEFAULT NULL,
    
    timestamp             TIMESTAMP NULL DEFAULT NULL,
    
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
    
    PRIMARY KEY (tag, period)
);

CREATE TABLE ClanHistory (
    timestamp             TIMESTAMP,
                          
    tag                   VARCHAR(16),
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
    tag                   VARCHAR(16),
    
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
    tag                   VARCHAR(16),
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
    tag                   VARCHAR(16),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE Spell (
    timestamp             TIMESTAMP,
    tag                   VARCHAR(16),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE Troop (
    timestamp             TIMESTAMP,
    tag                   VARCHAR(16),
    name                  VARCHAR(255),
    village               VARCHAR(32),
    
    level                 INTEGER,
    maxLevel              INTEGER,
    
    PRIMARY KEY (timestamp, tag, name, village),
    FOREIGN KEY (timestamp, tag) REFERENCES PlayerHistory (timestamp, tag)
);

CREATE TABLE War (
    number                INTEGER NOT NULL,
    
    state                 VARCHAR(32) NOT NULL,
    teamSize              INTEGER NOT NULL,
    preparationStartTime  TIMESTAMP NOT NULL,
    startTime             TIMESTAMP NOT NULL,
    endTime               TIMESTAMP NOT NULL,
    
    PRIMARY KEY (number),
    UNIQUE      (preparationStartTime)
);

CREATE TABLE WarClan (
    number                INTEGER,
    type                  VARCHAR(32),
    
    tag                   VARCHAR(16),
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
    number                  INTEGER,
    tag                     VARCHAR(16),
    
    type                    VARCHAR(32),
    mapPosition             INTEGER,
    name                    VARCHAR(64),
    townHallLevel           INTEGER,
    opponentAttacks         INTEGER,
    bestOpponentAttack_nr   INTEGER,
    
    attackCount             INTEGER,
    stars                   INTEGER,
    newStars                INTEGER,
    destruction             INTEGER,
    newDestruction          INTEGER,
    attackPositionDiff      INTEGER,
    attackPositionDiffAvg   DOUBLE,
    
    defenseCount            INTEGER,
    lostStars               INTEGER,
    lostDestruction         INTEGER,
    defensePositionDiff     INTEGER,
    defensePositionDiffAvg  DOUBLE,
    
    PRIMARY KEY (number, tag),
    FOREIGN KEY (number, type) REFERENCES WarClan (number, type)
);

CREATE TABLE Attack (
    number                INTEGER,
    order_                INTEGER,
    
    attackerTag           VARCHAR(16),
    defenderTag           VARCHAR(16),
    stars                 INTEGER,
    destructionPercentage INTEGER,
    
    PRIMARY KEY (number, order_),
    FOREIGN KEY (number) REFERENCES War (number)
);

CREATE TABLE Games (
    number                INTEGER,
    
    startTime             TIMESTAMP NOT NULL,
    endTime               TIMESTAMP     NULL DEFAULT NULL,
    
    maxPoints             INTEGER,
    
    PRIMARY KEY (number),
    UNIQUE      (startTime)
);

CREATE TABLE GamesPlayer (
    number                INTEGER,
    tag                   VARCHAR(16),
    
    points                INTEGER,
    percentage            DOUBLE,
    
    PRIMARY KEY (number, tag),
    FOREIGN KEY (number) REFERENCES Games (number)
);

INSERT INTO VERSION (number, version1, version2, version3, info) values (0, 0, 0, 1, 'Install script');
