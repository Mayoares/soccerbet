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

if(isset($_POST["resetEvaluationDone"]))
{
	$resetMatchNr=$_POST['resetMatchNr'];
	resetMatch($resetMatchNr, $dbutil);
}

//echo "<br>userId=$userId";

?>

<!DOCTYPE HTML>
<html>

<head>
	<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />
	<title>Werke's Tippspiel - Adminbereich</title>
</head>

<body>

<center>

<p><font size="5"><b> Werke's Tippspiel zur Europameisterschaft 2016 in Frankreich</b></font></p>
<img src="../pics/Logo_EM_2016.png" class="image" width="400" alt="Logo_EM_2016">

<h1><font color="red">Adminbereich</font></h1>

<form method=\"POST\" action=\"../util/login.php5\">
	<input type='submit' name='logout' value='Logout'>
</form>

<br>

<div class="block">
	<h1>Newsletter upload</h1>
	<hr>
	<form enctype="multipart/form-data" action="./UploadAndSendNewsletter.php5?userId=$userId" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
	<p>Folgende Datei hochladen: <input name="uploadedfile" type="file" />
	<input type="submit" name='uploadNews' value="Newsletter hochladen und verschicken" /></p>
	</form>
</div>

<div class="block">
	<h1>User administrieren</h1>
	<hr>
	<p><a href='../anybody/showUserList.php5?userId=$userId'>User Liste ansehen</a></p>
	
	<p><a href='../anybody/showUserRanks.php5?userId=$userId'>User Rangliste ansehen</a></p>
	<hr>
	<?php echo "add $userId";
	echo "<form method=\"POST\" action=\"addUser.php5?userId=$userId\">";?>
	<input type='submit' name='addUser' value='User anlegen'></form></p>
	<hr>
	<p><form method='POST' action='resetUserPassword.php5?userId=$userId?'>User 
	<td bgcolor=slategray><select name='SelectedUsername'>
	<?php allUsersAsOption('User');?>
	<input type='submit' name='resetUser' value='Passwort zurücksetzen'></form></p>
	<hr>
	<p><form method='POST' action='removeUser.php5?userId=$userId'>User 
	<td bgcolor=slategray><select name='SelectedUsername'>
	<?php allUsersAsOption('User');?>
	<input type='submit' name='removeUser' value='User löschen'>
	</form></p>
	<hr>
	<p><form method='POST' action='sendTestMail.php5?userId=$userId'>
	<input type='Text' size='35' name='email' value=''>
	<input type='submit' name='sendTestMail' value='Send Test Mail'></form></p>
</div>

<div class="block">
	<h1>Tipps ansehen</h1>
	<hr>
	<p><a href='../anybody/showAllUsersGroupTipps.php5?userId=$userId'>Gruppenspieltipps</a></p>
	<p><a href='../anybody/showAllUsersGroupRankTipps.php5?userId=$userId'>Platzierungstipps</a></p>
	<p><a href='../anybody/showAllUsersFinalmatchTipps.php5?userId=$userId'>Finalspieltipps</a></p>
	<p><a href='../anybody/showAllUsersSpecialTipps.php5?userId=$userId'>Spezialtipps</a></p>
</div>

<p class='info'>Bitte hier nichts klicken, bevor es wirklich soweit ist!</p>

