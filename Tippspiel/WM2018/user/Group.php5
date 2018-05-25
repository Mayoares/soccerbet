<?php
session_start();
$userId=$_GET["userId"];
$group=$_GET["group"];
include_once("../../connection/dbaccess.php5");
include_once("../util/Citations.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");
include_once("MatchPrediction.php5");
include_once("../../general/log/log.php5");
$userName=$dbutil->getUserName($userId);
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
echo "<title>Werke's Tippspiel - Gruppe $group tippen</title>";
echo "</head>";
echo "<body>";
echo "<p class=\"info\">Hinweis zur Tippabgabe:</br><u><b>Platzierungen</b></u> und <u><b>Gruppenspiele</b></u> m&uuml;ssen <u><b>separat</b></u> gespeichert werden!</p>";
echo "<h2>Gruppe $group</h2>";

$citation->printCitation($group);
echo "<br>";


if(isset($_POST["saveRanks"])){
	saveRanks($userName, $userId, $group, $dbutil);
	echo "<br>";
}

$table_teams=dbschema::teams;
$sql="SELECT * FROM $table_teams m WHERE m.group='$group'";
$sqlResult=mysql_query($sql);
$sqlTeams = getTeams($group);
echo "<form action='Group.php5?userId=$userId&group=$group' method='POST'>";
echo "<table>";
$rankCnt = 1;
while($array=mysql_fetch_array($sqlResult))
{
	$teamName=$array["name"];
	$table_groupranktipps=dbschema::groupranktipps;
	$tippedTeam = getTeamOnRank($userName, $group, $rankCnt, $dbutil);
	echo "<tr class='border_bottom'>";
	echo "<td>$rankCnt.</td>";
	echo "<td bgcolor=slategray><select name='Rank-$rankCnt'>";
	allTeamsAsOption($sqlTeams, $tippedTeam, "Rank");
	echo "</td>";
	echo "</tr>";
	$rankCnt = $rankCnt+1;
}
echo "</table>";
echo "<br>";
echo "<input type='submit' name='saveRanks' value='Platzierungen speichern' class='button'/>";
echo "</form>";

echo "<br> <br> ";

$saveMatchErrorText="";
$matchnrPost=0;
// find last clicked save button and save the entered result
for($i=0;$i<100;$i++){
	if(isset($_POST["saveMatch-$i"])){
		$saveMatchErrorText = saveMatch($i, $userName, $dbutil);
		$matchnrPost=$i;
	}
}

if(isset($_POST["saveMatches"])){
	// echo "save matches";
	// save all matches of this group
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.group='$group'";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult))
	{
		$matchnr=$array["matchnr"];
		// echo "save match $matchnr";
		saveMatch($matchnr, $userName, $dbutil);
	}
	echo "<font color=\"green\">Ergebnisse der Gruppenspiele erfolgreich gespeichert :)</font> <br><br>";
}


$table_matches=dbschema::matches;
$sql="SELECT * FROM $table_matches m WHERE m.group='$group'";

$sqlResult=mysql_query($sql);
echo "<table>";
while($array=mysql_fetch_array($sqlResult)){


	$date=$array["matchdate"];
	$time=$array["matchtime"];
	$formattedTime = substr($time, 0, 5) . " Uhr"; 
	$matchnr=$array["matchnr"];

	$teamName1=$dbutil->getTeamName($array["team1"]);
	$teamName2=$dbutil->getTeamName($array["team2"]);

	$tippGoalsTeam1=getGoals1($userName, $matchnr);
	$tippGoalsTeam2=getGoals2($userName, $matchnr);
	
	$picname1=$dbutil->getPicName($teamName1);
	$picname2=$dbutil->getPicName($teamName2);
	
	echo "<tr class='border_bottom'>";
	echo "<form action='Group.php5?userId=$userId&matchnr=$matchnr&group=$group' method='POST'>";
	echo "<td> $date </td> <td> $formattedTime </td> <td>Spiel $matchnr &nbsp;&nbsp;</td> " .
			"<td><p><img src=\"../pics/$picname1\" alt=\"Flagge-$teamName1\"></p></td>" .
			"<td style='width: 120px !important'><b>$teamName1</b> </td> <td>-</td> 
			<td><p><img src=\"../pics/$picname2\" alt=\"Flagge-$teamName2\"></p></td>
			<td style='width: 120px !important'> <b>$teamName2</b></td>  
	<td> <input type=\"Text\" size=\"2\" name=\"$matchnr-GoalsTeam1\" value=\"$tippGoalsTeam1\"></td><td> : </td>
	<td> <input type=\"Text\" size=\"2\" name=\"$matchnr-GoalsTeam2\" value=\"$tippGoalsTeam2\"></td>";
	//if User=real
	if($userName=='real')
	{
		echo "<td><input type='submit' name='saveMatch-$matchnr' value='Speichern' class='button'/></td>";
		if($matchnrPost==$matchnr)
		{
			if(strlen($saveMatchErrorText)>0){
				echo "<td>$saveMatchErrorText <font color=\"#C81B00\"> --> <b>nicht</b> gespeichert! <font></td>";
			} else {
				echo "<td><font color=\"green\"> gespeichert! </font></td>";
			}
		}
	}
	echo "</tr>";
}
echo "</table>";
echo "<br>";
echo "<td><input type='submit' name='saveMatches' value='Gruppenspiele speichern' class='button'/></td>";
echo "</form>";
echo "</body>";
echo "</html>";

