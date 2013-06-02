<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles.css">
<style type="text/css">
p{margin:0;
}
</style>
</head> 
<body>
<div id="main">

<?php
include 'config.php';
$origTxt = str_replace("'", "''", $_GET['search_field']);

$txt = '%' . $origTxt . '%';

$pattern = '/[ \n\t]/';
$terms = preg_split($pattern, $origTxt);
$termsCount = count($terms);
?>

<h1>Results for "<?php echo(str_replace('<', '{', $origTxt))?>"</h1>

<?php
try
{
	/////////////////////
	// QUERY EXECUTION //
	/////////////////////
	
	$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME, $DATABASE_LOGIN, $DATABASE_PASSWORD);
	
	$paramsList = array();
	$qry = $db->prepare('
	
SELECT id, type, text, matches FROM
(
	
	/* A T H L E T E S */
	
	SELECT aID AS id, \'a\' AS type, CONCAT(aname, \' (\' ,code , \')\') AS text, COUNT(*) AS matches FROM
	(
		SELECT aID, aname, code
		FROM aview
		WHERE ' . buildConds('aname', $terms, $paramsList) . '
			
		UNION ALL
		
		SELECT aID, aname, code
		FROM aview
		WHERE ' . buildConds('coname', $terms, $paramsList) . '
	) am
	GROUP BY aID
	
	UNION ALL
	
	
	/* C I T I E S */
		
	SELECT ciID as id, \'ci\' AS type, CONCAT(ciname, \' (\' ,code , \')\') AS text, COUNT(*) AS matches FROM
	(
		SELECT ciID, ciname, code
		FROM ciview
		WHERE ' . buildConds('ciname', $terms, $paramsList) . '
		
		UNION ALL
		
		SELECT ciID, ciname, code
		FROM ciview
		WHERE ' . buildConds('coname', $terms, $paramsList) . '
	) cim
	GROUP BY ciID
	
	UNION ALL


	/* C O U N T R I E S */
	
	SELECT code AS id, \'co\' AS type, CONCAT(coname, \' (\' ,code , \')\') AS text, COUNT(*) AS matches FROM
	(
		SELECT co.name AS coname, co.iocCode AS code
		FROM countries co
		WHERE ' . buildConds('co.name', $terms, $paramsList) . '
		
		UNION ALL
		
		SELECT co.name AS coname, co.iocCode AS code
		FROM countries co
		WHERE ' . buildConds('co.iocCode', $terms, $paramsList) . '
	) com
	GROUP BY code	
	
	UNION ALL

	
	/* D I S C I P L I N E S */

	SELECT dID AS id, \'d\' AS type, CONCAT(sname, \' \', dname) AS text, COUNT(*) AS matches FROM
	(
		SELECT dID, dname, sname
		FROM dview
		WHERE ' . buildConds('dname', $terms, $paramsList) . '
		
		UNION ALL
		
		SELECT dID, dname, sname
		FROM dview
		WHERE ' . buildConds('sname', $terms, $paramsList) . '
	) dm
	GROUP BY dID
	
	UNION ALL
	
	
	/* S P O R T S */

	SELECT sID AS id, \'s\' AS type, sname AS text, COUNT(*) AS matches FROM
	(
		SELECT s.sportID AS sID, s.name AS sname
		FROM sports s
		WHERE ' . buildConds('s.name', $terms, $paramsList) . '
	) sm
	GROUP BY sID
	
	UNION ALL
	
	
	/* E V E N T S */
	
	SELECT eID AS id, \'e\' AS type, CONCAT(sname, \' \', dname, \' \', ename, \' \', year) AS text, COUNT(*) AS matches FROM
	(
		SELECT eID, ename, sname, dname, ciname, year
		FROM eview
		WHERE ' . buildConds('ename', $terms, $paramsList) . '
		
		UNION ALL
		
		SELECT eID, ename, sname, dname, ciname, year
		FROM eview
		WHERE ' . buildConds('sname', $terms, $paramsList) . '		
		
		UNION ALL
		
		SELECT eID, ename, sname, dname, ciname, year
		FROM eview
		WHERE ' . buildConds('dname', $terms, $paramsList) . '
		
		UNION ALL
		
		SELECT eID, ename, sname, dname, ciname, year
		FROM eview
		WHERE ' . buildConds('year', $terms, $paramsList) . ' OR ' . buildConds('ciname', $terms, $paramsList) . '
	) em
	GROUP BY eID
	
	UNION ALL
	
	
	/* G A M E S */
	SELECT gID as id, \'g\' AS type, CONCAT(ciname, \' \', year, \' Games\') AS text, COUNT(*) AS matches FROM
	(
		SELECT gID, ciname, year
		FROM gview
		WHERE ' . buildConds('ciname', $terms, $paramsList) . '
		
		UNION ALL
		
		SELECT gID, ciname, year
		FROM gview
		WHERE ' . buildConds('year', $terms, $paramsList) . '
	) gm
	GROUP BY gID
	
) found
ORDER BY matches DESC
LIMIT 100;

	');
	
	bindParams($qry, $paramsList);
	$qry->execute();

	
	/////////////////////
	// DISPLAY RESULTS //
	/////////////////////
	
	while($data = $qry->fetch())
	{
		$percent = intval(min(100, $data['matches'] / $termsCount * 100));
		$sizeInfo = 'style="font-size:' . 3 * sqrt($percent) . 'px"';
		$url = 'href="details-' . $data['type'] . '.php?'.http_build_query(array('id' => $data['id'])) . '"';
		echo("<p" . $sizeInfo . "><a " . $url . ">" . $data['text'] . "</a> - <i>" . $percent . "%</i></p>");
	}


// TODO special medal search ?

	// Close database
	$db = null;

}
catch (Exception $e)
{
	die('Could not open database, error: ' . $e->getMessage());
}

/*
 * This function builds alternative conditions in case of a multiple term search.
 */
function buildConds($field, $terms, &$paramsList) {
	
	$conds = array();
	foreach ($terms as $term) {
		array_push($conds, ' LCASE('. $field . ') LIKE LCASE(?)');
		array_push($paramsList, '%' . $term . '%');
	}
		
	return implode(" OR ", $conds);
}

/*
 * This function binds the gathered values to a request.
 */
function bindParams($req, $paramsList) {
	$i = 1;
	foreach ($paramsList as $param) {
		$req->bindValue($i, $param, PDO::PARAM_STR);
		$i++;
	}
}
?>
</div>
</body>
</html> 