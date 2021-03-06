<?php
session_start();
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
echo "<title>Werke's Tippspiel - Meine Spezial-Tipps</title>";
echo "</head>";

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/calc.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");
$userId=$_GET["userId"];
$userName=$dbutil->getUserName($userId);


if(strlen($userName)>0)
{
	$log=new logger();
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "User=".$userName." kontrolliert seine Spezialtipps.");
	
	printChampions($userName);
	printTopscorer($userName);
}
else
{
	echo "<br> kein User gesetzt";
}

function printFinal($userName, $matchtype){
	
	echo "<br>";
	echo "<h2>$matchtype</h2>";
	echo "<br>";
	
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::matches;
	
	$sqlMatches="SELECT matchnr FROM $table_matches m WHERE matchtype = '$matchtype'";
	$sqlResultMatches=mysql_query($sqlMatches);
	echo "<table>";
	while($array=mysql_fetch_array($sqlResultMatches))
	{	
		$matchnr=$array["matchnr"];
		$sql="SELECT * FROM $table_finalmatchtipps ft WHERE ft.user = '$userName' AND ft.matchnr = '$matchnr'";
		$sqlResult=mysql_query($sql);
		if (!$sqlResult) {

			$log->error("Fehler bei Anzeige der Tabelle der Endrundentipps. Vorangegangene Abfragen:");
			$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sql);
			$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sqlMatches);
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
			$team1=$dbutil->getTeamName($teamShort1); if(!isset($team1)){$team1 = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
			$team2=$dbutil->getTeamName($teamShort2); if(!isset($team2)){$team2 = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
			
			echo "<tr> <td>Spiel $matchnr &nbsp; &nbsp; &nbsp;</td><td width=100><b> $team1 </b></td><td>-</td><td width=100><b> $team2</b></td>  	<td><b>$goalsX : $goalsY</b></td></tr>";
		}
	}
	echo "</table>";
}

function printChampions($userName){
	
	echo "<br>";
	echo "<h2>Spezial-Tipps</h2>";
	echo "<br>";
	
	$dbutil=new dbutil();
	$champion=$dbutil->getRostrumPrediction($userName, 1);  if(!isset($champion)){$champion = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
	$vice=$dbutil->getRostrumPrediction($userName, 2); if(!isset($vice)){$vice = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
	$third=$dbutil->getRostrumPrediction($userName, 3); if(!isset($third)){$third = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
	//echo "<table style='font-size:14px'>";
	echo "<table>";
	echo "<tr><td>Weltmeister &nbsp; &nbsp; &nbsp;</td><td><b>$champion</b></td></tr>";
	echo "<tr><td>Zweitplatzierter &nbsp; &nbsp; &nbsp;</td><td><b>$vice</b></td></tr>";
	echo "<tr><td>Platz 3 &nbsp; &nbsp; &nbsp;</td><td><b>$third</b></td></tr>";
	echo "</table>";
}

function printTopscorer($username){
	
	$dbutil=new dbutil();
	$topscorer=$dbutil->getTopScorerPrediction($username); if(strlen($topscorer)==0){$topscorer = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
	$topScorerTeam=$dbutil->getTopScorerTeamPrediction($username);
	if(strlen($topScorerTeam)==0){$topScorerTeam = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
	
	//echo "<table style='font-size:18px'>";
	echo "<table>";
	echo "<tr><td>Torsch&uuml;tzenk&ouml;nig &nbsp; &nbsp; &nbsp;</td><td><b>$topscorer</b></td> &nbsp; <td>($topScorerTeam)</td> </tr>";
	echo "</table>";
}


mysql_close();
$log=new logger();
$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Verbindung zur MySQL-DB geschlossen.");
?>