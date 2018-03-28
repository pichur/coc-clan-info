CREATE TABLE Version (
    number      INTEGER,
    version1    INTEGER,
    version2    INTEGER,
    version3    INTEGER,
    info        VARCHAR(128),
    updateDate  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updateUser  VARCHAR(64)
);
CREATE TRIGGER VersionLog BEFORE INSERT ON Version FOR EACH ROW SET new.updateUser = user();

CREATE TABLE clan (
    timestamp           TIMESTAMP PRIMARY KEY,
    
    tag                 CHAR(9),
    name                VARCHAR(64),
    type                VARCHAR(32),
    description         VARCHAR(255),
    clanLevel           INTEGER,
    clanPoints          INTEGER,
    clanVersusPoints    INTEGER,
    requiredTrophies    INTEGER,
    warFrequency        VARCHAR(32),
    warWinStreak        INTEGER,
    warWins             INTEGER,
    warTies             INTEGER,
    warLosses           INTEGER,
    isWarLogPublic      BOOLEAN,
    members             INTEGER,
    location_id         INTEGER,
    
    FOREIGN KEY (location_id) REFERENCES location (id)
);

CREATE TABLE location (
    id                  INTEGER PRIMARY KEY,
    name                VARCHAR(64),
    isCountry           BOOLEAN,
    countryCode         VARCHAR(8),
);

CREATE TABLE badge (
    timestamp           TIMESTAMP PRIMARY KEY,
    active              BOOLEAN,
    
    small               VARCHAR(255),
    medium              VARCHAR(255),
    large               VARCHAR(255),
);

INSERT INTO VERSION (number, version1, version2, version3, info) values (0, 0, 0, 1, 'Install script');
