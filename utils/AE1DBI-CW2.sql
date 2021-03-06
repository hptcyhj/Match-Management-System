USE db_zy18738;

DROP TABLE IF EXISTS Registration;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Matches;

CREATE TABLE Users (
    UserId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(1000) NOT NULL,
    Phone VARCHAR(30) NOT NULL UNIQUE,
    Email VARCHAR(255) NOT NULL UNIQUE,
    TypeId INT NOT NULL DEFAULT 1  -- 1 for user, 0 for admin
) ENGINE = InnoDB;

CREATE TABLE Matches (
    MatchId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    StartTime TIME NOT NULL,
    duration TIME NOT NULL,
    MatchDate DATE NOT NULL,
    MatchInfo VARCHAR(1000),
    location VARCHAR(100) NOT NULL,
    capacity INT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE Registration (
    UserId INT NOT NULL,
    MatchId INT NOT NULL,
    CONSTRAINT pk
        PRIMARY KEY (UserId, MatchId),
    CONSTRAINT fk1
        FOREIGN KEY (UserId)
        REFERENCES Users (UserId)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk2
        FOREIGN KEY (MatchId)
        REFERENCES Matches (MatchId)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE = InnoDB;