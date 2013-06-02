-- For all disciplines, compute the country which waited the most between two successive medals.

-- Interpretation : 
-- No discipline may happen at two different seasons
-- If countries are ex aequo then display all countries

CREATE OR REPLACE VIEW Di_Co_MedalYear AS
SELECT DISTINCT Di.disciplineID, Te.ioccode, Ga.year
FROM teams Te
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN games Ga ON Ga.gameID = Ev.gameID
JOIN disciplines Di ON Di.disciplineID = Ev.disciplineID
WHERE Te.rank < 4 AND Te.rank > 0;

CREATE OR REPLACE VIEW Di_Co_MedYear_MedFollowYear AS
SELECT MY1.*, MY2.year AS followYear
FROM Di_Co_MedalYear MY1
JOIN Di_Co_MedalYear MY2 ON MY1.disciplineID = MY2.disciplineID AND MY1.ioccode = MY2.ioccode AND MY1.year < MY2.year;

CREATE OR REPLACE VIEW Di_Co_WaitTime AS
SELECT Yrs1.disciplineID, Yrs1.ioccode, Yrs1.followYear-Yrs1.year AS waitTime
FROM Di_Co_MedYear_MedFollowYear Yrs1
LEFT OUTER JOIN Di_Co_MedYear_MedFollowYear Yrs2
ON Yrs1.disciplineID = Yrs2.disciplineID
	AND Yrs1.ioccode = Yrs2.ioccode
	AND Yrs1.year = Yrs2.year
	AND Yrs1.followYear > Yrs2.followYear
WHERE Yrs2.disciplineID IS NULL;

CREATE OR REPLACE VIEW Di_LongestMedalGap AS
SELECT WT1.disciplineID, MAX(WT1.waitTime) AS maxGap
FROM Di_Co_WaitTime WT1
GROUP BY WT1.disciplineID;

CREATE OR REPLACE VIEW Di_LongestMedalGapCountry AS
SELECT LMG.disciplineID, CWT.ioccode
FROM Di_LongestMedalGap LMG
JOIN Di_Co_WaitTime CWT
ON LMG.disciplineID = CWT.disciplineID AND LMG.maxGap = CWT.waitTime;

SELECT Di.name, Co.name
FROM Di_LongestMedalGapCountry LMGC
JOIN disciplines Di ON Di.disciplineID = LMGC.disciplineID
JOIN countries Co ON Co.ioccode = LMGC.ioccode