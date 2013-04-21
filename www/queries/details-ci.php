
<?php
include 'config.php';
$id =  $_GET['id'];
$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);

$qry = $db->prepare('
	
SELECT ci.name AS ciname
FROM cities ci
WHERE ci.cityID = ' . $id . '

	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
$name = $res['ciname'];

?>
<h1>Details for City "<?php echo($name)?>":</h1>



<h3>Hosted games in year:</h3>
<?php 
$qry = $db->prepare('	
SELECT DISTINCT year, gameID
FROM games g
WHERE g.cityID = ' . $id . '
	');
$qry->execute();

$getYear = function($arr) {
	return '<a href="details-g.php?id=' . $arr['gameID'] . '">' . $arr['year'] . '</a>';
};
$years = array_map($getYear, $qry->fetchAll());

echo(implode(', ', $years));

$db = null;

?>
