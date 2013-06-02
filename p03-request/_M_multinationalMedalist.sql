-- List all Olympians who won medals for multiple nations.
SELECT Ath.name
FROM athletes Ath
JOIN memberships Me ON Ath.athleteID = Me.athleteID
JOIN teams Te ON Me.teamID = Te.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Ath.athleteID
HAVING COUNT(DISTINCT Te.ioccode) > 1