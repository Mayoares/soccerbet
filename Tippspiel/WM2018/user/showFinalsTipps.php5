<?php
session_start();
$userId=$_GET["userId"];
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
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
	$log=new logger();
	$log->info("User=".$userName." kontrolliert seine Endrundentipps.");
	
	printFinal($userName, 'Achtelfinale');
	printFinal($userName, 'Viertelfinale');
	printFinal($userName, 'Halbfinale');
	printFinal($userName, 'Platz3');
	printFinal($userName, 'Finale');
}
else
{
	echo "<br> kein User gesetzt";
}

function getTeamName($shortname) {
	$table_teams=dbschema::teams;
	$sql="SELECT t.name FROM $table_teams t WHERE t.shortname='$shortname'";
	$result=mysql_query($sql);
	$array=mysql_fetch_array($result);
	$name=$array["name"];
	return $name;
}

function printFinal($userName, $matchtype){
	
	echo "<br>";
	echo "<h2>$matchtype</h2>";
	
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::matches;
	
	$sqlMatches="SELECT matchnr FROM $table_matches m WHERE matchtype = '$matchtype'";
	$sqlResultMatches=mysql_query($sqlMatches);
	echo "<table>";
	while($array=mysql_fetch_array($sqlResultMatches))
	{	
		$matchnr=$array["matchnr"];
		$sql="SELECT * FROM $table_finalmatchtipps ft WHERE ft.user = '$userName' AND ft.matchnr = '$matchnr'";
		$log=new logger();
		$sqlResult=mysql_query($sql);
		if (!$sqlResult) {

			$log=new logger();
			$log->error("Fehler bei Anzeige der Tabelle der Endrundentipps. Vorangegangene Abfragen:");
			$log->info($sql);
			$log->info($sqlMatches);
			$log->error("Fehler Ende");
			echo "<br> MIST DB error";
		}
		else
		{
			$array=mysql_fetch_array($sqlResult);
			$teamShort1 = $array["teamX"];
			$teamShort2 = $array["teamY"];
			$goalsX = $array["goalsX"]; if(!isset($goalsX)){$goalsX = "<font color=\"#C81B00\"><b> X </b></font>";}
			$goalsY = $array["goalsY"]; if(!isset($goalsY)){$goalsY = "<font color=\"#C81B00\"><b> X </b></font>";}
			$team1=getTeamName($teamShort1); if(!isset($team1)){$team1 = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
			$team2=getTeamName($teamShort2); if(!isset($team2)){$team2 = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
			
			echo "<tr> <td>Spiel $matchnr &nbsp; &nbsp; &nbsp;</td><td width=100><b> $team1 </b></td><td>-</td><td width=100><b> $team2</b></td>  	<td><b>$goalsX : $goalsY</b></td></tr>";
		}
	}
	echo "</table>";
}
?>