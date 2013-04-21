
<?php
include 'config.php';
$id = $_GET['id'];
$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);

$qry = $db->prepare('
	
SELECT co.name AS coname
FROM countries co
WHERE co.iocCode = \'' . $id . '\'

	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
$name = $res['coname'];

?>
<h1>Details for Country "<?php echo($name)?>":</h1>



<h3>Hosted games:</h3>
<?php 
$qry = $db->prepare('	
SELECT DISTINCT g.year AS year, ci.name AS ciname, g.gameID AS gID
FROM games g
INNER JOIN cities ci ON ci.cityID = g.cityID
WHERE ci.iocCode = \'' . $id . '\'
	');
$qry->execute();

$getYear = function($arr) {
	return '<a href="details-g.php?id=' . $arr['gID'] . '">' . $arr['ciname'] . ' (' . $arr['year'] . ')</a>';
};

$years = array_map($getYear, $qry->fetchAll());

echo(implode('<br />', $years));

?>

<h3>Athletes:</h3>

<?php 
$qry = $db->prepare('	
SELECT DISTINCT g.year AS year, a.name AS aname, a.athleteID AS aID
FROM games g
INNER JOIN events e ON e.gameID = g.gameID
INNER JOIN teams t ON t.eventID = e.eventID
INNER JOIN memberships m ON m.teamID = t.teamID
INNER JOIN athletes a ON a.athleteID = m.athleteID
INNER JOIN countries co ON co.iocCode = t.iocCode
WHERE co.iocCode = \'' . $id . '\'
ORDER BY year DESC
');
$qry->execute();

$res = $qry->fetchAll();
$size = count($res);
$prevYear = -1;

for ($i = 0; $i < $size; $i++) {
	$r = $res[$i];
	
	// year lvl
	if ($prevYear != $r['year']) {
		$prevYear = $r['year'];
		echo('<b>' . $r['year'] . '</b><br />');
	}
	
	// Event description
	echo('<a href="details-a.php?id=' . $r['aID'] .  '">' . $r['aname'] . '</a><br />');
}
?>

<h3>Medals:</h3>
<?php
$medals = array(1 => 'Gold', 2 => 'Silver', 3 => 'Bronze');
$qry = $db->prepare('
SELECT t.rank AS rank, COUNT(*) AS count
FROM teams t
WHERE t.rank < 4 AND t.rank > 0 AND t.iocCode = \'' . $id . '\'
GROUP BY t.rank
ORDER BY t.rank
');
$qry->execute();

$res = $qry->fetchAll();
$rowCnt = count($res);

for ($i = 0; $i < $rowCnt; $i++) {
	$r = $res[$i];
	echo('  ' . $medals[$r['rank']] . ': ' . $r['count'] . '<br />');
}

?>


<h3>Medals by Games</h3>
<?php
$qry = $db->prepare('
SELECT t.rank AS rank, g.year AS year, COUNT(*) AS count, g.gameID AS gID
FROM teams t
INNER JOIN events e ON e.eventID = t.eventID
INNER JOIN games g ON g.gameID = e.gameID
WHERE t.rank < 4 AND t.rank > 0 AND t.iocCode = \'' . $id . '\'
GROUP BY g.year, t.rank
ORDER BY g.year DESC, t.rank
');
$qry->execute();

$res = $qry->fetchAll();
$rowCnt = count($res);

$prevYear = -1;

for ($i = 0; $i < $rowCnt; $i++) {
	$r = $res[$i];
	if ($prevYear != $r['year']) {
		echo('<a href="details-g.php?id=' . $r['gID'] . '">' . $r['year'] . '</a><br />');
	}
	echo(' - ' . $medals[$r['rank']] . ': ' . $r['count'] . '<br />');
}
?>


<?php
$db = null;
?>

