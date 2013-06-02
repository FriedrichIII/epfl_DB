--A. Print the names of athletes who won medals at both summer and winter Olympics
-- TODO Check the multiple joins are consistant syntaxically
SELECT DISTINCT A.name AS aname
FROM Athletes A
JOIN	(SELECT M.athleteID
	FROM Memberships M
	JOIN Teams T
	ON M.teamID = T.teamID
	JOIN Events E
	ON T.eventID = E.eventID
	JOIN Games G
	ON G.gameID = E.gameID
	WHERE T.rank < 4 AND T.rank > 0
	GROUP BY M.athleteID, G.seasonName
	HAVING COUNT(*) = 2) MM
ON A.athleteID = MM.athleteID