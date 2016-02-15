<?php

// Verbindung zur Datenbank aufbauen
include_once("../util/dbschema.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbutil.php5");

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
	if(!isAdmin($adminuserId))
	{
		header("Location: ../util/loginSpecial.php5");
		exit;
	}
	else if(isset($_POST["evaluationFinalMatch"]))
	{
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
		echo "<br>";
		echo "<br>";
		$SelectedMatchnr=$_POST['SelectedMatchnr'];
		//echo "<br>adminid=$adminuserId, SelectedMatchnr=$SelectedMatchnr<br>";
		evaluateFinalMatch($SelectedMatchnr);
		setMatchEvaluated($SelectedMatchnr);
		echo "<br>";
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
	}
	else
	{
		header("Location: ../util/login.php5");
		exit;
	}
}

function setMatchEvaluated($matchnr){
	include_once("../util/dbschema.php5");
	$table_matches=dbschema::matches;
	$sql = "UPDATE $table_matches SET evaluationDone='T' WHERE matchnr='$matchnr'";
	$log=new adminlogger();
	$log->info($sql);
	$sqlUpdateResult=mysql_query($sql);
	if(!$sqlUpdateResult)
	{
		echo "evaluationDone='T' konnte nicht gesetzt werden für matchnr='$matchnr'<br>";
		echo mysql_error();
	}
	else
	{
		echo "<br> evaluationDone='T' für matchnr='$matchnr' OK <br>";
	}
}

function evaluateFinalMatch($matchnr){
	
	echo "<html>";
	echo "<head>";
	echo "<title>Auswertung </title>";
	echo "</head>";
	echo "<body>";

	$matchtype = getMatchType($matchnr);
	echo "Spiel <$matchnr> ($matchtype) für alle User auswerten ...<br>";
	echo "<br>";
	
	$arrayReal=getRealResult($matchnr);
	$team1RealShort = $arrayReal["teamX"];
	$team1Real=getTeamName($team1RealShort);
	$team2RealShort = $arrayReal["teamY"];
	$team2Real=getTeamName($team2RealShort);
	$GoalsTeam1Real=$arrayReal["goalsX"];
	$GoalsTeam2Real=$arrayReal["goalsY"];
	$WinnerReal=$arrayReal["winner"];
	$GoalDiffReal=$arrayReal["goaldiff"];
	echo "Ergebnis: <b>$team1Real - $team2Real  $GoalsTeam1Real : $GoalsTeam2Real</b><br>";
	echo "<br>";
	
	// für jeden Standard-User (außer admin und real) ...
	include_once("../util/dbUtil.php5");
	$allStandardUsers=getAllStandardUsers();
	while($arrayUser=mysql_fetch_array($allStandardUsers)){
		
		$user=$arrayUser["username"];
		// echo "<br>User: $user<br>";
		$userHasFinalMatchPair=false;
		$score = 0;
		// ... hole seine Finalspieltipps ($matchtype)
		$table_finalmatchtipps=dbschema::finalmatchtipps;
		$table_matches=dbschema::matches;
		$sql="SELECT * FROM $table_finalmatchtipps t, $table_matches m WHERE t.matchnr=$matchnr AND m.matchnr=$matchnr AND m.matchtype='$matchtype' AND t.user='$user'";
		$sqlResultUserTipps=mysql_query($sql);
		echo mysql_error();
		while($arrayUserTipps=mysql_fetch_array($sqlResultUserTipps)){
			
			$team1UserShort = $arrayUserTipps["teamX"];
			$team1User=getTeamName($team1UserShort);
			$team2UserShort = $arrayUserTipps["teamY"];
			$team2User=getTeamName($team2UserShort);
			$userMatchnr=$arrayUserTipps["matchnr"];
			$User=$arrayUserTipps["user"];
			$GoalsTeam1User=$arrayUserTipps["goalsX"];
			$GoalsTeam2User=$arrayUserTipps["goalsY"];
			$WinnerUser=$arrayUserTipps["winner"];
			$GoalDiffUser=$arrayUserTipps["goaldiff"];
			
			// ... und überprüfe, ob diese Paarung dabei ist
			if($team1UserShort==$team1RealShort && $team2UserShort==$team2RealShort)
			{
				$userHasFinalMatchPair=true;
				echo "<b>$user</b> : $team1User - $team2User  $GoalsTeam1User : $GoalsTeam2User";
				echo "&nbsp; Paarung richtig";
				if($GoalsTeam1User==$GoalsTeam1Real && $GoalsTeam2User==$GoalsTeam2Real)
				{
					echo "&nbsp; Ergebnis richtig";
					$score = calcScore($user, $matchtype, true, true);
					break;
				}
				else if($WinnerUser==$WinnerReal)
				{
					echo "&nbsp; Siegertipp richtig";
					$score = calcScore($user, $matchtype, true, false);
					break;
				}
			}
			else if($team1UserShort==$team2RealShort && $team2UserShort==$team1RealShort)
			{
				$userHasFinalMatchPair=true;
				echo "<b>$user</b> : $team1User - $team2User  $GoalsTeam1User : $GoalsTeam2User";
				echo "&nbsp; Paarung richtig, aber umgedreht";
				if($GoalsTeam1User==$GoalsTeam2Real && $GoalsTeam2User==$GoalsTeam1Real)
				{
					echo "&nbsp; Ergebnis richtig";
					$score = calcScore($user, $matchtype, true, true);
					break;
				}
				else if(($WinnerUser==0 && $WinnerReal==0) ||
				($WinnerUser==1 && $WinnerReal==2) ||
				($WinnerUser==2 && $WinnerReal==1))
				{
					echo "&nbsp; Siegertipp richtig";
					$score = calcScore($user, $matchtype, true, false);
					break;
				}
			}
			//echo "<tr>";
			//echo "<td>$User</td><td>$matchnr </td><td><b>$GoalsTeam1User : $GoalsTeam2User</b></td><td><font color=\"#32cd32\"><b>$GoalsTeam1Real : $GoalsTeam2Real</b></font</td><td>$Score</td>";
			//echo "</tr>";
		} // end while2
		if(!$userHasFinalMatchPair)
		{
			echo "<b>$user</b> hat diese Paarung nicht getippt.";
			$score = 0;
		}
		echo " ==> <font color=\"red\"><b> $score </b></font> Punkte.";
		updateScoreInDB($user, $userMatchnr, $score);
		echo "<br>";
	} // end while1
	
	//echo "<table>";
	//echo "<th>User</th><th> Spiel </th><th> korrekt </th><th> Tipp</th><th> Punkte</th>";
	//echo "</table>";
}

