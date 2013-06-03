CREATE VIEW aview AS
SELECT
	a.athleteID AS aID, a.name AS aname, co.name AS coname, t.iocCode AS code
FROM athletes a
INNER JOIN memberships m ON a.athleteID = m.athleteID
INNER JOIN teams t ON t.teamID = m.teamID
INNER JOIN countries co ON t.iocCode = co.iocCode;

CREATE VIEW ciview AS
SELECT
	ci.cityID AS ciID, ci.name AS ciname, co.name AS coname, co.iocCode AS code
FROM cities ci
INNER JOIN countries co ON ci.iocCode = co.iocCode;

CREATE VIEW dview AS
SELECT
	d.disciplineID AS dID, d.name AS dname, s.name AS sname
FROM disciplines d
INNER JOIN sports s ON d.sportID = s.sportID;

CREATE VIEW eview AS
SELECT
	e.eventID AS eID, e.name AS ename, d.name AS dname, s.name AS sname, g.cityID as ciname, g.year as year
FROM events e
INNER JOIN disciplines d ON e.disciplineID = d.disciplineID
INNER JOIN sports s ON d.sportID = s.sportID
INNER JOIN games g ON e.gameID = g.gameID;

CREATE VIEW gview AS
SELECT
	g.gameID AS gID, ci.name AS ciname, g.year as year
FROM games g
INNER JOIN cities ci ON g.cityID = ci.cityID;

-- k
CREATE OR REPLACE VIEW Ga_Co_MedCnt AS
SELECT Ga_1.gameID, Te_1.ioccode, COUNT(Te_1.rank < 4 and Te_1.rank > 0) AS medCount
FROM teams Te_1
JOIN events Ev_1 ON Te_1.eventID = Ev_1.eventID
JOIN games Ga_1 ON Ev_1.gameID = Ga_1.gameID
GROUP BY Ga_1.gameID, Te_1.ioccode;

CREATE OR REPLACE VIEW Ga_Co_MedPos AS
SELECT MedCnt_1.gameID, MedCnt_1.ioccode, COUNT(MedCnt_2.gameID<>0)+1 AS medPos
FROM Ga_Co_MedCnt MedCnt_1
LEFT OUTER JOIN Ga_Co_MedCnt MedCnt_2
ON (MedCnt_1.gameID = MedCnt_2.gameID AND MedCnt_1.medCount < MedCnt_2.medCount)
GROUP BY MedCnt_1.gameID, MedCnt_1.ioccode; 

CREATE OR REPLACE VIEW Se_Co_AvgPos AS
SELECT Ga.seasonName, MedPos.ioccode, AVG(MedPos.medPos) AS avgPos
FROM Ga_Co_MedPos MedPos
JOIN games Ga ON Ga.gameID = MedPos.gameID
GROUP BY Ga.seasonName, MedPos.ioccode;

CREATE OR REPLACE VIEW Se_Co_HomePos AS
SELECT Ga.seasonName, MedPos.ioccode, AVG(MedPos.medPos) AS avgPos
FROM Ga_Co_MedPos MedPos
JOIN games Ga ON Ga.gameID = MedPos.gameID
JOIN cities Ci ON (Ga.cityID = Ci.cityID AND Ci.ioccode = MedPos.ioccode)
GROUP BY Ga.seasonName, MedPos.ioccode;

CREATE OR REPLACE VIEW Se_Co_Benef AS
SELECT	AvgPos.seasonName, AvgPos.ioccode, AvgPos.avgPos-HomePos.avgPos as benefit
FROM Se_Co_AvgPos AvgPos
JOIN Se_Co_HomePos HomePos
ON AvgPos.seasonName = HomePos.seasonName AND AvgPos.ioccode = HomePos.ioccode;

-- L
CREATE OR REPLACE VIEW MedalMultiTeams AS
SELECT Te.teamID
FROM memberships Me
JOIN teams Te ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(*) > 1;

CREATE OR REPLACE VIEW Full_MedalMultiTeam AS
SELECT Te.*
FROM MedalMultiTeams MMT
JOIN teams Te ON Te.teamID = MMT.teamID;

CREATE OR REPLACE VIEW Ga_Co_TeamMedaCount AS
SELECT Ga.gameID, Co.ioccode, COUNT(*) AS teamMedalists
FROM memberships Me
JOIN Full_MedalMultiTeam Te ON Me.teamID = Te.teamID
JOIN countries Co ON Co.ioccode = Te.ioccode
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN games Ga ON Ga.gameID = Ev.gameID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Ga.gameID, Co.ioccode;

CREATE OR REPLACE VIEW Co_AvgTeamMedalist AS
SELECT Te.ioccode, AVG(Te.teamMedalists) AS avgMedalists
FROM Ga_Co_TeamMedaCount Te
GROUP BY Te.gameID;

