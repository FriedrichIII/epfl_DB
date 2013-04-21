--E. List all cities which hosted the Olympics more than once.
SELECT ct.name
FROM Cities ct
JOIN	(SELECT DISTINCT g.cityID
	FROM Games g
	GROUP BY g.cityID
	HAVING COUNT(*) > 1) h
ON c.cityID = h.citiyID