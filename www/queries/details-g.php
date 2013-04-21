
<?php
$id =  $_GET['id'];
$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');

$qry = $db->prepare('
SELECT ci.name AS ciname, g.year AS year
FROM games g
INNER JOIN cities ci ON g.cityID = ci.cityID
WHERE g.gameID = \'' . $id . '\'
	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
$name = $res['ciname'] . ' ' . $res['year'] ;
?>

<h1>Details for Olympic Games "<?php echo($name)?>":</h1>


<h3>Medalists:</h3>

<?php
$medals = array(1 => 'Gold', 2 => 'Silver', 3 => 'Bronze');

$qry = $db->prepare('	
SELECT a.name AS aname, a.athleteID AS aID, t.rank AS rank
FROM games g
INNER JOIN events e ON e.gameID = g.gameID
INNER JOIN teams t ON t.eventID = e.eventID
INNER JOIN memberships m ON m.teamID = t.teamID
INNER JOIN athletes a ON a.athleteID = m.athleteID
WHERE e.eventID = \'' . $id . '\' AND t.rank < 4
ORDER BY t.rank
');


$qry->execute();

$res = $qry->fetchAll();
$size = count($res);
$prevRank = -1;

for ($i = 0; $i < $size; $i++) {
	$r = $res[$i];
	
	// rank
	if ($prevRank < $r['rank']) {
		$prevRank = $r['rank'];
		echo('<b>' . $medals[$r['rank']] . '</b><br />');
	}
	
	// Athlete
	echo('<a href="details-a.php?id=' . $r['aID'] .  '">' . $r['aname'] . '</a><br />');
}

$db = null;
?>

