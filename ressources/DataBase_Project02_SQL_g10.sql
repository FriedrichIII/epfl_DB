
CREATE TABLE Sports
( sportID INTEGER,
  name CHAR(128),
  PRIMARY KEY (sportID)
);
CREATE TABLE Disciplines(
  disciplineID INTEGER,
  name CHAR(128),
  sportID INTEGER,        -- relation FromSport
  PRIMARY KEY (disciplineID),
  FOREIGN KEY (sportID) REFERENCES Sports (sportID)
);
CREATE TABLE Seasons
( seasonName CHAR(64),
  PRIMARY KEY(seasonName)
);
CREATE TABLE Countries
( name CHAR(128),
  iocCode CHAR(4),
  PRIMARY KEY (iocCode)
);
CREATE TABLE Cities
( cityID INTEGER,
  name CHAR(128),
  iocCode CHAR(4),    -- relation LiesIn
  PRIMARY KEY (cityID),
  FOREIGN KEY (iocCode) REFERENCES Countries (iocCode)
);

CREATE TABLE Games
( gameID INTEGER,
  year INTEGER,
  seasonName CHAR(64),   -- relation InSeason
  cityID INTEGER,        -- relation Hosts
  PRIMARY KEY (gameID),
  UNIQUE (seasonName, year),
  FOREIGN KEY (seasonName) REFERENCES Seasons (seasonName),
  FOREIGN KEY (cityID) REFERENCES Cities (cityID)
);

CREATE TABLE Athletes
( athleteID INTEGER,
  name CHAR(128),
  PRIMARY KEY (athleteID)
);

CREATE TABLE Events
( eventID INTEGER,
  name CHAR(128),
  gameID INTEGER NOT NULL,        -- relation HappensIn
  disciplineID INTEGER NOT NULL,  -- relation FromDiscipline
  PRIMARY KEY (eventID),
  FOREIGN KEY (gameID) REFERENCES Games (gameID),
  FOREIGN KEY (disciplineID) REFERENCES Disciplines (disciplineID)
);

-- Extra check when inserting new entry with rank:
-- Check whether the element with wanted rank is free (for the event)
--   if yes put it with given rank
--   if no, check whether the row with this rank is exAequo and this is exAequo
--     if yes, increment the rank and try again
--     if no, insertion failed
-- PRINCIPLE: add any entry as soon as it is not inconsistent
--            the table as a whole
CREATE TABLE Teams -- includes relation Participates
( teamID INTEGER,
  iocCode CHAR(4) NOT NULL,
  eventID INTEGER NOT NULL,
  rank INTEGER,
  exAequo INTEGER,      -- actually boolean
  disqualified INTEGER, -- actually boolean
  PRIMARY KEY (teamID),
  FOREIGN KEY (iocCode) REFERENCES Countries (iocCode),
  FOREIGN KEY (eventID) REFERENCES Events (eventID),
  UNIQUE(eventID, rank)
);

-- extra check at insertion:
-- an athlete cannot join two teams of different countries in the same game
-- an athlete cannot join two teams with the same eventID
CREATE TABLE Memberships
( teamID INTEGER,
  athleteID INTEGER,
  PRIMARY KEY (teamID, athleteID),
  FOREIGN KEY (teamID) REFERENCES Teams (teamID),
  FOREIGN KEY (athleteID) REFERENCES Athletes (athleteID)
);