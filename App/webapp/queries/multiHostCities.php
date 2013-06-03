<?php include '../includes/header.php'; ?>
<h1>Multihost Cities</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
SELECT ci.name AS ciname
FROM Cities ci
JOIN	(SELECT DISTINCT g.cityID
	FROM Games g
	GROUP BY g.cityID
	HAVING COUNT(*) > 1) h
ON ci.cityID = h.cityID
	');
	$qry->execute();
	while($data = $qry->fetch())
	{
		echo($data['ciname'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>