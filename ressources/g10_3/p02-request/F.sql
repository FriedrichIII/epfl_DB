--F. List names of all athletes who competed for more than one country.
SELECT a.name AS aname
FROM Athletes a
JOIN 	(SELECT DISTINCT m.athleteID
	FROM Memberships m
	JOIN Teams t
	ON m.teamID = t.teamID
	GROUP BY m.athleteID
	HAVING COUNT(DISTINCT t.iocCode) > 1 ) m2
ON a.athleteID = m2.athleteID