<?php include '../includes/header.php'; ?>
<h1>3 Mos Medal by Sport</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	SELECT Sp.name AS sportName, Co.name AS countryName, SpCoMedCnt.medCount
	FROM	(SELECT SpCoMedCnt_1.sportID, SpCoMedCnt_1.ioccode, SpCoMedCnt_1.medCount
		FROM	(SELECT Sp_1.sportID, Te_1.ioccode, COUNT(Te_1.rank < 4 AND Te_1.rank > 0) AS medCount
			FROM sports Sp_1
			JOIN disciplines Di_1 ON Sp_1.sportID = Di_1.sportID
			JOIN events Ev_1 ON Di_1.disciplineID = Ev_1.disciplineID
			JOIN teams Te_1 ON Ev_1.eventID = Te_1.eventID
			GROUP BY Sp_1.sportID, Te_1.ioccode) SpCoMedCnt_1
		JOIN	(SELECT Sp_2.sportID, Te_2.ioccode, COUNT(Te_2.rank < 4 AND Te_2.rank > 0) AS medCount
			FROM sports Sp_2
			JOIN disciplines Di_2 ON Sp_2.sportID = Di_2.sportID
			JOIN events Ev_2 ON Di_2.disciplineID = Ev_2.disciplineID
			JOIN teams Te_2 ON Ev_2.eventID = Te_2.eventID
			GROUP BY Sp_2.sportID, Te_2.ioccode) SpCoMedCnt_2
		ON (SpCoMedCnt_1.sportID = SpCoMedCnt_2.sportID AND SpCoMedCnt_1.medCount <= SpCoMedCnt_2.medCount)
		GROUP BY SpCoMedCnt_1.sportID, SpCoMedCnt_1.ioccode, SpCoMedCnt_1.medCount
		HAVING COUNT(DISTINCT SpCoMedCnt_2.medCount) <= 3) SpCoMedCnt
	JOIN sports Sp ON Sp.sportID = SpCoMedCnt.sportID
	JOIN countries Co ON Co.ioccode = SpCoMedCnt.ioccode
	ORDER BY Sp.sportID ASC, SpCoMedCnt.medCount DESC
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['sportName'] . ': ' . $data['countryName'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>