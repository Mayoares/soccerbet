<?php
// we must never forget to start the session
session_start();
$adminuserId=$_GET["adminuserId"];
$userName=$_POST["SelectedUsername"];
include_once("../../general/log/log.php5");
echo "<html>";
echo "<head>";
echo "<title>Werke's Tippspiel - Auswertung Finalspielteilnahmen</title>";
echo "</head>";
echo "<body>";
//echo "Alle Tipps der Finalspielteilnahmen von einem User auswerten ...<br>";

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess-local.php5");
include_once("../util/calc.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");
//$userName=$dbutil->getUserName($userId);


if(strlen($userName)>0)
{
	echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
	echo "<br>";
	echo "<br><b>$userName</b><br>";
	calcScore($userName, false);
}
else
{
	$allUsers=getAllUsers();
	while($array=mysql_fetch_array($allUsers))
	{
		$username=$array["username"];
		echo "<br>$username";
		calcScore($username, true);
		echo "<br>";
	}
}
mysql_close();
echo "<br>";
echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";

function getAllUsers()
{
	$table_users=dbschema::users;
	$result = mysql_query("SELECT username FROM $table_users");
	return $result;
}

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
	$team1='teamX';
	$realTeams1=getRealTeams($team1,$matchtype);
	$score1=checkTeamsIncluded($user, $team1, $realTeams1, $matchtype, $silent);
	$team2='teamY';
	$realTeams2=getRealTeams($team2, $matchtype);
	$score2=checkTeamsIncluded($user, $team2, $realTeams2, $matchtype, $silent);
	$score=$score1+$score2;
	echo "<br>Score User $user für $matchtype: $score";
	return $score;
}
function getRealTeams($column, $matchtype) {
	$table_matches=dbschema::matches;
	$table_finalmatchtipps=dbschema::finalmatchtipps;
    //$sql="SELECT $column FROM $table_matches m WHERE m.matchtype = '$matchtype'";
    $sql="SELECT f.$column FROM $table_matches m, $table_finalmatchtipps f " .
	"WHERE m.matchtype = '$matchtype' " .
	"AND f.matchnr = m.matchnr AND f.user = 'real'";
    //echo "<br>SQL=$sql";
    $result=mysql_query($sql);
    return $result;
}

function userHasIncluded($user, $matchtype, $team) {
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::matches;
	$sql="SELECT m.matchnr FROM $table_finalmatchtipps f, $table_matches m WHERE user = '$user' AND m.matchtype = '$matchtype' AND m.matchnr = f.matchnr AND (f.teamX = '$team' OR f.teamY = '$team')";
	//		echo "<br>$sql";
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
		$userScore=0;
		while($teamsArray=mysql_fetch_array($realTeams))
		{
			$team=$teamsArray["$column"];
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
		if($matchtype=='Achtelfinale')	
		{
			return 2;
		}
		else if($matchtype=='Viertelfinale')	
		{
			return 3;
		}
		else if($matchtype=='Halbfinale')	
		{
			return 4;
		}
		else if($matchtype=='Platz3')	
		{
			return 5;
		}
		else if($matchtype=='Finale')	
		{
			return 5;
		}
}

?>
