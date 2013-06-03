<?php include '../includes/header.php'; ?>
<?php

//TODO add error test: qry->errorCode() === '00000'

// Prepare pdo
include '../queries/config.php';
$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);

// Detect insertion type
// Gather field info
// Call insertion function with info
$entryType = $_GET['entry_type'];
$action = $_GET['action'];
$result = '00000';

// Athlete
if ($entryType == 'athlete') {
	$name = myEscape($_GET['name_field']);
	if ($action == "Insert") {
		$result = insertAthlete($db, $name);
	} else {
		$result = deleteAthlete($db, $name);
	}

	// City
} elseif ($entryType == 'city') {
	$name = myEscape($_GET['name_field']);
	$countryCode = myEscape($_GET['country_code_field']);
	if ($action == "Insert") {
		$result = insertCity($db, $name, $countryCode);
	}else{
		$result = deleteCity($db, $name, $coutryCode);
	}

	// Country
} elseif ($entryType == 'country') {
	$name = myEscape($_GET['name_field']);
	$countryCode = myEscape($_GET['country_code_field']);

	if ($action == "Insert") {
		$result = insertCountry($db, $name, $countryCode);
	}else{
		$result = deleteCountry($db, $name, $countryCode);
	}

} else if ($entryType == 'discipline') {
	$name = myEscape($_GET['name_field']);
	$sport = myEscape($_GET['sport_field']);
	if ($action == "Insert") {
		$result = insertDiscipline($db, $name, $sport);
	}else{
		$result = deleteDiscipline($db, $name, $sport);
	}

	// Event
} elseif ($entryType == 'event') {
	$discipline = myEscape($_GET['discipline_field']);
	$year = myEscape($_GET['year_field']);
	$season = myEscape($_GET['season']);

	if ($action == "Insert") {
		$result = insertEvent($db, $discipline, $year, $season);
	}else{
		$result = deleteEvent($db, $discipline, $year, $season);
	}

	// Sport
} elseif ($entryType == 'sport') {
	$name = myEscape ($_GET['name_field']);

	if ($action == "Insert") {
		$result = insertSport($db, $name);
	}else{
		$result = deleteSport($db, $name);
	}

	// Team
} elseif ($entryType == 'team') {
	$athletes = explode('/', myEscape ($_GET['athletes_field']));
	$arrLen = count($athletes);
	for ($i = 0; $i < $arrLen; $i++) {
		$athletes[$i] = trim($athletes[$i]);
	}
	$countryCode = myEscape ($_GET['country_code_field']);
	$discipline = myEscape ($_GET['discipline_field']);
	$year = myEscape ($_GET['year_field']);
	$season = myEscape ($_GET['season']);
	$rank = myEscape ($_GET['rank_field']);

	if ($action == "Insert") {
		$result = insertTeam($db, $athletes, $countryCode, $discipline, $year, $season, $rank);
	}else{
		$result = deleteTeam($db, $athletes, $countryCode, $discipline, $year, $season, $rank);
	}

	// Other (unkown) type
} else {
	echo('Unknown entry type<br />');
}

if ($result === '00000') {
	echo("Insertion/Deletion successful.<br />");
} else {
	echo("Insertion/Deletion failed (". $result . ").<br />");
}


