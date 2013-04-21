-- B. Print the names of gold medalists in sports which appeared only once at the Olympics.
SELECT a.name
FROM Athletes a
JOIN Membership m
ON a.athleteID = m.athleteID
JOIN Teams t
ON m.teamID = t.teamID
JOIN Events e
ON t.eventID = e.eventID
JOIN Disciplines d
ON e.disciplineID = d.disciplineID
JOIN Sports s
ON d.sportID = s.sportID
WHERE t.rank = 1 AND s.sportID in
	(SELECT s2.sportID
	FROM Events e2
	JOIN Disciplines d2
	ON e2.disciplineID = d2.disciplineID
	JOIN Sports s2
	ON d2.sportID = s2.sportID
	GROUP BY s2.sportID
	-- TODO appears once: One event or one game?
	HAVING COUNT(DISTINCT e2.gameID) = 1)