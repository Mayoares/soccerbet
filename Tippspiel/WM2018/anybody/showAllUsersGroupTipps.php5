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

<title>Werke's Tippspiel - Gruppenspiel-Tipps</title>

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
include_once("../util/calc.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");

$log=new viewlogger();
$log->info("clicked showAllUsersTipps.php5");


printGroupMatches();


function getUserInfo($username){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$username'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	return "$firstname $lastname";
}

function printGroupMatches(){
	
	echo "<h1>Gruppenspiel-Tipps</h1>";
	echo "<br>";
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.matchtype='Gruppenspiel'";
	$sqlResult=mysql_query($sql);
	
	echo "<table border='1' rules='all'>";
	echo "<tr>";
	echo "<th>Spiel</th> ";
	while($array=mysql_fetch_array($sqlResult)){
		
		$matchnr = $array["matchnr"];
		//echo "<td></td><td> $matchnr </td><td></td>";
		echo "<th align='center'> $matchnr </th>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<th>  Paarung </th>";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$teamName1=$array["team1"];
		$teamName2=$array["team2"];
		//echo "<td align='right'>$teamName1</td> <td align='center'>-</td> <td align='left'>$teamName2</td>";
		echo "<td align='center'>$teamName1-$teamName2</td>";
	}
	echo "</tr>";
	
	$table_users=dbschema::users;
	$sql="SELECT * FROM $table_users t where username!='admin' ORDER BY lastname,firstname";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		echo "<tr>";
		printGroupMatchesForUser($array["username"]);
		echo "</tr>";
	}
	echo "</table>";
}
function printGroupMatchesForUser($username){
	
	$table_matches=dbschema::matches;
	$table_users=dbschema::users;
	$table_tipps=dbschema::groupmatchtipps;
	echo "<tr>";
	echo "<td style='white-space:nowrap;'>" . getUserInfo($username) . "</td>";
	$sql="SELECT * FROM $table_matches m LEFT JOIN $table_tipps gmt ON m.matchnr=gmt.matchnr AND gmt.user='$username' WHERE m.matchtype='Gruppenspiel' ";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$matchnr=$array["matchnr"];
		$tippGoalsTeam1=$array["goalsX"];
		$tippGoalsTeam2=$array["goalsY"];
		$evaluationDone=$array["evaluationDone"];
		if($evaluationDone === "T"){
			// get points
			$points = $array["score"];
			if($points == NULL){ // should not happen when evaluation is already done, but use default then
				echo "<td align='center'>$tippGoalsTeam1 : $tippGoalsTeam2</td>";
			} else if($points == 0){ // print white
				echo "<td><div align='center' style=\"background-color:white\">$tippGoalsTeam1 : $tippGoalsTeam2</div></td>";
			} else if($points == 2){ // print light blue
				echo "<td><div align='center' style=\"background-color:CCFFFF\">$tippGoalsTeam1 : $tippGoalsTeam2</div></td>";
			} else if($points == 4){ // print light green
				echo "<td><div align='center' style=\"background-color:CCFF99\">$tippGoalsTeam1 : $tippGoalsTeam2</div></td>";
			}
		} else {
			//echo "<td align='right'>$tippGoalsTeam1</td><td align='center'> : </td><td align='left'>$tippGoalsTeam2</td>";
			echo "<td align='center'>$tippGoalsTeam1 : $tippGoalsTeam2</td>";
		}
	}
	echo "</tr>";
}
?>