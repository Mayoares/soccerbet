<?php
// we must never forget to start the session
session_start();
$userName=$_POST["SelectedUsername"];
if(strlen($userName)==0)
{
	$userName=$_GET["userId"];
}
?>

<html>

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<!-- Facivon 
<link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon"> -->

<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />

<title>Werke's Tippspiel - Alle Tipps</title>

</head>

<body>

<center>

<img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">

<div class="block">
	<p><a href="../util/loginPunktestand.php5"> <h2>zur&uuml;ck</h2> </a> </p>
</div>

</center>

<?php
include_once("../../connection/dbaccess.php5");
include_once("../../general/log/log.php5");
include_once("../util/calc.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");

if(strlen($userName)>0)
{
	echo "<br>";
	
	$log=new viewlogger();	
	$log->info("clicked showAllTippsAndScore.php5 for '$userName'");
	
	printUserInfo($userName);
	printTotalScore($userName);
	echo "<br>";
	printChampions($userName);
	printTopscorer($userName);
	
	printGroup('A', $userName);
	printGroup('B', $userName);
	printGroup('C', $userName);
	printGroup('D', $userName);
	printGroup('E', $userName);
	printGroup('F', $userName);
// 	printGroup('G', $userName);
// 	printGroup('H', $userName);
	
	printFinal($userName, 'Achtelfinale');
	printFinal($userName, 'Viertelfinale');
	printFinal($userName, 'Halbfinale');
// 	printFinal($userName, 'Platz3');
	printFinal($userName, 'Finale');
	
}	
else
{
	echo "<br> kein User gesetzt";
}

function printUserInfo($userName){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$userName'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	echo "<h2>Alle Tipps von $firstname $lastname</h2>";
}
function printTotalScore($userName){
	
	include_once("../util/UserScores.php5");
	$userScores = new UserScores();
	$totalscore=$userScores->getScoreSumUser($userName);
	echo "<h3> Gesamt: <font color=\"#C81B00\">$totalscore</font> Punkte</h3>";
}

