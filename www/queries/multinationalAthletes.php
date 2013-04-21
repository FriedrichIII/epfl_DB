<h1>Multinational Athletes</h1>
<?php
try
{
	$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');
	$qry = $db->prepare('
SELECT a.name
FROM Athletes a
JOIN 	(SELECT DISTINCT m.athleteID
	FROM Membership m
	JOIN Teams t
	ON m.teamID = t.teamID
	GROUP BY m.athleteID
	HAVING COUNT(DISTINCT t.iocCode) > 1 ) m2
ON a.athleteID = m2.athleteID');
	$qry->execute();
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>