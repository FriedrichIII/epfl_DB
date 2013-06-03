<h1>First Medal Places by Country</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	SELECT Ev.name AS eventName
	FROM events Ev
	JOIN 	(SELECT Ev1.eventId
		FROM events Ev1
		JOIN teams Te ON Ev1.eventID = Te.eventID
		WHERE Te.rank < 4 AND Te.rank > 0
		GROUP BY Ev1.eventID
		HAVING COUNT(DISTINCT Te.ioccode) = 1) EvIds
	ON Ev.eventID = EvIds.eventID
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['eventName'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>