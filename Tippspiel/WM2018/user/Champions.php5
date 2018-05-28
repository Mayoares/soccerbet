<?php
session_start();
if(!isset($_GET['userId']))
{
	header("Location: ../util/login.php5");
}
else if (isset($_POST['cancel']))
{
	$userId=$_GET['userId'];
	header("Location: ./overview.php5?userId=$userId");
}
else
{
	$userId=$_GET["userId"];
	echo "<html>";
	echo "<head>";
	echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
	echo "<title>Werke's Tippspiel - Spezial-Tipps</title>";
	echo "</head>";
	echo "<body>";
	
	run($userId);
}

function run($userId){
	
	// Verbindung zur Datenbank aufbauen
	include_once("../../connection/dbaccess.php5");
	include_once("../util/dbutil.php5");
	include_once("../util/dbschema.php5");
	include_once("../../general/log/log.php5");
	include_once("../util/Citations.php5");
	
	// for debugging: print all values in POST : echo "<pre>"; print_r($_POST); echo "</pre>";

	echo "<h2>Stockerl-Tipps</h2>";
	$citation->printCitation("Stockerl");
	echo "<br>";
	
	$username=$dbutil->getUserName($userId);
	$storageSuccess=true;
	if($storageSuccess && isset($_POST['rank1'])){
		
		$storageSuccess = storeNewRank($username, $dbutil->getShortName($_POST['rank1']), 1);
	}
	
	if($storageSuccess && isset($_POST['rank2'])){
		
		$storageSuccess = storeNewRank($username, $dbutil->getShortName($_POST['rank2']), 2);
	}
	
	if($storageSuccess && isset($_POST['rank3'])){
		
		$storageSuccess = storeNewRank($username, $dbutil->getShortName($_POST['rank3']), 3);
	}
		
	if($storageSuccess){
    	
		echo "<font color=\"green\">Stockerl-Tipps erfolgreich gespeichert :)</font>";
		echo "<br>";
    }
	
	$table_teams=dbschema::teams;
	$sqlTeams=mysql_query("SELECT * FROM $table_teams ORDER BY name");
	
	include_once("../shared/SelectFunctions.php5");
	$worldChampion=$Select->getRostrumPrediction($username, 1, $dbutil);
	$vice=$Select->getRostrumPrediction($username, 2, $dbutil);
	$third=$Select->getRostrumPrediction($username, 3, $dbutil);
		
	echo "<form action='Champions.php5?userId=$userId' method='POST'>";
	echo "<table>";	
	echo "<tr class='border_bottom'>";	
	echo "  <td> <b>Weltmeister</b> </td>";
	echo "	<td bgcolor=slategray>";
	echo "    <select name='rank1' style='width: 150px !important'>";
	allTeamsAsOptionForRank($sqlTeams, $worldChampion);
	echo "    </select>";
	echo "	</td>";
	echo "</tr>";	
	echo "<tr class='border_bottom'>";	
	echo "	<td> <b>Zweitplatzierter</b> ";
	echo "	<td bgcolor=slategray>";
	echo "    <select name='rank2' style='width: 150px !important'>";
	allTeamsAsOptionForRank($sqlTeams, $vice);
	echo "    </select>";
	echo "	</td>";
	echo "</tr>";	
	echo "<tr class='border_bottom'>";
	echo "	<td> <b>Platz 3</b> ";
	echo "	<td bgcolor=slategray>";
	echo "    <select name='rank3' style='width: 150px !important'>";
	allTeamsAsOptionForRank($sqlTeams, $third);
	echo "    </select>";
	echo "	</td>";
	echo "</tr>";	
	
	echo "</table>";
		
	echo "<br>";
	echo "<input type='submit' name='save' value='Speichern'>";
	echo "</form>";
	
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<h2>Torsch&uuml;tzenk&ouml;nig</h2>";
	$citation->printCitation("Tor");
	echo "<br>";
	
	if(isset($_POST["topscorer"])){
		$topscorer=$_POST["topscorer"];
		$team=$_POST["team"];
		$shortTeamName=$dbutil->getShortName($team);
		$updated = updateTopScorerTipp($username, $topscorer, $shortTeamName);
		if($updated)
		{
			echo "<font color=\"green\">Torsch&uuml;tzenk&ouml;nig erfolgreich gespeichert :)</font>";
			echo "<br>";
		}
	}
	
	$tippTopscorer=getTippedTopScorer($username);
	$dbutil=new dbutil();
	$tippedTeam=$dbutil->getTippedTopScorerTeam($username);
	$table_teams=dbschema::teams;
	$sqlTeams=mysql_query("SELECT * FROM $table_teams ORDER BY name");

	echo "<form action='Champions.php5?userId=$userId' method='POST'>";
	echo "<table>";	
	echo "<tr class='border_bottom'>";	
	echo "  <td> <b>Spieler</b> </td>";
	echo "  <td> ";
	echo "	<input type='text' name='topscorer' value='$tippTopscorer' style='width: 150px !important'>";
	echo "	</td>";
	echo "</tr>";	
	echo "<tr class='border_bottom'>";
	echo "  <td> <b>Team</b> </td>";
	allTeamsAsOption($sqlTeams, $tippedTeam);
	echo "	</td>";
	echo "</tr>";		
	echo "</table>";	
	echo "<br>";
	echo "<input type='submit' name='save' value='Speichern'>";
	echo "</form>";
	echo "</body>";
	echo "</html>";
	
	mysql_close();
	$log=new logger();
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Verbindung zur MySQL-DB geschlossen.");
}

