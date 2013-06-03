<?php include '../includes/header.php'; ?>
<h1>Longest Medal Wait by Discipline</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	
	SELECT Di.name AS disciplineName, Co.name AS countryName
	FROM Di_LongestMedalGapCountry LMGC
	JOIN disciplines Di ON Di.disciplineID = LMGC.disciplineID
	JOIN countries Co ON Co.ioccode = LMGC.ioccode
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['disciplineName'] . ': ' . $data['countryName'] . '<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>