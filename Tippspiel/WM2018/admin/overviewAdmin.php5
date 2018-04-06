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

<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<!-- Facivon 
<link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon"> -->

<link rel='stylesheet' type='text/css' href='../../style/style-WM2018.css' />

<title>Werke's Tippspiel - Adminbereich</title>

</head>

<body>

<center>

<img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">

<h1><font color="#C81B00">Adminbereich</font></h1>

<?php 
	echo "<form method=\"POST\" action=\"../util/login.php5\">";
	echo "<input type='submit' name='logout' value='Logout'>";
	echo "</form>";
?>

<br>

<div class="section group">
	<div class="col span_1_of_3">
	
<div class="block">
	<h1><font color="#ffffff">User administrieren</font></h1>
	<hr>
	<?php 
	
	echo "<p><form method=\"POST\" action=\"addUser.php5?userId=$userId\">";
	echo "<input type='submit' name='addUser' value='User anlegen'>";
	echo "</form></p>";
	
	echo "<hr>";
	
	echo "<p><form method=\"POST\" action=\"resetUserPassword.php5?userId=$userId\">User ";
	echo "<td bgcolor=slategray><select name='SelectedUsername'>";
	allUsersAsOption("User");
	echo "<input type='submit' name='resetUser' value='Passwort zur&uuml;cksetzen'>";
	echo "</form></p>";
	
	echo "<hr>";
	
	echo "<p><form method=\"POST\" action=\"removeUser.php5?userId=$userId\">User ";
	echo "<td bgcolor=slategray><select name='SelectedUsername'>";
	allUsersAsOption("User");
	echo "<input type='submit' name='removeUser' value='User l&ouml;schen'>";
	echo "</form></p>";
	
	echo "<hr>";
	
	echo "<p><form method=\"POST\" action=\"sendTestMail.php5?userId=$userId\">Test-e-Mail an folgende Adresse: ";
	echo "<input type=\"Text\" size=\"30\" name=\"email\" value=\"\">";
	echo "<input type='submit' name='sendTestMail' value='Send Test-e-Mail'>";
	echo "</form></p>";
	
	echo "<hr>";
	
	echo "<p><a href='../anybody/showUserList.php5?userId=$userId'>User Liste ansehen</a></p>";
	//  	echo "<p><a href='../anybody/showUserRanks.php5?userId=$userId'>User Rangliste ansehen</a></p>";
	?>
</div>

	</div>
	<div class="col span_1_of_3">

<div class="block">
	<h1><font color="#ffffff">Newsletter upload</font></h1>
	<hr>
	<?php 
	echo "<p><form enctype=\"multipart/form-data\" action=\"./UploadAndSendNewsletter.php5?userId=$userId\" method=\"POST\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\" />"; // value in Bytes --> 10^6 = 1MB
	echo "Folgende Datei hochladen: <input name=\"uploadedfile\" type=\"file\" /> &nbsp";
	echo "<input type=\"submit\" name='uploadNews' value=\"Newsletter hochladen und verschicken\" />";
	echo "</form></p>";
	?>
</div>

<!-- <!-- <div class="block"> -->
<!-- <!-- 	<h1>Tipps ansehen</h1> -->
<!-- <!-- 	<hr> -->
	<?php 
// // 	echo "<p><a href='../anybody/showAllUsersGroupTipps.php5?userId=$userId'>Gruppenspieltipps</a></p>";
// // 	echo "<p><a href='../anybody/showAllUsersGroupRankTipps.php5?userId=$userId'>Platzierungstipps</a></p>";
// // 	echo "<p><a href='../anybody/showAllUsersFinalmatchTipps.php5?userId=$userId'>Finalspieltipps</a></p>";
// // 	echo "<p><a href='../anybody/showAllUsersSpecialTipps.php5?userId=$userId'>Spezialtipps</a></p>";
// // 	?>
<!-- <!-- </div> -->