function storeNewRank($user, $team, $rank){
	
	//echo "<br>$user, $team, $rank";
	$table_championtipps=dbschema::championtipps;
	$sqlInsertUpdate="INSERT INTO $table_championtipps (user,team,rank) VALUES ('$user', '$team', '$rank') ON DUPLICATE KEY UPDATE team=VALUES(team)";
	$log=new logger();	
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sqlInsertUpdate);
	$sqlInsertUpdateResult=mysql_query($sqlInsertUpdate);
	if (!$sqlInsertUpdateResult) {
		$log->error("SQL error: " . mysql_error());
		echo "<p class=\"info\">Rang " . $rank . " konnte nicht gespeichert werden.</p>"; 
		return false;
	}
	else
	{
		$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Inserted/Updated champion prediction ($user,$team,$rank)");
		return true;
	}
}


function getTippedTopScorer($username){
	$table_topscorertipps=dbschema::topscorertipps;
	$sqlQueryResult=mysql_query("SELECT * FROM $table_topscorertipps WHERE user='$username'");
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$topscorer=$sqlResultArray["topscorer"];
	return $topscorer;
}

function allTeamsAsOptionForRank($sqlTeams, $tippedTeam) {
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
				echo"<option>$name</option>";
			}
		}
		if($tippedTeam!="")
		{
			echo "<option selected>$tippedTeam</option>";
		}
		else
		{
			echo "<option selected>---</option>";
		}
	}
}

function allTeamsAsOption($sqlTeams, $tippedTeam) {
	$numTeams=mysql_num_rows($sqlTeams);
	if ($numTeams==0)
		echo "keine passenden Datens&auml;tze gefunden";
	else
	{
		echo "<td bgcolor=slategray><select name='team' style='width: 150px !important'>";
		for ($i=0; $i<$numTeams; $i++)
		{
			$name = mysql_result($sqlTeams, $i, "name");
			if($name!=$tippedTeam)
			{
				echo"<option>$name</option>";
			}
		}
		if($tippedTeam!="")
		{
			echo "<option selected>$tippedTeam</option>";
		}
		else
		{
			echo "<option selected>---</option>";
		}
		echo "</select>";
	}
}

function updateTopScorerTipp($username, $topscorer, $shortTeamName){
	
	include_once("../../general/log/log.php5");
	$log=new logger();	
	$table_topscorertipps=dbschema::topscorertipps;
	$sqlInsertUpdate="INSERT INTO $table_topscorertipps (user,topscorer,team,score) VALUES ('$username', '$topscorer', '$shortTeamName', '0') ON DUPLICATE KEY UPDATE topscorer=VALUES(topscorer),team=VALUES(team),score=VALUES(score)";
	$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), $sqlInsertUpdate);
	$sqlInsertUpdateResult=mysql_query($sqlInsertUpdate);
	if (!$sqlInsertUpdateResult) {
	$log->error("SQL error: " . mysql_error());
		echo "<p class=\"info\">Torsch&uumltzenk&oumlnig konnte NICHT gespeichert werden.</p>"; 
		return false;
	}
	else
	{
		$log->infoCall(basename($_SERVER["SCRIPT_FILENAME"]), "Inserted/Updated topscorer prediction ('$username', '$topscorer', '$shortTeamName')");
		return true;
	}
}
?>