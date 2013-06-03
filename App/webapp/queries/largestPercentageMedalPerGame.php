<?php include '../includes/header.php'; ?>
<h1>Largest Percentage of Medal per Game</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
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

	SELECT Ga.seasonName AS season, Ga.year AS year, Co.name AS biggestPercentCountry
	FROM 	(SELECT MP1.gameID, MP1.ioccode
		FROM Ga_Co_MedPercent MP1
		LEFT OUTER JOIN Ga_Co_MedPercent MP2
		ON MP1.gameID = MP2.gameID AND MP1.medPercent < MP2.medPercent
		WHERE MP2.gameID IS NULL) MPC
	JOIN games Ga ON Ga.gameID = MPC.gameID
	JOIN countries Co ON Co.ioccode = MPC.ioccode
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['season'] . ' Olympics of ' . $data['year'] . ' : ' . $data['biggestPercentCountry'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>