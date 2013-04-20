--A. Print the names of athletes who won medals at both summer and winter Olympics
-- TODO Check the multiple joins are consistant syntaxically
SELECT A.name
FROM Athletes A
JOIN Membership M
ON A.athleteID = M.athleteID
JOIN Teams T
ON M.teamID = T.teamID
JOIN Events E
ON T.eventID = E.eventID
JOIN Games G
ON G.gameID = E.gameID
WHERE T.rank < 4 AND T.rank > 0
GROUP BY A.name
HAVING COUNT (DISTINCT G.season) = 2