<!-- <p class='info'>Bitte hier nichts klicken, bevor es wirklich soweit ist!</p> -->


	</div>
	<div class="col span_1_of_3">

<div class="block">
	<h1><font color="#ffffff">Auswertung</font></h1>
	<hr>
	<h2>Gruppenphase</h2>
	<?php 
	echo "<p><form method=\"POST\" action=\"evaluationGroupMatch.php5?adminuserId=$userId\">";
	echo "Gruppen-Spiel Nr.: ";
	echo "<td bgcolor=slategray><select name='SelectedMatchnr'>";
	allGroupMatchesAsOption("SelectedMatchnr", $dbutil);
	echo "</td>";
	echo "<input type='submit' name='evaluationGroupMatch' value='Auswertung Ergebnis Gruppenspiel'>";
	echo "</form></p>";
	
	echo "<p><form method=\"POST\" action=\"evaluationGroupRank.php5?adminuserId=$userId\">";
	echo "Gruppe ";
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
	echo "<input type='submit' name='evaluationGroupRank' value='Auswertung Tabellenplatz Gruppenphase'>";
	echo "</form></p>";
	echo "<hr>";
	?>
	<h2>Endrunden-Tipps</h2>
	<?php
	
	echo "<p><form method=\"POST\" action=\"evaluationParticipants.php5?adminuserId=$userId\">";
	echo "<input type=\"submit\" name=\"eval3\" value=\"Auswertung Teilnehmer Endrunde\">";
	echo "</form></p>";
	
	echo "<p><form method=\"POST\" action=\"evaluationFinalMatch.php5?adminuserId=$userId\">";
	echo "Endrunden-Spiel Nr.: ";
	echo "<td bgcolor=slategray><select name='SelectedMatchnr'>";
	allFinalMatchesAsOption("SelectedMatchnr", $dbutil);
	echo "</td>";
	echo "<input type='submit' name='evaluationFinalMatch' value='Auswertung Ergebnis Endrunde'>";
	echo "</form></p>";
	echo "<hr>";
	?>
	<h2>Spezial-Tipps</h2>
	<?php
	
	echo "<p><form method=\"POST\" action=\"evaluationSpecials.php5?adminuserId=$userId\">";
	echo "<input type=\"submit\" name=\"eval\" value=\"Auswertung Spezial-Tipps\">";
	echo "</form></p>";
	echo "<p>Torsch&uuml;tzenk&ouml;nig (Eingabe manuell in Datenbank)</p>";
	echo "<hr>";
	?>
	<h2>Korrektur</h2>
	<?php
	echo "<p><form method=\"POST\" action=\"overviewAdmin.php5?userId=$userId\">Spiel Nr.: ";
	echo "<input type=\"Text\" size=\"2\" name=\"resetMatchNr\" value=\"\"> <input type=\"submit\" name=\"resetEvaluationDone\" value=\"Auswertung r&uuml;ckg&auml;ngig machen\">";
	echo "</form></p>";
	?>
</div>

	</div>
</div>

<?php

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
		$log->warn("Auswertung Gruppenspiele: keine passenden Datens&auml;tze gefunden");
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
		$log->warn("Auswertung Finalspiele: keine passenden Datens&auml;tze gefunden");
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
		$log->warn("Auswahlliste User: keine passenden Datens&auml;tze gefunden");
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
	
	include_once("../../connection/dbaccess.php5");
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
		echo "<p class='info'>Spiel $matchnr wurde zur&uuml;ckgesetzt ... es muss jetzt nochmal ausgewertet werden.</p>";
		//echo "Spiel '$matchnr' wurde zur&uuml;ckgesetzt ... es muss jetzt nochmal ausgewertet werden.<br>";
	} 
	else
	{
		$errorMessage = "evaluationDone='F' konnte nicht gesetzt werden f&uuml;r matchnr='$matchnr'";
		echo $errorMessage;
		$log->error($errorMessage);
	}
}

echo "<br></body></html>";

?>