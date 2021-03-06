<?php
// we must never forget to start the session
session_start();
$userId=$_GET["userId"];
$groups=$_GET["groups"];
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
echo "<title>Werke's Tippspiel - Meine Gruppen-Tipps</title>";
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
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "User=".$userName." kontrolliert seine Gruppentipps (".$groups.").");
	
	if($groups === 'Part1'){
		printGroup('A', $userName);
		printGroup('B', $userName);
		printGroup('C', $userName);
		printGroup('D', $userName);
		//printGroup('E', $userName);
		//printGroup('F', $userName);
	}
	else if($groups === 'Part2'){
		//printGroup('D', $userName);
		printGroup('E', $userName);
		printGroup('F', $userName);
		printGroup('G', $userName);
		printGroup('H', $userName);
	}
}
else
{	
	echo "<br> kein User gesetzt";
}

function printGroup($group, $userName){
	echo "<h1 style=\"margin-top: 0px;margin-bottom: 0px;\">Gruppe $group</h1>";
	echo "<table CELLSPACING=20>";
	echo "<td>";
	printGroupRanks($group, $userName);
	echo "</td>";
	echo "<td>";
	printGroupMatches($group, $userName);
	echo "</td>";
	echo "</table>";
}

function printGroupRanks($group, $userName){
	
	include_once("../util/dbutil.php5");
	
	$table_teams=dbschema::teams;
	$table_groupranktipps=dbschema::groupranktipps;
	echo "<table>";
	echo "<thead>";
	echo "<tr>";
	echo "<th></th><th><u>Platzierungen:</u></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	
	for($rank=1; $rank<=4; $rank++)
	{
		$sql="SELECT t.name, t.group FROM $table_groupranktipps r, $table_teams t " .
		"WHERE r.team = t.shortname AND r.user = '$userName' AND t.group='$group' AND r.rank='$rank'";
		$sqlResult=mysql_query($sql);
		$array=mysql_fetch_array($sqlResult);
		$teamName=$array["name"]; if(!isset($teamName)){$teamName = "<font color=\"#C81B00\"><b> FEHLT! </b></font>";}
		if(mysql_error()!=0)
		{
			$log->error($sql);
		}
		echo "<tr>";
		echo "<td> $rank. </td><td><b> $teamName </b></td><td> </td>";
		echo "</tr>";
	}
	// zusaetzliche Zeilen, damit Ranktipps genauso gross in der Tabelle erscheinen wie die Matchtipps
	for($r=0; $r<12; $r++)
	{
		echo "<tr>";
		echo "<td></td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
}

function printGroupMatches($group, $userName){
	
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.group='$group'";
	
	$sqlResult=mysql_query($sql);
	//	echo "<u>Spiele:</u>";
	echo "<table>";
	echo "<tr>";
	echo "<th><u>Spiele:</u></th>";
	echo "</tr>";
	while($array=mysql_fetch_array($sqlResult)){
		
		
		$date=$array["matchdate"]; 
		$time=$array["matchtime"]; 
		$matchnr=$array["matchnr"];
		
		$dbutil = new dbutil();
		$teamName1=$dbutil->getTeamName($array["team1"]);
		$teamName2=$dbutil->getTeamName($array["team2"]);
		
		$tippGoalsTeam1=$dbutil->getGoals1($userName, $matchnr); if(!isset($tippGoalsTeam1)){$tippGoalsTeam1 = "<font color=\"#C81B00\"><b> X </b></font>";}
		$tippGoalsTeam2=$dbutil->getGoals2($userName, $matchnr); if(!isset($tippGoalsTeam2)){$tippGoalsTeam2 = "<font color=\"#C81B00\"><b> X </b></font>";}
		echo "<tr>";
		echo "<td>Spiel $matchnr &nbsp;&nbsp;</td> " .
				"<td width=100>  $teamName1 </td> <td>-</td> <td width=100> $teamName2&nbsp;&nbsp;&nbsp;</td>" .
				"<td> <b>$tippGoalsTeam1</b></td><td> : </td><td> <b>$tippGoalsTeam2</b></td>";
		echo "</tr>";
	}
	echo "</table>";
}

mysql_close();
$log=new logger();
$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Verbindung zur MySQL-DB geschlossen.");
?>