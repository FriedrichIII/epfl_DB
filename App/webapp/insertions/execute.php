<?php

// Prepare pdo
include '../queries/config.php';
$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);


// Detect insertion type
// Gather field info
// Call insertion function with info
$entryType = $_GET['entry_type'];
$action = $_GET['action'];

// Athlete
if ($entryType == 'athlete') {
	$name = $_GET['name_field'];
	if ($action = "insert") {
		insertAthlete($db, $name);
	} else {
		deleteAthlete($db, $name);
	}
	
// City
} elseif ($entryType == 'city') {
	$name = $_GET['name_field'];
	$countryCode = $_GET['country_code_field'];
	insertCity($db, $name, $countryCode);
	
// Country
} elseif ($entryType == 'country') {
	
// Event
} elseif ($entryType == 'event') {

// Sport
} elseif ($entryType == 'sport') {

// Team
} elseif ($entryType == 'team') {

// Team Membership
} elseif ($entryType == 'teamMembership') {
	
// Other (unkown) type
} else {
	echo('Unknown entry type');
}

function insertAthlete($db, $name) {
	$qry = $db->prepare('
	
INSERT INTO athletes
(name)
VALUES
(' . $name . ')	
	
	');
	$qry->execute();
	echo('Entry sucessfully inserted!');
}

function deleteAthlete($db, $name) {
	$qry = $db->prepare('
		
	DELETE FROM athletes
	WHERE name = \'' . $name . '\'	
		
		');
	$qry->execute();
	echo('Entry sucessfully deleted!');	
}

function insertCity($db, $name, $countryCode) {
	$qry = $db->prepare('');
}

function insertCountry($db, $name, $countryCode) {
	
}

function insertDiscipline($db, $name, $sport) {
	
}

function insertEvent($db, $discipline, $year) {
	
}

function insertSport($db, $name) {
	
}

function insertTeam($db, $country, $year, $discipline, $rank, $athletes) {
	
}

?>
<br />
<a href="index.php">back...</a>