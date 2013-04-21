
<?php
$id =  $_GET['id'];
$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');

$qry = $db->prepare('
	
SELECT a.name AS aname
FROM athletes a
WHERE a.athleteID = ' . $id . '

	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
$name = $res['aname'];

?>
<h1>Details for "<?php echo($name)?>": </h1>



<h3>Country</h3>
<?php 
$qry = $db->prepare('	
SELECT DISTINCT co.name AS coname
FROM athletes a
INNER JOIN memberships m ON m.athleteID = a.athleteID
INNER JOIN teams t ON t.teamID = m.teamID
INNER JOIN countries co ON co.iocCode = t.iocCode
WHERE a.athleteID = ' . $id . '
	');
$qry->execute();

$getCoName = function($arr) { return $arr['coname'];};
$countries = array_map($getCoName, $qry->fetchAll());

echo(implode(', ', $countries))?>



<h3> Sports and Disciplines </h3>
<?php 
$qry = $db->prepare('
SELECT DISTINCT s.name AS sname, d.name AS dname
FROM athletes a
INNER JOIN memberships m ON m.athleteID = a.athleteID
INNER JOIN teams t ON t.teamID = m.teamID
INNER JOIN events e ON e.eventID = t.eventID
INNER JOIN disciplines d ON e.disciplineID = d.disciplineID
INNER JOIN sports s ON s.sportID = d.sportID
WHERE a.athleteID = ' . $id . '

	');
$qry->execute();

$getSDName = function($arr) {return $arr['sname'] . " / " . $arr['dname'];};
$discs = array_map($getSDName, $qry->fetchAll());

echo(implode("<br />", $discs));?>


<h3>Game Participations</h3>
<?php
$qry = $db->prepare('
SELECT DISTINCT g.year AS year, ci.name AS ciname
FROM athletes a
INNER JOIN memberships m ON m.athleteID = a.athleteID
INNER JOIN teams t ON t.teamID = m.teamID
INNER JOIN events e ON e.eventID = t.eventID
INNER JOIN games g ON g.gameID = e.gameID
INNER JOIN cities ci ON ci.cityID = g.cityID
WHERE a.athleteID = ' . $id . '
ORDER BY g.year
	');
$qry->execute();

$getGDesc = function($arr) {
	return $arr['ciname'] . " " . $arr['year'];
};
$games = array_map($getGDesc, $qry->fetchAll());

echo(implode("<br />", $games));
?>

<h3>Medals</h3>
<?php
$medals = array(1 => 'Gold', 2 => 'Silver', 3 => 'Bronze');
$qry = $db->prepare('
SELECT t.rank AS rank, g.year AS year, e.name AS ename, d.name AS dname, s.name AS sname
FROM athletes a
INNER JOIN memberships m ON m.athleteID = a.athleteID
INNER JOIN teams t ON t.teamID = m.teamID
INNER JOIN events e ON e.eventID = t.eventID
INNER JOIN games g ON g.gameID = e.gameID
INNER JOIN disciplines d ON e.disciplineID = d.disciplineID
INNER JOIN sports s ON s.sportID = d.sportID
WHERE a.athleteID = ' . $id . ' AND t.rank < 4
ORDER BY t.rank, g.year DESC
	');
$qry->execute();

$res = $qry->fetchAll();
$size = count($res);
$currRank = -1;

for ($i = 0; $i < $size; $i++) {
	$r = $res[$i];
	
	// Rank lvl
	if ($currRank < $r['rank']) {
		echo('<b>' . $medals[$r['rank']] . '</b><br />');
	}
	
	// Event description
	echo($r['year'] . ' - ' . $r['sname'] . ' / ' . $r['dname'] . ' / '. $r['ename'] . '<br />');
}

?>
