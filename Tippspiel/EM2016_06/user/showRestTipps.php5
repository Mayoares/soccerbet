<?php
// we must never forget to start the session
session_start();
$userId=$_GET["userId"];
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />";
echo "<title>Werke's Tippspiel - Meine Endrunden-Tipps</title>";
echo "</head>";

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/calc.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");
$userName=$dbutil->getUserName($userId);


if(strlen($userName)>0)
{
	printFinal($userName, 'Achtelfinale');
	printFinal($userName, 'Viertelfinale');
	printFinal($userName, 'Halbfinale');
	//printFinal($userName, 'Platz3');
	printFinal($userName, 'Finale');
	
// 	printChampions($userName);
// 	printTopscorer($userName);
}
else
{
	echo "<br> kein User gesetzt";
}

function getTeamName($shortname) {
	$table_teams=dbschema::teams;
	$sql="SELECT t.name FROM $table_teams t WHERE t.shortname='$shortname'";
	//$log=new logger();
	//$log->info($sql);
	$result=mysql_query($sql);
	$array=mysql_fetch_array($result);
	$name=$array["name"];
	return $name;
}

function printFinal($userName, $matchtype){
	
	echo "<br>";
	echo "<h2>$matchtype</h2>";
	echo "<br>";
	
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::matches;
	
	
	$sqlMatches="SELECT matchnr FROM $table_matches m WHERE matchtype = '$matchtype'";
	$log=new logger();
	$log->info($sqlMatches);
	$sqlResultMatches=mysql_query($sqlMatches);
	echo "<table>";
	while($array=mysql_fetch_array($sqlResultMatches))
	{	
		$matchnr=$array["matchnr"];
		$sql="SELECT * FROM $table_finalmatchtipps ft WHERE ft.user = '$userName' AND ft.matchnr = '$matchnr'";
		$log=new logger();
		$log->info($sql);
		$sqlResult=mysql_query($sql);
		if (!$sqlResult) {

			echo "<br> MIST DB error";
		}
		else
		{
			$array=mysql_fetch_array($sqlResult);
			$teamShort1 = $array["teamX"];
			$teamShort2 = $array["teamY"];
			$goalsX = $array["goalsX"]; if(!isset($goalsX)){$goalsX = "<font color=\"red\"><b> X </b></font>";}
			$goalsY = $array["goalsY"]; if(!isset($goalsY)){$goalsY = "<font color=\"red\"><b> X </b></font>";}
			$team1=getTeamName($teamShort1); if(!isset($team1)){$team1 = "<font color=\"red\"><b> FEHLT! </b></font>";}
			$team2=getTeamName($teamShort2); if(!isset($team2)){$team2 = "<font color=\"red\"><b> FEHLT! </b></font>";}
			
			echo "<tr> <td>Spiel $matchnr &nbsp; &nbsp; &nbsp;</td><td width=100><b> $team1 </b></td><td>-</td><td width=100><b> $team2</b></td>  	<td><b>$goalsX : $goalsY</b></td></tr>";
		}
	}
	echo "</table>";
}

function printChampions($userName){
	
	echo "<br>";
	echo "<br>";
	echo "<h2>Spezial-Tipps</h2>";
	echo "<br>";
	
	$champion=getTippedTeamRostrum($userName, 1);  if(!isset($champion)){$champion = "<font color=\"red\"><b> FEHLT! </b></font>";}
	$vice=getTippedTeamRostrum($userName, 2); if(!isset($vice)){$vice = "<font color=\"red\"><b> FEHLT! </b></font>";}
	$third=getTippedTeamRostrum($userName, 3); if(!isset($third)){$third = "<font color=\"red\"><b> FEHLT! </b></font>";}
	//echo "<table style='font-size:14px'>";
	echo "<table>";
	echo "<tr><td>Europameister &nbsp; &nbsp; &nbsp;</td><td><b>$champion</b></td></tr>";
	echo "<tr><td>Zweitplatzierter &nbsp; &nbsp; &nbsp;</td><td><b>$vice</b></td></tr>";
	//echo "<tr><td>Platz 3 &nbsp; &nbsp; &nbsp;</td><td><b>$third</b></td></tr>";
	echo "</table>";
}

function printTopscorer($username){
	
	$topscorer=getTippedTopScorer($username); if(strlen($topscorer)==0){$topscorer = "<font color=\"red\"><b> FEHLT! </b></font>";}
	$tippedTeamShort=getTippedTopScorerTeam($username);
	$topScorerTeam=getTeamName($tippedTeamShort); if(strlen($topScorerTeam)==0){$topScorerTeam = "<font color=\"red\"><b> FEHLT! </b></font>";}
	
	//echo "<table style='font-size:18px'>";
	echo "<table>";
	echo "<tr><td>Torsch&uuml;tzenk&ouml;nig &nbsp; &nbsp; &nbsp;</td><td><b>$topscorer</b></td> &nbsp; <td>($topScorerTeam)</td> </tr>";
	echo "</table>";
}

function getTippedTeamRostrum($username, $rank){
	$table_championtipps=dbschema::championtipps;
	$sqlQuery="SELECT * FROM $table_championtipps WHERE user='$username' AND rank=$rank";
	$sqlQueryResult=mysql_query($sqlQuery);
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$teamShort=$sqlResultArray["team"];
	return getTeamName($teamShort);
}

function getTippedTopScorer($username){
	$table_topscorertipps=dbschema::topscorertipps;
	$sqlQueryResult=mysql_query("SELECT * FROM $table_topscorertipps WHERE user='$username'");
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$topscorer=$sqlResultArray["topscorer"];
	return $topscorer;
}

function getTippedTopScorerTeam($username){
	
	$table_topscorertipps=dbschema::topscorertipps;
	$sqlQueryResult=mysql_query("SELECT * FROM $table_topscorertipps WHERE user='$username'");
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$teamShort=$sqlResultArray["team"];
	return $teamShort;
}
?>