function printGroup($group, $userName){
	echo "<h3>Gruppe $group</h3>";
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
	$sqluser="SELECT t.name, t.shortname, r.rank as ranktipp, t.group, r.score FROM $table_groupranktipps r, $table_teams t " .
		"WHERE r.team = t.shortname AND r.user = '$userName' AND t.group='$group'" .
		"ORDER BY ranktipp";
	
	echo "<table>";
	echo "<thead>";
	echo "<tr>";
	echo "<th colspan='4' align='left'><u>Platzierungen:</u></th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	$resultUser=mysql_query($sqluser);
	while($array=mysql_fetch_array($resultUser)){
		
		$ranktipp=$array["ranktipp"];
		$teamName=$array["name"];
		if(mysql_error()!=0)
		{
			$log->error($sqluser);
		}
		echo "<tr>";
		echo "<td> $ranktipp. </td><td><b> $teamName </b></td><td> </td>";
		$correctTeam=getCorrectTeam($group, $ranktipp);
		printCorrectTeam($correctTeam);
		$score=$array["score"];
		printScore($score);
		echo "</tr>";
		//&nbsp;&nbsp;
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
function getCorrectTeam($group, $ranktipp){
	$table_teams=dbschema::teams;
	$table_groupranktipps=dbschema::groupranktipps;
	$sql="SELECT t.name FROM $table_groupranktipps r, $table_teams t " .
		"WHERE r.team = t.shortname AND r.user = 'real' AND t.group='$group' AND r.rank='$ranktipp'";
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$name=$sqlArray["name"];
	return $name;
}

function printCorrectGroupMatchResult($correctGoals1, $correctGoals2){
	
	//$log=new viewlogger();	
	//$log->info("CorrectGroupMatchResult=" . $correctGoals1 . ":" . $correctGoals2);
	if(strlen($correctGoals1) == 0 && strlen($correctGoals2) == 0)
	{
		echo "<td>&nbsp; &nbsp; <font color=\"#32cd32\"><b> - : - </b></td>";
	}
	else
	{
		echo "<td>&nbsp; &nbsp; <font color=\"#32cd32\"><b> $correctGoals1 : $correctGoals2 </b></td>";
	}
}

function printCorrectTeam($correctTeam){
	
	//$log=new viewlogger();	
	//$log->info("CorrectTeam=" . $correctTeam);
	if(strlen($correctTeam) == 0)
	{
		echo "<td>&nbsp; &nbsp; <font color=\"#32cd32\"><b> --- </b></td>";
	}
	else
	{
		echo "<td>&nbsp; &nbsp; <font color=\"#32cd32\"><b> $correctTeam </b></td>";
	}
}

function printCorrectTeamParticipant($correctTeam){
	
	//$log=new viewlogger();	
	//$log->info("CorrectTeamParticipant=" . $correctTeam);
	if(strlen($correctTeam) == 0)
	{
		echo "<td width=150>&nbsp; &nbsp; <font color=\"#32cd32\"><b> --- </b></td>";
	}
	else
	{
		echo "<td width=150>&nbsp; &nbsp; <font color=\"#32cd32\"><b> $correctTeam </b></td>";
	}
}

function printCorrectTopscorer($correctScorer, $correctTeam){
	
	//$log=new viewlogger();	
	//$log->info("CorrectScorer=$correctScorer CorrectTeam= $correctTeam");
	echo "<td>&nbsp; &nbsp; <font color=\"#32cd32\"><b> $correctScorer </b></td>";
	echo "<td>&nbsp; &nbsp; <font color=\"#32cd32\">($correctTeam)</td>";
}

function getCorrectChampion($rank){
	$table_teams=dbschema::teams;
	$table_championtipps=dbschema::championtipps;
	$sql="SELECT t.name FROM $table_championtipps c, $table_teams t " .
		"WHERE c.team = t.shortname AND c.user = 'real' AND c.rank='$rank'";
	//$log=new viewlogger();	
	//$log->info($sql);
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$name=$sqlArray["name"];
	return $name;
}

function getCorrectTopscorer(){
	$table_topscorertipps=dbschema::topscorertipps;
	$sql="SELECT t.topscorer FROM $table_topscorertipps t WHERE t.user = 'real'";
	//$log=new viewlogger();	
	//$log->info($sql);
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$name=$sqlArray["topscorer"];
	return $name;
}

function printScore($score){

	if($score != null)
	{
		echo "<td>&nbsp; &nbsp; <font color=\"#C81B00\"><b> $score </b></td>";
	}	
	else
	{
		echo "<td>&nbsp; &nbsp; <font color=\"#C81B00\"><b> 0 </b></td>";
	}
}

function printGroupMatches($group, $userName){
	
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.group='$group'";
	
	$sqlResult=mysql_query($sql);
	//	echo "<u>Spiele:</u>";
	echo "<table>";
	echo "<tr>";
	echo "<th colspan='4' align='left'><u>Spiele:</u></th>";
	echo "</tr>";
	while($array=mysql_fetch_array($sqlResult)){
		
		
		$date=$array["matchdate"]; 
		$time=$array["matchtime"]; 
		$matchnr=$array["matchnr"];
		
		//		$teamName1=$dbutil->getTeamName($array["team1"]);
		//		$teamName2=$dbutil->getTeamName($array["team2"]);
		$teamName1=getTeamName($array["team1"]);
		$teamName2=getTeamName($array["team2"]);
		
		$tippGoalsTeam1=getGoals1($userName, $matchnr);
		$tippGoalsTeam2=getGoals2($userName, $matchnr);
		echo "<tr>";
		echo "<td>Spiel $matchnr &nbsp;&nbsp;</td> " .
				"<td width=100>  $teamName1 </td> <td>-</td> <td width=100> $teamName2&nbsp;&nbsp;&nbsp;</td>" .
				"<td> <b>$tippGoalsTeam1</b></td><td> : </td><td> <b>$tippGoalsTeam2</b></td>";
		printCorrectGroupMatchResult(getGoals1('real', $matchnr), getGoals2('real', $matchnr));
		$score = getScoreGroupMatch($userName, $matchnr);
		printScore($score);
		echo "</tr>";
	}
	echo "</table>";
}


function getGoals1($userName, $matchnr){
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	$result=mysql_query("SELECT goalsX FROM $table_groupmatchtipps WHERE user = '$userName' AND matchnr = $matchnr;");
	$array=mysql_fetch_array($result);
	$goals=$array["goalsX"];
	return $goals;
}
function getGoals2($userName, $matchnr){
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	$result=mysql_query("SELECT goalsY FROM $table_groupmatchtipps WHERE user = '$userName' AND matchnr = $matchnr;");
	$array=mysql_fetch_array($result);
	$goals=$array["goalsY"];
	return $goals;
}

function getTeamName($shortname) {
	$table_teams=dbschema::teams;
	$sql="SELECT t.name FROM $table_teams t WHERE t.shortname='$shortname'";
	//$log=new viewlogger();
	//$log->info($sql);
	$result=mysql_query($sql);
	$array=mysql_fetch_array($result);
	$name=$array["name"];
	return $name;
}

function printFinal($userName, $matchtype){
	
	echo "<h3>$matchtype</h3>";
	echo "<table CELLSPACING=20>";
	echo "<td>";
	echo "<table>";
	echo "<tbody>";
	printEvaluationFinalParticipants($userName, $matchtype, false);
	echo "</tbody>";
	echo "</table>";
	echo "</td>";
	echo "<td>";
	printFinalMatches($userName, $matchtype);
	echo "</td>";
	echo "</table>";
}	

function printFinalMatches($userName, $matchtype){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_matches=dbschema::matches;
	
	$sqlReal="SELECT * FROM $table_finalmatchtipps ft, $table_matches m
		WHERE ft.user = 'real' AND m.matchtype = '$matchtype' AND m.matchnr = ft.matchnr ORDER BY ft.matchnr";
	
	$sql="SELECT * FROM $table_finalmatchtipps ft, $table_matches m
		WHERE ft.user = '$userName' AND m.matchtype = '$matchtype' AND m.matchnr = ft.matchnr ORDER BY ft.matchnr";
	
	//$log=new viewlogger();
	//$log->info($sql);
	$sqlResultReal=mysql_query($sqlReal);
	$sqlResult=mysql_query($sql);
	if (!$sqlResult) {
		
		echo "<br> MIST DB error";
	}
	else
	{
		echo "<table>";
		echo "<th colspan='4' align='left'><u>Spiele:</u></th>";
		echo "<tbody>";
		while($array=mysql_fetch_array($sqlResult))
		{
			$arrayReal=mysql_fetch_array($sqlResultReal);
			$teamShort1Real = $arrayReal["teamX"];
			$teamShort2Real = $arrayReal["teamY"];
			$team1Real=getTeamName($teamShort1Real);
			$team2Real=getTeamName($teamShort2Real);
			$goalsXReal = $arrayReal["goalsX"];
			$goalsYReal = $arrayReal["goalsY"];
			
			$matchnr=$array["matchnr"];
			$teamShort1 = $array["teamX"];
			$teamShort2 = $array["teamY"];
			$team1=getTeamName($teamShort1);
			$team2=getTeamName($teamShort2);
			$goalsX = $array["goalsX"];
			$goalsY = $array["goalsY"];
			echo "<font color=\"#32cd32\">";
			echo "<tr>";
			echo "<td>Spiel $matchnr &nbsp; &nbsp; &nbsp;</td>";
			echo "<td width=100><font color=\"#32cd32\"><b> $team1Real </b></font></td>";
			echo "<td><font color=\"#32cd32\">-</font></td>";
			echo "<td width=100><font color=\"#32cd32\"><b> $team2Real</b></font></td>";
			echo "<td><font color=\"#32cd32\"><b>$goalsXReal : $goalsYReal</b></font>&nbsp; &nbsp; &nbsp; &nbsp; </td>";
			echo "<td width=100><b> $team1 </b></td><td>-</td><td width=100><b> $team2</b></td>  	<td><b>$goalsX : $goalsY</b></td>";
			$score = getScoreFinalMatch($userName, $matchnr);
			printScore($score);
			echo "</tr>";
			echo "";
		}
		echo "</tbody>";
		echo "</table>";
	}
}

function getScoreGroupMatch($userName, $matchnr){
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	$sql = "SELECT * from " . $table_groupmatchtipps . " WHERE user='$userName' AND matchnr='$matchnr'";
	//$log=new viewlogger();
	//$log->info($sql);
	$sqlResult=mysql_query($sql);
	$array=mysql_fetch_array($sqlResult);
	$score=$array["score"];
	return $score;
}

function getScoreFinalMatch($userName, $matchnr){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$sql = "SELECT * from " . $table_finalmatchtipps . " WHERE user='$userName' AND matchnr='$matchnr'";
	//$log=new viewlogger();
	//$log->info($sql);
	$sqlResult=mysql_query($sql);
	$array=mysql_fetch_array($sqlResult);
	$score=$array["score"];
	return $score;
}

function getScoreChampions($rank, $userName){
	
	$table_championtipps=dbschema::championtipps;
	$sql = "SELECT * from " . $table_championtipps . " WHERE user='$userName' AND rank='$rank'";
	//$log=new viewlogger();
	//$log->info($sql);
	$sqlResult=mysql_query($sql);
	$array=mysql_fetch_array($sqlResult);
	$score=$array["score"];
	return $score;
}

function getScoreTopscorer($userName){
	
	$table_topscorertipps=dbschema::topscorertipps;
	$sql = "SELECT * from $table_topscorertipps WHERE user='$userName'";
	//$log=new viewlogger();
	//$log->info($sql);
	$sqlResult=mysql_query($sql);
	$array=mysql_fetch_array($sqlResult);
	$score=$array["score"];
	return $score;
}


function printChampions($userName){
	
	$worldchampion=getTippedTeamRostrum($userName, 1);
	$vice=getTippedTeamRostrum($userName, 2);
	$third=getTippedTeamRostrum($userName, 3);
	echo "<table>";
	echo "<tr><td>Europameister &nbsp; &nbsp; &nbsp;</td><td><b>$worldchampion</b></td>";
	printCorrectTeam(getCorrectChampion(1));
	printScore(getScoreChampions(1, $userName));
	echo "</tr>";
	echo "<tr><td>Zweitplatzierter &nbsp; &nbsp; &nbsp;</td><td><b>$vice</b></td>";
	printCorrectTeam(getCorrectChampion(2));
	printScore(getScoreChampions(2, $userName));
	echo "</tr>";
// 	echo "<tr><td>Drittplatzierter &nbsp; &nbsp; &nbsp;</td><td><b>$third</b></td>";
// 	printCorrectTeam(getCorrectChampion(3));
// 	printScore(getScoreChampions(3, $userName));
// 	echo "</tr>";
	echo "</table>";
}

function printTopscorer($username){
	
	// tipped values
	$topscorer=getTippedTopScorer($username);
	$tippedTeamShort=getTippedTopScorerTeam($username);
	$topScorerTeam=getTeamName($tippedTeamShort);
	// real values
	$TopscorerReal=getCorrectTopscorer();
	$realTeamShort=getTippedTopScorerTeam("real");
	$realTeam=getTeamName($realTeamShort);
	
	echo "<br>";
	echo "<table>";
	echo "<tr>";
	echo "<td>Torsch&uuml;tzenk&ouml;nig &nbsp; &nbsp; &nbsp; </td><td> $topscorer </b> &nbsp; </td><td> ($topScorerTeam)</td>";
	printCorrectTopscorer($TopscorerReal, $realTeam);
	$score=getScoreTopscorer($username);
	printScore($score);
	echo "</tr>";
	echo "</table>";
	echo "<br>";
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

function printEvaluationFinalParticipants($user, $matchtype, $silent)
{
	echo "<th colspan='4' align='left'><u>Mannschaften im $matchtype:</u></th>";
	$matches=getMatches($matchtype);
	$realTeams=getRealTeams($matchtype);
	$score=checkTeamsIncluded($user, "team", $realTeams, $matches, $matchtype, $silent);
	//echo "<br>Score User $user fuer $matchtype: $score";
	return $score;
}
function getMatches($matchtype) {
	$table_matches=dbschema::matches;
    $sql="SELECT * FROM $table_matches m WHERE m.matchtype = '$matchtype' ";
    $result=mysql_query($sql);
    return $result;
}

function getRealTeams($matchtype) {
	$table_matches=dbschema::matches;
	$table_finalmatchtipps=dbschema::finalmatchtipps;
    $sql="SELECT f.teamX as team, f.matchnr as matchnr FROM $table_matches m, $table_finalmatchtipps f " .
	"WHERE m.matchtype = '$matchtype' " .
	"AND f.matchnr = m.matchnr AND f.user = 'real' " .
    "UNION SELECT f.teamY as team, f.matchnr as matchnr FROM $table_matches m, $table_finalmatchtipps f " .
	"WHERE m.matchtype = '$matchtype' " .
	"AND f.matchnr = m.matchnr AND f.user = 'real' " . 
	"ORDER BY matchnr";
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

function checkTeamsIncluded($user, $column, $realTeams, $matches, $matchtype, $silent)
{
	$userScore=0;
	while($matchesArray=mysql_fetch_array($matches))
	{
		$matchnr=$matchesArray["matchnr"];
		for($a = 0; $a < 2; $a++)
		{
			echo "<tr>";
			echo "<td>Spiel $matchnr</td>";

			$teamsArray=mysql_fetch_array($realTeams);
			$team=$teamsArray["$column"];
			if(strlen($team)==0)
			{
				if(!$silent)
				{
					printCorrectTeamParticipant(getTeamName($team));
					echo "<td>?</td>";
					printScore("-");
				}
			}
			else if(userHasIncluded($user, $matchtype, $team))
			{
				$addScore=getScore($matchtype);
				$userScore = $userScore+$addScore;
				if(!$silent)
				{
					printCorrectTeamParticipant(getTeamName($team));
					echo "<td>JA</td>";
					printScore($addScore);
				}
			}
			else
			{
				if(!$silent)
				{
					printCorrectTeamParticipant(getTeamName($team));
					echo "<td>nein</td>";
					printScore("0");
				}
			}
		}
		echo "</tr>";
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