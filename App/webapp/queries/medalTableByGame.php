<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
SELECT Ga.year AS year, Ga.seasonName AS season, Ci.name AS cityName, goldTab.ioccode AS countryCode, goldTab.goldCount AS gold, silverTab.silverCount AS silver, bronzeTab.bronzeCount AS bronze
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
WHERE Ga.year = \'' . str_replace("'", "''",$_GET['year_field']) . '\' AND Ga.seasonName = \'' . str_replace("'", "''",$_GET['season']) . '\'
ORDER BY goldTab.goldCount DESC, silverTab.silverCount DESC, bronzeTab.bronzeCount DESC
	
	');
	$qry->execute();

	
	$init = 0;
	while($data = $qry->fetch())
	{
		if ($init == 0) {
			$init = 1;
			echo("<h1>" . $data['season'] . " Games of " . $data['cityName'] . " " . $data['year']  . " - Medal Table</h1>");
			echo("(gold - silver - bronze)<br/><br/>");
		}
		echo($data['countryCode'] . ' > ' . $data['gold'] . ' - ' . $data['silver'] . ' - ' . $data['bronze']. ' <br/>');
	}
	if ($init == 0) {
		echo("No game for given year/season.");
	}
}


catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>