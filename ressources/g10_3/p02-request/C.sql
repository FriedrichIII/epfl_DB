--C. For each country print the place where it won its first medal.
-- Convention: Summer year x is earlier than Winter year x
-- WARN: Nomination of seasons may affect this request
SELECT co.name AS coname, ci.name AS ciname, g.year
FROM countries co
INNER JOIN teams t ON co.iocCode = t.iocCode
INNER JOIN events e ON e.eventID = t.eventID
INNER JOIN games g ON g.gameID = e.gameID
INNER JOIN cities ci ON ci.cityID = g.cityID
WHERE CONCAT(g.year, co.iocCode) in
	(SELECT CONCAT(MIN(g2.year), t2.iocCode)
	FROM teams t2
	INNER JOIN events e2 ON e2.eventID = t2.eventID
	INNER JOIN games g2 ON g2.gameID = e2.gameID
	GROUP BY t2.iocCode)
ORDER BY co.name
	
