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
echo "<a href='../util/loginPunktestand.php5'>zurück</a>";
echo "<br>";
echo "<br>";
echo "<title>Werke's Tippspiel - Tipps ALL USERS (Germany)</title>";
echo "</head>";
echo "<body>";
include_once("../../connection/dbaccess-local.php5");
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
	
	echo "<h3>Finalspiele</h3>";
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
	$sql="SELECT * FROM $table_users t ORDER BY lastname,firstname";
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