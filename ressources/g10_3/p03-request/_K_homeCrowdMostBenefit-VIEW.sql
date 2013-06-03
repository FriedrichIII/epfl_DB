-- Compute which country in which Olympics has benefited the most from playing in front of the home 
-- crowd. The benefit is computed as the number of places it has advanced its position on the medal table 
-- compared to its average position for all Olympic Games. Repeat this computation separately for winter 
-- and summer games.

-- diff of average rating and home average rating

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

SELECT Benef2.*
FROM	(SELECT Benef.seasonName, MAX(Benef.benefit) AS maxBenef
	FROM Se_Co_Benef Benef
	GROUP BY Benef.seasonName) BenefMax
JOIN Se_Co_Benef Benef2 ON Benef2.seasonName = BenefMax.seasonName AND Benef2.benefit = BenefMax.maxBenef