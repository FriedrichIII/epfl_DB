CREATE TABLE Seasons
( seasonName CHAR(64),
  PRIMARY KEY(seasonName)
)

CREATE TABLE Sports
( sportID INTEGER,
  name CHAR(128),
  PRIMARY_KEY (sportID)
)

CREATE TABLE Countries
( countryID INTEGER,
  name CHAR(128),
  iocCode CHAR(4),
  PRIMARY KEY (countryID)
)

CREATE TABLE Athletes
( athleteID INTEGER,
  name CHAR(128),
  PRIMARY KEY (athleteID),
)

CREATE TABLE Cities
( cityID INTEGER,
  name CHAR(128),
  countryID INTEGER,    -- relation LiesIn
  PRIMARY KEY (cityID),
  FOREIGN KEY (countryID) REFERENCES Countries
)

CREATE TABLE Games
( gameID INTEGER,
  year INTEGER,
  season CHAR(16),       -- relation InSeason
  cityID CHAR(128),      -- relation Hosts
  PRIMARY KEY (gameID),
  UNIQUE (season, year),
  FOREIGN KEY (seasonName) REFERENCES Seasons,
  FOREIGN KEY (cityID) REFERENCES Cities
)

CREATE TABLE Disciplines
( disciplineID INTEGER,
  name CHAR(128),
  sportID INTEGER,        -- relation FromSport
  PRIMARY_KEY (disciplineID),
  FOREIGN_KEY (sportID) REFERENCES Sports
)

CREATE TABLE Events
( eventID INTEGER,
  name CHAR(128),
  gameID INTEGER,         -- relation HappensIn
  disciplineID INTEGER,   -- relation FromDiscipline
  PRIMARY_KEY (eventID),
  FOREIGN_KEY (gameID) REFERENCES Games,
  FOREIGN_KEY (disciplineID) REFERENCES Disciplines 
)





-- RELATION AND AGGREGATION TABLES

CREATE TABLE Admissions -- aggregation Admission /relation IsAdmitted
( admissionID INTEGER,
  gameID INTEGER,
  athleteID INTEGER,
  countryID INTEGER,    -- relation Represents
  PRIMARY KEY (admissionID),
  FOREIGN KEY (gameID) REFERENCES Games,
  FOREIGN KEY (athleteID) REFERENCES Athletes,
  FOREIGN KEY (countryID) REFERENCES Countries,
  UNIQUE (gameID, athleteID)
)

CREATE TABLE Participations  -- relation Participations
(
  admissionID INTEGER,
  eventID INTEGER,
  ranking INTEGER,
  exAequo BOOLEAN,
  disqualified BOOLEAN,
  PRIMARY KEY (admissionID, eventID),
  FOREIGN KEY (admissionID) REFERENCES Admissions,
  FOREIGN KEY (eventID) REFERENCES Events,
  CHECK ranking > 0,
  UNIQUE (ranking)
)