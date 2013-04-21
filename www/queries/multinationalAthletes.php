<h1>Multinational Athletes</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
SELECT a.name AS aname
FROM Athletes a
JOIN 	(SELECT DISTINCT m.athleteID
	FROM Memberships m
	JOIN Teams t
	ON m.teamID = t.teamID
	GROUP BY m.athleteID
	HAVING COUNT(DISTINCT t.iocCode) > 1 ) m2
ON a.athleteID = m2.athleteID
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['aname'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>