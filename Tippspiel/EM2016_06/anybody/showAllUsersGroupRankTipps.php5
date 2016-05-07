<?php
session_start();
$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
?>

<html>

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<!-- Facivon 
<link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon"> -->

<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />

<title>Werke's Tippspiel - Platzierungs-Tipps</title>

</head>

<body>

<center>

<img src="../pics/Logo_EM_2016.png" class="image" width="250" alt="Logo_EM_2016">

<div class="block">
	<p><a href="../util/loginPunktestand.php5"> <h2>zur&uuml;ck</h2> </a> </p>
</div>

</center>

<?php
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
	
	echo "<h1>Platzierungs-Tipps</h1>";
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
	$sql="SELECT * FROM $table_users t where username!='admin' ORDER BY lastname,firstname";
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