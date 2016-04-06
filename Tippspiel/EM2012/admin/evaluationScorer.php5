<?php
// we must never forget to start the session
session_start();
$userId=$_GET["userId"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Auswertung </title>
</head>
<body>
	
	Torschützenkönig aller User auswerten ...<br>
<br>
	
<?php

// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
include_once("../../general/log/log.php5");
$table_topscorertipps=dbschema::topscorertipps;
$sqluser="SELECT * FROM $table_topscorertipps t WHERE NOT t.username='real' ORDER BY t.username";
$ergebnisUser=mysql_query($sqluser);
echo mysql_error(); 
echo "<table>";
while($arrayUser=mysql_fetch_array($ergebnisUser)){

  $User=$arrayUser["username"];
  $TopscorerTipp=$arrayUser["topscorer"];
  $TopscorerReal=getCorrectTopscorer($RankUser);

  $Score=-1;
  if($TopscorerTipp===$TopscorerReal)
  {
	  $Score=10;
  }
  else 
  {
	  $Score=0;
  }
  
  echo "<tr>";
  echo "<td> $User </td>";
  echo "<td>Tipp: <b>$TopscorerTipp</b> </td>";
  echo "<td>korrekt: <b>$TopscorerReal</b> </td>";
  echo "<td>Punkte: <b>$Score</b></td>";
  echo "</tr>";
  $sqlUpdate="UPDATE $table_topscorertipps SET score='$Score' WHERE username='$User'";
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
echo "<a href='./overviewAdmin.php5?userId=$userId'>zurück zur Übersicht</a>";

function getCorrectTopscorer(){
	$table_topscorertipps=dbschema::topscorertipps;
	$sql="SELECT t.topscorer FROM $table_topscorertipps t WHERE t.username = 'real'";
	$log=new adminlogger();	
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$name=$sqlArray["topscorer"];
	return $name;
}

//Datenbankconnection schließen
mysql_close();
?>
