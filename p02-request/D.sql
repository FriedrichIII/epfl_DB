--D. Print the name of the country which won the most medals in summer Olympics and the country which won the most medals in winter Olympics.

SELECT c.name, csm.season
FROM Countries c
JOIN	(SELECT t.iocCode, g.season, COUNT(*) mc
	FROM 	(SELECT *
		FROM Teams tt
		WHERE tt.rank > 0 && tt.rank < 4) t
	JOIN Events e
	ON t.eventID = e.eventID
	JOIN Games g
	ON e.gameID = g.gameID
	GROUP BY t.iocCode, g.season) csm
ON c.iocCode = csm.iocCode
JOIN	(SELECT csm2.season, MAX(csm2.mc) mm
	FROM	(SELECT t2.iocCode, g2.season, COUNT(*) mc
		FROM 	(SELECT *
			FROM Teams tt2
			WHERE tt2.rank > 0 && tt2.rank < 4) t2
		JOIN Events e2
		ON t2.eventID = e2.eventID
		JOIN Games g2
		ON e2.gameID = g2.gameID
		GROUP BY t2.iocCode, g2.season) csm2
	GROUP BY csm2.season) smm
ON csm.season = smm.season AND csm.mc = smm.mm
