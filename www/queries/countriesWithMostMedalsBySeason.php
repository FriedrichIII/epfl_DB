<h1>Countries with most Medals by Season</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
SELECT c.name, csm.seasonName
FROM Countries c
JOIN	(SELECT t.iocCode, g.seasonName, COUNT(*) mc
	FROM 	(SELECT *
		FROM Teams tt
		WHERE tt.rank > 0 && tt.rank < 4) t
	JOIN Events e
	ON t.eventID = e.eventID
	JOIN Games g
	ON e.gameID = g.gameID
	GROUP BY t.iocCode, g.seasonName) csm
ON c.iocCode = csm.iocCode
JOIN	(SELECT csm2.seasonName, MAX(csm2.mc) mm
	FROM	(SELECT t2.iocCode, g2.seasonName, COUNT(*) mc
		FROM 	(SELECT *
			FROM Teams tt2
			WHERE tt2.rank > 0 && tt2.rank < 4) t2
		JOIN Events e2
		ON t2.eventID = e2.eventID
		JOIN Games g2
		ON e2.gameID = g2.gameID
		GROUP BY t2.iocCode, g2.seasonName) csm2
	GROUP BY csm2.seasonName) smm
ON csm.seasonName = smm.seasonName AND csm.mc = smm.mm');
	$qry->execute();
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>