function getRealResult($matchnr) {
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::finalmatchtipps;
	$sqlreal="SELECT * FROM `$table_finalmatchtipps` t WHERE t.user='real' AND t.matchnr=$matchnr";
	$ergebnisReal=mysql_query($sqlreal);
	$arrayReal=mysql_fetch_array($ergebnisReal);
	return $arrayReal;
}

function getMatchType($matchnr)
{
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches WHERE matchnr='$matchnr'";
	$sqlResult=mysql_query($sql);
	$arrayMatchInfo=mysql_fetch_array($sqlResult);
	$matchtype = $arrayMatchInfo["matchtype"];
	return $matchtype;
}

function calcScore($user, $matchtype, $winnerCorrect, $resultCorrect)
{
	include_once("../share/ScoreDefinitions.php5");
	$score=0;
	if ($matchtype=='Achtelfinale')
	{
		if($resultCorrect)
		{
			return ScoreDefinitions::getEighthFinalCorrectResult();
		}
		else if($winnerCorrect)
		{
			return ScoreDefinitions::getEighthFinalCorrectWinner();
		}
	}
	if ($matchtype=='Viertelfinale')
	{
		if($resultCorrect)
		{
			return ScoreDefinitions::getQuarterFinalCorrectResult();;
		}
		else if($winnerCorrect)
		{
			return ScoreDefinitions::getQuarterFinalCorrectWinner();;
		}
	}
	if ($matchtype=='Halbfinale')
	{
		if($resultCorrect)
		{
			return ScoreDefinitions::getSemiFinalCorrectResult();;
		}
		else if($winnerCorrect)
		{
			return ScoreDefinitions::getSemiFinalCorrectWinner();;
		}
	}
	if ($matchtype=='Platz3')
	{
		if($resultCorrect)
		{
			return ScoreDefinitions::getLittleFinalCorrectResult();;
		}
	}
	if ($matchtype=='Finale')
	{
		if($resultCorrect)
		{
			return ScoreDefinitions::getFinalCorrectResult();;
		}
	}
}

function updateScoreInDB($user, $userMatchnr, $score){
	
	//$table_users=dbschema::users;
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$sql = "UPDATE $table_finalmatchtipps SET score = '$score' WHERE user = '$user' AND matchnr=$userMatchnr";
	$log=new adminlogger();
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	if(!$sqlResult)
	{
		echo "<br>";
		echo 'Datenbankfehler. MIST!';
		echo "<br>";
		echo $sql;
		echo "<br>";
		echo mysql_error();
		echo "<br>";
		echo "<br>";
	}
	else
	{
		echo "<br>Update in DB wurde ausgeführt (Spiel $userMatchnr)<br>";
		$log->info("Update in DB wurde ausgeführt.");
	}	
}
?>
