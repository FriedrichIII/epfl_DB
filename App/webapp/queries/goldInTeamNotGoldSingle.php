<?php include '../includes/header.php'; ?>
<h1>Gold in Team but Not Gold Single</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	
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