//Datenbankconnection schliessen
mysql_close();

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

function getTeams($group){
	$table_teams=dbschema::teams;
	$sqlQuery="SELECT t.name FROM $table_teams t WHERE t.group='$group'";
	$sqlQueryResult=mysql_query($sqlQuery);
	return $sqlQueryResult;
}

function allTeamsAsOption($sqlTeams, $tippedTeam, $optionName) {
	$numTeams=mysql_num_rows($sqlTeams);
	if ($numTeams==0)
	echo "keine passenden Datens&auml;tze gefunden";
	else
	{
		for ($i=0; $i<$numTeams; $i++)
		{
			$name = mysql_result($sqlTeams, $i, "name");
			if($name!=$tippedTeam)
			{
				echo"<option name=$optionName >$name</option>";
			}
		}
		if($tippedTeam!="")
		{
			echo "<option name=$optionName selected>$tippedTeam</option>";
		}
		else
		{
			echo "<option name=$optionName selected>---</option>";
		}
	}
}

function getTeamOnRank($username, $group, $rank, $dbutil){

	$table_groupranktipps=dbschema::groupranktipps;
	$table_teams=dbschema::teams;
	$sqlQuery="SELECT t.name FROM $table_teams t,$table_groupranktipps rt WHERE rt.user='$username' AND t.shortname = rt.team AND t.group='$group' AND rt.rank='$rank'";
	$sqlQueryResult=mysql_query($sqlQuery);
	$array=mysql_fetch_array($sqlQueryResult);
	$teamName=$array["name"];
	return $teamName;
}

function saveMatch($matchnr, $userName, $dbutil){
	include_once("../util/calc.php5");
	include_once("../util/dbschema.php5");
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.matchnr='$matchnr'";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){

		$GoalsTeam1=$_POST["$matchnr-GoalsTeam1"];
		$GoalsTeam2=$_POST["$matchnr-GoalsTeam2"];
		//echo "Ihre 1.Eingabe war: ". $GoalsTeam1 ."<br>";
		//echo "Ihre 2.Eingabe war: ". $GoalsTeam2 ."<br>";

		$teamName1=$dbutil->getTeamName($array["team1"]);
		$teamName2=$dbutil->getTeamName($array["team2"]);
		$calcor = new calc;
		$winner=$calcor->calcWinner($GoalsTeam1, $GoalsTeam2);
		//echo "Goals=$GoalsTeam1:$GoalsTeam2 Winner=$winner";
		
		if($GoalsTeam1<0 || $GoalsTeam2<0){
			return "Toranzahl unter 0"; 
		} else {
			$goaldiff = $GoalsTeam1-$GoalsTeam2;
			// echo "create match pred ($userName, $matchnr, $teamName1, $teamName2, $GoalsTeam1, $GoalsTeam2, $winner, $goaldiff)";
			$matchpred = new MatchPrediction($userName, $matchnr, $teamName1, $teamName2, $GoalsTeam1, $GoalsTeam2, $winner, $goaldiff);
			insertUpdateMatchPrediction($matchpred);
			return "";
		}
	}
}

