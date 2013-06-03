<?php include '../includes/header.php'; ?>
<h1>First Medal Type by Nation</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('

	SELECT Co.name AS country, CASE FM.rank WHEN 1 THEN \'Gold\' WHEN 2 THEN \'Silver\' WHEN 3 THEN \'Bronze\' ELSE \'ERROR\' END AS firstMedal
	FROM countries Co
	JOIN Co_firstMedal FM ON Co.ioccode = FM.ioccode
	ORDER BY FM.rank ASC
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['country'] . ': ' . $data['firstMedal'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>