<?php
// we must never forget to start the session
session_start();
$adminuserId=$_GET["adminuserId"];
$userId=$_GET["userId"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Auswertung </title>
</head>
<body>
	
	Stockerltipps aller User auswerten ...<br>
<br>
	
<?php

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
include_once("../../general/log/log.php5");
$table_championtipps=dbschema::championtipps;
$sqluser="SELECT c.user, c.team, c.rank AS ranktipp FROM $table_championtipps c WHERE NOT c.user='real' ORDER BY c.user,c.rank";
$ergebnisUser=mysql_query($sqluser);
echo mysql_error(); 
echo "<table>";
while($arrayUser=mysql_fetch_array($ergebnisUser)){

  $User=$arrayUser["user"];
  $Team=$arrayUser["team"];
  $RankUser=$arrayUser["ranktipp"];
//  $RankReal=$arrayUser["realrank"];
  $TeamReal=getRealTeam($RankUser);

  $Score=-1;
  if($Team===$TeamReal)
  {
	  $Score=getScore($RankUser);
  }
  else 
  {
	  $Score=0;
  }
  
  echo "<tr>";
  echo "<td>                                  $User         </td>";
  echo "<td>Platz                          <b>$RankUser</b> </td>";
  echo "<td>Tipp:                          <b>$Team    </b> </td>";
  echo "<td>korrekt: <font color=\"green\"><b>$TeamReal</b> </td>";
  echo "<td>Punkte:  <font color=\"#C81B00\">  <b>$Score   </b> </td>";
  echo "</tr>";
  $sqlUpdate="UPDATE $table_championtipps SET score='$Score' WHERE user='$User' AND rank='$RankUser'";
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
echo "<br>";
echo "<br>";
echo "<br>";
echo "<a href='../admin/overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";

function getRealTeam($rank){
	$table_championtipps=dbschema::championtipps;
	$sql="SELECT t.team FROM $table_championtipps t WHERE t.user='real' AND t.rank='$rank'";
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$team=$sqlArray["team"];
	//echo "<br>Team=$team, Rank=$rank";
	return $team;
}

function getScore($rank){
	if($rank==1)
	{
		return 15;
	}
	else if($rank==2)
	{
		return 10;
	}
// 	else if($rank==3)
// 	{
// 		return 8;
// 	}
}

//Datenbankconnection schliessen
mysql_close();
?>
