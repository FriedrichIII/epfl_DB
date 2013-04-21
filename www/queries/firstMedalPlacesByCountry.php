<h1>First Medal Places by Country</h1>
<?php
try
{
	include 'config.php';
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
// 	$qry = $db->prepare('
// SELECT ctry.name, ct.name
// FROM Countries ctry, Games g0
// JOIN Cities ct
// ON g0.cityID = ct.cityID
// JOIN	(SELECT cgy.iocCode, cgy.seasonName, cgy.minYear
// 	FROM	(SELECT t.iocCode, gg.seasonName, MIN(gg.year) minYear
// 		FROM	(SELECT *
// 			FROM Teams tt
// 			WHERE tt.rank < 4 AND tt.rank > 0) t
// 		JOIN Events e
// 		ON e.eventID = t.eventID
// 		JOIN Games gg
// 		ON e.gameID = gg.gameID
// 		GROUP BY t.iocCode, gg.seasonName ) cgy
// 	JOIN	(SELECT t2.iocCode, MIN(gg2.year) minYear
// 		FROM	(SELECT *
// 			FROM Teams tt2
// 			WHERE tt2.rank < 4 AND tt2.rank > 0) t2
// 		JOIN Events e2
// 		ON e2.eventID = t2.eventID
// 		JOIN Games gg2
// 		ON e2.gameID = gg2.gameID
// 		GROUP BY t2.iocCode) cy
// 	ON cgy.iocCode = cy.iocCode AND cgy.minYear = cy.minYear
// 	LEFT OUTER JOIN
// 		(SELECT cgy3.iocCode
// 		FROM	(SELECT t3.iocCode, gg3.seasonName, MIN(gg3.year) minYear
// 			FROM	(SELECT *
// 				FROM Teams tt3
// 				WHERE tt3.rank < 4 AND tt3.rank > 0) t3
// 			JOIN Events e3
// 			ON e3.eventID = t3.eventID
// 			JOIN Games gg3
// 			ON e3.gameID = gg3.gameID
// 			GROUP BY t3.iocCode, gg3.seasonName ) cgy3
// 		JOIN	(SELECT t32.iocCode, MIN(gg32.year) minYear
// 			FROM	(SELECT *
// 				FROM Teams tt32
// 				WHERE tt32.rank < 4 AND tt32.rank > 0) t32
// 			JOIN Events e32
// 			ON e32.eventID = t32.eventID
// 			JOIN Games gg32
// 			ON e32.gameID = gg32.gameID
// 			GROUP BY t32.iocCode) cy3
// 		ON cgy3.iocCode = cy3.iocCode AND cgy3.minYear = cy3.minYear
// 		GROUP BY cgy3.iocCode
// 		HAVING COUNT(*) = 1) oneSeason
// 	ON cgy.iocCode = oneSeason.iocCode
// 	WHERE cgy.seasonName = \'Summer\' OR oneSeason.iocCode IS NOT NULL ) csy
// ON ctry.iocCode = csy.iocCode AND g0.seasonName = csy.seasonName AND g0.year = csy.minYear
// 	');
	$qry = $db->prepare('
	
	SELECT co.name AS coname, ci.name AS ciname, g.year
	FROM countries co
	INNER JOIN teams t ON co.iocCode = t.iocCode
	INNER JOIN events e ON e.eventID = t.eventID
	INNER JOIN games g ON g.gameID = e.gameID
	INNER JOIN cities ci ON ci.cityID = g.cityID
	WHERE CONCAT(g.year, co.iocCode) in
		(SELECT CONCAT(MIN(g2.year), t2.iocCode)
		FROM teams t2
		INNER JOIN events e2 ON e2.eventID = t2.eventID
		INNER JOIN games g2 ON g2.gameID = e2.gameID
		GROUP BY t2.iocCode)
	ORDER BY co.name
	
	');
	$qry->execute();
	
	while($data = $qry->fetch())
	{
		echo($data['coname'] . ': ' . $data['ciname'] . ' (' . $data['year'] . ')<br />');
	}
}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>