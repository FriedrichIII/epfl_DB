<?php include '../includes/header.php'; ?>
<?php
include 'config.php';

try {
$id = $_GET['id'];
$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);

$qry = $db->prepare('
SELECT s.name AS sname, d.name AS dname, e.name AS ename, g.year AS year
FROM sports s
INNER JOIN disciplines d ON s.sportID = d.sportID
INNER JOIN events e ON e.disciplineID = d.disciplineID
INNER JOIN games g ON g.gameID = e.gameID
WHERE e.eventID = \'' . $id . '\'
	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
$name = $res['sname'] . ' ' . $res['dname'] . ' ' . $res['ename'] . ' ' . $res['year'] ;
?>

<h1>Details for Event "<?php echo($name)?>":</h1>



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
}catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>
