<?php include '../includes/header.php'; ?>
<h1>Top 10 In First Apparition Of disciplines</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('

	SELECT Co.name AS country, CM.gold AS gold, CM.silver AS silver, CM.bronze AS bronze
	FROM countries Co
	JOIN Co_to10Pos T10 ON Co.ioccode = T10.ioccode
	JOIN Co_medals CM ON CM.ioccode = T10.ioccode
	ORDER BY CM.gold DESC, CM.silver DESC, CM.bronze DESC

	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['country'] . ': ' . $data['gold'] . ', ' . $data['silver'] . ', ' . $data['bronze'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>