--C. For each country print the place where it won its first medal.
-- Convention: Summer year x is earlier than Winter year x
-- WARN: Nomination of seasons may affect this request
SELECT Co.name, Ci.name
FROM countries Co
JOIN	(SELECT CoGa_1.ioccode, CoGa_1.cityID
	FROM 	(SELECT DISTINCT CoTe_1.ioccode, Ga_1.year, Ga_1.seasonName, Ga_1.cityID
		FROM	(SELECT Co_1.ioccode, Te_1.eventID
			FROM countries Co_1 JOIN teams Te_1
			ON Te_1.ioccode = Co_1.ioccode
			WHERE Te_1.rank > 4) CoTe_1
		JOIN events Ev_1 ON CoTe_1.eventID = Ev_1.eventID
		JOIN games Ga_1 ON Ga_1.gameID = Ev_1.gameID) CoGa_1
	LEFT OUTER JOIN
		(SELECT DISTINCT CoTe_2.ioccode, Ga_2.year, Ga_2.seasonName, Ga_2.cityID
		FROM	(SELECT Co_2.ioccode, Te_2.eventID
			FROM countries Co_2 JOIN teams Te_2
			ON Te_2.ioccode = Co_2.ioccode
			WHERE Te_2.rank > 4) CoTe_2
		JOIN events Ev_2 ON CoTe_2.eventID = Ev_2.eventID
		JOIN games Ga_2 ON Ga_2.gameID = Ev_2.gameID) CoGa_2
		-- is earlyer: t1.year < t2.year or (t1.year = t2.year and t1.seasonName < t2.seasonName)
	ON (CoGa_1.ioccode = CoGa_2.ioccode AND (CoGa_1.year > CoGa_2.year OR (CoGa_1.year = CoGa_2.year AND CoGa_1.seasonName > CoGa_2.seasonName)))
	WHERE CoGa_2.ioccode IS NULL) CoGa
ON Co.ioccode = CoGa.ioccode
JOIN cities Ci ON Ci.cityId = CoGa.cityID
