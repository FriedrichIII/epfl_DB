--G. For each Olympic Games print the name of the country with the most participants.
SELECT g.year, g.season, c.name
FROM Games g, Countries c
JOIN
	(SELECT e.gameID, t.iocCode, COUNT(DISTINCT m.athleteID) ac
	FROM Memberships m
	JOIN Teams t
	ON m.teamID = t.teamID
	JOIN Events e
	ON t.eventID = e.eventID
	GROUP BY e.gameID, t.iocCode) gca
ON g.gameID = gca.gameID AND c.iocCode = gca.iocCode
JOIN	(SELECT gca2.gameID, MAX(gca2.ac) am
	FROM	(SELECT e2.gameID, t2.iocCode, COUNT(DISTINCT m2.athleteID) ac
		FROM Memberships m2
		JOIN Teams t2
		ON m2.teamID = t2.teamID
		JOIN Events e2
		ON t2.eventID = e2.eventID
		GROUP BY e2.gameID, t2.iocCode) gca2
	GROUP BY gca2.gameID) gam
ON gca.gameID = gam.gameID AND gca.ac = gam.am