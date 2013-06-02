-- Compute medal table for the specific Olympic Games supplied by the user. Medal table should contain 
-- country’s IOC code followed by the number of gold, silver, bronze and total medals. It should first be 
-- sorted by the number of gold, then silvers and finally bronzes.

-- create medal table by game for each game, need to add clause WHERE g.year = $user-year AND g.season = $user-season
SELECT Ga.year, Ga.seasonName, Ci.name AS cityName, goldTab.ioccode, goldTab.goldCount, silverTab.silverCount, bronzeTab.bronzeCount
FROM	(SELECT gold_Ga.gameID, gold_Co.ioccode, COUNT(*) AS goldCount
	FROM countries gold_Co
	JOIN 	(SELECT DISTINCT golt_Te.eventID, golt_Te.ioccode
		FROM teams golt_Te
		WHERE golt_Te.rank = 1) TeGold
	ON gold_Co.ioccode = TeGold.ioccode
	JOIN events Ev ON Ev.eventID = TeGold.eventID
	JOIN games gold_Ga ON gold_Ga.gameID = Ev.gameID
	GROUP BY gold_Ga.gameID, gold_Co.ioccode) goldTab
JOIN 	(SELECT silver_Ga.gameID, silver_Co.ioccode, COUNT(*) AS silverCount
	FROM countries silver_Co
	JOIN 	(SELECT DISTINCT silver_Te.eventID, silver_Te.ioccode
		FROM teams silver_Te
		WHERE silver_Te.rank = 2) TeGold
	ON silver_Co.ioccode = TeGold.ioccode
	JOIN events Ev ON Ev.eventID = TeGold.eventID
	JOIN games silver_Ga ON silver_Ga.gameID = Ev.gameID
	GROUP BY silver_Ga.gameID, silver_Co.ioccode) silverTab
ON (goldTab.gameID = silverTab.gameID AND goldTab.ioccode = silverTab.ioccode)
JOIN	(SELECT bronze_Ga.gameID, bronze_Co.ioccode, COUNT(*) AS bronzeCount
	FROM countries bronze_Co
	JOIN 	(SELECT DISTINCT bronze_Te.eventID, bronze_Te.ioccode
		FROM teams bronze_Te
		WHERE bronze_Te.rank = 3) TeGold
	ON bronze_Co.ioccode = TeGold.ioccode
	JOIN events Ev ON Ev.eventID = TeGold.eventID
	JOIN games bronze_Ga ON bronze_Ga.gameID = Ev.gameID
	GROUP BY bronze_Ga.gameID, bronze_Co.ioccode) bronzeTab
ON (goldTab.gameID = bronzeTab.gameID AND goldTab.ioccode = bronzeTab.ioccode)
JOIN games Ga ON Ga.gameID = goldTab.gameID
JOIN cities Ci ON Ga.cityID = Ci.cityID
-- WHERE Ga.year = $user_year AND Ga.season = $user_season
ORDER BY Ga.year ASC, Ga.seasonName ASC, goldTab.goldCount DESC