<div class="block">
	<h1>Auswertung</h1>
	<h2>Gruppen</h2>
	<hr>
	<?php echo "<form method=\"POST\" action=\"evaluationGroupMatch.php5?adminuserId=$userId\">";?>Spiel 
	<td bgcolor=slategray><select name='SelectedMatchnr'>
	<?php allGroupMatchesAsOption('SelectedMatchnr', $dbutil);?></td>
	<input type='submit' name='evaluationGroupMatch' value='auswerten'></form></p>

	<p><form method='POST' action='evaluationGroupRank.php5?adminuserId=$userId'>Platzierungstipps von Gruppe <td bgcolor=slategray><select name='SelectedGroup'>";
	<option name=SelectedGroup >A</option>
	<option name=SelectedGroup >B</option>
	<option name=SelectedGroup >C</option>
	<option name=SelectedGroup >D</option>
	<option name=SelectedGroup >E</option>
	<option name=SelectedGroup >F</option>
	</td>
	<input type='submit' name='evaluationGroupRank' value='auswerten'></form></p>
	<hr>
	<h2>Endrunde</h2>

	<p><form method='POST' action='evaluationParticipants.php5?adminuserId=$userId'>
	<input type='submit' name='eval3' value='Auswertung Finalspiel-Teilnahme'>
	</form></p>

	<p><form method='POST' action='evaluationFinalMatch.php5?adminuserId=$userId'>Spiel <td bgcolor=slategray><select name='SelectedMatchnr'>
	<?php allFinalMatchesAsOption("SelectedMatchnr", $dbutil);?>
	</td><input type='submit' name='evaluationFinalMatch' value='auswerten'></form></p>

	<p><form method='POST' action='evaluationSpecials.php5?adminuserId=$userId'>
	<input type='submit' name='eval' value='Auswertung Weltmeister & Co'></form></p>
	<hr>
	<h2>Korrektur</h2>
	<p><form method='POST' action='overviewAdmin.php5?userId=$userId'> Spiel 
	<input type='Text' size='1' name='resetMatchNr' value=''>
	<input type='submit' name='resetEvaluationDone' value='Auswertung rückgängig machen'></form></p>
</div>

<p><font color="#cacaca">&copy; 2016. All rights reserved.</font></p>
<p><font color="#cacaca">Idea by Werke.</font></p>
<p><font color="#cacaca">Developed by Mayoar.</font></p>
<p><font color="#cacaca">Designed by R&oslash;bl.</font></p>

<?php

/*
echo "<h2>Newsletter</h2>";
echo "<form enctype=\"multipart/form-data\" action=\"./UploadAndSendNewsletter.php5?userId=$userId\" method=\"POST\">";
echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000\" />";
echo "Folgende Datei hochladen: <input name=\"uploadedfile\" type=\"file\" /> &nbsp";
echo "<input type=\"submit\" name='uploadNews' value=\"Newsletter hochladen und verschicken\" />";
echo "</form>";
*/

/*
echo "<h2>User administrieren</h2>";
echo "<a href='../anybody/showUserList.php5?userId=$userId'>User Liste ansehen</a>";
echo "<br>";
echo "<a href='../anybody/showUserRanks.php5?userId=$userId'>User Rangliste ansehen</a>";
echo "<br>";
echo "<br>";
*/

/*
echo "<form method=\"POST\" action=\"addUser.php5?userId=$userId\">";
echo "<input type='submit' name='addUser' value='User anlegen'>";
echo "</form>";
*/

/*
echo "<form method=\"POST\" action=\"resetUserPassword.php5?userId=$userId\">";
echo "<td bgcolor=slategray><select name='SelectedUsername'>";
allUsersAsOption("User");
echo "<input type='submit' name='resetUser' value='Passwort zurücksetzen'>";
echo "</form>";
*/

/*
echo "<form method=\"POST\" action=\"removeUser.php5?userId=$userId\">";
echo "<td bgcolor=slategray><select name='SelectedUsername'>";
allUsersAsOption("User");
echo "<input type='submit' name='removeUser' value='User löschen'>";
echo "</form>";
*/

/*
echo "<form method=\"POST\" action=\"sendTestMail.php5?userId=$userId\">";
echo "<input type=\"Text\" size=\"80\" name=\"email\" value=\"\">";
echo "<input type='submit' name='sendTestMail' value='Send Test Mail'>";
echo "</form>";
*/

/*
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
*/