-- M
-- N
CREATE OR REPLACE VIEW Se_Ye_Co_Medal AS
SELECT DISTINCT Ga.seasonName, Ga.year, Co.ioccode, Te.rank
FROM countries Co
JOIN teams Te ON Co.ioccode = Te.ioccode
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN games Ga ON Ga.gameID = Ev.gameID
WHERE Te.rank < 4 AND Te.rank > 0;

CREATE OR REPLACE VIEW Co_firstMedals AS
SELECT Me_1.ioccode, Me_1.rank
FROM Se_Ye_Co_Medal Me_1
LEFT OUTER JOIN Se_Ye_Co_Medal Me_2
ON (Me_1.year > Me_2.year OR (Me_1.year = Me_2.year AND Me_1.seasonName > Me_2.seasonName))
WHERE Me_2.year IS NULL;

CREATE OR REPLACE VIEW Co_firstMedal AS
SELECT FM_1.*
FROM Co_firstMedals FM_1
LEFT OUTER JOIN Co_firstMedals FM_2
ON (FM_1.rank > FM_2.rank)
WHERE FM_2.ioccode IS NULL;

-- O
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

-- P
-- Q
CREATE OR REPLACE VIEW Co_Ga_MedCnt AS
SELECT Te.ioccode, Ga.gameID, COUNT(*) AS medCnt
FROM teams Te
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN games Ga ON Ga.gameID = Ev.gameID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.ioccode, Ga.gameID;

CREATE OR REPLACE VIEW Ga_MedTot AS
SELECT CoMc.gameID, SUM(CoMc.medCnt) AS medTot
FROM Co_Ga_MedCnt CoMc
GROUP BY CoMc.gameID;

CREATE OR REPLACE VIEW Ga_Co_MedPercent AS
SELECT CoMc.gameID, CoMc.ioccode, CoMc.medCnt/GaMt.medTot*100 AS medPercent
FROM Co_Ga_MedCnt CoMc
JOIN Ga_MedTot GaMt ON CoMc.gameID = GaMt.gameID;

-- R
CREATE OR REPLACE VIEW SingleMedalists AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) = 1;

CREATE OR REPLACE VIEW Ev_Co_Score AS
SELECT Ev.eventID, Te.ioccode, (4-Te.rank)*COUNT(*) AS score
FROM events Ev 
JOIN teams Te ON Te.eventID = Ev.eventID
JOIN SingleMedalists SM ON SM.teamID = Te.teamID
GROUP BY Ev.eventID, Te.ioccode, Te.rank
HAVING COUNT(*) = 1

UNION ALL

SELECT Ev.eventID, Te.ioccode, (4-Te.rank)*COUNT(*)/2 AS score
FROM events Ev 
JOIN teams Te ON Te.eventID = Ev.eventID
JOIN SingleMedalists SM ON SM.teamID = Te.teamID
GROUP BY Ev.eventID, Te.ioccode, Te.rank
HAVING COUNT(*) > 1;

CREATE OR REPLACE VIEW Sp_Co_Score AS
SELECT Di.sportID, Sc.ioccode, SUM(Sc.score) AS score
FROM Ev_Co_Score Sc
JOIN events Ev ON Sc.eventID = Ev.eventID
JOIN disciplines Di ON Di.disciplineID = Ev.disciplineID
GROUP BY Di.sportID, Sc.ioccode;

CREATE OR REPLACE VIEW Sp_Co_Top10Scores AS
SELECT Sc1.sportID, Sc1.ioccode, Sc1.score
FROM Sp_Co_Score Sc1
JOIN Sp_Co_Score Sc2
ON Sc1.sportID = Sc2.sportID AND Sc1.score <= Sc2.score
GROUP BY Sc1.sportID, Sc1.ioccode, Sc1.score
HAVING COUNT(*) <= 10;

-- S
CREATE OR REPLACE VIEW TeamMedalists AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) > 1;

CREATE OR REPLACE VIEW SingleMedalists AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) = 1;

CREATE OR REPLACE VIEW TeamMedalistsAthlete AS
SELECT Me.athleteID
FROM memberships Me
JOIN TeamMedalists TM ON Me.teamID = TM.teamID;

CREATE OR REPLACE VIEW SingleMedalistsAthlete AS
SELECT Me.athleteID
FROM memberships Me
JOIN SingleMedalists SM ON Me.teamID = SM.teamID;

-- T
CREATE OR REPLACE VIEW TeamGold AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank = 1
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) > 1;

CREATE OR REPLACE VIEW SingleNotGold AS
SELECT Te.teamID
FROM teams Te
JOIN memberships Me ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 1
GROUP BY Te.teamID
HAVING COUNT(DISTINCT Me.athleteID) = 1;

CREATE OR REPLACE VIEW TeamMedalistsAthlete AS
SELECT Me.athleteID
FROM memberships Me
JOIN TeamGold TM ON Me.teamID = TM.teamID;

CREATE OR REPLACE VIEW SingleMedalistsAthlete AS
SELECT Me.athleteID
FROM memberships Me
JOIN SingleNotGold SM ON Me.teamID = SM.teamID;

-- V
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