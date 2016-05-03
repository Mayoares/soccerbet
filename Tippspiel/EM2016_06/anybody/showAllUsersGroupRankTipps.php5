<?php
session_start();
$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' type='text/css' href='../../style/style.css' />";
echo "<br>";
echo "<a href='../util/loginPunktestand.php5'>zur�ck</a>";
echo "<br>";
echo "<br>";
echo "<title>Werke's Tippspiel - Tipps ALL USERS (Germany)</title>";
echo "</head>";
echo "<body>";
include_once("../../connection/dbaccess.php5");
include_once("../../general/log/log.php5");
include_once("../util/calc.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");

$log=new viewlogger();
$log->info("clicked showAllUsersGroupRankTipps.php5");


printGroupRanks();


function getUserInfo($username){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$username'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	return "$firstname $lastname";
}

function printGroupRanks(){
	
	echo "<h3>Gruppenplatzierungen</h3>";
	$table_teams=dbschema::teams;
	$sql="SELECT * FROM $table_teams t ORDER BY t.group, t.shortname";
	$sqlResult=mysql_query($sql);
	
	echo "<br>";
	echo "<table border='1' rules='all'>";
	echo "<tr>";
	echo "<th>Gruppe</th> ";
	while($array=mysql_fetch_array($sqlResult)){
		
		$group = $array["group"];
		echo "<th align='center'> $group </th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<th>  Team </th>";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$teamName=$array["shortname"];
		echo "<td align='center'>&nbsp; $teamName &nbsp;</td>";
	}
	echo "</tr>";
	
	$table_users=dbschema::users;
	$sql="SELECT * FROM $table_users t ORDER BY lastname,firstname";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		echo "<tr>";
		printGroupRanksForUser($array["username"]);
		echo "</tr>";
	}
	echo "</table>";
}
function printGroupRanksForUser($username){
	
	$table_groupranktipps=dbschema::groupranktipps;
	$table_teams=dbschema::teams;
	echo "<tr>";
	echo "<td>" . getUserInfo($username) . "</td>";
	$sql="SELECT * FROM $table_teams t LEFT JOIN $table_groupranktipps grt ON grt.user='$username' AND t.shortname = grt.team ORDER BY t.group, t.shortname";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$group = $array["group"];
		$teamName=$array["team"];
		$tippRank=$array["rank"];
		$points = $array["score"];
		if($points == NULL){ 
			echo "<td align='center'>$tippRank</td>";
		} else if($points == 0){ // print white
			echo "<td><div align='center' style=\"background-color:white\">$tippRank</div></td>";
		} else if($points == 2){ // print light green
			echo "<td><div align='center' style=\"background-color:CCFF99\">$tippRank</div></td>";
		}
	}
	echo "</tr>";
}

?>