<?php include '../includes/header.php'; ?>
<h1>Gold in Team but Not Gold Single</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	
	SELECT Ath.name AS athlete
	FROM	(SELECT DISTINCT SM.athleteID
		FROM SingleMedalistsAthlete SM
		JOIN TeamMedalistsAthlete TM ON SM.athleteID = TM.athleteID) AID
	JOIN athletes Ath ON Ath.athleteID = AID.athleteID
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['athlete'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>