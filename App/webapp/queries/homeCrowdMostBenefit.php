<?php include '../includes/header.php'; ?>
<h1>Home Most Benefit By Season</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	
	SELECT Benef2.seasonName AS seasonName, Benef2.ioccode AS country, Benef2.benefit AS benefit
	FROM	(SELECT Benef.seasonName, MAX(Benef.benefit) AS maxBenef
		FROM Se_Co_Benef Benef
		GROUP BY Benef.seasonName) BenefMax
	JOIN Se_Co_Benef Benef2 ON Benef2.seasonName = BenefMax.seasonName AND Benef2.benefit = BenefMax.maxBenef
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['seasonName'] . ': ' . $data['country'] . ' (' . $data['benefit'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>