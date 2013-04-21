--E. List all cities which hosted the Olympics more than once.
SELECT ci.name AS ciname
FROM Cities ci
JOIN	(SELECT DISTINCT g.cityID
	FROM Games g
	GROUP BY g.cityID
	HAVING COUNT(*) > 1) h
ON ci.cityID = h.cityID