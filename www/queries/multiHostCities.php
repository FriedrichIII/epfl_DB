<h1>Multihost Cities</h1>
<?php
try
{
	$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');
	$qry = $db->prepare('
SELECT ct.name
FROM Cities ct
JOIN	(SELECT DISTINCT g.cityID
	FROM Games g
	GROUP BY g.cityID
	HAVING COUNT(*) > 1) h
ON c.cityID = h.citiyID')
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>