/*
echo "	<h2>Auswertung</h2>";
//echo "<h3><font color=\"red\">Bitte hier nichts klicken, bevor es wirklich soweit ist! </font></h3>";
echo "<p class=\"info\">Bitte hier nichts klicken, bevor es wirklich soweit ist!";

echo "	<h4>Gruppen</h4>";
echo "<form method=\"POST\" action=\"evaluationGroupMatch.php5?adminuserId=$userId\">";
echo "Spiel ";
echo "<td bgcolor=slategray><select name='SelectedMatchnr'>";
allGroupMatchesAsOption("SelectedMatchnr", $dbutil);
echo "</td>";
echo "<input type='submit' name='evaluationGroupMatch' value='auswerten'>";
echo "</form>";

echo "<form method=\"POST\" action=\"evaluationGroupRank.php5?adminuserId=$userId\">";
echo "Platzierungstipps von Gruppe ";
echo "<td bgcolor=slategray><select name='SelectedGroup'>";
echo"<option name=SelectedGroup >A</option>";
echo"<option name=SelectedGroup >B</option>";
echo"<option name=SelectedGroup >C</option>";
echo"<option name=SelectedGroup >D</option>";
echo"<option name=SelectedGroup >E</option>";
echo"<option name=SelectedGroup >F</option>";
//echo"<option name=SelectedGroup >G</option>";
//echo"<option name=SelectedGroup >H</option>";
echo "</td>";
echo "<input type='submit' name='evaluationGroupRank' value='auswerten'>";
echo "</form>";

echo "<h3>Endrunde</h3>";

echo "<form method=\"POST\" action=\"evaluationParticipants.php5?adminuserId=$userId\">";
echo "<input type=\"submit\" name=\"eval3\" value=\"Auswertung Finalspiel-Teilnahme\">";
echo "</form>";

echo "<form method=\"POST\" action=\"evaluationFinalMatch.php5?adminuserId=$userId\">";
echo "Spiel ";
echo "<td bgcolor=slategray><select name='SelectedMatchnr'>";
allFinalMatchesAsOption("SelectedMatchnr", $dbutil);
echo "</td>";
echo "<input type='submit' name='evaluationFinalMatch' value='auswerten'>";
echo "</form>";

echo "<form method=\"POST\" action=\"evaluationSpecials.php5?adminuserId=$userId\">";
echo "<input type=\"submit\" name=\"eval\" value=\"Auswertung Weltmeister & Co\">";
echo "</form>";

echo "	<h2>Korrektur</h2>";
echo "<form method=\"POST\" action=\"overviewAdmin.php5?userId=$userId\">";
echo "<input type=\"Text\" size=\"1\" name=\"resetMatchNr\" value=\"\"> <input type=\"submit\" name=\"resetEvaluationDone\" value=\"Spiel-Auswertung rückgängig machen\">";
echo "</form>";
*/

function allGroupMatchesAsOption($optionName, $dbutil) {
	include_once("../../connection/dbaccess-local.php5");
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
	include_once("../../connection/dbaccess-local.php5");
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

function allUsersAsOption($optionName) {
	include_once("../../connection/dbaccess-local.php5");
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

function resetMatch($matchnr, $dbutil){
	
	include_once("../../connection/dbaccess-local.php5");
	include_once("../util/dbschema.php5");
	include_once("../../general/log/log.php5");

	$table_matches=dbschema::matches;
	//Spielauswertung rueckgaengig machen
	$sql = "UPDATE $table_matches SET evaluationDone='F' WHERE matchnr='$matchnr'";
	$log=new adminlogger();
	$log->info($sql);
	$sqlUpdateResult=mysql_query($sql);
	if($sqlUpdateResult)
	{
		echo "<p class='info'>Spiel $matchnr wurde zurückgesetzt ... es muss jetzt nochmal ausgewertet werden.</p>";
		//echo "Spiel '$matchnr' wurde zurückgesetzt ... es muss jetzt nochmal ausgewertet werden.<br>";
	} 
	else
	{
		$errorMessage = "evaluationDone='F' konnte nicht gesetzt werden für matchnr='$matchnr'";
		echo $errorMessage;
		$log->error($errorMessage);
	}
}

echo "<br></body></html>";

?>