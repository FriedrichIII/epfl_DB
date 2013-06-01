<h1>Countries with most Participants by Game</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('

SELECT g_plus_c.year AS year, g_plus_c.seasonName AS season, g_plus_c.name AS coname, ac
FROM 	(SELECT g.year AS year, g.seasonName AS seasonName, c.name AS name, g.gameID AS gameID, c.iocCode AS iocCode
	FROM Games g, Countries c) g_plus_c
JOIN	(SELECT e.gameID, t.iocCode, COUNT(DISTINCT m.athleteID) ac
	FROM Memberships m
	JOIN Teams t
	ON m.teamID = t.teamID
	JOIN Events e
	ON t.eventID = e.eventID
	GROUP BY e.gameID, t.iocCode) gca
ON g_plus_c.gameID = gca.gameID AND g_plus_c.iocCode = gca.iocCode
JOIN	(SELECT gca2.gameID, MAX(gca2.ac) am
	FROM	(SELECT e2.gameID, t2.iocCode, COUNT(DISTINCT m2.athleteID) ac
		FROM Memberships m2
		JOIN Teams t2
		ON m2.teamID = t2.teamID
		JOIN Events e2
		ON t2.eventID = e2.eventID
		GROUP BY e2.gameID, t2.iocCode) gca2
	GROUP BY gca2.gameID) gam
ON gca.gameID = gam.gameID AND gca.ac = gam.am
ORDER BY year, season ASC
');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['season'] . ' ' . $data['year'] . ': ' . $data['coname'] . ' (' . $data['ac'] . ')<br />' );
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>