<?php include '../includes/header.php'; ?>
<h1>Top 10 Team Sport</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('

	SELECT Co.name AS countryName, Te.avgMedalists AS avgTeamMedalistCount
	FROM Co_AvgTeamMedalist Te
	JOIN countries Co ON Te.ioccode = Co.ioccode
	ORDER BY Te.avgMedalists DESC
	LIMIT 10
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['countryName'] . ': ' . $data['avgTeamMedalistCount'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>