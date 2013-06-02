-- List names of all athletes who won medals both in individual and team sports

CREATE OR REPLACE VIEW TeamMedalists AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) > 1;

CREATE OR REPLACE VIEW SingleMedalists AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) = 1;

CREATE OR REPLACE VIEW SingleMedalistsAthlete AS
SELECT Me.athleteID
FROM memberships Me
JOIN TeamMedalists TM ON Me.teamID = TM.teamID;

CREATE OR REPLACE VIEW TeamMedalistsAthlete AS
SELECT Me.athleteID
FROM memberships Me
JOIN SingleMedalists SM ON Me.teamID = SM.teamID;

SELECT Ath.name
FROM	(SELECT DISTINCT SM.athleteID
	FROM SingleMedalistsAthlete SM
	JOIN TeamMedalistsAthlete TM ON SM.athleteID = TM.athleteID) AID
JOIN athletes Ath ON Ath.athleteID = AID.athleteID