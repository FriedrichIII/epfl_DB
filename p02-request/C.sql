--C. For each country print the place where it won its first medal.
SELECT c0.name, ctsy.name
FROM Countries c0, (
	SELECT ct.name, g0.season, g0.year
	FROM Games g0
	JOIN Cities ct
	ON g0.cityID = ct.citiyID) ctsy
JOIN 
	(SELECT c.iocCode, MIN(g.year)
	FROM Country c
	JOIN	(SELECT *
		FROM Teams tt
		WHERE tt.rank < 4 AND tt.rank > 0 ) t
	ON cd.iocCode = t.iocCode
	JOIN Events e
	ON t.eventID = e.eventID
	JOIN Games g
	ON e.gameID = g.gameID
	GROUP BY c.iocCode) cmy
ON c0.iocCode = cmy.iocCode AND ctsy.year = cmy.year
-- TODO solve two place for one year (season) problem