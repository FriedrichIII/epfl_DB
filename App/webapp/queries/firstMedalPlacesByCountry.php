<?php include '../includes/header.php'; ?>
<h1>First Medal Places by Country</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	
	SELECT co.name AS coname, ci.name AS ciname, g.year
	FROM countries co
	INNER JOIN teams t ON co.iocCode = t.iocCode
	INNER JOIN events e ON e.eventID = t.eventID
	INNER JOIN games g ON g.gameID = e.gameID
	INNER JOIN cities ci ON ci.cityID = g.cityID
	WHERE CONCAT(g.year, co.iocCode) in
		(SELECT CONCAT(MIN(g2.year), t2.iocCode)
		FROM teams t2
		INNER JOIN events e2 ON e2.eventID = t2.eventID
		INNER JOIN games g2 ON g2.gameID = e2.gameID
		GROUP BY t2.iocCode)
	ORDER BY co.name
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['coname'] . ': ' . $data['ciname'] . ' (' . $data['year'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>