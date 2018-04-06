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

<link rel='stylesheet' type='text/css' href='../../style/style-WM2018.css' />

<title>Werke's Tippspiel - Endrunden-Tipps</title>

</head>

<body>

<center>

<img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">

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
$log->info("clicked showAllUsersFinalmatchTipps.php5");


printFinalMatches();


function getUserInfo($username){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$username'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	return "$firstname $lastname";
}

function printFinalMatches(){
	
	echo "<h1>Endrunden-Tipps</h1>";
	echo "<br>";
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.matchtype!='Gruppenspiel'";
	$sqlResult=mysql_query($sql);
	
	echo "<table border='1' rules='all'>";
	echo "<tr>";
	echo "<th>Spiel</th> ";
	while($array=mysql_fetch_array($sqlResult)){
		
		$matchnr = $array["matchnr"];
		echo "<th align='center'> $matchnr </th>";
	}
	echo "</tr>";
	
	$table_users=dbschema::users;
	$sql="SELECT * FROM $table_users t where username!='admin' ORDER BY lastname,firstname";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		echo "<tr>";
		printFinalMatchesForUser($array["username"]);
		echo "</tr>";
	}
	echo "</table>";
}
function printFinalMatchesForUser($username){
	
	$table_matches=dbschema::matches;
	$table_users=dbschema::users;
	$table_tipps=dbschema::finalmatchtipps;
	echo "<tr>";
	echo "<td>" . getUserInfo($username) . "</td>";
	$sql="SELECT * FROM $table_matches m LEFT JOIN $table_tipps t ON m.matchnr=t.matchnr AND t.user='$username' WHERE m.matchtype!='Gruppenspiel' ";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$matchnr=$array["matchnr"];
		$teamName1=$array["teamX"];
		$teamName2=$array["teamY"];
		$tippGoalsTeam1=$array["goalsX"]; if (strlen($tippGoalsTeam1)==0) $tippGoalsTeam1='&nbsp; ';
		$tippGoalsTeam2=$array["goalsY"]; if (strlen($tippGoalsTeam2)==0) $tippGoalsTeam2='&nbsp; ';
		$points = $array["score"];
		$evaluationDone=$array["evaluationDone"];
		
		$result = "$teamName1-$teamName2<br>$tippGoalsTeam1 : $tippGoalsTeam2";
		if($evaluationDone === "T"){
			if($points == NULL){ // for the user 'real'
				echo "<td align='center'>$result</td>";
			} else if($points == 0){ // print white
				echo "<td><div align='center' style=\"background-color:white\">$result</div></td>";
			} else if($points == 2){ // print light blue
				echo "<td><div align='center' style=\"background-color:CCFFFF\">$result</div></td>";
			} else if($points == 3){ // print light blue (a bit darker)
				echo "<td><div align='center' style=\"background-color:99ffff\">$result</div></td>";
			} else if($points == 4){ // print light green
				echo "<td><div align='center' style=\"background-color:CCFF99\">$result</div></td>";
			} else if($points == 5){ // print light green (a bit darker)
				echo "<td><div align='center' style=\"background-color:b7e589\">$result</div></td>";
			} else if($points == 6){ // print light green (even a bit more shade)
				echo "<td><div align='center' style=\"background-color:a3cc7a\">$result</div></td>";
			} else if($points == 8){ // print green
				echo "<td><div align='center' style=\"background-color:8eb26b\">$result</div></td>";
			}
		} else {
			echo "<td align='center'>$result</td>";
		}
	}
	echo "</tr>";
}

?>