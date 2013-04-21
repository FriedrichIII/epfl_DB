
<?php
$id =  $_GET['id'];
$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');

$qry = $db->prepare('
SELECT ci.name AS ciname, ci.cityID AS ciID, g.year AS year, g.seasonName AS season, co.name AS coname, co.iocCode AS iocCode
FROM games g
INNER JOIN cities ci ON g.cityID = ci.cityID
INNER JOIN countries co ON co.iocCode = ci.iocCode
WHERE g.gameID = \'' . $id . '\'
	');

$qry->execute();
$res = $qry->fetch();
$qry->fetchAll();
?>

<h1>Details for Olympic Games "<?php echo($res['ciname'] . ' ' . $res['year'])?>":</h1>

<h3>Season:</h3>
<?php echo($res['season'])?>
<h3>Place:</h3>
<?php echo('<a href="details-ci.php?id=' . $res['ciID'] . '">' . $res['ciname'] . '</a>, '
 . '<a href="details-co.php?id=' . $res['iocCode'] . '">' . $res['coname']. '</a>')?>


<h3>Events / Medalists:</h3>

<?php
$medals = array(1 => 'Gold', 2 => 'Silver', 3 => 'Bronze');

$qry = $db->prepare('	
SELECT e.eventID as eID, a.athleteID as aID, a.name AS aname, t.rank AS rank, d.name AS dname, s.name AS sname, e.name AS ename
FROM games g
INNER JOIN events e ON e.gameID = g.gameID
INNER JOIN teams t ON t.eventID = e.eventID
INNER JOIN memberships m ON m.teamID = t.teamID
INNER JOIN athletes a ON a.athleteID = m.athleteID
INNER JOIN disciplines d ON d.disciplineID = e.disciplineID
INNER JOIN sports s ON s.sportID = d.disciplineID
WHERE g.gameID = \'' . $id . '\' AND t.rank < 4
ORDER BY s.name, d.name, t.rank, a.name
');


$qry->execute();

$res = $qry->fetchAll();
$size = count($res);
$prevEID = -1;

for ($i = 0; $i < $size; $i++) {
	$r = $res[$i];
	
	// rank
	if ($prevEID != $r['eID']) {
		$prevEID = $r['eID'];
		echo('<a href="details-e.php?id="' . $r['eID'] . '"><b>' . $r['sname'] .
		 ' ' . $r['dname'] . ' ' . $r['ename'] . '</b><br />');
	}
	
	// Athlete
	echo('<a href="details-a.php?id=' . $r['aID'] .  '">' . $r['aname'] .
	 ' ('  . $medals[$r['rank']] . ')</a><br />');
}

$db = null;
?>

