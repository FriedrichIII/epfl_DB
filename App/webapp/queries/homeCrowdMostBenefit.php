<?php include '../includes/header.php'; ?>
<h1>Home Most Benefit By Season</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	
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

	SELECT Benef2.seasonName AS seasonName, Benef2.ioccode AS country, Benef2.benefit AS benefit
	FROM	(SELECT Benef.seasonName, MAX(Benef.benefit) AS maxBenef
		FROM Se_Co_Benef Benef
		GROUP BY Benef.seasonName) BenefMax
	JOIN Se_Co_Benef Benef2 ON Benef2.seasonName = BenefMax.seasonName AND Benef2.benefit = BenefMax.maxBenef
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['seasonName'] . ': ' . $data['country'] . ' (' . $data['benefit'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>