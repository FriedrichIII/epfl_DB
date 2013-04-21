--C. For each country print the place where it won its first medal.
-- Convention: Summer year x is earlier than Winter year x
-- WARN: Nomination of seasons may affect this request
SELECT ctry.name, ct.name
FROM Countries ctry, Games g0
JOIN Cities ct
ON g0.cityID = ct.cityID
JOIN	(SELECT cgy.iocCode, cgy.seasonName, cgy.minYear
	FROM	(SELECT t.iocCode, gg.seasonName, MIN(gg.year) minYear -- year of first medal for each season
		FROM	(SELECT *
			FROM Team tt
			WHERE tt.rank < 4 AND tt.rank > 0) t
		JOIN Events e
		ON e.teamID = t.teamID
		JOIN Games gg
		ON e.gameID = gg.gameID
		GROUP BY t.iocCode, gg.seasonName ) cgy
	JOIN	(SELECT t2.iocCode, MIN(gg2.year) minYear -- year of first medal ever
		FROM	(SELECT *
			FROM Team tt2
			WHERE tt2.rank < 4 AND tt2.rank > 0) t2
		JOIN Events e2
		ON e2.teamID = t2.teamID
		JOIN Games gg2
		ON e2.gameID = gg2
		GROUP BY t2.iocCode) cy
	ON cgy.iocCode = cy.iocCode AND cgy.minYear = cy.minYear
	LEFT OUTER JOIN
		(SELECT cgy3.iocCode -- countries that recieved first summer medal and first winter medal in different year
		FROM	(SELECT t3.iocCode, gg3.seasonName, MIN(gg3.year) minYear -- year of first medal for each season
			FROM	(SELECT *
				FROM Team tt3
				WHERE tt3.rank < 4 AND tt3.rank > 0) t3
			JOIN Events e3
			ON e3.teamID = t3.teamID
			JOIN Games gg3
			ON e3.gameID = gg3.gameID
			GROUP BY t3.iocCode, gg3.seasonName ) cgy3
		JOIN	(SELECT t32.iocCode, MIN(gg32.year) minYear -- year of first medal ever
			FROM	(SELECT *
				FROM Team tt32
				WHERE tt32.rank < 4 AND tt32.rank > 0) t32
			JOIN Events e32
			ON e32.teamID = t32.teamID
			JOIN Games gg32
			ON e32.gameID = gg32
			GROUP BY t32.iocCode) cy3
		ON cgy3.iocCode = cy3.iocCode AND cgy3.minYear = cy3.minYear
		GROUP BY cgy3.iocCode
		HAVING COUNT(*) = 1) oneSeason
	ON cgy.iocCode = oneSeason.iocCode
	WHERE cgy.seasonName = 'Summer' OR oneSeason.iocCode IS NOT NULL ) csy
ON ctry.iocCode = csy.iocCode AND g0.seasonName = csy.seasonName AND g0.year = csy.minYear
	
