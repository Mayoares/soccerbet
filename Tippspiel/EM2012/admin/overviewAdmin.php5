<?php
// we must never forget to start the session
session_start();
include_once("../util/dbutil.php5");

$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
//echo "<br>userId=$userId";
echo "<html>";
echo "<head><title>EM-Tipp Admin</title></head>";

echo "<body>";
echo "<h1>Admin Überblick</h1>";
echo "<form method=\"POST\" action=\"../util/login.php5\">";
echo "<input type='submit' name='logout' value='Logout'>";
echo "</form>";
echo "<br>";

echo "<h2>Newsletter</h2>";
echo "<form enctype=\"multipart/form-data\" action=\"./UploadAndSendNewsletter.php5\" method=\"POST\">";
echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000\" />";
echo "Folgende Datei hochladen: <input name=\"uploadedfile\" type=\"file\" /> &nbsp";
echo "<input type=\"submit\" name='uploadNews' value=\"Newsletter hochladen und verschicken\" />";
echo "</form>";

echo "<h2>User administrieren</h2>";
echo "<a href='../anybody/showUserList.php5?userId=$userId'>User Liste ansehen</a>";
echo "<br>";
echo "<a href='../anybody/showUserRanks.php5?userId=$userId'>User Rangliste ansehen</a>";
echo "<br>";
echo "<br>";
echo "<form method=\"POST\" action=\"addUser.php5?userId=$userId\">";
echo "<input type='submit' name='addUser' value='User anlegen'>";
echo "</form>";
echo "<br>";
echo "<br>";
echo "<form method=\"POST\" action=\"resetUserPassword.php5?userId=$userId\">";
echo "<td bgcolor=slategray><select name='SelectedUsername'>";
allUsersAsOption("User");
echo "<input type='submit' name='resetUser' value='Passwort zurücksetzen'>";
echo "</form>";
echo "<br>";
echo "<form method=\"POST\" action=\"removeUser.php5?userId=$userId\">";
echo "<td bgcolor=slategray><select name='SelectedUsername'>";
allUsersAsOption("User");
echo "<input type='submit' name='removeUser' value='User löschen'>";
echo "</form>";
echo "<br>";

echo "	<h2>Tipps ansehen</h2>";
echo "<a href='../anybody/showAllUsersGroupTipps.php5?userId=$userId'>Gruppenspieltipps</a>";
echo "<br>";
echo "<a href='../anybody/showAllUsersGroupRankTipps.php5?userId=$userId'>Platzierungstipps</a>";
echo "<br>";
echo "<a href='../anybody/showAllUsersFinalmatchTipps.php5?userId=$userId'>Finalspieltipps</a>";
echo "<br>";
echo "<a href='../anybody/showAllUsersSpecialTipps.php5?userId=$userId'>Spezialtipps</a>";
echo "<br>";
echo "<br>";

echo "	<h2>Auswertung</h2>";
echo "<h3><font color=\"red\">Bitte hier nichts klicken, bevor es wirklich soweit ist! </font></h3>";
echo "	<h4>Gruppen</h4>";
echo "<form method=\"POST\" action=\"evaluationGroupMatch.php5?adminuserId=$userId\">";
echo "Spiel ";
echo "<td bgcolor=slategray><select name='SelectedMatchnr'>";
allGroupMatchesAsOption("SelectedMatchnr", $dbutil);
echo "</td>";
echo "<input type='submit' name='evaluationGroupMatch' value='auswerten'>";
echo "</form>";
echo "<br>";

echo "<form method=\"POST\" action=\"evaluationGroupRank.php5?adminuserId=$userId\">";
echo "Platzierungstipps von Gruppe ";
echo "<td bgcolor=slategray><select name='SelectedGroup'>";
echo"<option name=SelectedGroup >A</option>";
echo"<option name=SelectedGroup >B</option>";
echo"<option name=SelectedGroup >C</option>";
echo"<option name=SelectedGroup >D</option>";
echo "</td>";
echo "<input type='submit' name='evaluationGroupRank' value='auswerten'>";
echo "</form>";
echo "<br>";

echo "<h4>Finalspiele</h4>";

echo "<form method=\"POST\" action=\"evaluationParticipants.php5?adminuserId=$userId\">";
echo "<input type=\"submit\" name=\"eval3\" value=\"Auswertung Finalspiel-Teilnahme\">";
echo "</form>";
echo "<br>";

