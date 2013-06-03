<h1>Multinational Medalist</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	SELECT Ath.name AS athleteName
	FROM athletes Ath
	JOIN memberships Me ON Ath.athleteID = Me.athleteID
	JOIN teams Te ON Me.teamID = Te.teamID
	WHERE Te.rank < 4 AND Te.rank > 0
	GROUP BY Ath.athleteID
	HAVING COUNT(DISTINCT Te.ioccode) > 1
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['athleteName'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>