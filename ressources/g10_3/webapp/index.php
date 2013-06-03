<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
</head> 
<body>
<div id="main">

<h1>Olympic stats</h1>

<form action="queries/search.php" method="get">
<input type="text" name="search_field">
<input type="submit" value="Search">
</form>

<p>&rarr; <a href="insertions/index.php">Insert/Delete data</a></p>

<h3>Special queries:</h3>
<ul>
<li><a href="queries/multiseasonMedalists.php"               >A. multi-season medalists</a></li>
<li><a href="queries/goldMedalists.php"                      >B. gold medalists of unique events</a></li>
<li><a href="queries/firstMedalPlacesByCountry.php"          >C. first medal places by country</a></li>
<li><a href="queries/countriesWithMostMedalsBySeason.php"    >D. countries with most medals by season</a></li>
<li><a href="queries/multiHostCities.php"                    >E. multi-host cities</a></li>
<li><a href="queries/multinationalAthletes.php"              >F. multinational athletes</a></li>
<li><a href="queries/countriesWithMostParticipantsByGame.php">G. countries with most participants by game</a></li>
<li><a href="queries/medallessCountries.php"                 >H. medalless countries</a></li>
<li><a href="queries/medalTableByGameInput.php"              >I. medals table for specific game</a></li>
<li><a href="queries/3mostMedalBySport.php"                  >J. three nations with most medals by sport</a></li>
<li><a href="queries/homeCrowdMostBenefit.php"               >K. nation with most home crowd benefit</a></li>
<li><a href="queries/top10TeamSport.php"                     >L. top ten nations for team sports</a></li>
<li><a href="queries/multinationalMedalist.php"              >M. multinational medalists</a></li>
<li><a href="queries/goldSilverBronzeNationStarter.php"      >N. first medal type by nation</a></li>
<li><a href="queries/longestMedalWaitByDiscipline.php"       >O. longest medal wait by discipline</a></li>
<li><a href="queries/eventsMedalIn1Country.php"              >P. events medal in one country</a></li>
<li><a href="queries/largestPercentageMedalPerGame.php"      >Q. largest percentage medal per game</a></li>
<li><a href="queries/mostSuccessfulCountryBySport.php"       >R. most sucessful country by sport</a></li>
<li><a href="queries/singleAndTeamMedalists.php"             >S. single and team medalists</a></li>
<li><a href="queries/goldInTeamNotGoldSingle.php"            >T. gold in team but not in single</a></li>
<li><a href="queries/top10FirstApparition.php"               >V. top ten first apparition</a></li>



</ul>

</div>
</body>
</html>