<?php include '../includes/header.php'; ?>
<h1>Multi-Season Medalists</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
SELECT DISTINCT A.name AS aname
FROM Athletes A
JOIN	(SELECT M.athleteID
	FROM Memberships M
	JOIN Teams T
	ON M.teamID = T.teamID
	JOIN Events E
	ON T.eventID = E.eventID
	JOIN Games G
	ON G.gameID = E.gameID
	WHERE T.rank < 4 AND T.rank > 0
	GROUP BY M.athleteID, G.seasonName
	HAVING COUNT(*) = 2) MM
ON A.athleteID = MM.athleteID
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
<?php include '../includes/footer.php'; ?>