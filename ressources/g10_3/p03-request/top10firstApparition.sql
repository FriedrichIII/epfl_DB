-- List top 10 countries according to their success on the events which appear at the Olympics for the first 
-- time. Present the list in the form of the medal table (as described for query I).

-- INTERPRETATION :
-- As events are specific to a game, We consider first discipline apparition

CREATE OR REPLACE VIEW Di_yearPlayed AS
SELECT Ev.disciplineID, Ga.year
FROM events Ev
JOIN games Ga ON Ev.gameID = Ga.gameID;

CREATE OR REPLACE VIEW DiFirstYear AS
SELECT Di1.disciplineID, Di1.year
FROM Di_yearPlayed Di1
LEFT OUTER JOIN Di_yearPlayed Di2 ON Di1.disciplineID = Di2.disciplineID AND Di1.year > Di2.year
WHERE Di2.disciplineID IS NULL;

CREATE OR REPLACE VIEW Co_GoldCnt AS
SELECT Te.ioccode, COUNT(*) AS cnt
FROM teams Te
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN DiFirstYear Di ON Ev.disciplineID = Di.disciplineID
JOIN games Ga ON Ev.gameID = Ga.gameID AND Ga.year = Di.year
WHERE Te.rank = 1
GROUP BY Te.ioccode;

CREATE OR REPLACE VIEW Co_SilverCnt AS
SELECT Te.ioccode, COUNT(*) AS cnt
FROM teams Te
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN DiFirstYear Di ON Ev.disciplineID = Di.disciplineID
JOIN games Ga ON Ev.gameID = Ga.gameID AND Ga.year = Di.year
WHERE Te.rank = 2
GROUP BY Te.ioccode;

CREATE OR REPLACE VIEW Co_BronzeCnt AS
SELECT Te.ioccode, COUNT(*) AS cnt
FROM teams Te
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN DiFirstYear Di ON Ev.disciplineID = Di.disciplineID
JOIN games Ga ON Ev.gameID = Ga.gameID AND Ga.year = Di.year
WHERE Te.rank = 3
GROUP BY Te.ioccode;

CREATE OR REPLACE VIEW Co_medals AS
SELECT Gd.ioccode, Gd.cnt AS gold, Si.cnt AS silver, Br.cnt AS bronze
FROM Co_GoldCnt Gd
JOIN Co_SilverCnt Si ON Gd.ioccode = Si.ioccode
JOIN Co_BronzeCnt Br ON Gd.ioccode = Br.ioccode;

CREATE OR REPLACE VIEW Co_to10Pos AS
SELECT CM1.ioccode
FROM Co_medals CM1
JOIN Co_medals CM2 
ON 	CM1.gold <= CM2.gold OR
	(CM1.gold = CM2.gold AND CM1.silver <= CM2.silver) OR
	(CM1.gold = CM2.gold AND CM2.silver = CM2.silver AND CM1.bronze <= CM2.bronze)
GROUP BY CM1.ioccode
HAVING COUNT(*) <= 10;


SELECT Co.name, CM.gold, CM.silver, CM.bronze
FROM countries Co
JOIN Co_to10Pos T10 ON Co.ioccode = T10.ioccode
JOIN Co_medals CM ON CM.ioccode = T10.ioccode
ORDER BY CM.gold DESC, CM.silver DESC, CM.bronze DESC
