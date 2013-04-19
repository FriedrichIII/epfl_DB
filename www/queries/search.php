<h1>Search results</h1>
<p>for: "<?php echo(str_replace('<', '{', $_GET['search_field']))?>"</p>
<?php
try
{
	$db = new PDO('mysql:host=localhost;dbname=olympics', 'root', '');

	
	
	// ATHLETES
	$qry = $db->prepare('
	
	SELECT
		name
	FROM
		athletes
	WHERE
		name LIKE ?;
	
	');
	$qry->execute(array('%' . $_GET['search_field'] . '%'));
	echo('<h3>Athletes</h3>');
	while($data = $qry->fetch())
	{
		echo($data['name'] . "<br />");
	}
	
	
		
	// CITIES
	$qry = $db->prepare('
	
	SELECT
		name
	FROM
		cities
	WHERE
		name LIKE ?;
	
	');
	$qry->execute(array('%' . $_GET['search_field'] . '%'));
	echo('<h3>Cities</h3>');
	while($data = $qry->fetch())
	{
		echo($data['name'] . "<br />");
	}
	
	
	
	// COUNTRIES
	$qry = $db->prepare('
	
	SELECT
		name
	FROM
		countries
	WHERE
		name LIKE ?;
	
	');
	$qry->execute(array('%' . $_GET['search_field'] . '%'));
	echo('<h3>Countries</h3>');
	while($data = $qry->fetch())
	{
		echo($data['name'] . "<br />");
	}
	
	
	
	// DISCIPLINES
	$qry = $db->prepare('
	
	SELECT
		d.name AS dname, s.name AS sname
	FROM
		disciplines d INNER JOIN sports s
		ON d.sportID = s.sportID
	WHERE 
		d.name LIKE ? OR s.name LIKE ?;
	
	');
	$qry->execute(array('%' . $_GET['search_field'] . '%', '%' . $_GET['search_field'] . '%'));
	echo('<h3>Disciplines</h3>');
	while($data = $qry->fetch())
	{
		echo($data['sname'] . ",  " . $data['dname'] . "<br />");
	}
	
	
	
	// EVENTS
	$qry = $db->prepare('
	
	SELECT
		name
	FROM
		events
	WHERE
		name LIKE ?;
	
	');
	$qry->execute(array('%' . $_GET['search_field'] . '%'));
	echo('<h3>Events</h3>');
	while($data = $qry->fetch())
	{
		echo($data['name'] . "<br />");
	}
	
	
	
	// GAMES
	/*
	 * Search on season, year and city
	 */
	$qry = $db->prepare('
	
	SELECT
		g.seasonName, g.year, c.name
	FROM
		games g INNER JOIN cities c
		ON c.cityID = g.cityID
	WHERE 
		g.seasonName LIKE ? OR g.year LIKE ? OR c.name LIKE ?
	ORDER BY
		g.year;
	
	');
	$qry->execute(array('%' . $_GET['search_field'] . '%', '%' . $_GET['search_field'] . '%', '%' . $_GET['search_field'] . '%'));
	echo('<h3>Games</h3>');
	while($data = $qry->fetch())
	{
		echo($data['seasonName'] . " games of " . $data['name'] . " " . $data['year'] . "<br />");
	}
	
	
	
	// SPORTS
	$qry = $db->prepare('
	
	SELECT
		name
	FROM
		Sports
	WHERE
		name LIKE ?;
	
	');		
	$qry->execute(array('%' . $_GET['search_field'] . '%'));
	echo('<h3>Sports</h3>');
	while($data = $qry->fetch())
	{
		echo($data['name'] . "<br />");
	}
	

	
	// TODO special medal search
	


}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}
?>
