<?php
// we must never forget to start the session
session_start();
$userId=$_GET["adminuserId"];
$SelectedGroup=$_POST['SelectedGroup'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Auswertung </title>
</head>
<body>
	
<?php
echo "Tabellenplatztipps der Gruppe <b>$SelectedGroup</b> auswerten ...<br>";
echo "<br>";
	

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbutil.php5");

$adminuserId=$_GET["adminuserId"];

if(strlen($adminuserId)==0)
{
	$log=new adminlogger();
	$log->warn("AdminuserId was empty");
	header("Location: ../util/login.php5");
	exit;
}
else
{
	if(!isAdmin($adminuserId,$dbutil))
	{
		header("Location: ../util/login.php5");
		exit;
	}
	else if(isset($_POST["evaluationGroupRank"]))
	{
		echo "<a href='../admin/overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
		echo "<br>";
		echo "<br>";
		evaluateGroupRanks($SelectedGroup,$dbutil);
		echo "<br>";
		echo "<a href='../admin/overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	}
	else
	{
		header("Location: ../util/login.php5");
		exit;
	}
}

function isAdmin($adminuserId,$dbutil){
	
	$adminuserName=$dbutil->getUserName($adminuserId);
	if($adminuserName==="admin")
	{
		return true;
	}
	else
	{
		$log=new adminlogger();
		$log->warn("Wrong user (" . $adminuserName . ") tried to add user.");
		return false;
	}
}

function evaluateGroupRanks($SelectedGroup,$dbutil){
	
	include_once("../util/dbutil.php5");
$table_groupranktipps=dbschema::groupranktipps;
$table_teams=dbschema::teams;
//$sqluser="SELECT r.user, r.team, r.rank as ranktipp, t.rank AS realrank, t.group FROM $table_groupranktipps r, $table_teams t WHERE r.team = t.shortname";
$sqluser="SELECT r.user, r.team, r.rank AS ranktipp, t.group FROM $table_groupranktipps r, $table_teams t WHERE t.group='$SelectedGroup' AND r.team = t.shortname AND NOT r.user='real' ORDER BY r.user, t.group";
$ergebnisUser=mysql_query($sqluser);
echo mysql_error(); 
$scoreSum=0;  
echo "<table>";
echo "<th>User</th><th> Team </th><th> Tipp</th><th> korrekt </th><th> Punkte</th>";
while($arrayUser=mysql_fetch_array($ergebnisUser)){

  $User=$arrayUser["user"];
  $Team=$arrayUser["team"];
  $Group=$arrayUser["group"];
  $RankUser=$arrayUser["ranktipp"];
  $RankReal=getRealRank($Team);
  $teamName=$dbutil->getTeamName($Team);

  $Score=-1;
  if($RankUser==$RankReal)
  {
	  $Score=2;
	  $scoreSum=$scoreSum+$Score;
  }
  else 
  {
	  $Score=0;
  }
  
  echo "<tr>";
  //echo "<br> $User Gruppe $Group : $Team : Tipp: Platz <b>$RankUser</b> korrekt : Platz <b>$RankReal</b> Punkte: $Score";
  echo "<div align=\"center\">";
  echo "<td> $User </td><td> $teamName </td> " .
  "<td><div align=\"center\"> <b>$RankUser</b></div> </td>" . 
  "<td><div align=\"center\"> <font color=\"#32cd32\"><b>$RankReal</b></div> </td>" . 
  "<td><div align=\"center\"> <font color=\"#FF0000\"> $Score</div></td>";
  echo "</div>";
  echo "</tr>";
  $sqlUpdate="UPDATE $table_groupranktipps SET score='$Score' WHERE user='$User' AND team='$Team'";
  $log=new adminlogger();	
  $log->info($sqlUpdate);
  $sqlUpdateResult=mysql_query($sqlUpdate);
  if(!$sqlUpdateResult)
  {
	  $log->error('Datenbankfehler. MIST!:' . mysql_error());
  }
  else
  {
	  $log->info('Update OK.');
  }
}
echo "</table>";
}

function getRealRank($team){
	$table_groupranktipps=dbschema::groupranktipps;
	$sql="SELECT t.rank FROM $table_groupranktipps t WHERE t.user='real' AND t.team='$team'";
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$rank=$sqlArray["rank"];
//	echo "<br>Team=$team, Rank=$rank";
	return $rank;
}

//Datenbankconnection schliessen
mysql_close();
?>
