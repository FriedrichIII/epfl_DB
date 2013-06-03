<?php include '../includes/header.php'; ?>
<h1>Most Successful Country by Sport</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	$qry = $db->prepare('
	CREATE OR REPLACE VIEW SingleMedalists AS
	SELECT Te.teamID
	FROM teams Te
	JOIN memberships Me ON Te.teamID = Me.teamID
	WHERE Te.rank < 4 AND Te.rank > 0
	GROUP BY Te.teamID
	HAVING COUNT(DISTINCT Me.athleteID) = 1;

	CREATE OR REPLACE VIEW Ev_Co_Score AS
	SELECT Ev.eventID, Te.ioccode, (4-Te.rank)*COUNT(*) AS score
	FROM events Ev 
	JOIN teams Te ON Te.eventID = Ev.eventID
	JOIN SingleMedalists SM ON SM.teamID = Te.teamID
	GROUP BY Ev.eventID, Te.ioccode, Te.rank
	HAVING COUNT(*) = 1

	UNION ALL

	SELECT Ev.eventID, Te.ioccode, (4-Te.rank)*COUNT(*)/2 AS score
	FROM events Ev 
	JOIN teams Te ON Te.eventID = Ev.eventID
	JOIN SingleMedalists SM ON SM.teamID = Te.teamID
	GROUP BY Ev.eventID, Te.ioccode, Te.rank
	HAVING COUNT(*) > 1;

	CREATE OR REPLACE VIEW Sp_Co_Score AS
	SELECT Di.sportID, Sc.ioccode, SUM(Sc.score) AS score
	FROM Ev_Co_Score Sc
	JOIN events Ev ON Sc.eventID = Ev.eventID
	JOIN disciplines Di ON Di.disciplineID = Ev.disciplineID
	GROUP BY Di.sportID, Sc.ioccode;

	CREATE OR REPLACE VIEW Sp_Co_Top10Scores AS
	SELECT Sc1.sportID, Sc1.ioccode, Sc1.score
	FROM Sp_Co_Score Sc1
	JOIN Sp_Co_Score Sc2
	ON Sc1.sportID = Sc2.sportID AND Sc1.score <= Sc2.score
	GROUP BY Sc1.sportID, Sc1.ioccode, Sc1.score
	HAVING COUNT(*) <= 10;

	SELECT Sp.name AS sport, Co.name AS country, Sc.score AS score
	FROM Sp_Co_Top10Scores Sc
	JOIN sports Sp ON Sc.sportID = Sp.sportID
	JOIN countries Co ON Co.ioccode = Sc.ioccode
	ORDER BY Sp.name, Sc.score DESC
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['sport'] . ': ' . $data['country'] . ' (' . $data['score'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
<?php include '../includes/footer.php'; ?>