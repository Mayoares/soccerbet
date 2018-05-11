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

<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />

<title>Werke's Tippspiel - Spezial-Tipps</title>

</head>

<body>

<center>

<img src="../pics/Tippspiel-Logo.PNG" class="image" width="300" alt="Logo">

<div class="block">
	<p><a href="../util/loginPunktestand.php5"> <h2>zur&uuml;ck</h2> </a> </p>
</div>

</center>

<?php
include_once("../../connection/dbaccess.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbschema.php5");

$log=new viewlogger();
$log->info("clicked showAllUsersSpecialTipps.php5");


printSpecials();

function printSpecials(){
	
	echo "<h1>Spezial-Tipps</h1>";
	echo "<br>";
	
	echo "<table border='1' rules='all'>";
	echo "<tr>";
	echo "<th>User</th> ";
	echo "<th>Weltmeister</th> ";
	echo "<th>Zweitplatzierter</th> ";
//  	echo "<th>Platz 3</th> ";
	echo "<th>Torsch&uuml;tzenk&ouml;nig</th> ";
	echo "</tr>";
	
	$table_users=dbschema::users;
	$sql="SELECT * FROM $table_users t where username!='admin' ORDER BY lastname,firstname";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		echo "<tr>";
		printSpecialsForUser($array["username"]);
		echo "</tr>";
	}
	echo "</table>";
}

function printSpecialsForUser($username){
	$worldChampion=getTippedTeamRostrum($username, 1);
	$vice=getTippedTeamRostrum($username, 2);
	$third=getTippedTeamRostrum($username, 3);
	
	$dbutil=new dbutil();
	$tippTopscorer=getTippedTopScorer($username);
	$tippedTeam=$dbutil->getTippedTopScorerTeam($username);
	echo "<tr>";
	echo "<td>" . getUserInfo($username) . "</td>";
	echo "<td>" . $worldChampion . "</td>";
	echo "<td>" . $vice . "</td>";
// 	echo "<td>" . $third . "</td>";
	echo "<td>" . $tippTopscorer . "($tippedTeam)" . "</td>";
	echo "</tr>";
}

function getUserInfo($username){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$username'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	return "$firstname $lastname";
}

function getTippedTeamRostrum($username, $rank){
	$table_championtipps=dbschema::championtipps;
	$sqlQuery="SELECT * FROM $table_championtipps WHERE user='$username' AND rank=$rank";
//	echo "<br>$sqlQuery";
	$sqlQueryResult=mysql_query($sqlQuery);
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$teamShort=$sqlResultArray["team"];
	return getTeamName($teamShort);
}

function getTeamName($shortname) {
	$table_teams=dbschema::teams;
	$result=mysql_query("SELECT t.name FROM $table_teams t WHERE t.shortname='$shortname'");
	$array=mysql_fetch_array($result);
	$name=$array["name"];
	return $name;
}

function getTippedTopScorer($username){
	$table_topscorertipps=dbschema::topscorertipps;
	$sqlQueryResult=mysql_query("SELECT * FROM $table_topscorertipps WHERE user='$username'");
	$sqlResultArray=mysql_fetch_array($sqlQueryResult);
	$topscorer=$sqlResultArray["topscorer"];
	return $topscorer;
}

?>