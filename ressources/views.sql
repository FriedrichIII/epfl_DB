CREATE VIEW aview AS
SELECT
	a.athleteID AS aID, a.name AS aname, co.name AS coname, t.iocCode AS code
FROM athletes a
INNER JOIN memberships m ON a.athleteID = m.athleteID
INNER JOIN teams t ON t.teamID = m.teamID
INNER JOIN countries co ON t.iocCode = co.iocCode;

CREATE VIEW ciview AS
SELECT
	ci.cityID AS ciID, ci.name AS ciname, co.name AS coname, co.iocCode AS code
FROM cities ci
INNER JOIN countries co ON ci.iocCode = co.iocCode;

CREATE VIEW dview AS
SELECT
	d.disciplineID AS dID, d.name AS dname, s.name AS sname
FROM disciplines d
INNER JOIN sports s ON d.sportID = s.sportID;

CREATE VIEW eview AS
SELECT
	e.eventID AS eID, e.name AS ename, d.name AS dname, s.name AS sname, g.cityID as ciname, g.year as year
FROM events e
INNER JOIN disciplines d ON e.disciplineID = d.disciplineID
INNER JOIN sports s ON d.sportID = s.sportID
INNER JOIN games g ON e.gameID = g.gameID;

CREATE VIEW gview AS
SELECT
	g.gameID AS gID, ci.name AS ciname, g.year as year
FROM games g
INNER JOIN cities ci ON g.cityID = ci.cityID;