echo "<form method=\"POST\" action=\"evaluationFinalMatch.php5?adminuserId=$userId\">";
echo "Spiel ";
echo "<td bgcolor=slategray><select name='SelectedMatchnr'>";
allFinalMatchesAsOption("SelectedMatchnr", $dbutil);
echo "</td>";
echo "<input type='submit' name='evaluationFinalMatch' value='auswerten'>";
echo "</form>";
echo "<br>";

echo "<form method=\"POST\" action=\"evaluationSpecials.php5?adminuserId=$userId\">";
echo "<input type=\"submit\" name=\"eval\" value=\"Auswertung Vize- und Europameister\">";
echo "</form>";
echo "<br>";

function allGroupMatchesAsOption($optionName, $dbutil) {
	include_once("../../connection/dbaccess.php5");
	include_once("../util/dbschema.php5");
	include_once("../../general/log/log.php5");
	$log=new adminlogger();	
	$table_matches=dbschema::matches;
	$sqlmatches="SELECT * FROM $table_matches WHERE matchtype='Gruppenspiel' AND NOT evaluationDone='T' ORDER BY matchnr";
	//$log->info($sqlmatches);
	$sqlmatchesResult=mysql_query($sqlmatches);
	$nummatches=mysql_num_rows($sqlmatchesResult);
	if ($nummatches==0)
	{
		$log->warn("keine passenden Datensätze gefunden");
		echo "keine passenden Datensätze gefunden";
	}
	else
	{
		$log->info("Anzahl noch auszuwertende Gruppenspiele=". $nummatches);
		for ($i=0; $i<$nummatches; $i++)
		{
			$matchnr = mysql_result($sqlmatchesResult, $i, "matchnr");
			$team1 = $dbutil->getTeamName(mysql_result($sqlmatchesResult, $i, "team1"));
			$team2 = $dbutil->getTeamName(mysql_result($sqlmatchesResult, $i, "team2"));
			//if($name!='admin' && $name!='real')
			echo"<option name=$optionName value='$matchnr' >$matchnr: $team1-$team2</option>";
		}
	}
}

function allFinalMatchesAsOption($optionName, $dbutil) {
	include_once("../../connection/dbaccess.php5");
	include_once("../util/dbschema.php5");
	include_once("../util/dbutil.php5");
	include_once("../../general/log/log.php5");
	$log=new adminlogger();	
	$table_matches=dbschema::matches;
	$sqlmatches="SELECT * FROM $table_matches WHERE NOT matchtype='Gruppenspiel' AND NOT evaluationDone='T' ORDER BY matchnr";
	//$log->info($sqlmatches);
	$sqlmatchesResult=mysql_query($sqlmatches);
	$nummatches=mysql_num_rows($sqlmatchesResult);
	if ($nummatches==0)
	{
		$log->warn("keine passenden Datensätze gefunden");
		echo "keine passenden Datensätze gefunden";
	}
	else
	{
		$log->info("Anzahl noch auszuwertende Finalspiele=". $nummatches);
		for ($i=0; $i<$nummatches; $i++)
		{
			$matchnr = mysql_result($sqlmatchesResult, $i, "matchnr");
			$team1="";
			$team2="";
			$team1Short=mysql_result($sqlmatchesResult, $i, "team1");
			if(!empty($team1Short))
			{
				$team1 = $dbutil->getTeamName($team1Short);
			}
			$team2Short=mysql_result($sqlmatchesResult, $i, "team2");
			if($team2Short!="")
			{
				$team2 = $dbutil->getTeamName($team2Short);
			}
			//if($name!='admin' && $name!='real')
			echo"<option name=$optionName value='$matchnr' >$matchnr: $team1-$team2</option>";
		}
	}
}

echo "<br></body></html>";


function allUsersAsOption($optionName) {
	include_once("../../connection/dbaccess.php5");
	include_once("../util/dbschema.php5");
	include_once("../../general/log/log.php5");
	$log=new adminlogger();	
	$table_users=dbschema::users;
	$sqlUsers="SELECT * FROM $table_users ORDER BY username";
	//$log->info($sqlUsers);
	$sqlUsersResult=mysql_query($sqlUsers);
	$numUsers=mysql_num_rows($sqlUsersResult);
	if ($numUsers==0)
	{
		$log->warn("keine passenden Datensätze gefunden");
		echo "keine passenden Datensätze gefunden";
	}
	else
	{
		$log->info("Anzahl User=". $numUsers);
		for ($i=0; $i<$numUsers; $i++)
		{
			$name = mysql_result($sqlUsersResult, $i, "username");
			if($name!='admin' && $name!='real')
			{
				echo"<option name=$optionName >$name</option>";
			}
		}
	}
}


?>