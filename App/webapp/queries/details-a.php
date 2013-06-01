
<?php
include 'config.php';
$id =  $_GET['id'];

try {
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	
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
	<h1>Details for Athlete "<?php echo($name)?>":</h1>
	
	
	
	<h3>Country</h3>
	<?php 
	$qry = $db->prepare('	
	SELECT DISTINCT co.name AS coname, co.iocCode AS iocCode
	FROM athletes a
	INNER JOIN memberships m ON m.athleteID = a.athleteID
	INNER JOIN teams t ON t.teamID = m.teamID
	INNER JOIN countries co ON co.iocCode = t.iocCode
	WHERE a.athleteID = ' . $id . '
		');
	$qry->execute();
	
	$getCoName = function($arr) {
		return '<a href="details-co.php?id=' . $arr['iocCode'] . '">' .$arr['coname'] . '</a>';
	};
	$countries = array_map($getCoName, $qry->fetchAll());
	
	echo(implode(', ', $countries))?>
	
	
	
	<h3>Sports and Disciplines</h3>
	<?php 
	$qry = $db->prepare('
	SELECT DISTINCT s.name AS sname, d.name AS dname, d.disciplineID AS dID
	FROM athletes a
	INNER JOIN memberships m ON m.athleteID = a.athleteID
	INNER JOIN teams t ON t.teamID = m.teamID
	INNER JOIN events e ON e.eventID = t.eventID
	INNER JOIN disciplines d ON e.disciplineID = d.disciplineID
	INNER JOIN sports s ON s.sportID = d.sportID
	WHERE a.athleteID = ' . $id . '
	
		');
	$qry->execute();
	
	$getSDName = function($arr) {
		return '<a href="details-d.php?id=' . $arr['dID'] . '">' .$arr['sname'] . " / " . $arr['dname'] . '</a>';
	};
	$discs = array_map($getSDName, $qry->fetchAll());
	
	echo(implode("<br />", $discs));?>
	
	
	<h3>Game Participations</h3>
	<?php
	$qry = $db->prepare('
	SELECT DISTINCT g.year AS year, ci.name AS ciname, g.gameID AS gID
	FROM athletes a
	INNER JOIN memberships m ON m.athleteID = a.athleteID
	INNER JOIN teams t ON t.teamID = m.teamID
	INNER JOIN events e ON e.eventID = t.eventID
	INNER JOIN games g ON g.gameID = e.gameID
	INNER JOIN cities ci ON ci.cityID = g.cityID
	WHERE a.athleteID = ' . $id . '
	ORDER BY g.year DESC
		');
	$qry->execute();
	
	$getGDesc = function($arr) {
		return '<a href="details-g.php?id=' . $arr['gID'] . '">' . $arr['ciname'] . " " . $arr['year'] . '</a>';
	};
	$games = array_map($getGDesc, $qry->fetchAll());
	
	echo(implode("<br />", $games));
	?>
	
	<h3>Medals</h3>
	<?php
	$medals = array(1 => 'Gold', 2 => 'Silver', 3 => 'Bronze');
	$qry = $db->prepare('
	SELECT t.rank AS rank, g.year AS year, e.name AS ename, d.name AS dname, s.name AS sname, e.eventID AS eID
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
	$prevRank = -1;
	
	for ($i = 0; $i < $size; $i++) {
		$r = $res[$i];
		
		// Rank lvl
		if ($prevRank < $r['rank']) {
			$prevRank = $r['rank'];
			echo('<b>' . $medals[$r['rank']] . '</b><br />');
		}
		
		// Event description
		echo('<a href="details-e.php?id=' . $r['eID'] . '">' . $r['sname'] .
		 ' ' . $r['dname'] . ' '. $r['ename'] . ' ' . $r['year'] . '</a><br />');
	}
	
	$db = null;

} catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}

?>
