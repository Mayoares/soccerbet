<?php
// we must never forget to start the session
session_start();
$adminuserId=$_GET["adminuserId"];
$userName=$_POST["SelectedUsername"];
include_once("../../general/log/log.php5");
echo "<html>";
echo "<head>";
echo "<title>Tippspiel - Auswertung Finalspielteilnahmen</title>";
echo "</head>";
echo "<body>";

include_once("../util/calc.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");
//$userName=getUserName($userId);


if(strlen($userName)>0)
{
	echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
	echo "<br>";
	echo "<br><b>$userName</b><br>";
	calcScore($userName, false);
}
else
{
	include_once("../util/dbUtil.php5");
	// für jeden Standard-User (außer admin und real) ...
	$allStandardUsers=getAllStandardUsers();
	while($arrayUser=mysql_fetch_array($allStandardUsers))
	{
		$username=$arrayUser["username"];
		echo "<br>$username";
		calcScore($username, true);
//		calcScore($username, false);
		echo "<br>";
	}
}
mysql_close();
echo "<br>";
echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";

function calcScore($user, $silent)
{
	$score=0;
	$score=$score+getEvaluation($user, 'Achtelfinale', $silent);
	$score=$score+getEvaluation($user, 'Viertelfinale', $silent);
	$score=$score+getEvaluation($user, 'Halbfinale', $silent);
	$score=$score+getEvaluation($user, 'Platz3', $silent);
	$score=$score+getEvaluation($user, 'Finale', $silent);
	echo "<br>Gesamtscore für Finalspielteilnahmen-Tipps von User $user : $score";
	updateScoreInDB($user, $score);
}

function updateScoreInDB($user, $score){
	
	$table_users=dbschema::users;
	$sql = "UPDATE $table_users SET finalparticipantscore = '$score' WHERE username = '$user'";
	$log=new adminlogger();
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	if(!$sqlResult)
	{
		echo 'Datenbankfehler. MIST!<br>';
		echo mysql_error();
		echo "<br>";
		echo "<br>";
	}
	else
	{
		echo '<br>Update in DB wurde ausgeführt<br>';
		$log->info("Update in DB wurde ausgeführt");
	}	
}

  
function getEvaluation($user, $matchtype, $silent)
{
	include_once("../share/RealResults.php5");
	$realTeams=getRealTeams($matchtype);
	$score=checkTeamsIncluded($user, 'team', $realTeams, $matchtype, $silent);
	echo "<br>Score User $user für $matchtype: $score";
	return $score;
}


function userHasIncluded($user, $matchtype, $team) {
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::matches;
	$sql="SELECT m.matchnr FROM $table_finalmatchtipps f, $table_matches m WHERE user = '$user' AND m.matchtype = '$matchtype' AND m.matchnr = f.matchnr AND (f.teamX = '$team' OR f.teamY = '$team')";
//			echo "<br>$sql";
	$result=mysql_query($sql);
	$resultArray=mysql_fetch_array($result);
	$match=$resultArray["matchnr"];
	//echo "<br> Matchnr $match";
	if(empty($match))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function checkTeamsIncluded($user, $column, $realTeams, $matchtype, $silent)
{
//	echo "checkTeamsIncluded($user, $column, $realTeams, $matchtype, $silent)<br>";
		$userScore=0;
		while($teamsArray=mysql_fetch_array($realTeams))
		{
//			echo "TeamsArray:";
//			print_r($teamsArray);
//			echo "<br>";
			$team=$teamsArray["$column"];
//			echo "Team=$team<br>";
			if(userHasIncluded($user, $matchtype, $team))
			{
				$addScore=getScore($matchtype);
				$userScore = $userScore+$addScore;
				if(!$silent)
				{
					echo "<br>User hat '$team' im '$matchtype' getippt und erhält '$addScore' Punkte.";
				}
			}
			else
			{
				if(!$silent)
				{
					echo "<br>User hat '$team' NICHT im '$matchtype' getippt und erhält keine Punkte.";
				}
			}
		}
		return $userScore;
}

function getScore($matchtype)
{
	include_once("../share/ScoreDefinitions.php5");
	
	if($matchtype=='Achtelfinale')	
	{
		return ScoreDefinitions::getEighthFinalParticipant();
	}
	else if($matchtype=='Viertelfinale')	
	{
		return ScoreDefinitions::getQuarterFinalParticipant();
	}
	else if($matchtype=='Halbfinale')	
	{
		return ScoreDefinitions::getSemiFinalParticipant();
	}
	else if($matchtype=='Platz3')	
	{
		return ScoreDefinitions::getLittleFinalParticipant();
	}
	else if($matchtype=='Finale')	
	{
		return ScoreDefinitions::getFinalParticipant();
	}
}

?>