function insertAthlete($db, $name) {
	$qry = $db->prepare('
	
	INSERT INTO athletes
	VALUES(DEFAULT, \'' . $name . '\')
	
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function deleteAthlete($db, $name) {
	$qry = $db->prepare('
		
	DELETE FROM athletes
	WHERE name = \'' . $name . '\'	
		
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function insertCity($db, $name, $countryCode) {
	$qry = $db->prepare('
	
	INSERT INTO cities
	VALUES(DEFAULT, \''. $name . '\', \''. $countryCode . '\')
	
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function deleteCity($db, $name, $countryCode) {
	$qry = $db->prepare('
	
	DELETE FROM cities
	WHERE name = \'' . $name . '\'

	');
	$qry->execute();
	
	return $qry->errorCode();
}

function insertCountry($db, $name, $countryCode) {
	$qry = $db->prepare('
		
	INSERT INTO countries
	VALUES(\''. $name . '\', \''. $countryCode . '\')
		
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function deleteCountry($db, $name, $countryCode) {
	$qry = $db->prepare('
		
	DELETE FROM countries
	WHERE name = \'' . $name . '\' OR iocCode = \'' . $countryCode . '\'
	
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function insertDiscipline($db, $name, $sport) {

	// Get Sport id
	$qry = $db->prepare('
	SELECT sportID
	FROM sports
	WHERE name = \'' . $sport . '\'
	');
	$qry->execute();

	$sIDarr = $qry->fetchAll();

	if (count($sIDarr) == 0) {
		echo('Cannot insert discipline: no sport corresponding to input!');
	} else {
		$sID = $sIDarr[0][0];
		echo($sID);

		// Add new discipline entry
		$qry = $db->prepare('
				
		INSERT INTO disciplines
		VALUES(DEFAULT, \''. $name . '\', \''. $sID . '\')
				
		');
		$qry->execute();
		
		return $qry->errorCode();
	}

}

function deleteDiscipline($db, $name, $sport) {

	$qry = $db->prepare('
			
	DELETE FROM disciplines
	WHERE name = \'' . $name . '\'
		
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function insertEvent($db, $discipline, $year, $season) {
	// TODO

	$name = $discipline . " - " . $season . " " . $year;

	$miss = false;

	// get gameID
	$gID = getGameID($db, $season, $year);
	if ($gID == -1) {
		echo('Cannot insert event: no game corresponding to input!');
		$miss = true;
	}
	echo("gameID: " . $gID);

	// get disciplineID
	$dID = -1;
	if ($miss == false) {
		// get gameID
		$qry = $db->prepare('
		
			SELECT disciplineID
			FROM disciplines
			WHERE disciplines.name = \'' . $discipline . '\'
		');
		$qry->execute();

		$dIDarr = $qry->fetchAll();

		if (count($dIDarr) == 0) {
			echo('Cannot insert event: no discipline corresponding to input!');
			$miss = true;
		} else {
			$dID = $dIDarr[0][0];
		}
	}

	if ($miss == false) {
		$qry = $db->prepare('
					
		INSERT INTO events
		VALUES(DEFAULT, \''. $name . '\', \''. $gID . '\', \''. $dID . '\')
					
		');
		$qry->execute();
		
		return $qry->errorCode();
	}
}

function deleteEvent($db, $discipline, $year, $season) {

	$miss = false;

	// get gameID
	$gID = getGameID($db, $season, $year);
	if ($gID == -1) {
		echo('Cannot insert event: no game corresponding to input!');
		$miss = true;
	}

	// get disciplineID
	$dID = -1;
	if ($miss == false) {
		// get gameID
		$qry = $db->prepare('
			
				SELECT disciplineID
				FROM disciplines
				WHERE disciplines.name = \'' . $discipline . '\'
			');
		$qry->execute();

		$dIDarr = $qry->fetchAll();

		if (count($dIDarr) == 0) {
			echo('Cannot insert event: no discipline corresponding to input!');
			$miss = true;
		} else {
			$dID = $dIDarr[0][0];
		}
	}

	if ($miss == false) {
		$qry = $db->prepare('
			
		DELETE FROM cities
		WHERE gameID = \'' . $gID . '\' AND disciplineID = \'' . $dID . '\'
		
		');
		$qry->execute();
		
		return $qry->errorCode();
	}
}

function insertSport($db, $name) {

	$qry = $db->prepare('
				
	INSERT INTO sports
	VALUES(DEFAULT, \''. $name . '\')
				
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function deleteSport($db, $name) {

	$qry = $db->prepare('
			
	DELETE FROM sports
	WHERE name = \'' . $name . '\'
		
	');
	$qry->execute();
	
	return $qry->errorCode();
}

function insertTeam($db, $athletes, $countryCode, $discipline, $year, $season, $rank) {

	// get gameID
	$gID = getGameID($db, $season, $year);
	if ($gID == -1) {
		echo('Cannot insert event: no game corresponding to input!');
		$miss = true;
	}

	// get disciplineID
	$dID = -1;
	if ($miss == false) {
		$qry = $db->prepare('
				
			SELECT disciplineID
			FROM disciplines
			WHERE disciplines.name = \'' . $discipline . '\'
		
		');
		$qry->execute();

		$dIDarr = $qry->fetchAll();

		if (count($dIDarr) == 0) {
			echo('Cannot insert event: no discipline corresponding to input!');
			$miss = true;
		} else {
			$dID = $dIDarr[0][0];
		}
	}

	// get eventID
	$eID = -1;
	if ($miss == false) {
		$qry = $db->prepare('
					
		SELECT eventID
		FROM events
		WHERE events.disciplineID = \'' . $dID . '\' AND events.gameID = \'' . $gID . '\'
		
		');
		$qry->execute();

		$eIDarr = $qry->fetchAll();

		if (count($eIDarr) == 0) {
			echo('Cannot insert event: no discipline corresponding to input!');
			$miss = true;
		} else {
			$eID = $eIDarr[0][0];
		}
	}


	// get athletes IDs
	$aIDs = array();
	if ($miss == false) {
		$i = 0;
		foreach ($athletes as $athlete) {
			$qry = $db->prepare('
			
			SELECT athleteID
			FROM athletes
			WHERE athletes.name = \''. $athlete . '\'
			
			');
				
			$qry->execute();
				
			$aIDarr = $qry->fetchAll();
				
			if (count($aIDarr) == 0) {
				echo('Cannot insert event: no athlete corresponding to input!');
				$miss = true;
			} else {
				$aIDs[$i] = $aIDarr[0][0];
			}
			$i++;
		}
	}


	if ($miss == false) {
		$qry = $db->prepare('
						
		INSERT INTO teams
		VALUES(DEFAULT,\''. $countryCode . '\', \''. $rank . '\', \''. $eID . '\', NULL, NULL)
					
		');
		$qry->execute();

		$tID = $db->lastInsertId();
		
		foreach ($aIDs as $aID) {
			$qry = $db->prepare('
									
			INSERT INTO memberships
			VALUES(\''. $tID . '\', \''. $aID . '\')
							
			');
			$qry->execute();
		}

		return $qry->errorCode();
	}

}

function deleteTeam($db, $athletes, $countryCode, $discipline, $year, $season, $rank) {

	// get gameID
	$gID = getGameID($db, $season, $year);
	if ($gID == -1) {
		echo('Cannot insert event: no game corresponding to input!');
		$miss = true;
	}

	// get disciplineID
	$dID = -1;
	if ($miss == false) {
		$qry = $db->prepare('
				
			SELECT disciplineID
			FROM disciplines
			WHERE disciplines.name = \'' . $discipline . '\'
		
		');
		$qry->execute();

		$dIDarr = $qry->fetchAll();

		if (count($dIDarr) == 0) {
			echo('Cannot insert event: no discipline corresponding to input!');
			$miss = true;
		} else {
			$dID = $dIDarr[0][0];
		}
	}

	// get eventID
	$eID = -1;
	if ($miss == false) {
		$qry = $db->prepare('
					
		SELECT eventID
		FROM events
		WHERE events.disciplineID = \'' . $dID . '\' AND events.gameID = \'' . $gID . '\'
		
		');
		$qry->execute();

		$eIDarr = $qry->fetchAll();

		if (count($eIDarr) == 0) {
			echo('Cannot insert event: no discipline corresponding to input!');
			$miss = true;
		} else {
			$eID = $eIDarr[0][0];
		}
	}
	
	if ($miss == false) {

		$qry = $db->prepare('
					
		DELETE FROM memberships
		WHERE teamID in (
			SELECT teamID
			FROM teams
			WHERE iocCode = \'' . $countryCode . '\' AND eventID = \'' . $eID . '\')
		');
		$qry->execute();
		
		$qry = $db->prepare('
			
		DELETE FROM teams
		WHERE iocCode = \'' . $countryCode . '\' AND eventID = \'' . $eID . '\'
	
		');
		$qry->execute();
		
		return $qry->errorCode();
	}
}

function getGameID($db, $season, $year) {
	$qry = $db->prepare('
		SELECT gameID
		FROM games
		WHERE games.year = \'' . $year . '\' AND games.seasonName = \'' . $season . '\'
	');
	$qry->execute();

	$gIDarr = $qry->fetchAll();

	if (count($gIDarr) == 0) {
		return -1;
	} else {
		return $gIDarr[0][0];
	}
}

function myEscape($input) {
	$input = str_replace("'", "''", $input);
	return $input;
}

?>
<br />
<a href="index.php">back...</a>
<?php include '../includes/footer.php'; ?>