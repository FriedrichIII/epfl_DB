<?php include '../includes/header.php'; ?>
<h1>Most Successful Country by Sport</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('

	SELECT Sp.name AS sport, Co.name AS country, Sc.score AS score
	FROM Sp_Co_Top10Scores Sc
	JOIN sports Sp ON Sc.sportID = Sp.sportID
	JOIN countries Co ON Co.ioccode = Sc.ioccode
	ORDER BY Sp.name, Sc.score DESC
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['sport'] . ': ' . $data['country'] . ' (' . $data['score'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>