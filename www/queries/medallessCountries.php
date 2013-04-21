<h1>Medalless Countries</h1>
<?php
try
{
	$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');
	$qry = $db->prepare('
SELECT c.name
FROM Countries c
LEFT OUTER JOIN
	(SELECT DISTINCT t.iocCode
	FROM Team t
	WHERE t.rank > 0 and t.rank < 4) Oc
ON c.iocCode = Oc.iocCode
WHERE Oc.iocCode IS NULL');
	$qry->execute();
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>