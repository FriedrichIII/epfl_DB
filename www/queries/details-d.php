
<?php
include 'config.php';
$id = $_GET['id'];
$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);

$qry = $db->prepare('
SELECT d.name AS dname, s.name AS sname
FROM disciplines d
INNER JOIN sports s ON s.sportID = d.sportID
WHERE d.disciplineID = \'' . $id . '\'
	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
$name = $res['sname'] . ' ' . $res['dname'];

?>
<h1>Details for Discipline "<?php echo($name)?>":</h1>



<h3>Occurred in games:</h3>
<?php 
$qry = $db->prepare('	
SELECT DISTINCT g.year AS year, ci.name AS ciname, e.eventID AS eID
FROM disciplines d
INNER JOIN events e ON e.disciplineID = d.disciplineID
INNER JOIN games g ON g.gameID = e.gameID
INNER JOIN cities ci ON ci.cityID = g.cityID
WHERE d.disciplineID = ' . $id . '
ORDER BY g.year
	');
$qry->execute();

$getGame = function($arr) {
	return '<a href="details-e.php?id=' . $arr['eID'] . '">' . $arr['ciname'] . ' ' . $arr['year'] . '</a>';
};

$games = array_map($getGame, $qry->fetchAll());

echo(implode('<br />', $games));

?>

<h3>Medalists:</h3>

<?php
$medals = array(1 => 'Gold', 2 => 'Silver', 3 => 'Bronze');

$qry = $db->prepare('	
SELECT g.year AS year, a.name AS aname, a.athleteID AS aID, t.rank AS rank
FROM games g
INNER JOIN events e ON e.gameID = g.gameID
INNER JOIN teams t ON t.eventID = e.eventID
INNER JOIN memberships m ON m.teamID = t.teamID
INNER JOIN athletes a ON a.athleteID = m.athleteID
INNER JOIN disciplines d ON d.disciplineID = e.disciplineID
WHERE d.disciplineID = \'' . $id . '\' AND t.rank < 4
ORDER BY t.rank, g.year DESC
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
	echo('<a href="details-a.php?id=' . $r['aID'] .  '">' . $r['year'] . ' - ' . $r['aname'] . '</a><br />');
}

$db = null;
?>