function insertUpdateMatchPrediction($matchprediction)
{
	$log=new logger();	
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	// columns: user 	matchnr 	goalsX 	goalsY 	winner 	goaldiff 	score
	$sqlinsert="INSERT INTO $table_groupmatchtipps VALUES ('$matchprediction->userName', '$matchprediction->matchnr', '$matchprediction->GoalsTeam1', '$matchprediction->GoalsTeam2', '$matchprediction->winner', '$matchprediction->goaldiff', NULL)";
	$sqlInsertResult=mysql_query($sqlinsert);
	if ($sqlInsertResult) {
		$log->info($sqlinsert);
		$log->info("Eintrag fuer User=$matchprediction->userName: Spiel $matchprediction->matchnr : $matchprediction->teamName1 - $matchprediction->teamName2  	$matchprediction->GoalsTeam1 : $matchprediction->GoalsTeam2");
	} else {
		//		echo "<br><font color='#EE0000'> Ung&uuml;ltige Abfrage: <b>$sqlinsert</b> <br>Error:$sqlerror</font>"; 
		$sqlupdateMatch="UPDATE $table_groupmatchtipps SET " .
			"goalsX='$matchprediction->GoalsTeam1', " .
			"goalsY='$matchprediction->GoalsTeam2', " .
			"winner='$matchprediction->winner', " .
			"goaldiff=$matchprediction->goaldiff " .
			"WHERE `$table_groupmatchtipps`.`user`='$matchprediction->userName' AND `$table_groupmatchtipps`.`matchnr`=$matchprediction->matchnr";
		$log->info($sqlupdateMatch);
		$sqlupdateMatchResult=mysql_query($sqlupdateMatch);
		if (!$sqlupdateMatchResult) {
			$sqlerror=mysql_error();
			$log->error($sqlerror);
			echo "<br><font color='#EE0000'> Ung&uuml;ltiger Request: <b>$sqlupdateMatch</b> <br>Error:$sqlerror</font>"; 
		}
		else
		{
			$log->info("Update fuer User=$matchprediction->userName: Spiel $matchprediction->matchnr : $matchprediction->teamName1 - $matchprediction->teamName2  	$matchprediction->GoalsTeam1 : $matchprediction->GoalsTeam2");
		}
	}
}

function saveRanks($userName, $userId, $group, $dbutil){
	
	$teamName1=$_POST["Rank-1"];
	$teamName2=$_POST["Rank-2"];
	$teamName3=$_POST["Rank-3"];
	$teamName4=$_POST["Rank-4"];
	
	if(areAllTeamNamesDifferent($teamName1,$teamName2,$teamName3,$teamName4))
	{
		for($rankCnt=1;$rankCnt<=4;$rankCnt++)
		{
			$teamName=$_POST["Rank-$rankCnt"];
			$shortTeamName=$dbutil->getShortName($teamName);
			insertUpdateRankTipp($userName, $teamName, $shortTeamName, $rankCnt);
		}
		echo "<font color=\"green\">Platzierungen erfolgreich gespeichert :)</font>";
		echo "<br>";
	}
	else
	{	
		echo "<p class=\"info\">Platzierungen wurden <b> NICHT </b> gespeichert :(</p>";
		echo "<br>";
	}
}

function insertUpdateRankTipp($username, $teamName, $teamShort, $rank){
	$log=new logger();	
	$table_groupranktipps=dbschema::groupranktipps;
	$sqlInsertUpdateRank="INSERT INTO $table_groupranktipps " .
		"(`user`,`team`,`rank`,`score`) VALUES ('$username', '$teamShort', '$rank', NULL) ON DUPLICATE KEY UPDATE rank=VALUES(rank)";
	$log->info($sqlInsertUpdateRank);
	$resultInsert=mysql_query($sqlInsertUpdateRank);
	if($resultInsert=!1)
	{
		echo "<br><font color='#EE0000'>Update des Tipps [<b>$teamShort</b> landet auf Platz <b>$rank</b>] fehlgeschlagen.</font>";
		$log->error("Update Rank fehlgeschlagen:" + mysql_error());
		
	}
}

function areAllTeamNamesDifferent($teamOnRank1,$teamOnRank2,$teamOnRank3,$teamOnRank4)
{
	if(areTeamsDifferent($teamOnRank1,$teamOnRank2,1,2))
	{
		if(areTeamsDifferent($teamOnRank1,$teamOnRank3,1,3))
		{
			if(areTeamsDifferent($teamOnRank1,$teamOnRank4,1,4))
			{
				if(areTeamsDifferent($teamOnRank2,$teamOnRank3,2,3))
				{
					if(areTeamsDifferent($teamOnRank2,$teamOnRank4,2,4))
					{
						if(areTeamsDifferent($teamOnRank3,$teamOnRank4,3,4))
						{
							return true;
						}
					}
				}
			}
		}
	}	
	return false;
}

function areTeamsDifferent($team1,$team2,$rank1,$rank2)
{
	if($team1==$team2)
	{
		echo "<p class=\"info\">Platzierungen m&uuml;ssen eindeutig sein. <b>$team1</b> wurden Platz <b>$rank1</b> und <b>$rank2</b> zugeordnet!</p>";
		echo "<br>";
		return false;
	}	
	return true;
}


?>
