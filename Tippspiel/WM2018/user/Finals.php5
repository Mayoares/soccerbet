<?php
// we must never forget to start the session
session_start();
include_once("../../general/log/log.php5");
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
	echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
	echo "<title>Werke's Tippspiel - Endrunde tippen</title>";
	echo "</head>";
	echo "<body>";
	echo "<p class=\"info\">Hinweis zur Tippabgabe:</br><u><b>Paarungen</b></u> und <u><b>Ergebnisse</b></u> m&uuml;ssen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!</p>";
	
	$matchnrPost=0;
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
	echo "<h2 id='Achtelfinale'>Achtelfinale</h2>";
	$citation->printCitation("Achtelfinale");
	echo "<br>";
	run($userId, "Achtelfinale", $matchnrPost, $dbutil, $saveMatchErrorText);
	echo "<h2 id='Viertelfinale'>Viertelfinale</h2>";
	$citation->printCitation("Viertelfinale");
	echo "<br>";
	run($userId, "Viertelfinale", $matchnrPost, $dbutil, $saveMatchErrorText);
	echo "<h2 id='Halbfinale'>Halbfinale</h2>";
	$citation->printCitation("Halbfinale");
	echo "<br>";
	run($userId, "Halbfinale", $matchnrPost, $dbutil, $saveMatchErrorText);
	echo "<h2 id='Platz3'>Spiel um Platz 3</h2>";
	$citation->printCitation("Platz3");
	echo "<br>";
	run($userId, "Platz3", $matchnrPost, $dbutil, $saveMatchErrorText);
	echo "<h2 id='Finale'>Finale</h2>";
	$citation->printCitation("Finale");
	echo "<br>";
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
   		echo "<form action='Finals.php5?userId=$userId&matchnr=$matchnr#$finaltype' method='POST'>";
		echo "<td></td>";
		printDescription($matchnr);
		// only possible teams
		if($finaltype=="Achtelfinale"){
			$sqlTeams1 = getTeamsOfGroup($matchnr, 1);
			$sqlTeams2 = getTeamsOfGroup($matchnr, 2);
		}
		/*if($finaltype=="Viertelfinale"){
			$sqlTeams1 = getTeamsOfGroup($matchnr, 1);
			$sqlTeams2 = getTeamsOfGroup($matchnr, 2);
		}*/
		
		echo "</tr>";
		echo "<tr class='border_bottom'>";
		echo "<td> $date </td><td> $formattedTime </td><td> Spiel $matchnr </td> ";
		echo "<td bgcolor=slategray><select  name='$matchnr-Team1' id='finalcombo' style='width: 150px !important'>";
		allTeamsAsOption($sqlTeams1, $tippedTeam1, "Team1");
		echo "</td>";
		echo "<td> - </td>";
		echo "<td bgcolor=slategray><select name='$matchnr-Team2' id='finalcombo' style='width: 150px !important'>";
		allTeamsAsOption($sqlTeams2, $tippedTeam2, "Team2");
		echo "</td>";
		echo "<td width='100' align='center'>
		<input type=\"Text\" size=\"2\" name=\"$matchnr-GoalsTeam1\" value=\"$tippedGoals1\"><b> :</b>
		<input type=\"Text\" size=\"2\" name=\"$matchnr-GoalsTeam2\" value=\"$tippedGoals2\">";
		echo "</td>";
		echo "<td><input type='submit' name='saveMatch-$matchnr' value='Speichern'></td>";
		echo "</form>";
		if($matchnrPost==$matchnr)
		{
			if(strlen($saveMatchErrorText)>0){
				echo "<td>$saveMatchErrorText <p class=\"info\"> --> <b>nicht</b> gespeichert! </p></td>";
			} else {
				echo "<td> <font color=\"green\"> Ergebnis erfolgreich gespeichert :) <font> </td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";
	echo "<br>";
	//echo "&nbsp";
	//echo "<input type='submit' name='cancel' value='Abbrechen'>";

	echo "</body>";
	echo "</html>";

}

function printDescription($matchnr){
	switch($matchnr)
	{
		case 49 : echo "<td></td><td></td><td>Sieger Gruppe A</td><td> -</td><td>Zweiter Gruppe B</td><td></td><td></td>";break;
		case 50 : echo "<td></td><td></td><td>Sieger Gruppe C</td><td> -</td><td>Zweiter Gruppe D</td><td></td><td></td>";break;
		case 51 : echo "<td></td><td></td><td>Sieger Gruppe B</td><td> -</td><td>Zweiter Gruppe A</td><td></td><td></td>";break;
		case 52 : echo "<td></td><td></td><td>Sieger Gruppe D</td><td> -</td><td>Zweiter Gruppe C</td><td></td><td></td>";break;
		case 53 : echo "<td></td><td></td><td>Sieger Gruppe E</td><td> -</td><td>Zweiter Gruppe F</td><td></td><td></td>";break;
		case 54 : echo "<td></td><td></td><td>Sieger Gruppe G</td><td> -</td><td>Zweiter Gruppe H</td><td></td><td></td>";break;
		case 55 : echo "<td></td><td></td><td>Sieger Gruppe F</td><td> -</td><td>Zweiter Gruppe E</td><td></td><td></td>";break;
		case 56 : echo "<td></td><td></td><td>Sieger Gruppe H</td><td> -</td><td>Zweiter Gruppe G</td><td></td><td></td>";break;
		case 57 : echo "<td></td><td></td><td>Sieger Spiel 49</td><td> -</td><td>Sieger Spiel 50</td><td></td><td></td>";break;
		case 58 : echo "<td></td><td></td><td>Sieger Spiel 53</td><td> -</td><td>Sieger Spiel 54</td><td></td><td></td>";break;
		case 59 : echo "<td></td><td></td><td>Sieger Spiel 51</td><td> -</td><td>Sieger Spiel 52</td><td></td><td></td>";break;
		case 60 : echo "<td></td><td></td><td>Sieger Spiel 55</td><td> -</td><td>Sieger Spiel 56</td><td></td><td></td>";break;
		case 61 : echo "<td></td><td></td><td>Sieger Spiel 57</td><td> -</td><td>Sieger Spiel 58</td><td></td><td></td>";break;
		case 62 : echo "<td></td><td></td><td>Sieger Spiel 59</td><td> -</td><td>Sieger Spiel 60</td><td></td><td></td>";break;
		case 63 : echo "<td></td><td></td><td>Verlierer Spiel 61</td><td> -</td><td>Verlierer Spiel 62</td><td></td><td></td>";break;
		case 64 : echo "<td></td><td></td><td>Sieger Spiel 61</td><td> -</td><td>Sieger Spiel 62</td><td></td><td></td>";break;
	}
}

function getTeamsOfGroup($matchnr, $named){
	//echo "<br>getTeamsOfGroup($matchnr, $named)";
	switch($matchnr)
	{
		// Achtelfinale
		case 49 : return ($named==1) ? getTeams('A') : getTeams('B');
		case 50 : return ($named==1) ? getTeams('C') : getTeams('D');
		case 51 : return ($named==1) ? getTeams('B') : getTeams('A');
		case 52 : return ($named==1) ? getTeams('D') : getTeams('C');
		case 53 : return ($named==1) ? getTeams('E') : getTeams('F');
		case 54 : return ($named==1) ? getTeams('G') : getTeams('H');
		case 55 : return ($named==1) ? getTeams('F') : getTeams('E');
		case 56 : return ($named==1) ? getTeams('H') : getTeams('G');
		
		// Viertelfinale
		case 57 : return ($named==1) ? getTeamsOfGroups('A','B') : getTeamsOfGroups('C','D');
		case 58 : return ($named==1) ? getTeamsOfGroups('E','F') : getTeamsOfGroups('G','H');
		case 59 : return ($named==1) ? getTeamsOfGroups('A','B') : getTeamsOfGroups('C','D');
		case 60 : return ($named==1) ? getTeamsOfGroups('E','F') : getTeamsOfGroups('G','H');
	}
}

function allTeamsAsOption($sqlTeams, $tippedTeam, $optionName) {
	$numTeams=mysql_num_rows($sqlTeams);
	if ($numTeams==0)
	echo "keine passenden Datens&auml;tze gefunden";
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

function getTeamsOfthreeGroups($group1, $group2, $group3){
	$table_teams=dbschema::teams;
	$sqlQuery="SELECT t.name FROM $table_teams t WHERE t.group='$group1' UNION SELECT t.name FROM $table_teams t WHERE t.group='$group2' UNION SELECT t.name FROM $table_teams t WHERE t.group='$group3'";
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
		$teamShort1=$dbutil->getShortName($team1);
		$teamShort2=$dbutil->getShortName($team2);
		$goalsTeam1Str=$_POST["$matchnr-GoalsTeam1"];
		$goalsTeam1 = ((strlen($goalsTeam1Str)==0) ? "NULL" : $goalsTeam1Str);
		$goalsTeam2Str=$_POST["$matchnr-GoalsTeam2"];
		$goalsTeam2 = ((strlen($goalsTeam2Str)==0) ? "NULL" : $goalsTeam2Str);
		//echo "goalsTeam1=$goalsTeam1, goalsTeam2=$goalsTeam2";
		$winner=$calc->calcWinner($goalsTeam1,$goalsTeam2);
		if($team1=="---" && $team2=="---")
		{
			removeFinalMatchTipp($userName, $matchnr);
		}	
		else
		{
			if($goalsTeam1Str<0 || $goalsTeam2Str<0){
				return "<p class=\"info\">Toranzahl unter 0!</p>"; 
			} else {
				return insertUpdateFinalMatchTipp($userName, $matchnr, $teamShort1, $teamShort2, $goalsTeam1, $goalsTeam2, $winner);
			}
		}
		//echo "<br> $date $time Spiel $matchnr : $team1 - $team2  &nbsp; &nbsp; <b>$goalsTeam1 : $goalsTeam2</b> winner=$winner";
	}
}

function insertUpdateFinalMatchTipp($userName, $matchnr, $teamShort1, $teamShort2, $GoalsTeam1, $GoalsTeam2, $winner){

	// only for user "real"
	if($userName==='real')
	{
		updateInMatches($matchnr, "'$teamShort1'", "'$teamShort2'");
	} 
	
	//echo "insertUpdateFinalMatchTipp($userName $matchnr $teamShort1 $teamShort2 $GoalsTeam1:$GoalsTeam2)<br>";
	return insertUpdateInTipps($userName, $matchnr, $teamShort1, $teamShort2, $GoalsTeam1, $GoalsTeam2, $winner);
	
}

function insertUpdateInTipps($userName, $matchnr, $teamShort1, $teamShort2, $GoalsTeam1, $GoalsTeam2, $winner){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$goaldiff=$GoalsTeam1-$GoalsTeam2;
	//echo "$goaldiff=$GoalsTeam1-$GoalsTeam2";
	$teamShort1String = ($teamShort1=='') ? "NULL" : "'$teamShort1'";
	$teamShort2String = ($teamShort2=='') ? "NULL" : "'$teamShort2'";
	
	$sqlInsertUpdate="INSERT INTO $table_finalmatchtipps (user,matchnr,teamX,teamY,goalsX,goalsY,winner,goaldiff,score) " .
			"VALUES ('$userName', '$matchnr', $teamShort1String, $teamShort2String, $GoalsTeam1, $GoalsTeam2, '$winner', '$goaldiff', NULL) " .
			"ON DUPLICATE KEY UPDATE teamX=VALUES(teamX),teamY=VALUES(teamY),goalsX=VALUES(goalsX),goalsY=VALUES(goalsY),winner=VALUES(winner),goaldiff=VALUES(goaldiff),score=VALUES(score)";
	$log=new logger();	
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sqlInsertUpdate);
	$sqlInsertUpdateResult=mysql_query($sqlInsertUpdate);
	if (!$sqlInsertUpdateResult) {
		$mysqlInsertUpdateError=mysql_error();
		$log->error("SQL-Error: " . $mysqlInsertUpdateError);
		return "<p class=\"info\">Datenbank-Problem beim Speichern des Endrundentipps</p>";
	} else {
		$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Endrundentipp eingetragen: ('$userName', matchnr=$matchnr, $teamShort1String, $teamShort2String, $GoalsTeam1, $GoalsTeam2, winner=$winner, Tordifferenz=$goaldiff, score=NULL) ");
		return "";
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
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sqlremove);
	$sqlRemoveResult=mysql_query($sqlremove);
	if ($sqlInsertResult=!1) {
		$sqlerror=mysql_error();
		$log->error($sqlerror);
		echo "<br><font color='#EE0000'> Ung&uuml;ltiger Request: <b>$sqlupdateMatch</b> <br>Error:$sqlerror</font>";
	}
	echo "<br><p class=\"info\"> Tipp f&uuml;r Spiel <b>$matchnr</b> gel&ouml;scht!</p>";
}

function updateInMatches($matchnr, $teamShort1, $teamShort2){
	
	$table_matches=dbschema::matches;
	$sqlupdateMatch="UPDATE $table_matches SET " .
			"team1 = $teamShort1, " .
			"team2 = $teamShort2 " .
			"WHERE $table_matches.matchnr=$matchnr";
	$log=new logger();
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sqlupdateMatch);
	$sqlupdateMatchResult=mysql_query($sqlupdateMatch);
	if ($sqlupdateMatchResult!=1) {
		$sqlerror=mysql_error();
		$log->error("SQL-Error: " . $sqlerror);
		echo "<p class=\"info\"> Ung&uuml;ltiger Request: <b>$sqlupdateMatch</b> Error:$sqlerror</p>";
	} 
}

function clearInMatches($matchnr){

	updateInMatches($matchnr, "NULL", "NULL");
}


mysql_close();
$log=new logger();
$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Verbindung zur MySQL-DB geschlossen.");
?>
