<?php

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess-local.php5");
include_once("../util/dbschema.php5");
include_once("../../general/log/log.php5");

$adminuserId=$_GET["adminuserId"];

if(strlen($adminuserId)==0)
{
	$log=new adminlogger();
	$log->warn("AdminuserId was empty");
	header("Location: ../util/login.php5");
	exit;
}
else
{
	include_once("../util/dbutil.php5");
	if(!isAdmin($adminuserId, $dbutil))
	{
		header("Location: ../util/login.php5");
		exit;
	}
	else if(isset($_POST["evaluationGroupMatch"]))
	{
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
		echo "<br>";
		echo "<br>";
		$SelectedMatchnr=$_POST['SelectedMatchnr'];
		//echo "<br>adminid=$adminuserId, SelectedMatchnr=$SelectedMatchnr<br>";
		evaluateGroupMatch($SelectedMatchnr, $dbutil);
		echo "<br>";
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
	}
	else
	{
		header("Location: ../util/login.php5");
		exit;
	}
}

function isAdmin($adminuserId, $dbutil){
	
	$adminuserName=$dbutil->getUserName($adminuserId);
	if($adminuserName==="admin")
	{
		return true;
	}
	else
	{
		$log=new adminlogger();
		$log->warn("Wrong user (" . $adminuserName . ") tried to add user.");
		return false;
	}
}

function evaluateGroupMatch($matchnr, $dbutil){
	
	echo "<html>";
	echo "<head>";
	echo "<title>Auswertung </title>";
	echo "</head>";
	echo "<body>";

	echo "Spiel <$matchnr> für alle User auswerten ...<br>";
	$arrayReal=getRealResult($matchnr);
	$team1Short = getTeamShort($matchnr, "team1");
	$team1=$dbutil->getTeamName($team1Short);
	$team2Short = getTeamShort($matchnr, "team2");
	$team2=$dbutil->getTeamName($team2Short);
	$GoalsTeam1Real=$arrayReal["goalsX"];
	$GoalsTeam2Real=$arrayReal["goalsY"];
	$WinnerReal=$arrayReal["winner"];
	$GoalDiffReal=$arrayReal["goaldiff"];
	
	echo "Ergebnis: <b>$team1 - $team2  $GoalsTeam1Real : $GoalsTeam2Real</b><br><br>";
	
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	$sqluser="SELECT t.user, t.matchnr, t.goalsX, t.goalsY, t.winner, t.goaldiff, t.score FROM $table_groupmatchtipps t WHERE t.matchnr=$matchnr AND NOT t.user='real' ORDER BY (t.user)";
	$ergebnisUser=mysql_query($sqluser);
	echo mysql_error();

	echo "<table>";
	echo "<th>User</th><th> Spiel </th><th> Tipp</th><th> korrekt </th><th> Punkte</th>";
	while($arrayUser=mysql_fetch_array($ergebnisUser)){

		$matchnr=$arrayUser["matchnr"];
		$User=$arrayUser["user"];
		$GoalsTeam1User=$arrayUser["goalsX"];
		$GoalsTeam2User=$arrayUser["goalsY"];
		$WinnerUser=$arrayUser["winner"];
		$GoalDiffUser=$arrayUser["goaldiff"];

		$Score=-1;
		$Score=getEvaluationGroup($GoalsTeam1User, $GoalsTeam2User, $WinnerUser, $GoalsTeam1Real, $GoalsTeam2Real, $WinnerReal);
		echo "<tr>";
		echo "<td>$User</td><td>$matchnr </td><td><b>$GoalsTeam1User : $GoalsTeam2User</b></td><td><font color=\"#32cd32\"><b>$GoalsTeam1Real : $GoalsTeam2Real</b></td><td>$Score</td>";
		echo "</tr>";
		$sqlUpdate="UPDATE $table_groupmatchtipps SET score='$Score' WHERE user='$User' AND matchnr='$matchnr'";
		$log=new adminlogger();
		$log->info($sqlUpdate);
		$sqlUpdateResult=mysql_query($sqlUpdate);
		if(!$sqlUpdateResult)
		{
			$log->error('Datenbankfehler. MIST!:' . mysql_error());
		}
		else
		{
			$log->info('Update OK.');
			$table_matches=dbschema::matches;
			//Eintragen welche Spiele bereits ausgewertet wurden (fuer Punktestand-Anzeige)
			$sql = "UPDATE $table_matches SET evaluationDone='T' WHERE matchnr='$matchnr'";
			$log->info($sql);
			$sqlUpdateResult=mysql_query($sql);
			if(!$sqlUpdateResult)
			{
				echo "evaluationDone='T' konnte nicht gesetzt werden für matchnr='$matchnr'";
			}
			
		}
	}
	echo "</table>";
}

function getRealResult($matchnr) {
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	$table_matches=dbschema::groupmatchtipps;
	$sqlreal="SELECT t.user, t.matchnr, t.goalsX, t.goalsY, t.winner, t.goaldiff, t.score FROM `$table_groupmatchtipps` t , $table_matches m WHERE m.matchnr=t.matchnr AND t.user='real' AND t.matchnr='$matchnr'";
	$ergebnisReal=mysql_query($sqlreal);
	$arrayReal=mysql_fetch_array($ergebnisReal);
	return $arrayReal;
}

function getTeamShort($matchnr, $teamColumn){
	$table_matches=dbschema::matches;
	$sql="SELECT t.$teamColumn FROM $table_matches t WHERE t.matchnr='$matchnr'";
	$sqlResult=mysql_query($sql);
	$arrayResult=mysql_fetch_array($sqlResult);
	return $arrayResult["$teamColumn"];
}

function getEvaluationGroup($GoalsTeam1User, $GoalsTeam2User, $WinnerUser, $GoalsTeam1Real, $GoalsTeam2Real, $WinnerReal)
{
	$Score=0;
	if($GoalsTeam1User==$GoalsTeam1Real && $GoalsTeam2User==$GoalsTeam2Real)
	{
		$Score=4;
	}
	else if($WinnerUser==$WinnerReal)
	{
		$Score=2;
	}
	return $Score;
}

//Datenbankconnection schliessen
mysql_close();
?>
