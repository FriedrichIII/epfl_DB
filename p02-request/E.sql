--E. List all cities which hosted the Olympics more than once.
SELECT ct.name
FROM Cities c
JOIN	(SELECT g.cityID
	FROM Games g
	GROUP BY g.cityID
	HAVING COUNT(*) > 1) h
ON c.cityID = h.citiyID