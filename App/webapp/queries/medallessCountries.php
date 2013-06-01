<h1>Medalless Countries</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
SELECT c.name AS coname
FROM Countries c
LEFT OUTER JOIN
	(SELECT DISTINCT t.iocCode
	FROM Teams t
	WHERE t.rank > 0 and t.rank < 4) Oc
ON c.iocCode = Oc.iocCode
WHERE Oc.iocCode IS NULL');
	$qry->execute();
	while($data = $qry->fetch())
	{
		echo($data['coname'] . '<br/>');
	}
}


catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>