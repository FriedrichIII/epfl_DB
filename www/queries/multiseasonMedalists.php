<h1>Multi-Season Medalists</h1>
<?php
try
{
	$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');
	$qry = $db->prepare('
SELECT A.name
FROM Athletes A
JOIN	(SELECT M.athleteID
	FROM Membership M
	JOIN Teams T
	ON M.teamID = T.teamID
	JOIN Events E
	ON T.eventID = E.eventID
	JOIN Games G
	ON G.gameID = E.gameID
	WHERE T.rank < 4 AND T.rank > 0
	GROUP BY M.athleteID
	HAVING COUNT (DISTINCT G.season) = 2) MM
ON A.athleteID = MM.athleteID')
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>